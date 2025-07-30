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
 * Class representing a rubber stamp annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.12
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Stamp
    extends SetaPDF_Core_Document_Page_Annotation_Markup
{

    /**#@+
     * Icon names defined in PDF 32000-1:2008 - 12.5.6.4 Text Annotations
     *
     * @var integer
     */
    const ICON_APPROVED = 'Approved'; // Default size: 245.378 x 64.53
    const ICON_EXPERIMENTAL = 'Experimental';
    const ICON_NOT_APPROVED = 'NotApproved';
    const ICON_AS_IS = 'AsIs';
    const ICON_EXPIRED = 'Expired';
    const ICON_NOT_FOR_PUBLIC_RELEASE = 'NotForPublicRelease'; // Default size: 245.378 x 64.53
    const ICON_CONFIDENTIAL = 'Confidential'; // Default size: 245.378 x 64.53
    const ICON_FINAL = 'Final';
    const ICON_SOLD = 'Sold';
    const ICON_DEPARTMENTAL = 'Departmental';
    const ICON_FOR_COMMENT = 'ForComment';
    const ICON_TOP_SECRET = 'TopSecret';
    const ICON_DRAFT = 'Draft'; // Default size: 245.378 x 64.53
    const ICON_FOR_PUBLIC_RELEASE = 'ForPublicRelease';
    /**#@-*/

    /**
     * Creates a rubber stamp annotation dictionary
     *
     * @param SetaPDF_Core_DataStructure_Rectangle|array $rect
     * @param string $icon
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createAnnotationDictionary($rect, $icon = null)
    {
        if (!($rect instanceof SetaPDF_Core_DataStructure_Rectangle)) {
            $rect = SetaPDF_Core_DataStructure_Rectangle::byArray($rect);
        }

        $dictionary = parent::_createAnnotationDictionary($rect, SetaPDF_Core_Document_Page_Annotation_Link::TYPE_STAMP);
        if (null !== $icon)
            $dictionary['Name'] = new SetaPDF_Core_Type_Name($icon);

        return $dictionary;
    }

    /**
     * The constructor
     *
     * @param array|SetaPDF_Core_Type_Abstract|SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_IndirectObjectInterface $objectOrDictionary The annotation dictionary or a rect value
     * @throws InvalidArgumentException
     */
    public function __construct($objectOrDictionary)
    {
        $dictionary = $objectOrDictionary instanceof SetaPDF_Core_Type_Abstract
            ? $objectOrDictionary->ensure(true)
            : $objectOrDictionary;

        if (!($dictionary instanceof SetaPDF_Core_Type_Dictionary)) {
            $args = func_get_args();
            $dictionary = $objectOrDictionary = call_user_func_array(
                array('SetaPDF_Core_Document_Page_Annotation_Stamp', 'createAnnotationDictionary'),
                $args
            );
            unset($args);
        }

        if (!SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($dictionary, 'Subtype', 'Stamp')) {
            throw new InvalidArgumentException('The Subtype entry in a rubber stamp annotation shall be "Stamp".');
        }

        parent::__construct($objectOrDictionary);
    }

    /**
     * Get the icon name of the annotation
     *
     * @return string
     */
    public function getIconName()
    {
        if (!$this->_annotationDictionary->offsetExists('Name')) {
            return 'Draft';
        }

        return $this->_annotationDictionary->getValue('Name')->getValue();
    }

    /**
     * Set the name of the icon that shall be used in displaying the annotation.
     *
     * @param $iconName
     */
    public function setIconName($iconName)
    {
        if (null == $iconName) {
            $this->_annotationDictionary->offsetUnset('Name');
            return;
        }

        if (!$this->_annotationDictionary->offsetExists('Name')) {
            $this->_annotationDictionary->offsetSet('Name', new SetaPDF_Core_Type_Name($iconName));
            return;
        }

        $this->_annotationDictionary->getValue('Name')->setValue($iconName);
    }
}