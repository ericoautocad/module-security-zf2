<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 25/08/15
 * Time: 13:37
 */

namespace Security\Form;


use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class Funcionario extends Form {

    public function __construct(ServiceLocatorInterface $sm)
    {
        parent::__construct('formfuncionario');

        // definindo variaveis
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $repoGrupo = $em->getRepository('Security\Entity\Grupo');
        $arPerfil = array("" => "Selecione o perfil do funcionário");
        $arPerfil  += $repoGrupo->fetchPairs();
        $this->add($this->getNome());
        $this->add($this->getTelefone());
        $this->add($this->getEmail());
        //Os campos abaixo são necessario para cadastro de usuario
        $perfil = $this->getPerfil()->setAttribute('options', $arPerfil);
        $this->add($perfil);
        $this->add($this->getLogin());
        $this->add($this->getSenha());
        $this->add($this->getHorarioTrabalho());
        $this->add($this->getSubmit());


    }

    public function getHorarioTrabalho()
    {
        $element = new Text('horarioTrabalho');
        $element->setLabel('Horário de Trabalho:');
        $element->setAttributes(array('id' => 'horarioTrabalho', 'class' => 'form-control', 'placeholder' => '00:00 ás 00:00'));
        return $element;
    }
    public function getPerfil()
    {
        $element = new Select('perfil');
        $element->setLabel('Perfil:');
        $element->setAttributes(array('id' => 'perfil', 'class' => 'form-control validar'));
        return $element;
    }
    public function getLogin()
    {
        $element = new Text('login');
        $element->setLabel('Login:');
        $element->setAttributes(array('id' => 'login', 'class' => 'form-control validar', 'placeholder' => 'Digite o login do funcionário'));
        return $element;
    }

    public function getSenha()
    {
        $element = new Password('senha');
        $element->setLabel('Senha:');
        $element->setAttributes(array('id' => 'senha', 'class' => 'form-control validar', 'placeholder' => 'Digite a senha do funcionário'));
        return $element;
    }

    public function getNome()
    {
        $element = new Text('nome');
        $element->setLabel('Nome:');
        $element->setAttributes(array('id' => 'nome', 'class' => 'form-control validar', 'placeholder' => 'Digite o nome do funcionário', 'autofocus' => 'true'));
        return $element;
    }

    public function getEmail()
    {
        $element = new Text('email');
        $element->setLabel('Email:');
        $element->setAttributes(array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Digite o e-mail do funcionário'));
        return $element;
    }

    public function getTelefone()
    {
        $element = new Text('telefone');
        $element->setLabel('Telefone:');
        $element->setAttributes(array('id' => 'telefone', 'class' => 'form-control telefone', /*'data-mask'=>"(99) 9999-9999" */ ));
        return $element;
    }

    protected  function getSubmit()
    {
        $element = new Submit('salvar');
        $element->setValue('Salvar');
        $element->setAttributes(array('class' => 'btn btn-primary'));
        return $element;
    }

} 