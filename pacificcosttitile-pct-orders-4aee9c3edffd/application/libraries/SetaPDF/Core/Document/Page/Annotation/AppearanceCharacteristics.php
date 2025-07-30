<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Link.php 408 2013-02-26 13:55:24Z jan.slabon $
 */

/**
 * Class representing annotations appearance characteristics
 *
 * See PDF 32000-1:2008 - 12.5.6.19 Widget Annotations
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_AppearanceCharacteristics
{
    /**
     * The dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_dictionary;

    /**
     * @var SetaPDF_Core_Type_IndirectObjectInterface
     */
    protected $_indirectReference;

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Abstract $objectOrDictionary
     */
    public function __construct(SetaPDF_Core_Type_Abstract $objectOrDictionary)
    {
        if ($objectOrDictionary instanceof SetaPDF_Core_Type_IndirectObjectInterface)
            $this->_indirectReference = $objectOrDictionary;

        $this->_dictionary = $objectOrDictionary->ensure();
    }

    /**
     * Get the dictionary of it
     *
     * @return SetaPDF_Core_Type_Dictionary
     */
    public function getDictionary()
    {
        return $this->_dictionary;
    }

    /**
     * Get the rotation value
     *
     * @return numeric
     */
    public function getRotation()
    {
        if (!$this->_dictionary->offsetExists('R'))
            return 0;

        return $this->_dictionary->getValue('R')->getValue();
    }

    /**
     * Set the rotation value
     *
     * @param numberic|null $rotation
     * @return self
     */
    public function setRotation($rotation)
    {
        if (null === $rotation) {
            $this->_dictionary->offsetUnset('R');
            return;
        }

        $this->_dictionary->offsetSet('R', new SetaPDF_Core_Type_Numeric($rotation));

        return $this;
    }

    /**
     * Get the border color
     *
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getBorderColor()
    {
        $bc = $this->_dictionary->getValue('BC');
        if (null === $bc || count($bc) === 0)
            return null;

        return SetaPDF_Core_DataStructure_Color::createByComponents($bc);
    }

    /**
     * Set the border color
     *
     * @param null|array|numeric|SetaPDF_Core_DataStructure_Color $borderColor
     * @return self
     */
    public function setBorderColor($borderColor)
    {
        if (null === $borderColor) {
            $this->_dictionary->offsetUnset('BC');
            return;
        }

        if (!$borderColor instanceof SetaPDF_Core_DataStructure_Color) {
            $borderColor = SetaPDF_Core_DataStructure_Color::createByComponents($borderColor);
        }

        $this->_dictionary->offsetSet('BC', $borderColor->getValue());

        return $this;
    }

    /**
     * Get the background color
     *
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getBackgroundColor()
    {
        $bg = $this->_dictionary->getValue('BG');
        if (null === $bg || count($bg) === 0)
            return null;

        return SetaPDF_Core_DataStructure_Color::createByComponents($bg);
    }

    /**
     * Set the background color
     * @param null|array|numeric|SetaPDF_Core_DataStructure_Color $backgroundColor
     * @return self
     */
    public function setBackgroundColor($backgroundColor)
    {
        if (null === $backgroundColor) {
            $this->_dictionary->offsetUnset('BG');
            return;
        }

        if (!$backgroundColor instanceof SetaPDF_Core_DataStructure_Color) {
            $backgroundColor = SetaPDF_Core_DataStructure_Color::createByComponents($backgroundColor);
        }

        $this->_dictionary->offsetSet('BG', $backgroundColor->getValue());

        return $this;
    }
}