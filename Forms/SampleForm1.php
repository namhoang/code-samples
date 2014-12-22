<?php
/**
 * Model of invoice creation form.
 */
class Internal_Form_FirmInvoice_CreateForm extends Eng_Form
{
	/**
	 * Add form elements and subform on instantiation.
	 * 
	 * (non-PHPdoc)
	 * @see Zend_Form::init()
	 */
	public function init()
	{
		$this->addElement('hidden', 'firmId', array('required' => false));
		$this->addElement('text', 'dateIssued', array('required' => true, 'label' => 'Date Issued: '));
		$this->addElement('textarea', 'clientAddress', array('required' => true, 'label' => 'Client Address: '));
		$this->addElement('textarea', 'terms', array('required' => false, 'label' => 'Terms: '));
		$this->addElement('textarea', 'notesToClient', array('required' => false, 'label' => 'Notes To Client: '));
		$this->addElement('text', 'lateFee', array('required' => false, 'label' => 'Late Fee: '));
		$this->addElement('button', 'status', array('required' => true));
		
		// add revenue items subform
		$this->addSubForm(new Internal_Form_RevenueItem_AggregateForm(), 'revenueItems');
	}
	
	/**
	 * Prepare form for validation (invoke prior to self::isValid()).
	 * 
	 * @return void
	 */
	public function preValidation($postArray)
	{
		$this->getSubForm('revenueItems')->preValidation($postArray);		
	}
	
	/**
	 * Set element values based on an instance of \Eng\Entity\FirmInvoice.
	 * 
	 * @param \Eng\Entity\FirmInvoice $firmInvoice
	 * @return void
	 */
	public function setValuesFromEntity(\Eng\Entity\FirmInvoice $firmInvoice)
	{
		$this->getElement('dateIssued')->setValue($firmInvoice->getDateIssued()->format('m/d/Y'));
		$this->getElement('clientAddress')->setValue($firmInvoice->getClientAddress());		
		$this->getElement('terms')->setValue($firmInvoice->getTerms());
		$this->getElement('notesToClient')->setValue($firmInvoice->getNotesToClient());
		$this->getElement('lateFee')->setValue($firmInvoice->getLateFee());
		$this->getSubForm('revenueItems')->setValuesFromEntity($firmInvoice->getRevenueItems());
	}
}