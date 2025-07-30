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
 * Class representing a square annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.8
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Square
    extends SetaPDF_Core_Document_Page_Annotation_Markup
{
    /**
     * Creates a square annotation dictionary
     *
     * @param SetaPDF_Core_DataStructure_Rectangle|array $rect
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createAnnotationDictionary($rect)
    {
        if (!($rect instanceof SetaPDF_Core_DataStructure_Rectangle)) {
            $rect = SetaPDF_Core_DataStructure_Rectangle::byArray($rect);
        }

        $dictionary = parent::_createAnnotationDictionary($rect, SetaPDF_Core_Document_Page_Annotation_Link::TYPE_SQUARE);

        return $dictionary;
    }

    /**
     * The constructor
     *
     * @param array|SetaPDF_Core_Type_Abstract|SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_IndirectObjectInterface $objectOrDictionary
     * @throws InvalidArgumentException
     */
    public function __construct($objectOrDictionary)
    {
        $dictionary = $objectOrDictionary instanceof SetaPDF_Core_Type_Abstract
            ? $objectOrDictionary->ensure(true)
            : $objectOrDictionary;

        if (!($dictionary instanceof SetaPDF_Core_Type_Dictionary)) {
            $args = func_get_args();
            $objectOrDictionary = $dictionary = call_user_func_array(
                array('SetaPDF_Core_Document_Page_Annotation_Square', 'createAnnotationDictionary'),
                $args
            );
            unset($args);
        }

        if (!SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($dictionary, 'Subtype', 'Square')) {
            throw new InvalidArgumentException('The Subtype entry in a square annotation shall be "Square".');
        }

        parent::__construct($objectOrDictionary);
    }

    /**
     * Set the interior color
     *
     * @param null|array|SetaPDF_Core_DataStructure_Color $color
     */
    public function setInteriorColor($color)
    {
        if (null === $color) {
            $this->_annotationDictionary->offsetUnset('IC');
            return;
        }

        if (!$color instanceof SetaPDF_Core_DataStructure_Color) {
            $color = SetaPDF_Core_DataStructure_Color::createByComponents($color);
        }

        $this->_annotationDictionary->offsetSet('IC', $color->getValue());
    }

    /**
     * Get the interior color
     *
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getInteriorColor()
    {
        if (!$this->_annotationDictionary->offsetExists('IC'))
            return null;

        return SetaPDF_Core_DataStructure_Color::createByComponents(
            $this->_annotationDictionary->getValue('IC')
        );
    }

    /**
     * Get the border style object
     *
     * @param bool $create
     * @return null|SetaPDF_Core_Document_Page_Annotation_BorderStyle
     */
    public function getBorderStyle($create = false)
    {
        $bs = $this->_annotationDictionary->getValue('BS');
        if ($bs === null) {
            if (false == $create)
                return null;

            $bs = new SetaPDF_Core_Type_Dictionary();
            $this->_annotationDictionary->offsetSet('BS', $bs);
        }

        return new SetaPDF_Core_Document_Page_Annotation_BorderStyle($bs);
    }
}
