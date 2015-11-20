<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 11/11/15
 * Time: 11:17
 */

namespace Application\Service;


use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AbstractService implements  ServiceLocatorAwareInterface {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ServiceManager
     */

    protected  $sm;

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */

    public function getServiceLocator()
    {
        return $this->sm;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */

    public function setServiceLocator( ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;
    }

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

    public function insert(array $data , $entity)
    {
        $entity = new $entity($data);

        $em = $this->getEm();
        $em->persist($entity);
        $em->flush();
        return $entity;
    }

    public function update(array $data , $entity, $id)
    {
        $entity = $this->getEmRef($entity, $id);
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $entity);

        $em = $this->getEm();
        $em->persist($entity);
        $em->flush();
        return $entity;
    }

    public function delete( $entity, $id)
    {
        $findEntity = $this->getEm($entity)->find($id);
        if($findEntity){
            $entity = $this->getEmRef($entity, $id);
            $em = $this->getEm();
            $em->remove($entity);
            $em->flush();
            return true;
        }
        return false;
    }

    public function treatCurrencyValuePersists($valor)
    {

        return  str_replace("R$", "",str_replace( ",", ".",str_replace(".", "",$valor)));
    }
} 