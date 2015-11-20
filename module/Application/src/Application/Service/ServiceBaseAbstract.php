<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 13/11/15
 * Time: 10:24
 */

namespace Application\Service;

/**
 * Class ServiceBaseAbstract
 * Abstract base class for all services
 * @package Application\Service
 */
class ServiceBaseAbstract
{
    // Doctrine Entity Manager
    protected $em;
    // Service Manageer
    protected $sm;

    public function __construct( $sm, $em )
    {
        // Make the service manager available to all services
        $this->sm = $sm;
        // Make the entity manager available to all services
        $this->em = $em;
    }
}