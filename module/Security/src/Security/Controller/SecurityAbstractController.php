<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 03/06/15
 * Time: 10:10
 */

namespace Security\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SecurityAbstractController extends AbstractActionController
{
    public function getEm($entity = null)
    {
        if($entity === null){
            return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        } else{
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            return $em->getRepository($entity);
        }

    }

    public function getEmRef($entity, $id){
        return $this->getEm()->getReference($entity, $id);
    }

    protected $subFolderApplication = '';

    public function getUrlFromSubfolderApplication($stringUrl)
    {
        return $this->subFolderApplication . $stringUrl;
    }
}
