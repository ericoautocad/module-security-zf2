<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 21/08/15
 * Time: 10:11
 */

namespace Security\Repository;

use Doctrine\ORM\EntityRepository;
class SecurityAbstractRepository extends EntityRepository {

    /**
     * Obterm o para de dados id => nome , das tabelas que tem estes campos, este metodo é muito util para preencher
     * campos select em formulários com informações do banco de dados.
     * @return array
     */
    public function  fetchPairs()
    {
        $result = $this->findAll();

        $arrResult = array();
        if($result){
            foreach($result as $item){
                $arrResult[$item->getId()] = $item->getNome();
            }
        }

        return $arrResult;
    }
} 