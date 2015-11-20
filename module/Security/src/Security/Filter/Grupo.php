<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 21/08/15
 * Time: 08:23
 */

namespace Security\Filter;


use Zend\InputFilter\InputFilter;

class Grupo extends InputFilter {

    public function __construct()
    {
        $this->add(
            array(
                'name' => 'nome',
                'allow_empty' => false
            )
        );
    }
} 