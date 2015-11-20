<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 21/08/15
 * Time: 08:12
 */

namespace Security\Form;


use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class Grupo extends  Form {

    public  function  __construct()
    {
        parent::__construct('formgrupo');
        $this->add($this->getNome());
        $this->add($this->getSubmit());

    }

    public function getNome()
    {
        $element = new Text('nome');
        $element->setLabel('Nome');
        $element->setAttributes(
            array('id'=> 'nome', 'class' => 'form-control', 'placeholder' => 'Digite o nome do grupo de usuários', 'autofocus' => 'true')
        );
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

