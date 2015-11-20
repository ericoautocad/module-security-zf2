<?php

namespace Security\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="fk_usuario_grupo1_idx", columns={"grupo_id"})})
 * @ORM\Entity(repositoryClass="Security\Repository\Usuario")
 */
class Usuario
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
     * @ORM\Column(name="login", type="string", length=60, nullable=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="senha", type="string", length=255, nullable=true)
     */
    private $senha;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="ativo", type="integer", nullable=true)
     */
    private $ativo = '1';

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
     * @param int $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return int
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

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
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }
    public function __construct( array $data)
    {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $this);
        if(!empty($data)){

            $hydrator = new ClassMethods();
            $hydrator->hydrate($data, $this);
            $this->generateSaltSecurity();
            $this->setSenha($this->encryptPassword($this->getSenha()));
        }
    }

    public function generateSaltSecurity($login = null)
    {
        if( !is_null($this->getLogin()) && is_null($login) ) {
            $this->setSalt(Rand::getString(64, $this->getLogin(), true));
        }
        if(!is_null($this->getLogin()) && !is_null($login) && $login !== $this->getLogin() ){
            $this->setSalt(Rand::getString(64, $this->getLogin(), true));
        }

    }
    public function toArray()
    {
        $hydrator = new ClassMethods();
        return $hydrator->extract($this);
    }
    public  function encryptPassword($senha)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setSalt($this->getSalt());
        return $bcrypt->create($senha);
    }

}