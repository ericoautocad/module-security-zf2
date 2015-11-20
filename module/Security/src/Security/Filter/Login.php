<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 31/08/15
 * Time: 14:40
 */

namespace Security\Filter;

use Zend\InputFilter\InputFilter;

class Login extends  InputFilter{

    public function __construct(){
        $this->add(array(
            'name' =>  'login',
            'alow_empty' => false
        ));

        $this->add(array(
            'name' =>  'senha',
            'alow_empty' => false
        ));
    }
}