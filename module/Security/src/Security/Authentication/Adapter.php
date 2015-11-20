<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 21/08/15
 * Time: 16:55
 */

namespace Security\Authentication;

use Doctrine\ORM\EntityManager;
use Security\Entity\Usuario;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session;

class Adapter implements AdapterInterface {


    protected $sm;
    protected $login;
    protected  $password;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

    }
    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $user = $this->em->getRepository('Security\Entity\Usuario')
        ->findByLoginAndSenha(new Usuario(array()), $this->getLogin(), $this->getPassword());
        if($user){
            return new Result(Result::SUCCESS, $user, array());
        }
        else{
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array(
                'Não foi possível conectar. Login ou senha invalido.'
            ));
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $sm
     */
    public function setSm($sm)
    {
        $this->sm = $sm;
    }

    /**
     * @return mixed
     */
    public function getSm()
    {
        return $this->sm;
    }

}