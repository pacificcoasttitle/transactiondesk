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
 * DeviceN Color Space
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage ColorSpace
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_ColorSpace_DeviceN
    extends SetaPDF_Core_ColorSpace
    implements SetaPDF_Core_Resource
{
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

        if ($definition->offsetGet(0)->getValue() !== 'DeviceN') {
            throw new InvalidArgumentException('DeviceN color space has to be named "DeviceN".');
        }

        if ($definition->count() < 4) {
            throw new InvalidArgumentException('DeviceN color spaces definition has to be defined by at least 4 values.');
        }
    }

    /**
     * Get the names specifying the individual colour components.
     *
     * @return array
     */
    public function getNames()
    {
        return $this->getPdfValue()->offsetGet(1)->getValue()->toPhp();
    }

    /**
     * Set the names specifying the individual colour components.
     *
     * @param $names
     */
    public function setNames($names)
    {
        $value = $this->getPdfValue()->offsetGet(1);

        if ($names instanceof SetaPDF_Core_Type_Name) {
            $value->setValue($names);
        } else {
            if (!is_array($names))
                $names = array($names);

            foreach ($names AS $name) {
                if (!$name instanceof SetaPDF_Core_Type_Name) {
                   $name = new SetaPDF_Core_Type_Name($name);
                }

                $value->push($name);
            }
        }
    }

    /**
     * Get the alternate color space
     *
     * @return SetaPDF_Core_ColorSpace|SetaPDF_Core_ColorSpace_DeviceCmyk|SetaPDF_Core_ColorSpace_DeviceGray|SetaPDF_Core_ColorSpace_DeviceRgb|SetaPDF_Core_ColorSpace_IccBased|SetaPDF_Core_ColorSpace_Separation
     */
    public function getAlternateSpace()
    {
        $alternate = $this->getPdfValue()->offsetGet(2)->getValue();

        return SetaPDF_Core_ColorSpace::createByDefinition($alternate);
    }

    /**
     * Set the alternate color space
     *
     * @param SetaPDF_Core_ColorSpace $colorSpace
     */
    public function setAlternateSpace(SetaPDF_Core_ColorSpace $colorSpace)
    {
        $value = $this->getPdfValue();
        if ($colorSpace instanceof SetaPDF_Core_Resource) {
            $value->offsetSet(2, $colorSpace->getIndirectObject());
            return;
        }

        $value->offsetSet(2, $colorSpace->getPdfValue());
    }

    /**
     * Set the tint transformation function
     *
     * @param SetaPDF_Core_Type_Abstract $tintTransform
     * @throws InvalidArgumentException
     */
    public function setTintTransform(SetaPDF_Core_Type_Abstract $tintTransform)
    {
        $dict = $tintTransform->ensure();
        if ($dict instanceof SetaPDF_Core_Type_Stream) {
            $dict = $dict->getValue();
        }

        if (!$dict->offsetExists('FunctionType')) {
            throw new InvalidArgumentException('$tintTransformation shall be a PDF function.');
        }

        $this->getPdfValue()->offsetSet(3, $tintTransform);
    }

    /**
     * Get the tint transformation function
     *
     * @return SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_Stream
     */
    public function getTintTransform()
    {
        return $this->getPdfValue()->offsetGet(3)->ensure();
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