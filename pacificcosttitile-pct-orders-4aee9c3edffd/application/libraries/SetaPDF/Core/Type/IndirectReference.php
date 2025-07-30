<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: IndirectReference.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Class representing an indirect reference
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_IndirectReference
    extends SetaPDF_Core_Type_Abstract
    implements SetaPDF_Core_Type_IndirectObjectInterface
{
    /**
     * The owner document
     *
     * @var SetaPDF_Core_Document
     */
    protected $_ownerPdfDocument;

    /**
     * The initial object id
     *
     * @var int
     */
    protected $_objectId = null;

    /**
     * The initial generation number
     *
     * @var integer
     */
    protected $_gen = 0;

    /**
     * The object identifier
     *
     * @var string
     */
    protected $_objectIdent;

    /**
     * The constructor
     *
     * @param integer|SetaPDF_Core_Type_IndirectObject $objectId
     * @param integer|null $gen
     * @param SetaPDF_Core_Document $ownerPdfDocument
     * @throws InvalidArgumentException
     */
    public function __construct($objectId, $gen = 0, SetaPDF_Core_Document $ownerPdfDocument = null)
    {
        unset($this->_observed);

        if ($objectId instanceof SetaPDF_Core_Type_IndirectObject) {
            $this->_objectId = $objectId->getObjectId();
            $this->_gen = $objectId->getGen();

            $ownerPdfDocument = $objectId->getOwnerPdfDocument();
        } else {
            $this->_objectId = (int)$objectId;
            $this->_gen = (int)$gen;

            if (!($ownerPdfDocument instanceof SetaPDF_Core_Document)) {
                throw new InvalidArgumentException('$ownerPdfDocument has to be an instance of SetaPDF_Core_Document');
            }
        }

        $this->_ownerPdfDocument = $ownerPdfDocument;

        // Is this object or a reference already known?
        $this->_objectIdent = $this->_ownerPdfDocument->getInstanceIdent()
            . '-' . $this->_objectId
            . '-' . $this->_gen;

    }

    /**
     * Automatically resolves the indirect reference to the object
     *
     * The $forceObservation is used to forward/handle the observer pattern
     * If it is set to true or this object is observed already the
     * resolved object will get observed automatically.
     *
     * If the parameter is set to false, the document is detached from the resolved object,
     * so that it is only possible to use this object as a read only object.
     *
     * @param boolean $forceObservation If this is set to true, the resolved object will be observed automatically
     * @return SetaPDF_Core_Type_Abstract
     * @throws SetaPDF_Core_Type_IndirectReference_Exception
     */
    public function ensure($forceObservation = null)
    {
        if (null === $this->_ownerPdfDocument) {
            throw new SetaPDF_Core_Type_IndirectReference_Exception(
                'Automated resolving of object chains are only possible if a document is attached to the value.'
            );
        }

        try {
            $value = $this->_ownerPdfDocument->resolveIndirectObject($this->_objectId, $this->_gen);
        } catch (SetaPDF_Core_Document_ObjectNotDefinedException $e) {
            throw new SetaPDF_Core_Type_IndirectReference_Exception(
                sprintf('Object could not be resolved (%s, %s)', $this->getObjectId(), $this->getGen())
            );
        }

        if (
            !$value->isObserved() && (
                false !== $forceObservation && isset($this->_observed) ||
                true === $forceObservation && null !== $this->_ownerPdfDocument
            )
        ) {
            $value->observe();
        }

        if ($forceObservation === false) {
            $value->detach($this->_ownerPdfDocument);
        }

        return $value->ensure($forceObservation);
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
     * Set the indirect object value
     *
     * @param SetaPDF_Core_Type_IndirectObject $value
     * @throws InvalidArgumentException
     */
    public function setValue($value)
    {
        if (!($value instanceof SetaPDF_Core_Type_IndirectObject)) {
            throw new InvalidArgumentException('Parameter should be an object of type SetaPDF_Core_Type_IndirectObject.');
        }

        $this->_objectId = $value->getObjectId();
        $this->_gen = $value->getGen();

        if (isset($this->_observed))
            $this->notify();
    }

    /**
     * Get the indirect object
     *
     * @return null|SetaPDF_Core_Type_IndirectObject
     * @throws SetaPDF_Core_Type_IndirectReference_Exception
     */
    public function getValue()
    {
        if (null === $this->_ownerPdfDocument) {
            throw new SetaPDF_Core_Type_IndirectReference_Exception(
                'Automated resolving of object chains are only possible if a document is attached to the value.'
            );
        }

        try {
            return $this->_ownerPdfDocument->resolveIndirectObject($this->_objectId, $this->_gen);
        } catch (SetaPDF_Core_Document_ObjectNotDefinedException $e) {
            return null;
        }
    }

    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        try {
            $id = $pdfDocument->addIndirectObjectReferenceWritten($this);

            return ' ' . $id[0] . ' ' . $id[1] . ' R';
            // If the reference refers to a null object
        } catch (SetaPDF_Core_Document_ObjectNotFoundException $e) {
            return ' null';
        }
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
            'generation' => $this->_gen
        );
    }
}