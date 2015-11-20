<?php

namespace Security\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * PermissaoAcl
 *
 * @ORM\Table(name="permissao_acl", indexes={@ORM\Index(name="fk_permissao_acl_recurso_sistema_idx", columns={"recurso_sistema_id"}), @ORM\Index(name="fk_permissao_acl_grupo1_idx", columns={"grupo_id"})})
 * @ORM\Entity(repositoryClass="Security\Repository\PermissaoAcl")
 */
class PermissaoAcl
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="permissao", type="string", length=45, nullable=true)
     */
    private $permissao;

    /**
     * @var \Security\Entity\Grupo
     *
     * @ORM\ManyToOne(targetEntity="Security\Entity\Grupo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     * })
     */
    private $grupo;

    /**
     * @var \Security\Entity\RecursoSistema
     *
     * @ORM\ManyToOne(targetEntity="Security\Entity\RecursoSistema", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recurso_sistema_id", referencedColumnName="id")
     * })
     */
    private $recursoSistema;

    /**
     * @param \Security\Entity\Grupo $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * @return \Security\Entity\Grupo
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $permissao
     */
    public function setPermissao($permissao)
    {
        $this->permissao = $permissao;
    }

    /**
     * @return string
     */
    public function getPermissao()
    {
        return $this->permissao;
    }

    /**
     * @param \Security\Entity\RecursoSistema $recursoSistema
     */
    public function setRecursoSistema($recursoSistema)
    {
        $this->recursoSistema = $recursoSistema;
    }

    /**
     * @return \Security\Entity\RecursoSistema
     */
    public function getRecursoSistema()
    {
        return $this->recursoSistema;
    }
    public function __construct( array $data)
    {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $this);
    }

    public function toArray()
    {
        $hydrator = new ClassMethods();
        return $hydrator->extract($this);
    }


}

