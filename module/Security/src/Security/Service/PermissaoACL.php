<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 27/08/15
 * Time: 10:18
 */

namespace Security\Service;

use Security\Entity\RecursoSistema;
use Zend\ServiceManager\ServiceLocatorInterface;

class PermissaoAcl extends  SecurityAbstractService {


    /**1 remover recursos que foram desautorizado */
    public function desautorizaRecursosAutorizados($idGrupo, $recursosAutorizado = array())
    {

            $repoPermissaoAcl = $this->getEm('Security\Entity\PermissaoAcl');
            $lista = $repoPermissaoAcl->getPermissionsGroupDesautorizar($idGrupo, $recursosAutorizado );
            $em = $this->getEm();
            if(count($lista) > 0){
                foreach($lista as $permissao){
                    $em->remove($permissao);
                   $em->flush();
                }
            }
    }
    /** 2 adicionar permissão de recurso existente */
    public function adicionarPermissaoRecursosExistentes($idGrupo, $recursosAutorizado = array())
    {
        $recursoGrupo = $this->getEm('Security\Entity\PermissaoAcl')->getIdRecursoPermissionsGroup($idGrupo);
        $recursosAutorizado = array_combine( array_values($recursosAutorizado), array_values($recursosAutorizado) );
        $recursosExistenteAdicionar = array_diff($recursosAutorizado, $recursoGrupo);
        $novosRecursosGrupo = array();
        if( !empty($recursosExistenteAdicionar) ){
            $grupo = $this->getEmRef('Security\Entity\Grupo',$idGrupo);
            foreach($recursosExistenteAdicionar as $recurso){
                $data = array('grupo' => $grupo, 'permissao' => 'allow');
                $data['recursoSistema']= $this->getEmRef('Security\Entity\RecursoSistema',$recurso);
                if($data['recursoSistema']){
                    $novosRecursosGrupo[] =  parent::insert($data, 'Security\Entity\PermissaoAcl');
                }
            }
        }
        return $novosRecursosGrupo;
    }
    /** 3 adicionar permissão de recurso novo */
    public function adicionarPermissaoRecursosNovos($idGrupo, $recursosNovos = array())
    {
        if(!empty($recursosNovos)){
            $novosRecursoSistema = array();
            $grupo = $this->getEmRef('Security\Entity\Grupo',$idGrupo);
            foreach($recursosNovos as $recurso){
                $entityRecursoSistema = $this->getEm('Security\Entity\RecursoSistema')->findOneByUrl($recurso);
                if(!$entityRecursoSistema){
                    $novoRecurso = new RecursoSistema( array('url' => $recurso) );
                    $dataPermissao = array('grupo' =>$grupo, 'recursoSistema' => $novoRecurso, 'permissao' => 'allow');
                    $novosRecursoSistema[] = parent::insert($dataPermissao , 'Security\Entity\PermissaoAcl');
                }
            }
        }
        return $novosRecursoSistema;
    }

    public function gerenciaPermissoesGrupo($idGrupo, $paramsRequest = array())
    {
        $recursosExistentes = array();
        $recursosNovos = array();
        foreach( $paramsRequest as $recurso_sistema => $permissao){
            if( is_numeric($permissao) ){
                $recursosExistentes[] = $permissao;
            } else{
                $recursosNovos[] = $permissao;
            }
        }
        /**1 remover recursos que foram desautorizado */
        $result = $this->desautorizaRecursosAutorizados($idGrupo, $recursosExistentes);
        /** 2 adicionar permissão de recurso existente */
        $result = $this->adicionarPermissaoRecursosExistentes($idGrupo, $recursosExistentes);
        /** 3 adicionar permissão de recurso novo */
        $result = $this->adicionarPermissaoRecursosNovos($idGrupo, $recursosNovos);
        /**
         * @var $cache \Zend\Cache\Storage\Adapter\Filesystem
         */
        $cache = $this->getServiceLocator()->get('Cache');
        $cache->removeItem('cacheApp1');
    }

} 
