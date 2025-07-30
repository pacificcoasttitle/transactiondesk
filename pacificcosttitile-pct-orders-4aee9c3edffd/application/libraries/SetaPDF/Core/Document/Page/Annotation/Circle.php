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
 * Class representing a circle annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.8
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Circle
    extends SetaPDF_Core_Document_Page_Annotation_Square
{
    /**
     * @param SetaPDF_Core_DataStructure_Rectangle|array $rect
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createAnnotationDictionary($rect)
    {
        if (!($rect instanceof SetaPDF_Core_DataStructure_Rectangle)) {
            $rect = SetaPDF_Core_DataStructure_Rectangle::byArray($rect);
        }

        $dictionary = SetaPDF_Core_Document_Page_Annotation::_createAnnotationDictionary($rect, SetaPDF_Core_Document_Page_Annotation_Link::TYPE_CIRCLE);

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
                array('SetaPDF_Core_Document_Page_Annotation_Circle', 'createAnnotationDictionary'),
                $args
            );
            unset($args);
        }

        if (!SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($dictionary, 'Subtype', 'Circle')) {
            throw new InvalidArgumentException('The Subtype entry in a circle annotation shall be "Circle".');
        }

        SetaPDF_Core_Document_Page_Annotation::__construct($objectOrDictionary);
    }
}
