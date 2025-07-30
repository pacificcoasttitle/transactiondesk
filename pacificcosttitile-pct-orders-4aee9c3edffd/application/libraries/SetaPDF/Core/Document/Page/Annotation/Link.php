<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Link.php 456 2013-05-16 13:56:20Z jan.slabon $
 */

/**
 * Class representing a Link annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.5
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Link
    extends SetaPDF_Core_Document_Page_Annotation
{
    /**
     * @param SetaPDF_Core_DataStructure_Rectangle|array $rect
     * @param scalar|SetaPDF_Core_Type_Dictionary $actionOrDestination
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createAnnotationDictionary($rect, $actionOrDestination)
    {
        if (!($rect instanceof SetaPDF_Core_DataStructure_Rectangle)) {
            $rect = SetaPDF_Core_DataStructure_Rectangle::byArray($rect);
        }
        
        $dictionary = parent::_createAnnotationDictionary($rect, SetaPDF_Core_Document_Page_Annotation_Link::TYPE_LINK);
        
        switch (true) {
            case $actionOrDestination instanceof SetaPDF_Core_Document_Action: {
                $dictionary->offsetSet('A', $actionOrDestination->getActionDictionary());
                break;
            }
            case $actionOrDestination instanceof SetaPDF_Core_Document_Destination: {
                $dictionary->offsetSet('Dest', $actionOrDestination->getDestinationArray());
                break;
            }
            default: {
                throw new InvalidArgumentException(
                    '$actionOrDestination argument has to be type of SetaPDF_Core_Document_Action or SetaPDF_Core_Document_Destination'
                );
            }
        }


        $dictionary->offsetSet('Border', new SetaPDF_Core_Type_Array(array(
            new SetaPDF_Core_Type_Numeric(0),
            new SetaPDF_Core_Type_Numeric(0),
            new SetaPDF_Core_Type_Numeric(0)
        )));
        
        return $dictionary;
    }
    
    /**
     * The constructor
     *
     * @param scalar|SetaPDF_Core_Type_Abstract|SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_IndirectObjectInterface $objectOrDictionary
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
    	        array('SetaPDF_Core_Document_Page_Annotation_Link', 'createAnnotationDictionary'),
    	        $args
    	    );
            unset($args);
    	}
    
    	if (!SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($dictionary, 'Subtype', 'Link')) {
    		throw new InvalidArgumentException('The Subtype entry in a Link annotation shall be "Link".');
    	}
    	 
    	parent::__construct($objectOrDictionary);
    }
    
    /**
     * Get the destination of the item
     *
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_Document_Destination|false
     * @throws BadMethodCallException
     */
    public function getDestination(SetaPDF_Core_Document $document = null)
    {
    	if (!$this->_annotationDictionary->offsetExists('Dest'))
    		return false;
    
    	$dest = $this->_annotationDictionary->getValue('Dest')->ensure();
    	if ($dest instanceof SetaPDF_Core_Type_StringValue || $dest instanceof SetaPDF_Core_Type_Name) {
    		if ($document === null) {
    			throw new BadMethodCallException('To resolve a named destination the $document parameter has to be set.');
    		}
    
    		return SetaPDF_Core_Document_Destination::findByName($document, $dest->getValue());
    	}
    
    	return new SetaPDF_Core_Document_Destination($dest);
    }
    
    /**
     * Set the destination of the item
     *
     * @param SetaPDF_Core_Document_Destination|SetaPDF_Core_Type_Array|SetaPDF_Core_Type_String $destination
     * @throws InvalidArgumentException
     */
    public function setDestination($destination)
    {
    	if ($destination instanceof SetaPDF_Core_Document_Destination)
    		$destination = $destination->getDestinationArray();
    
    	if (!($destination instanceof SetaPDF_Core_Type_Array) &&
			!($destination instanceof SetaPDF_Core_Type_StringValue) &&
			!($destination instanceof SetaPDF_Core_Type_Name))
    	{
    		throw new InvalidArgumentException('Only valid destination values allowed (SetaPDF_Core_Type_Array, SetaPDF_Core_Type_StringValue, SetaPDF_Core_Type_Name or SetaPDF_Core_Document_Destination)');
    	}
    
    	$this->_annotationDictionary->offsetSet('Dest', $destination);
    	$this->_annotationDictionary->offsetUnset('A');
    }
    
    /**
     * Get the action of the item
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
     * @throws SetaPDF_Exception_NotImplemented
     * @todo Implement
     */
    public function setAction($action)
    {
    	if ($action instanceof SetaPDF_Core_Document_Action)
    		$action = $action->getActionDictionary();
    
    	if (!($action instanceof SetaPDF_Core_Type_Dictionary) || !$action->offsetExists('S'))
    	{
    		throw new InvalidArgumentException('Invalid $action parameter. SetaPDF_Core_Document_Action or SetaPDF_Core_Type_Dictionary with an S key needed.');
    	}
    
    	$this->_annotationDictionary->offsetSet('A', $action);
    	$this->_annotationDictionary->offsetUnset('Dest');
    }

    /**
     * Set the Quadpoints
     *
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $x3
     * @param $y3
     * @param $x4
     * @param $y4
     */
    public function setQuadPoints($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4)
    {
        $points = new SetaPDF_Core_Type_Array(array(
            new SetaPDF_Core_Type_Numeric($x1),
            new SetaPDF_Core_Type_Numeric($y1),
            new SetaPDF_Core_Type_Numeric($x2),
            new SetaPDF_Core_Type_Numeric($y2),
            new SetaPDF_Core_Type_Numeric($x3),
            new SetaPDF_Core_Type_Numeric($y3),
            new SetaPDF_Core_Type_Numeric($x4),
            new SetaPDF_Core_Type_Numeric($y4)
        ));
        
        $this->_annotationDictionary->offsetSet('QuadPoints', $points);
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