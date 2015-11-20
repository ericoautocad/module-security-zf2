<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/08/15
 * Time: 08:25
 */

namespace Security\Repository;



class PermissaoAcl extends  SecurityAbstractRepository {

    /**
     * obtem as permissoes a desautorizar no sistema
     * @param $idGrupo
     * @param array $recursoAutorizado
     * @return array
     * @throws \Exception
     */
    public function getPermissionsGroupDesautorizar($idGrupo, $recursoAutorizado = array())
    {
        try
        {
            $qb = $this->getEntityManager()->createQueryBuilder()
            ->select("p")->from("Security\Entity\PermissaoAcl", "p")
            ->innerJoin("Security\Entity\RecursoSistema", "rs", "with", "rs.id=p.recursoSistema")
            ->where('p.grupo = :grupo1')
            ->setParameter('grupo1', $idGrupo)
            ->andWhere( 'rs.id NOT IN(:recursos1)')
            ->setParameter('recursos1',  $recursoAutorizado)
            ->getQuery()
            ->getResult();

            return $qb;
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Obtem as permissoes de um grupo especifico
     * @param $idGroup
     * @return array
     * @throws \Exception
     */
    public function getPermissionsGroup($idGroup)
    {
        try
        {
            $qb = $this->getEntityManager()->createQueryBuilder()
            ->select( "p")->from("Security\Entity\PermissaoAcl", "p")

            ->innerJoin("Security\Entity\RecursoSistema", "rs", "with", "rs.id=p.recursoSistema")
            ->where('p.grupo = :grupo1')
            ->setParameter('grupo1', $idGroup)
            ->getQuery()
            ->getResult();
            return (empty($qb))?  array() : $qb;
               // throw new \Exception("Não foram encontrados registros de permissões para o grupo_id ".$idGroup."");
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Obtem todas as permissoes
     * @return array
     */
    public function getPermissions()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select( "p")->from("Security\Entity\PermissaoAcl", "p")
            ->innerJoin("Security\Entity\RecursoSistema", "rs", "with", "rs.id=p.recursoSistema")
            ->getQuery()
            ->getResult();
        return $qb;
    }

    /**
     * Obtem os id's de recurso daqs permissoes de um grupo
     * @param $idGroup
     * @return array
     */
    public function getIdRecursoPermissionsGroup($idGroup)
    {
        $permissoesGrupo = $this->getPermissionsGroup($idGroup);
        $id_recursosGrupo = array();
        foreach($permissoesGrupo as $recurso){
            $id_recursosGrupo[ $recurso->getRecursoSistema()->getId() ] = $recurso->getRecursoSistema()->getId();
        }
        return $id_recursosGrupo;
    }
} 
