<?php
/**
 * Action Controller for questionnaire submission answers.
 * 
 * @author Nam Hoang <nam@nano-think.com>
 */
class Internal_SubmissionAnswersController extends Zend_Controller_Action
{
    /**
     * Controller initialization.
     */
    public function init()
    {
        // initialize doctrine and retrieve entityManager
        $this->_doctrine = Zend_Registry::get('doctrine');
        $this->_em = $this->_doctrine->getEntityManager();
    
        $this->currentUser = $this->view->currentUser = Zend_Registry::get('user');
        
        $ajaxContext = $this->_helper->getHelper('ajaxContext');
        $ajaxContext->addActionContext('edit-client-comments', 'json')
                    ->initContext();
    }
    
    /**
     * Allow user to edit clietn comments.
     * 
     * @return void
     */
    public function editClientCommentsAction()
    {
        // retrieve based on request's id* param
        $submissionAnswerRepo = $this->_em->getRepository('Eng\Entity\SubmissionAnswer');
        $submissionAnswer = $submissionAnswerRepo->find($this->getRequest()->getParam('id'));
        $this->view->assign('submissionAnswer', $submissionAnswer);
        
        // instantiate new submission answer form
        $form = new Common_Form_SubmissionAnswerClientCommentsForm();        
        
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getPost())){
                $submissionAnswer->setClientComments($form->getValue('clientComments'));
                
                $this->_em->persist($submissionAnswer);
                $this->_em->flush();
                
                $this->view->assign('success', true);
                $this->view->assign('display', $this->view->partial('/submission-answers/comments.display.phtml', array('submissionAnswer' => $submissionAnswer)));
            }
        }else{
            $form->setValuesFromEntity($submissionAnswer);
        }
        
        $this->view->assign('form', $this->view->partial('/submission-answers/edit-client-comments.form.phtml', array('form' => $form, 'submissionAnswer' => $submissionAnswer)));
    }
}