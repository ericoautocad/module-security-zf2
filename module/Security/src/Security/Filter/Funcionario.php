<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 25/08/15
 * Time: 15:03
 */

namespace Security\Filter;


use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use \DoctrineModule\Validator\NoObjectExists;

class Funcionario extends  InputFilter {

    public  function __construct(ServiceLocatorInterface $sm, $validaLogin = false){
        $this->add(array(
            'name' => 'nome',
            'allow_empty' => false,
        ));

      $this->add(array(
            'name' => 'telefone',
            'allow_empty' => false,
        ));

       $this->add(array(
           'name' => 'email',
           'allow_empty' => false,
       ));

       $this->add(array(
           'name' => 'perfil',
           'allow_empty' => false,
       ));


        $this->add(array(
            'name' => 'login',
            'allow_empty' => false,
        ));


        // valida se o login é unico somente se solicitado
        if($validaLogin){
            $factory = new \Zend\InputFilter\Factory();
            $em = $sm->get('Doctrine\ORM\EntityManager');
            $repo = $em->getRepository('Security\Entity\Usuario');

            $validatorSignature = array(
                'name' => 'login',
                'validators' => array(
                    array(
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $repo,
                            'fields' => 'login',
                            'messages' => array( NoObjectExists::ERROR_OBJECT_FOUND => "Este login já existe na base de dados"  )
                        ),
                    ),
                )
            );

            $validator = $this->getFactory()->createInput( $validatorSignature );
            $this->add($validator);
        }

       $this->add(array(
           'name' => 'senha',
           'allow_empty' => false,
       ));
    }
} 