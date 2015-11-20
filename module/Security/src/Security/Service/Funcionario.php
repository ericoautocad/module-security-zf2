<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 25/08/15
 * Time: 14:14
 */

namespace Security\Service;


use Zend\Stdlib\Hydrator\ClassMethods;

class Funcionario  extends SecurityAbstractService {

    public function insert(array $data , $entity)
    {
        //antes de inserir o sistema deve criar um usuário para associar ao funcionario
        $dataUser = array('login' => $data['login'], 'senha' => $data['senha'], 'grupo' => $data['perfil'] );
        $serviceUsuario = $this->getServiceLocator()->get('security-service-usuario');
        $usuario = $serviceUsuario->insert($dataUser, 'Security\Entity\Usuario');

        if($usuario){
            $data['usuario'] = $usuario;
            $data['usuario_id'] =$usuario->getId();
            return parent::insert($data, $entity);
        } else{
            return false;
        }

    }


    public function update(array $data , $entity, $id)
    {
        /**
         * @var $entity \Security\Entity\Funcionario
         */
        $data['grupo'] = $this->getEmRef('Security\Entity\Grupo',$data['perfil'] );
        // instanciando o entity manager
        $em = $this->getEm();
        //instancia a entity
        $entity = $this->getEmRef($entity, $id);
        // hydrator
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $entity);
        //obtem o usuario do funcionario para atualizacao dos dados
        //var_dump($entity->getUsuario());
        //echo "<br/>";

        $hydrator->hydrate($data, $entity->getUsuario());
        $entity->getUsuario()->setSenha($entity->getUsuario()->encryptPassword($entity->getUsuario()->getSenha()));
        //var_dump($entity->getUsuario()); die;
        $em->persist($entity);
        $em->flush();
        return $entity;
    }
} 