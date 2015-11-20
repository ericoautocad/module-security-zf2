<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/08/15
 * Time: 13:10
 */

namespace Security\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RotaRecursoSistema extends  AbstractHelper{

    public function __invoke($rotaSistema)
    {
        $rotaRecurso = explode('\\',$rotaSistema);
        $rotaSistema = $rotaRecurso[count($rotaRecurso) - 1];
        $rotaSistema = str_replace("Controller", "",  $rotaSistema );
        return $rotaSistema;
    }

} 