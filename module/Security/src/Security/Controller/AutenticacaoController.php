<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 31/08/15
 * Time: 13:42
 */

namespace Security\Controller;



use Security\Form\AlteraDadosAcesso;
use Security\Form\Login;
use Zend\Authentication\Storage\Session;
use Zend\View\Model\ViewModel;

class AutenticacaoController extends  SecurityAbstractController {

    public  function indexAction()
    {

    }

    public function loginAction()
    {
        $request = $this->getRequest();
        /**
         * @var $auth \Zend\Authentication\AuthenticationService
         * @var $adapter \Security\Authentication\Adapter
         */
        if($this->identity()){
            $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/'));
        }
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $adapter = $auth->getAdapter();
        $form = new Login();
        if($request->isPost()){
            $form->setInputFilter( new \Security\Filter\Login());
            $data = $request->getPost()->toArray();
            $form->setData($data);

            if($form->isValid()){
                $adapter->setLogin($data['login'])->setPassword($data['senha']);

                if( $auth->authenticate()->isValid() ){

                    $repo = $this->getEm('Security\Entity\Usuario');
                    $auth->getStorage()->write( $repo->getDataUserSession($auth, $this->getServiceLocator()->get('servicemanager') ) );


                    $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security'));
                } else{
                    $this->flashMessenger()->addErrorMessage($auth->authenticate()->getMessages());
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/autenticacao/login'));
                }
            }
        }

        $view = new ViewModel( array('form' => $form) );
        $view->setTemplate('security/autenticacao/form.phtml');
        $view->setTerminal(true);
        return $view;
    }
    public function logoutAction()
    {
        /**
         * @var $authentication \Zend\Authentication\AuthenticationService
         */
        $authentication = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if($authentication->hasIdentity()){
            $authentication->clearIdentity();
        }
        return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/autenticacao/login'));
    }

    public function alterarDadosAcessoAction()
    {
        /**
         * @var $authentication \Zend\Authentication\AuthenticationService
         */
        $request = $this->getRequest();
        $form = new AlteraDadosAcesso();
        if($request->isPost()){
            $form->setInputFilter( new \Security\Filter\AlterarDadosAcesso() );
            $data = $request->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()){

                if($data["confirmar_nova_senha"] == $data["nova_senha"]){
                    $service = $this->getServiceLocator()->get('security-service-usuario');
                    $authentication = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                    //$idUser = $authentication->getIdentity()["user_id"];
                    if($service->alterarDadosAcesso($data, $authentication->getIdentity()["user_id"])){
                        $this->flashMessenger()->addSuccessMessage("Dados alterados com sucesso, para testar faça logout no sistema.");
                    }else{
                        $this->flashMessenger()->addErrorMessage("Sua senha atual não confere com a digitada, preencha os campos corretamente!. ");
                    }
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/autenticacao/alterar-dados-acesso'));
                }else{
                    $this->flashMessenger()->addErrorMessage("O valor digitado nos campos: nova senha e no campo repetir senha não são iguais. ");
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/autenticacao/alterar-dados-acesso'));
                }

            }else{
                $this->flashMessenger()->addErrorMessage("Não foram informados dados válidos para alterar seus dados de acesso.");
                return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/autenticacao/alterar-dados-acesso'));
            }

        }

        $view = new ViewModel( array('form' => $form) );
        $view->setTemplate('security/autenticacao/alterar-dados-acesso.phtml');
        return $view;

    }
} 