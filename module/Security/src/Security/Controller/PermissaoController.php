<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/08/15
 * Time: 14:57
 */

namespace Security\Controller;


use Zend\View\Model\ViewModel;

class PermissaoController extends  SecurityAbstractController {

    public function indexAction()
    {
        $repoGrupo = $this->getEm('Security\Entity\Grupo');
        $list = $repoGrupo->findAll();
        return new ViewModel( array(
            'list' => $list
        ));
    }

    public function gerenciarAction()
    {
        $id = $this->Params('id');
        $service= $this->getServiceLocator()->get('sercurity-service-permissao');
        $repoRecurso = $this->getEm('Security\Entity\RecursoSistema');
        $list = $repoRecurso->loadResourcesSystem( $this->getServiceLocator()->get('servicemanager') );
        $repoPermissao =  $this->getEm('Security\Entity\PermissaoAcl');

        $recursos = $repoRecurso->fetchPairs();
        $recursosGrupo =   $repoPermissao->getIdRecursoPermissionsGroup($id);

        return new ViewModel( array(
            'list' => $list,
            'recursos' => $recursos,
            'recursosGrupo' => $recursosGrupo,
            'idGrupo' => $id
        ));
    }

    public function editarAction()
    {
        $request = $this->getRequest();
        $id = $this->Params('id');
        if($request->isPost()){
            $paramsRequest = $request->getPost()->toArray();
            $servicePermissao = $this->getServiceLocator()->get('sercurity-service-permissao');
            $servicePermissao->gerenciaPermissoesGrupo($id, $paramsRequest);
            $this->flashMessenger()->addSuccessMessage('Permissões alteradas com sucesso!');
            return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/permissao'));
        } else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao gravar permissões!');
            return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/permissao'));
        }
    }
} 