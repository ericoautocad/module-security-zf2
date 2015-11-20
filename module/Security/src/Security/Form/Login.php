<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 31/08/15
 * Time: 14:07
 */

namespace Security\Form;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;


class Login extends Form {

    public function __construct()
    {
        parent::__construct('formlogin');

        $this->add($this->getLogin());
        $this->add($this->getSenha());
        $this->add($this->getSubmit());


    }

    public function getPerfil()
    {
        $element = new Select('perfil');
        $element->setLabel('Perfil');
        $element->setAttributes(array('id' => 'perfil', 'class' => 'form-control'));
        return $element;
    }
    public function getLogin()
    {
        $element = new Text('login');
        $element->setLabel('Login');
        $element->setAttributes(array('id' => 'login', 'class' => 'form-control', 'placeholder' => 'Digite o seu login'));
        return $element;
    }

    public function getSenha()
    {
        $element = new Password('senha');
        $element->setLabel('Senha');
        $element->setAttributes(array('id' => 'senha', 'class' => 'form-control', 'placeholder' => 'Digite a sua senha'));
        return $element;
    }



    protected  function getSubmit()
    {
        $element = new Submit('logar');
        $element->setValue('Logar');
        $element->setAttributes(array('class' => 'btn btn-primary'));
        return $element;
    }

}