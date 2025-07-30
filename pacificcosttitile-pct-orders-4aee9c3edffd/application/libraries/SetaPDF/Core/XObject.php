<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: XObject.php 335 2012-11-15 10:55:24Z jan $
 */

/**
 * Abstract class representing an external object
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
abstract class SetaPDF_Core_XObject implements SetaPDF_Core_Resource
{
    /**
     * An array caching XObject objects
     *
     * @var array
     */
    static protected $_xObjects = array();

    /**
     * The indirect object of the XObject
     *
     * @var SetaPDF_Core_Type_IndirectObject
     */
    protected $_indirectObject;

    /**
     * Get an external object by an indirect object/reference
     *
     * @param SetaPDF_Core_Type_IndirectObjectInterface $xObjectReference
     * @param string $subType
     * @return SetaPDF_Core_XObject_Form|SetaPDF_Core_XObject_Image
     * @throws SetaPDF_Exception_NotImplemented
     */
    static public function get(SetaPDF_Core_Type_IndirectObjectInterface $xObjectReference, $subType = null)
    {
        $indirectObject = $xObjectReference;

        $ident = $indirectObject->getObjectIdent();
        if (isset(self::$_xObjects[$ident])) {
            return self::$_xObjects[$ident];
        }

        $xObjectDict = $indirectObject->ensure()->getValue();
        $subType = $subType ? $subType : $xObjectDict->getValue('Subtype')->getValue();
        
        switch ($subType) {
            case 'Image':
                $xObject = new SetaPDF_Core_XObject_Image($indirectObject);
                break;
            case 'Form':
                $xObject = new SetaPDF_Core_XObject_Form($indirectObject);
                break;
            default:
                throw new SetaPDF_Exception_NotImplemented('Not implemented yet. (XObject: ' . $subType . ')');
        }

        self::$_xObjects[$ident] = $xObject;
        return $xObject;
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_IndirectObjectInterface $indirectObject
     */
    public function __construct(SetaPDF_Core_Type_IndirectObjectInterface $indirectObject)
    {
        $this->_indirectObject = $indirectObject;
    }

    /**
     * Get the indirect object of this XObject
     *
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function getIndirectObject()
    {
        return $this->_indirectObject;
    }

    /**
     * Get the resource type for external objects
     * 
     * @see SetaPDF_Core_Resource::getResourceType()
     * @return string
     */
    public function getResourceType()
    {
        return SetaPDF_Core_Resource::TYPE_X_OBJECT;
    }
    
    /**
     * Draw the external object on the canvas
     *
     * @param SetaPDF_Core_Canvas $canvas
     * @param int $x
     * @param int $y
     * @param null|float $width
     * @param null|float $height
     * @return mixed
     */
    abstract public function draw(SetaPDF_Core_Canvas $canvas, $x = 0, $y = 0, $width = null, $height = null);

    /* it is not possible to implement an abstract method which also is defined in an interface by the implementing class...
    abstract function getHeight($width = null);
    
    abstract function getWidth($height = null);
    */
}