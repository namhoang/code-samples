<?php
/**
 * Action Controller for billable items.
 * 
 * @author Nam Hoang <nam@nano-think.com>
 */
class Internal_BillableItemsController extends Eng_Controller_Action
{
    /**
     * Controller Initialization
     * 
     * @return void
     */
    public function init()
    {
        parent::init();

        // Initialize Ajax Contexts
        $ajaxContext = $this->_helper->getHelper('ajaxContext');
        $ajaxContext->addActionContext('create', 'json')
					->addActionContext('update', 'json')
					->addActionContext('delete', 'json')
					->addActionContext('view-referrals-by-expert', 'json')
					->initContext();
    }
    
    /**
     * Create Billable Item.
     * 
     * @return void
     */
    public function createAction()
    {
        $form = new Internal_Form_BillableItem_BillableItemForm($this->_em, 
                Internal_Form_BillableItemExtension_BillableItemExtensionForm::factory(
                        $this->getRequest()->getPost('type')));
         
        if($form->isValid($this->getRequest()->getPost())){
            $this->_em->persist($form->createEntity());
            $this->_em->flush();
    
            $this->view->success = true;
            return true;
        }else{
            $this->view->success = false;
        }
    
        $this->view->assign('form', 
            $this->view->partial('/events/view/billable-item-form.phtml'), array('form' => $form));
    }
        
    /**
     * Update Billable Item.
     * 
     * @return void
     */
	public function updateAction()
	{
	    $billableItemId = $this->getRequest()->getPost('billableItemId');
	    $billableItemRepo = $this->_em->getRepository('Eng\Entity\BillableItem');
	    $billableItem = $billableItemRepo->find($billableItemId);
	
        $form = new Internal_Form_BillableItem_BillableItemForm($this->_em, Internal_Form_BillableItemExtension_BillableItemExtensionForm::factory($billableItem->getBillableItemExtension()->getType()));
	    $form->setAttrib('id', 'billableItemForm_' . $billableItemId);
	    
	    if($this->getRequest()->getPost('submit')){
	        if($form->isValid($this->getRequest()->getPost())){
                $form->setEntityValues($billableItem);
                $this->_em->persist($billableItem);
	            $this->_em->flush();
	
	            $this->view->table = $this->view->partial('/experts/billable-items.table.phtml', array('expertReference' => $billableItem->getEventExpertReference())); // get latest table
	            $this->view->assign('success', true);
	            $this->view->assign('eventExpertReferenceId', $billableItem->getEventExpertReference()->getId());
	        }else{
	        $this->view->assign('success', false);
	        }
        }else{
            $form->setValuesFromEntity($billableItem);            
        }
	
        $this->view->form = $this->view->partial('/events/view/billable-item-form.phtml', array('form' => $form));
	}
	
	/**
	 * Delete Billable Item
	 * 
	 * @return void
	 */
	public function deleteAction()
	{
	    $billableItemRepo = $this->_em->getRepository('Eng\Entity\BillableItem');
	    $billableItem = $billableItemRepo->find($this->getRequest()->getPost('billableItemId'));
	
	    if($billableItem){
	        if($billableItem->isDeletable()){
	            $eventExpertReference = $billableItem->getEventExpertReference();
	
	            $this->_em->remove($billableItem);
	            $this->_em->flush();
	
	            $this->view->table = $this->view->partial('/experts/billable-items.table.phtml', array('expertReference' => $eventExpertReference)); // get latest table
	            $this->view->success = true;
	        }else{
	            $this->view->msg = "Sorry, this billable item is not deletable because it is attached to a payment";
	            $this->view->success = false;
	        }
	    }else{
	        $this->view->msg = "Sorry, this billable item does not exist.";
	        $this->view->success = false;
	    }
	}
	
	/**
	 * Search for Billable Items of type 'referral' by expert id.
	 * 
	 * @return void
	 */
	public function viewReferralsByExpertAction()
	{
	    $expert = $this->_em->getRepository('Eng\Entity\Expert')->find($this->getRequest()->getParam('expertId'));
	    
		$qb = $this->_em->createQueryBuilder();
		$qb->select('billableItemExtension')
		   ->from('Eng\Entity\BillableItemExtensionReferral', 'billableItemExtension');
		   ->innerJoin('billableItemExtension.referredEventExpertReference', 'referredEventExpertReference');
		   ->innerJoin('referredEventExpertReference.expert', 'expert');
		   ->andWhere('expert = :expertId');
		   ->setParameter('expertId', $this->getRequest()->getParam('expertId'));
		
		$query = $qb->getQuery();
		
		$billableItems = array();
		if($billableItemExtensions = $query->getResult())
		{
		    foreach($billableItemExtensions as $billableItemExtension){
		        $billableItems[] = $billableItemExtension->getBillableItem();
		    }		    
		}

		$this->view->assign('modal', $this->view->partial('/billable-items/view-referrals-by-expert.modal.phtml', array('billableItems' => $billableItems, 'referralReference' => $expert->getReferredByReference())));
		$this->view->assign('refereeName', $expert->getFullNameFL());
	}
}