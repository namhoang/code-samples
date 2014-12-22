<?php
namespace Eng\Entity;
/**
 * @Entity @HasLifecycleCallbacks
 * @Table(name="billable_items")
 * @author Nam Hoang <nam@nano-think.com>
 */ 
class BillableItem
{
    /**
     * @Column
     * @var float
     */
    private $amount;   
    
    /**
     * @OneToOne(targetEntity="BillableItemExtension", inversedBy="billableItem", cascade={"persist", "remove"})
     * @JoinColumn(name="billable_item_extension_id", referencedColumnName="id")
     * @var \Eng\Entity\BillableItemExtension
     */
    private $billableItemExtension;
    
    /**
     * @Column
     * @var string
     */
    private $description;
    
    /**
     * @Column (name="date_created", type="utcdatetime")
     * @var \DateTime
     */
    private $dateCreated;
    
    /**
     * @Column (name="date_updated", type="utcdatetime")
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @ManyToOne(targetEntity="EventExpertReference", inversedBy="billableItems", cascade={"persist"})
     * @joinColumn(name="event_expert_reference_id", referencedColumnName="id") 
     * @var Eng\Entity\EventExpertReference
     */
    private $eventExpertReference;

    /**
     * @Id @GeneratedValue
     * @Column
     * @var integer
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="ExpertPayment", inversedBy="billableItems")
     * @joinColumn(name="payment_id", referencedColumnName="id") 
     */    
    private $payment;
    /**
     * @Column
     * @var string
     */
    private $type;

    /**
     * Constructor
     * 
     * @param \Eng\Entity\EventExpertReference $eventExpertReference
     * @param decimal $amount
     * @param string $description
     * @param \Eng\Entity\BillableItemExtension $billableItemExtension
     */
    public function __construct(EventExpertReference $eventExpertReference, $amount, $description, BillableItemExtension $billableItemExtension)
    {
        $this->eventExpertReference = $eventExpertReference;
        $this->amount = $amount;
        $this->description = $description;
        $this->billableItemExtension = $billableItemExtension;
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
    }
    
    /**
     * Validate if instance is deletable
     * 
     * @return boolean
     */
    public function isDeletable()
    {
        if($this->getPayment()){
            return false;
        }
        
        return true;
    }    
    
    /**
     * Before persisting this instance, 
     * set date updated to curent datetime
     * 
     * @PreUpdate
     */
    public function update()
    {
        $this->dateUpdated = new \DateTime();
    }
    
	/**
     * @return the $amount
     */
    public function getAmount ()
    {
        return $this->amount;
    }

	/**
     * @param number $amount
     */
    public function setAmount ($amount)
    {
        $this->amount = $amount;
    }

	/**
     * @return the $billableItemExtension
     */
    public function getBillableItemExtension ()
    {
        return $this->billableItemExtension;
    }

	/**
     * @param \Eng\Entity\BillableItemExtension $billableItemExtension
     */
    public function setBillableItemExtension ($billableItemExtension)
    {
        $this->billableItemExtension = $billableItemExtension;
    }

	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @param number $id
     */
    public function setId ($id)
    {
        $this->id = $id;
    }

	/**
     * @return the $dateCreated
     */
    public function getDateCreated ()
    {
        return $this->dateCreated;
    }

	/**
     * @return the $dateUpdated
     */
    public function getDateUpdated ()
    {
        return $this->dateUpdated;
    }

	/**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated ($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

	/**
     * @param \DateTime $dateUpdated
     */
    public function setDateUpdated ($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

	/**
     * @return the $description
     */
    public function getDescription ()
    {
        return $this->description;
    }

	/**
     * @param string $description
     */
    public function setDescription ($description)
    {
        $this->description = $description;
    }

	/**
     * @return the $eventExpertReference
     */
    public function getEventExpertReference ()
    {
        return $this->eventExpertReference;
    }

	/**
     * @param \Eng\Entity\EventExpertReference $eventExpertReference
     */
    public function setEventExpertReference ($eventExpertReference)
    {
        $this->eventExpertReference = $eventExpertReference;
    }

	/**
     * @return the $payment
     */
    public function getPayment ()
    {
        return $this->payment;
    }

	/**
     * @param field_type $payment
     */
    public function setPayment ($payment)
    {
        $this->payment = $payment;
    }

	/**
	 * Get Type (Delegate Method)
     * @return the $type
     */
    public function getType ()
    {
        if($this->billableItemExtension){
            return $this->billableItemExtension->getType();            
        }
        
        return false;
    }
    
    /**
     * Get Type Display (Delegate Method)
     * @return boolean
     */
    public function getTypeDisplay()
    {
        if($this->billableItemExtension){
            return $this->billableItemExtension->getTypeDisplay();
        }
        
        return false;        
    }

	/**
     * @param string $type
     */
    public function setType ($type)
    {
        $this->type = $type;
    }
}