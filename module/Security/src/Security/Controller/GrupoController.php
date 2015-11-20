<?php
/**
 * Created by PhpStorm.
 * User: erico.oliveira
 * Date: 21/08/15
 * Time: 08:04
 */

namespace Security\Controller;

use Security\Form\Grupo;
use Zend\View\Model\ViewModel;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class GrupoController extends SecurityAbstractController {

    public function indexAction()
    {
        $repoGrupo = $this->getEm('Security\Entity\Grupo');
        $list = $repoGrupo->findAll();
        return new ViewModel( array(
            'list' => $list
        ));
    }

    public function adicionarAction()
    {
        // definindo variaveis
        $request = $this->getRequest();
        $serviceGrupo = $this->getServiceLocator()->get('security-service-grupo');
        $form = new Grupo();
        // definindo acao conforme method da request
        if($request->isPost())
        {
            $form->setInputFilter(new \Security\Filter\Grupo());
            $data = $request->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()){
                if($serviceGrupo->insert($data, 'Security\Entity\Grupo'))
                {
                    $this->flashMessenger()->addSuccessMessage('Dados inseridos com sucesso!');
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/grupo'));
                } else{
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao cadastrar os dados!');
                    return $this->redirect()->toRoute( array('controller' => 'grupo', 'action' => 'adicionar'));
                }
            }
        }

        $view = new ViewModel( array('form' => $form) );
        $view->setTemplate('security/grupo/form.phtml');
        return $view;
    }

    public function editarAction()
    {
        //definindo variaveis
        $request = $this->getRequest();
        $id = $this->Params('id');
        $serviceGrupo = $this->getServiceLocator()->get('security-service-grupo');
        $form =  new Grupo();
        $dadosGrupo = $this->getEm('Security\Entity\Grupo')->find($id)->toArray();
        $form->setData($dadosGrupo);
        // definindo acao conforme method da request
        if($request->isPost()){
            $form->setInputFilter(new \Security\Filter\Grupo());
            $data = $request->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()){
                if($serviceGrupo->update($data, 'Security\Entity\Grupo', $id)){
                    $this->flashMessenger()->addSuccessMessage('Dados editados com sucesso!');
                    return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/grupo'));
                } else{
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao editar os dados!');
                    return $this->redirect()->toRoute( array('controller' => 'grupo', 'action' => 'editar'));
                }
            }
        }
        $view = new ViewModel( array('form' => $form) );
        $view->setTemplate('security/grupo/form.phtml');
        return $view;
    }

    public function excluirAction()
    {
        //definindo variaveis
        $request = $this->getRequest();
        $id = $this->Params('id');
        $serviceGrupo = $this->getServiceLocator()->get('security-service-grupo');
        $dadosGrupo = $this->getEm('Security\Entity\Grupo')->find($id);
        // checando se o grupo é valido
        if($dadosGrupo){
            //tenta excluir grupo
            try
            {
                if($serviceGrupo->delete('Security\Entity\Grupo', $id)){
                    $this->flashMessenger()->addSuccessMessage('Dados excluídos com sucesso!');
                } else{
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao tentar excluir o registro!');
                }
            }
            catch (ForeignKeyConstraintViolationException $e)
            {
                $this->flashMessenger()->addErrorMessage('O grupo solicitado para exclusão está associado a algum usuário existente e por isso não pode ser excluído!');
            }
        } else{
            $this->flashMessenger()->addErrorMessage('Os dados informados para exclusão não são validos!');
        }
        return $this->redirect()->toUrl($this->getUrlFromSubfolderApplication('/security/grupo'));
    }
} 