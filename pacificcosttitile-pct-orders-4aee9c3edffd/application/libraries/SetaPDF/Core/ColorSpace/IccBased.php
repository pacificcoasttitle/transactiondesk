<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * ICCBased Color Space
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage ColorSpace
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_ColorSpace_IccBased
    extends SetaPDF_Core_ColorSpace
    implements SetaPDF_Core_Resource
{
    /**
     * Creates an instance of this color space
     *
     * @param SetaPDF_Core_IccProfile_Stream $iccStream
     * @return SetaPDF_Core_ColorSpace_IccBased
     */
    static public function create(SetaPDF_Core_IccProfile_Stream $iccStream)
    {
        return new self(new SetaPDF_Core_Type_Array(array(
            new SetaPDF_Core_Type_Name('ICCBased'),
            $iccStream->getIndirectObject()
        )));
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Abstract $definition
     * @throws InvalidArgumentException
     */
    public function __construct(SetaPDF_Core_Type_Abstract $definition)
    {
        parent::__construct($definition);

        $definition = $this->getPdfValue();

        if ($definition->offsetGet(0)->getValue() !== 'ICCBased') {
            throw new InvalidArgumentException('ICCBased color space has to be named "ICCBased".');
        }

        if ($definition->offsetGet(1)->ensure() instanceof SetaPDF_Core_Type_IndirectObjectInterface) {
            throw new InvalidArgumentException("ICCBased color space needs a ICC profile in it's definition.");
        }
    }

    /**
     * Get an instance of the ICC Profile stream
     * @return SetaPDF_Core_IccProfile_Stream
     */
    public function getIccProfileStream()
    {
        return new SetaPDF_Core_IccProfile_Stream($this->getPdfValue()->offsetGet(1));
    }

    /**
     * Gets an indirect object for this color space dictionary
     *
     * @see SetaPDF_Core_Resource::getIndirectObject()
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_Type_IndirectObjectInterface
     * @throws InvalidArgumentException
     */
    public function getIndirectObject(SetaPDF_Core_Document $document = null)
    {
        if (null === $this->_indirectObject) {
            if (null === $document) {
                throw new InvalidArgumentException('To initialize a new object $document parameter is not optional!');
            }

            $this->_indirectObject = $document->createNewObject($this->getPdfValue());
        }

        return $this->_indirectObject;
    }

    /**
     * Get the resource type of an implementation
     *
     * @return string
     */
    public function getResourceType()
    {
        return SetaPDF_Core_Resource::TYPE_COLOR_SPACE;
    }
}