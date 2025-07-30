<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: IndirectObject.php 366 2012-12-14 09:06:03Z maximilian $
 */

/**
 * Class representing an indirect object
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_IndirectObject
    extends SetaPDF_Core_Type_Abstract
    implements SplObserver, SetaPDF_Core_Type_IndirectObjectInterface
{
    /**
     * The value of the indirect object
     *
     * @var SetaPDF_Core_Type_Abstract
     */
    protected $_value;

    /**
     * The initial object id
     *
     * @var int
     */
    protected $_objectId;

    /**
     * The initial generation number
     *
     * @var integer
     */
    protected $_gen = 0;

    /**
     * The owner document
     *
     * @var SetaPDF_Core_Document
     */
    protected $_ownerPdfDocument;

    /**
     * The obejct identifier
     *
     * @var string
     */
    protected $_objectIdent;

    /**
     * The constructor
     *
     * @param null|SetaPDF_Core_Type_Abstract $value
     * @param SetaPDF_Core_Document $ownerPdfDocument
     * @param integer $objectId
     * @param integer $gen
     * @throws InvalidArgumentException
     */
    public function __construct(
        SetaPDF_Core_Type_Abstract $value = null,
        SetaPDF_Core_Document $ownerPdfDocument = null,
        $objectId = 0,
        $gen = 0
    )
    {
        unset($this->_observed);

        if (null !== $value) {
            $this->setValue($value);
        }

        if ($objectId <= 0) {
            throw new InvalidArgumentException('Object Id must be numeric and greater than 0');
        }
        $this->_objectId = (int)$objectId;
        $this->_gen = (int)$gen;

        if (!($ownerPdfDocument instanceof SetaPDF_Core_Document)) {
            throw new InvalidArgumentException('$ownerPdfDocument has to be an instance of SetaPDF_Core_Document');
        }
        $this->_ownerPdfDocument = $ownerPdfDocument;

        // Is this object or a reference already known?
        $this->_objectIdent = $this->_ownerPdfDocument->getInstanceIdent()
            . '-' . $this->_objectId
            . '-' . $this->_gen;
    }

    /**
     * Implementation of __clone()
     *
     * This has to be used with care, because a single object can only be used one time per document.
     * You only should use this, if you want to extract an object of an existing document and
     * reuse it changed in another one.
     *
     * The internal object-, generation number and document references are kept.
     *
     * At the end several objects will have the same object identifier!!
     *
     * @see SetaPDF_Core_Type_Abstract::__clone()
     */
    public function __clone()
    {
        $this->_value = clone $this->_value;
        parent::__clone();
    }

    /**
     * Implementation of __sleep()
     *
     * We also return observers for this object because it is needed if the object is unserialized as part
     * of a document.
     *
     * @see SetaPDF_Core_Type_Abstract::__sleep()
     */
    public function __sleep()
    {
        return array_keys(get_object_vars($this));
    }

    /**
     * Forward/reinit observation after unserailization
     *
     * @see SetaPDF_Core_Type_Abstract::__wakeup()
     */
    public function __wakeup()
    {
        if (isset($this->_observed)) {
            // to force re-observation
            $this->_value->attach($this);
        }
    }

    /**
     * Returns the initial object id
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->_objectId;
    }

    /**
     * Returns the initial generation number
     *
     * @return integer
     */
    public function getGen()
    {
        return $this->_gen;
    }

    /**
     * Returns the owner document
     *
     * @return SetaPDF_Core_Document
     */
    public function getOwnerPdfDocument()
    {
        return $this->_ownerPdfDocument;
    }

    /**
     * Observe the object if an owner document is attached
     */
    public function observe()
    {
        if (null !== $this->_ownerPdfDocument) {
            $this->attach($this->_ownerPdfDocument);
        }

        return $this;
    }

    /**
     * Add an observer to the object
     *
     * Implementation of the Observer Pattern.
     * This overwritten method forwards the attach()-call
     * to the value of the indirect object.
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        $isObserved = isset($this->_observed);
        parent::attach($observer);

        if (false === $isObserved && $this->_value instanceof SetaPDF_Core_Type_Abstract) {
            $this->_value->attach($this);
        }
    }

    /**
     * Triggered if a value of this object is changed
     *
     * Forward this to other observing objects.
     *
     * @param SplSubject $SplSubject
     */
    public function update(SplSubject $SplSubject)
    {
        if (isset($this->_observed))
            $this->notify();
    }

    /**
     * Get the Object Identifier
     *
     * This identifier has nothing todo with the object numbers
     * of a PDF document. They will be used to map an object to
     * docuement related object numbers.
     *
     * @return string
     */
    public function getObjectIdent()
    {
        return $this->_objectIdent;
    }

    /**
     * Sets the value of the PDF type
     *
     * @param SetaPDF_Core_Type_Abstract $value
     * @throws InvalidArgumentException
     */
    public function setValue($value)
    {
        if (!($value instanceof SetaPDF_Core_Type_Abstract)) {
            throw new InvalidArgumentException('Parameter should be a value of SetaPDF_Core_Type_Abstract');
        }

        /** indirect objects are reduce to a reference */
        if ($value instanceof SetaPDF_Core_Type_IndirectObject)
            $value = new SetaPDF_Core_Type_IndirectReference($value, null, $this->_ownerPdfDocument);

        /** observe */
        if (null !== $this->_value && isset($this->_observed)) {
            $this->_value->detach($this);
        }

        $this->_value = $value;

        if (isset($this->_observed)) {
            $this->_value->attach($this);
            $this->notify();
        }
    }

    /**
     * Gets the PDF value
     *
     * @return SetaPDF_Core_Type_Abstract
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Ensures the access to the value
     *
     * This method automatically forwards the request to the value
     *
     * @param boolean $forceObservation
     * @return SetaPDF_Core_Type_Abstract
     */
    public function ensure($forceObservation = null)
    {
        return $this->_value->ensure($forceObservation);
    }

    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        $id = $pdfDocument->getIdForObject($this);
        return $id[0] . ' ' . $id[1] . " obj\n"
            . (null !== $this->_value ? $this->_value->toPdfString($pdfDocument) : 'null')
            #. (null !== $this->_value ? $pdfDocument->typeToPdfString($this->_value) : 'null')
            . "\nendobj\n";
    }

    /**
     * Release objects/memory
     *
     * @see SetaPDF_Core_Type_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        if (!isset($this->_observed)) {
            $this->_ownerPdfDocument = null;

            if (null !== $this->_value) {
                $this->_value->detach($this);
                $this->_value->cleanUp();
            }
        }
    }

    /**
     * Converts the PDF data type to a PHP data type and returns it
     *
     * @see SetaPDF_Core_Type_Abstract::toPhp()
     * @return array
     */
    public function toPhp()
    {
        return array(
            'objectId' => $this->_objectId,
            'generation' => $this->_gen,
            'value' => $this->_value->toPhp()
        );
    }
}