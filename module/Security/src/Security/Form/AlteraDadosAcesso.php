<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/09/15
 * Time: 14:55
 */

namespace Security\Form;


use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Form;

class AlteraDadosAcesso extends Form {

    public function __construct(){
        parent::__construct('alteraDadosAcesso');
        $this->add($this->getSenhaAtual());
        $this->add($this->getNovaSenha());
        $this->add($this->getConfirmarNovaSenha());
        $this->add($this->getSubmit());
        $this->setAttributes(array('action'=> '/security/autenticacao/alterar-dados-acesso', 'method' => 'POST'));

    }

    protected function getSenhaAtual()
    {
        $element = new Password('senha_atual');
        $element->setLabel('Nova Senha:');
        $element->setAttributes(array('class' => 'form-control', 'placeholder' => 'Informe sua senha atual'));
        return $element;
    }

    protected function getNovaSenha()
    {
        $element = new Password('nova_senha');
        $element->setLabel('Nova Senha:');
        $element->setAttributes(array('class' => 'form-control', 'placeholder' => 'Informe a nova senha'));
        return $element;
    }

    protected function getConfirmarNovaSenha()
    {

        $element = new Password('confirmar_nova_senha');
        $element->setLabel('Repetir Nova Senha:');
        $element->setAttributes(array('class' => 'form-control',  'placeholder' => 'Repita a nova senha informada no campo anterior.'));
        return $element;
    }

    protected  function getSubmit()
    {
        $element = new Submit('alterar');
        $element->setValue('Alterar Dados de Acesso');
        $element->setAttributes(array('class' => 'btn btn-primary'));
        return $element;
    }
} 