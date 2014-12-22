<?php
/**
 * Strategy Pattern for event cost calcuations.
 * 
 * @author Nam Hoang <nam@nano-think.com>
 */
abstract class Eng_Strategy_Event_AbstractCostCalculator
{
    /**
     * @var Eng\Entity\Event
     */
    protected $event;
    
    /**
     * @var Eng\Entity\EventRate
     */
    protected $eventRate;
    
    /**
     * Factory method to create concrete strategy products
     * 
     * @param \Eng\Entity\Event $event
     * @param \Eng\Entity\EventRate $eventRate
     * @throws Exception
     * @return Eng_Strategy_Event_Cost_Hourly|Eng_Strategy_Event_Cost_Fixed
     */
    public function create(\Eng\Entity\Event $event, \Eng\Entity\EventRate $eventRate)
    {
        switch($eventRate->getType()){
            case Eng\Entity\EventRate::TYPE_HOURLY_VALUE:
                return new Eng_Strategy_Event_CostCalculator_Hourly($event, $eventRate);
                break;
            case Eng\Entity\EventRate::TYPE_FIXED_VALUE:
                return new Eng_Strategy_Event_CostCalculator_Fixed($event, $eventRate);
                break;
            default:
                throw new Exception('Invalid wage rate type.  Must align with a Eng_Strategy_Event_CostCalculator type.');
                break;
        }        
    }

    /**
     * Constructor.
     * 
     * @param \Eng\Entity\Event $event
     * @param \Eng\Entity\EventRate $eventRate
     * @return void
     */
    public function __construct(\Eng\Entity\Event $event, \Eng\Entity\EventRate $eventRate)
    {
        $this->event = $event;
        $this->eventRate = $eventRate;
    }

    /**
     * Cost algorithm.
     * 
     * @return float
     */
    abstract public function cost();
    
	/**
     * @return the $event
     */
    public function getEvent ()
    {
        return $this->event;
    }

	/**
     * @return the $eventRate
     */
    public function getEventRate ()
    {
        return $this->eventRate;
    }

	/**
     * @param \Eng\Entity\Event $event
     */
    public function setEvent ($event)
    {
        $this->event = $event;
    }

	/**
     * @param \Eng\Entity\EventRate $eventRate
     */
    public function setEventRate ($eventRate)
    {
        $this->eventRate = $eventRate;
    }

}