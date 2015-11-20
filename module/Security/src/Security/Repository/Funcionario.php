<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 19/11/15
 * Time: 13:16
 */

namespace Security\Repository;


class Funcionario extends  SecurityAbstractRepository {

    /**
     * obterm a lista de funcionarios
     * @param array $filtros
     * @return array
     */
    public function getFuncionarios($filtros = array())
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->addSelect('f.id');
        $qb->addSelect('f.nome');
        $qb->addSelect('f.telefone');
        $qb->addSelect('f.email');
        $qb->addSelect('f.horarioTrabalho');
        $qb->addSelect('g.nome as grupo');
        $qb->addSelect('g.id as id_grupo');
        $qb->addSelect('u.ativo');
        $qb->addSelect('u.login');
        //$qb->addSelect('u.senha');
        /**
         * Implementar clausulas where conforme os filtros
         */
        $qb->from("Security\Entity\Funcionario", "f");
        $qb->innerJoin("Security\Entity\Usuario","u","with","u.id=f.usuario");
        $qb->innerJoin("Security\Entity\Grupo","g","with","g.id=u.grupo");
        $qb->where("g.id <> 1");
        $qb->addOrderBy("f.nome", "ASC");
        return $qb->getQuery()->getResult();
    }

} 