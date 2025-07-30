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
 * Class representing annotations border style dictionary
 *
 * See PDF 32000-1:2008 - 12.5.4 Border Styles
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_BorderStyle
{
    const SOLID = 'S';
    const DASHED = 'D';
    const BEVELED = 'B';
    const INSET = 'I';
    const UNDERLINE = 'U';

    /**
     * The dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_dictionary;

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Dictionary $dictionary
     */
    public function __construct(SetaPDF_Core_Type_Dictionary $dictionary)
    {
        $this->_dictionary = $dictionary;
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
     * Get the border width
     *
     * @return numeric
     */
    public function getWidth()
    {
        if (!$this->_dictionary->offsetExists('W'))
            return 0;

        return $this->_dictionary->getValue('W')->getValue();
    }

    /**
     * Set the border width
     *
     * @param null|numeric $width
     * @return self
     */
    public function setWidth($width)
    {
        if (null === $width) {
            $this->_dictionary->offsetUnset('W');
            return;
        }

        $this->_dictionary->offsetSet('W', new SetaPDF_Core_Type_Numeric($width));

        return $this;
    }

    /**
     * Get the border style
     *
     * @return string
     */
    public function getStyle()
    {
        if (!$this->_dictionary->offsetExists('S'))
            return self::SOLID;

        return $this->_dictionary->getValue('S')->getValue();
    }

    /**
     * Set the border style
     *
     * @param null|string $style
     * @return self
     */
    public function setStyle($style)
    {
        if (null === $style) {
            $this->_dictionary->offsetUnset('S');
            return;
        }

        $this->_dictionary->offsetSet('S', new SetaPDF_Core_Type_Name($style));

        return $this;
    }

    /**
     * Get the dash pattern
     *
     * @return array|null
     */
    public function getDashPattern()
    {
        if (!$this->_dictionary->offsetExists('D')) {
            if ($this->getStyle() === self::DASHED) {
                return array(3);
            }
            return null;
        }

        return $this->_dictionary->getValue('D')->toPhp();
    }

    /**
     * Set the dash pattern
     *
     * @param array|SetaPDF_Core_Type_Array $pattern
     * @return self
     */
    public function setDashPattern($pattern)
    {
        if (!$pattern instanceof SetaPDF_Core_Type_Array) {
            $_pattern = (array)$pattern;
            $pattern = new SetaPDF_Core_Type_Array();
            foreach ($_pattern AS $dash) {
                $pattern->offsetSet(null, new SetaPDF_Core_Type_Numeric($dash));
            }
        }

        $this->_dictionary->offsetSet('D', $pattern);

        return $this;
    }
}