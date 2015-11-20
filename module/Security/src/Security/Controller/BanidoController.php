<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 19/11/15
 * Time: 18:20
 */

namespace Security\Controller;


use Zend\View\Model\ViewModel;

class BanidoController extends  SecurityAbstractController {

    public function acessoNegadoAction()
    {
        return new ViewModel();
    }
} 