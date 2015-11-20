<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 25/08/15
 * Time: 13:20
 */

namespace Security\Controller;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Security\Form\Funcionario;
use Zend\InputFilter\InputFilterInterface;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
class FuncionarioController extends SecurityAbstractController {

    public function indexAction()
    {
        $repoFuncionario = $this->getEm('Security\Entity\Funcionario');
        $repoGrupo = $this->getEm('Security\Entity\Grupo');
        $list = $repoFuncionario->getFuncionarios();
        $page = $this->params()->fromRoute('page');
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
            ->setDefaultItemCountPerPage(10);
        $form = new Funcionario($this->getServiceLocator()->get('servicemanager'));
        $perfil = $repoGrupo->fetchPairs();
        $form->setAttributes(array('action' => $this->getUrlFromSubfolderApplication('/security/funcionario/adicionar')));
        return new ViewModel( array(
            'list' => $paginator,
            'page' => $page,
            'formCadastro' => $form,
            'perfisFuncionario' => $perfil
        ));
    }

    public function adicionarAction()
    {
        //definindo variaveis
        $request = $this->getRequest();
        $service = $this->getServiceLocator()->get('security-service-funcionario');
        $form = new Funcionario($this->getServiceLocator()->get('servicemanager'));

        if($request->isPost()){
            $form->setInputFilter(new \Security\Filter\Funcionario($this->getServiceLocator()->get('servicemanager')), true);
            $data = $request->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()){
                if($service->insert($data, 'Security\Entity\Funcionario')){
                    $this->flashMessenger()->addSuccessMessage('Dados inseridos com sucesso!');
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/funcionario'));
                }
            }
        }
            $view = new ViewModel( array('form' => $form) );
            $view->setTemplate('security/funcionario/form.phtml');
            return $view;
    }

    public  function editarAction()
    {
        // definindo variaveis
        $request = $this->getRequest();
        $service = $this->getServiceLocator()->get('security-service-funcionario');
        $id = $this->Params('id');
        $form = new Funcionario($this->getServiceLocator()->get('servicemanager'));
        $dataFuncionario = $this->getEm('Security\Entity\Funcionario')->find($id)->toArray();

        $form->setData($dataFuncionario);
        if($request->isPost()){

            $data = $request->getPost()->toArray();
            $validaLoginUnico = ($data['login'] != $dataFuncionario['login'])? true : false;
            $form->setInputFilter( new \Security\Filter\Funcionario($this->getServiceLocator()->get('servicemanager')), $validaLoginUnico);
            $form->setData($data);
            if($form->isValid()){

                if($service->update($data, 'Security\Entity\Funcionario', $id) ){
                    $this->flashMessenger()->addSuccessMessage('Dados inseridos com sucesso!');
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/funcionario'));
                }
            }

        }
        $view = new ViewModel( array('form' => $form) );
        $view->setTemplate('security/funcionario/form.phtml');
        return $view;
    }

    public function excluirAction()
    {
        //definindo variaveis
        $request = $this->getRequest();
        $id = $this->Params('id');
        $serviceFuncionario = $this->getServiceLocator()->get('security-service-funcionario');
        $dadosFuncionario = $this->getEm('Security\Entity\Funcionario')->find($id);
        // checando se o grupo é valido
        if($dadosFuncionario){
            //tenta excluir grupo
            try
            {
                if($serviceFuncionario->delete('Security\Entity\Funcionario', $id)){
                    $this->flashMessenger()->addSuccessMessage('Dados excluídos com sucesso!');
                } else{
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao tentar excluir o registro!');
                }
            }
            catch (ForeignKeyConstraintViolationException $e)
            {
                $this->flashMessenger()->addErrorMessage('O funcionário solicitado para exclusão está associado a algum registro existente e por isso não pode ser excluído, consulte as restrições do seu banco de dados ou o Administrador do sistema para mais informações!');
            }
        } else{
            $this->flashMessenger()->addErrorMessage('Os dados informados para exclusão não são validos!');
        }
        return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/funcionario'));
    }
} 