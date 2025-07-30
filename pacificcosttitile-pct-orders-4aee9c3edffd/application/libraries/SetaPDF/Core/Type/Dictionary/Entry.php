<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Entry.php 366 2012-12-14 09:06:03Z maximilian $
 */

/**
 * Class representing a pair of a name object and a value in a dictionary
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_Dictionary_Entry extends SetaPDF_Core_Type_Abstract
    implements SplObserver
{
    /**
     * The key
     *
     * @var SetaPDF_Core_Type_Name
     */
    protected $_key;

    /**
     * The value
     *
     * @var SetaPDF_Core_Type_Abstract
     */
    protected $_value;

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Name $key
     * @param SetaPDF_Core_Type_Abstract $value
     */
    public function __construct(SetaPDF_Core_Type_Name $key = null, SetaPDF_Core_Type_Abstract $value = null)
    {
        unset($this->_observed);

        if (null !== $key) {
            $this->_key = $key;
        }

        if (null !== $value) {
            if ($value instanceof SetaPDF_Core_Type_IndirectObject) {
                $value = new SetaPDF_Core_Type_IndirectReference($value);
            }

            $this->_value = $value;
        }
    }

    /**
     * Implementation of __clone()
     *
     * @see SetaPDF_Core_Type_Abstract::__clone()
     */
    public function __clone()
    {
        $this->_key = clone $this->_key;
        $this->_value = clone $this->_value;

        parent::__clone();
    }

    /**
     * Add an observer to the object
     *
     * Implementation of the observer pattern.
     * This overwritten method forwards the attach()-call
     * to the key and value.
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        $isObserved = isset($this->_observed);
        parent::attach($observer);

        if (false === $isObserved) {
            if (null !== $this->_key) {
                $this->_key->attach($this);
            }

            if (null !== $this->_value) {
                $this->_value->attach($this);
            }
        }
    }

    /**
     * Triggered if a value of this object is changed
     *
     * Forward this to the parent document.
     *
     * @param SplSubject $SplSubject
     */
    public function update(SplSubject $SplSubject)
    {
        if (isset($this->_observed)) {
            $this->notify();
        }
    }

    /**
     * Set the key object
     *
     * @param SetaPDF_Core_Type_Name $key
     */
    public function setKey(SetaPDF_Core_Type_Name $key)
    {
        if (null !== $this->_key) {
            if ($this->_key->isObserved()) {
                $this->_key->detach($this);
            }
            $this->_key->cleanUp();
        }

        $this->_key = $key;
        if (isset($this->_observed)) {
            $this->_key->attach($this);
            $this->notify();
        }
    }

    /**
     * Get the key object
     *
     * @return SetaPDF_Core_Type_Name
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * Get the key value
     *
     * @return string
     */
    public function getKeyValue()
    {
        return $this->_key->getValue();
    }

    /**
     * Set the value object
     *
     * @param SetaPDF_Core_Type_Abstract $value
     * @throws InvalidArgumentException
     */
    public function setValue($value)
    {
        if (!($value instanceof SetaPDF_Core_Type_Abstract)) {
            if ($this->_value !== null) {
                $this->_value->setValue($value);
                return;
            }

            throw new InvalidArgumentException('Parameter should be a value of type SetaPDF_Core_Type_Abstract');
        }

        if ($value instanceof SetaPDF_Core_Type_IndirectObject) {
            $value = new SetaPDF_Core_Type_IndirectReference($value);
        }

        $oldValue = null;
        if (null !== $this->_value) {
            if ($this->_value->isObserved()) {
                $this->_value->detach($this);
            }
            $oldValue = $this->_value;
        }

        $this->_value = $value;

        if (isset($this->_observed)) {
            $this->_value->attach($this);
            $this->notify();
        }

        if ($oldValue) {
            $oldValue->cleanUp();
        }
    }

    /**
     * Get the value object
     *
     * @return SetaPDF_Core_Type_Abstract|SetaPDF_Core_Type_IndirectReference
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Ensures the value
     *
     * @param boolean $forceObservation
     * @return SetaPDF_Core_Type_Abstract|SetaPDF_Core_Type_Dictionary
     * @see SetaPDF_Core_Type_Abstract::ensure()
     */
    public function ensure($forceObservation = null)
    {
        return $this->_value->ensure($forceObservation);
    }

    /**
     * Converts the object to a pdf string
     *
     * @param SetaPDF_Core_Document SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        return (
            $this->_key->toPdfString($pdfDocument)
                . $this->_value->toPdfString($pdfDocument)
        );
    }

    /**
     * Converts the PDF data type to a PHP array and returns it
     *
     * @see SetaPDF_Core_Type_Abstract::toPhp()
     * @return array
     */
    public function toPhp()
    {
        return array(
            'key' => $this->_key->toPhp(),
            'value' => $this->_value->toPhp()
        );
    }

    /**
     * Release objects/memory
     *
     * @see SetaPDF_Core_Type_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        if (!isset($this->_observed)) {
            if (null !== $this->_key) {
                $this->_key->detach($this);
                $this->_key->cleanUp();
                $this->_key = null;
            }

            if (null !== $this->_value) {
                $this->_value->detach($this);
                $this->_value->cleanUp();
                $this->_value = null;
            }
        }
    }
}