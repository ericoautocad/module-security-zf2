<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 01/09/15
 * Time: 10:18
 */

namespace Security\Repository;


use Zend\ServiceManager\ServiceLocatorInterface;

class Usuario extends  SecurityAbstractRepository {

    /** metodo que obtem os dados da sessao do usuario logado conforme usuario e funcionario referenciado no banco
     * @param \Zend\Authentication\AuthenticationService $auth
     * @param ServiceLocatorInterface $sm
     * @return array|bool
     */
    public function getDataUserSession( \Zend\Authentication\AuthenticationService $auth, ServiceLocatorInterface $sm )
    {
        /**
         * @var $auth \Zend\Authentication\AuthenticationService
         * @var $adapter \Security\Authentication\Adapter
         */
        $dataUser = array('grupo' => $auth->getIdentity()->getGrupo()->getNome(), 'user_id' => $auth->getIdentity()->getId());
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $repoFunciorio = $em->getRepository('Security\Entity\Funcionario');
        $dataFuncionario = $repoFunciorio->findByUsuario($auth->getIdentity() );
        if(count($dataFuncionario) == 1){
            $dataUser['nome'] = $dataFuncionario[0]->getNome();
            $dataUser['funcionario_id'] = $dataFuncionario[0]->getId();
        } else{
            return false;
        }
        return $dataUser;
    }

    /**
     * meto que busca uma instancia de \Security\Entity\Usuario atraves de um login e senha
     * @param \Security\Entity\Usuario $usuario
     * @param $login
     * @param $senha
     * @return bool|\Security\Entity\Usuario
     */
    public function findByLoginAndSenha( \Security\Entity\Usuario $usuario, $login, $senha)
    {
        /**
         * @var $userLogin \Security\Entity\Usuario
         */
        $userLogin = $this->createQueryBuilder('u')
            ->where('u.login = :a1')
            ->setParameter('a1', $login)->getQuery()->getOneOrNullResult();
        if(!empty($userLogin)){
            $usuario->setSalt($userLogin->getSalt());
            if($usuario->encryptPassword($senha) == $userLogin->getSenha()){
                return $userLogin;
            }
        }
        return false;
    }
} 