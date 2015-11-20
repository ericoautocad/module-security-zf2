<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/09/15
 * Time: 15:19
 */

namespace Security\Filter;


use Zend\InputFilter\InputFilter;

class AlterarDadosAcesso extends InputFilter{

    public function __construct(){
        $this->add(array(
            'name' =>  'senha_atual',
            'alow_empty' => false
        ));

        $this->add(array(
            'name' =>  'nova_senha',
            'alow_empty' => false
        ));

        $this->add(array(
            'name' =>  'confirmar_nova_senha',
            'alow_empty' => false
        ));
    }
} 