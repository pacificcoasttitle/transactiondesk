<?php 
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Annotations.php 520 2013-08-12 14:38:45Z jan.slabon $
 */

/**
 * Helper class for handling annotations of a page
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotations
{
    /**
     * The page object
     * 
     * @var SetaPDF_Core_Document_Page
     */
    protected $_page;
    
    /**
     * The constructor
     * 
     * @param SetaPDF_Core_Document_Page $page
     */
    public function __construct(SetaPDF_Core_Document_Page $page)
    {
        $this->_page = $page;
    }

    /**
     * Release memory/resources
     */
    public function cleanUp()
    {
        $this->_page = null;
    }

    /**
     * @return SetaPDF_Core_Document_Page
     */
    public function getPage()
    {
        return $this->_page;
    }
    
    /**
     * Returns the Annots array if available or creates a new one
     *
     * @param boolean $create
     * @return false|SetaPDF_Core_Type_Array
     */
    public function getArray($create = false)
    {
        $pageDict = $this->_page->getPageObject(true)->ensure(true);
        
        if (false === $pageDict->offsetExists('Annots')) {
        	if (false === $create)
        		return false;
        
        	$pageDict->offsetSet('Annots', new SetaPDF_Core_Type_Array());
        }
        
        return $pageDict->offsetGet('Annots')->ensure();
    }
    
    /**
     * Get all annotations of this page
     *
     * Optionally the results can be filtered by the subtype parameter.
     * 
     * @param string $subtype
     * @return SetaPDF_Core_Document_Page_Annotation[]
     */
    public function getAll($subtype = null)
    {
    	$annotationsArray = $this->getArray();
    	if (false === $annotationsArray)
    		return array();
    
    	$annotations = array();
    	foreach ($annotationsArray AS $annotationValue) {
    		$annotationDictionary = $annotationValue->ensure(true);
    		if (null === $subtype || SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($annotationDictionary, 'Subtype', $subtype))
    			$annotations[] = SetaPDF_Core_Document_Page_Annotation::byObjectOrDictionary($annotationValue);
    	}
    
    	return $annotations;
    }

    /**
     * Adds an annotation to the page
     *
     * @param SetaPDF_Core_Document_Page_Annotation $annotation
     * @return SetaPDF_Core_Document_Type_IndirectObjectInterface
     */
    public function add(SetaPDF_Core_Document_Page_Annotation $annotation)
    {
        $annotationsArray = $this->getArray(true);
        $object = $annotation->getIndirectObject();

        if (null === $object) {
            $document = $this->_page->getPageObject(true)->getOwnerPdfDocument();
            $object = $document->createNewObject($annotation->getAnnotationDictionary());
            $annotation->setIndirectObject($object);
        }

        $annotationsArray->offsetSet(null, $object);
        
        return $object;
    }
}