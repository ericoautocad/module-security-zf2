<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 28/09/15
 * Time: 17:36
 */

namespace Security\Service;



use Base\Service\AbstractService;
use Zend\Crypt\Password\Bcrypt;

class Usuario extends SecurityAbstractService {

    /*public function update(array $data , $entity, $id)
    {
        return parent::update($data, $entity, $id);
    }*/
    public function insert(array $data , $entity)
    {
        /*$dados['salt'] = Rand::getString(64, $data['login'], true);
        $bcrypt = new Bcrypt();
        $bcrypt->setSalt($dados['salt']);
        $dados['senha'] = $bcrypt->create($data['senha']);
        */
        //setando a refencia entre a entidade grupo e a entidade usuario
        $data['grupo'] = $this->getEmRef('Security\Entity\Grupo',$data['grupo'] );

        return parent::insert($data, $entity);
    }
    public function update(array $data , $entity, $id)
    {
        $data['grupo'] = $this->getEmRef('Security\Entity\Grupo',$data['grupo'] );
        return parent::update($data, $entity, $id);
    }

    public function alterarDadosAcesso(array $data, $id)
    {

        /**
         * @var $usuario \Security\Entity\Usuario
         */

        $usuario = $this->getEmRef('Security\Entity\Usuario', $id );

        if($usuario){
            if( $this->checaSenhaAtualUsuario($usuario, $data['senha_atual']) ){
                $dataUser = $usuario->toArray();
                $dataUser['senha'] = $usuario->encryptPassword($data["nova_senha"]);
                return parent::update($dataUser, 'Security\Entity\Usuario', $id);
            }
            return false;
        }
        return false;

    }

    /**
     *
     * @param \Security\Entity\Usuario $obUsuario
     * @param $senha
     * @return bool
     */
    public function checaSenhaAtualUsuario( \Security\Entity\Usuario $obUsuario, $senha )
    {
        $senhaUser = $obUsuario->encryptPassword($senha);
        return ( $senhaUser == $obUsuario->getSenha() );
    }
} 