<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Link.php 408 2013-02-26 13:55:24Z jan.slabon $
 */

/**
 * Class representing a widget annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.19
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Widget
    extends SetaPDF_Core_Document_Page_Annotation
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

        $dictionary = SetaPDF_Core_Document_Page_Annotation::_createAnnotationDictionary($rect, SetaPDF_Core_Document_Page_Annotation_Link::TYPE_WIDGET);

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
                array('SetaPDF_Core_Document_Page_Annotation_Widget', 'createAnnotationDictionary'),
                $args
            );
            unset($args);
        }

        if (!SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($dictionary, 'Subtype', 'Widget')) {
            throw new InvalidArgumentException('The Subtype entry in a widget annotation shall be "Widget".');
        }

        parent::__construct($objectOrDictionary);
    }

    /**
     * Get the height of the annotation
     *
     * @param boolean $ignoreRotation
     * @return float|int
     */
    public function getHeight($ignoreRotation = false)
    {
        $rect = $this->getRect();
        $mk = $this->getAppearanceCharacteristics();
        if ($ignoreRotation || null === $mk)
            return $rect->getHeight();

        $rotation = $mk->getRotation();
        $interchange = ($rotation / 90) % 2;

        return $interchange ? $rect->getWidth() : $rect->getHeight();
    }

    /**
     * Get the width of the annotation
     *
     * @param boolean $ignoreRotation
     * @return float|int
     */
    public function getWidth($ignoreRotation = false)
    {
        $rect = $this->getRect();
        $mk = $this->getAppearanceCharacteristics();
        if ($ignoreRotation || null === $mk)
            return $rect->getWidth();

        $rotation = $mk->getRotation();
        $interchange = ($rotation / 90) % 2;

        return $interchange ? $rect->getHeight() : $rect->getWidth();
    }

    /**
     * Get the action of the annotation
     *
     * @return bool|SetaPDF_Core_Document_Action
     */
    public function getAction()
    {
        if (!$this->_annotationDictionary->offsetExists('A'))
            return false;

        return SetaPDF_Core_Document_Action::byObjectOrDictionary($this->_annotationDictionary->getValue('A'));
    }

    /**
     * Set the action of the item
     *
     * @throws InvalidArgumentException
     * @param SetaPDF_Core_Document_Action|SetaPDF_Core_Type_Dictionary $action
     */
    public function setAction($action)
    {
        if ($action instanceof SetaPDF_Core_Document_Action)
            $action = $action->getActionDictionary();

        if (!($action instanceof SetaPDF_Core_Type_Dictionary) || !$action->offsetExists('S')) {
            throw new InvalidArgumentException('Invalid $action parameter. SetaPDF_Core_Document_Action or SetaPDF_Core_Type_Dictionary with an S key needed.');
        }

        $this->_annotationDictionary->offsetSet('A', $action);
    }

    /**
     * Get the appearance characteristics object
     *
     * @param bool $create
     * @return null|SetaPDF_Core_Document_Page_Annotation_AppearanceCharacteristics
     */
    public function getAppearanceCharacteristics($create = false)
    {
        $mk = $this->_annotationDictionary->getValue('MK');
        if ($mk === null) {
            if (false == $create)
                return null;

            $mk = new SetaPDF_Core_Type_Dictionary();
            $this->_annotationDictionary->offsetSet('MK', $mk);
        }

        return new SetaPDF_Core_Document_Page_Annotation_AppearanceCharacteristics($mk);
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
