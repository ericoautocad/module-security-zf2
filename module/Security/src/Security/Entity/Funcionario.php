<?php

namespace Security\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Funcionario
 *
 * @ORM\Table(name="funcionario", indexes={@ORM\Index(name="fk_funcionario_usuario1_idx", columns={"usuario_id"})})
 * @ORM\Entity(repositoryClass="Security\Repository\Funcionario")
 */
class Funcionario
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
     * @ORM\Column(name="nome", type="string", length=60, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=45, nullable=true)
     */
    private $telefone;
    /**
     * @var string
     *
     * @ORM\Column(name="horario_trabalho", type="string", length=250, nullable=true)
     */
    private $horarioTrabalho;

    /**
     * @var \Security\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Security\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __construct( array $data)
    {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $this);
    }

    public function toArray()
    {
        $hydrator = new ClassMethods();
        $data =  $hydrator->extract($this);
        $data['perfil'] = $data['usuario']->getGrupo()->getId();
        $data['login'] = $data['usuario']->getLogin();
        return $data;
    }

    /**
     * @param \Security\Entity\Usuario $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return \Security\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param string $horarioTrabalho
     */
    public function setHorarioTrabalho($horarioTrabalho)
    {
        $this->horarioTrabalho = $horarioTrabalho;
    }

    /**
     * @return string
     */
    public function getHorarioTrabalho()
    {
        return $this->horarioTrabalho;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
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


}