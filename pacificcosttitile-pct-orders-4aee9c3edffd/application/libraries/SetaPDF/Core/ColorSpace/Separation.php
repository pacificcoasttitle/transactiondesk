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
 * Separation Color Space
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage ColorSpace
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_ColorSpace_Separation
    extends SetaPDF_Core_ColorSpace
    implements SetaPDF_Core_Resource
{
    /**
     * Creates a spot color color space
     *
     * @param SetaPDF_Core_Document $document
     * @param $name
     * @param $c|array
     * @param null $m
     * @param null $y
     * @param null $k
     *
     * @return SetaPDF_Core_ColorSpace_Separation
     */
    static public function createSpotColor(SetaPDF_Core_Document $document, $name, $c, $m = null, $y = null, $k = null)
    {
        if (is_array($c)) {
            list($c, $m, $y, $k) = $c;
        }

        $function = $document->createNewObject(new SetaPDF_Core_Type_Dictionary(array(
            'FunctionType' => new SetaPDF_Core_Type_Numeric(2),
            'Domain' => new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(1)
            )),
            'N' => new SetaPDF_Core_Type_Numeric(1),
            'Range' => new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(1),
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(1),
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(1),
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(1),
            )),
            'C0' => new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(0),
                new SetaPDF_Core_Type_Numeric(0),
            )),
            'C1' => new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Numeric($c),
                new SetaPDF_Core_Type_Numeric($m),
                new SetaPDF_Core_Type_Numeric($y),
                new SetaPDF_Core_Type_Numeric($k),
            ))
        )));

        $object = $document->createNewObject(new SetaPDF_Core_Type_Array(array(
            new SetaPDF_Core_Type_Name('Separation', true),
            new SetaPDF_Core_Type_Name($name),
            new SetaPDF_Core_Type_Name('DeviceCMYK ', true),
            $function
        )));

        return new self($object);
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

        if ($definition->offsetGet(0)->getValue() !== 'Separation') {
            throw new InvalidArgumentException('Separation color space has to be named "Separation".');
        }

        if ($definition->count() !== 4) {
            throw new InvalidArgumentException('Separation color spaces definition has to be defined by 4 values.');
        }
    }

    /**
     * Get the name of the colorant that this Separation color space is intended to represent
     *
     * @return string
     */
    public function getName()
    {
        return $this->getPdfValue()->offsetGet(1)->getValue();
    }

    /**
     * Set the name of the colorant that this Separation color space is intended to represent
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->getPdfValue()->offsetGet(1)->setValue($name);
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