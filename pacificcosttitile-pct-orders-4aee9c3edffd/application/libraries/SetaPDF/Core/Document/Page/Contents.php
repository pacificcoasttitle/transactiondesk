<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Contents.php 324 2012-11-08 10:28:41Z jan $
 */

/**
 * A class representing a pages content
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Contents
    implements Countable, SetaPDF_Core_Canvas_StreamProxyInterface
{
    /**
     * The page object to which this helper depends to
     * 
     * @var SetaPDF_Core_Document_Page
     */
    protected $_page;
    
    /**
     * The current content stream offset
     * 
     * @var integer
     */
    protected $_currentOffset;
    
    /**
     * The current active content stream
     * 
     * @var SetaPDF_Core_Type_Stream
     */
    protected $_currentStream;
    
    /**
     * Flag saying if the content is already encapsulated in a graphic state
     *  
     * @var boolean
     */
    protected $_encapsulatedInGraphicState = false;
    
    /**
     * An array holding encapsulate stream objects which are available to
     * encapsulate an existing content stream "q ... Q". Items are keyed by
     * the document id.
     * @var array
     */
    static protected $_encapsulatedCache = array();
    
    /**
     * The constructor
     * 
     * @param SetaPDF_Core_Document_Page $page
     */
    public function __construct(SetaPDF_Core_Document_Page $page)
    {
        $this->_page = $page;
    }
    
    public function cleanUp()
    {
        $this->_page = null;
        $this->_currentStream = null;
    }

    /**
     * @param string $bytes
     */
    public function write($bytes)
    {
        $stream = $this->getStreamObject(true);
    	$stream->write($bytes);
    }
    
    public function clear()
    {
        for ($offset = 0, $count = $this->count(); $offset < $count; $offset++) {
            $stream = $this->getStreamObjectByOffset($offset);
            if (false !== $stream) {
                $stream->clear();
            }
        }
    }
    
    /**
     * Gets the count of contents streams available for this page
     * 
     * @return integer
     */
    public function count()
    {
    	$contents = $this->_page->getAttribute('Contents', false);
    	if (null === $contents)
    		return 0;
    
    	$contents = $contents->ensure();
    	if ($contents instanceof SetaPDF_Core_Type_Array)
    		return $contents->count();
    
    	return 1;
    }

    /**
     * @param bool $create
     * @return bool|SetaPDF_Core_Type_Stream
     */
    public function getStreamObject($create = false)
    {
    	if (null === $this->_currentStream) {
    		$stream = $this->getLastStreamObject($create, true);
    		if (false === $stream)
    			return false;
    	}
    
    	return $this->_currentStream;
    }

    /**
     * @return string
     */
    public function getStream()
    {
        $streams = array();
        
        for ($offset = 0, $count = $this->count(); $offset < $count; $offset++) {
        	$streamObject = $this->getStreamObjectByOffset($offset);
        	if (false !== $streamObject) {
        		$streams[] = $streamObject->getStream();
        	}
        }
        
        return join("\n", $streams);
    }
    
    /**
     * Get a stream by offset in the contents array
     *
     * @param int $offset
     * @param bool $setActive
     * @throws InvalidArgumentException
     * @return boolean|SetaPDF_Core_Type_Array
     */
    public function getStreamObjectByOffset($offset = 0, $setActive = true)
    {
        $contents = $this->_page->getAttribute('Contents', false);
        
        if (null === $contents) {
            return false;
        }
        
        $contents = $contents->ensure(true);
        if (!($contents instanceof SetaPDF_Core_Type_Array)) {
            if ($offset !== 0)
                return false;
            
            $stream = $contents;
        } else {
            $stream = $contents->offsetGet($offset)->ensure(true);            
        }
        
        if ($setActive) {
            $this->_currentOffset = $offset;
        	$this->_currentStream = $stream;
        }
        
        return $stream;
    }
    
    /**
     * Get and/or create the last stream
     * 
     * @param boolean $create
     * @param boolean $setActive
     * @return boolean|SetaPDF_Core_Type_Array
     */
    public function getLastStreamObject($create = false, $setActive = true)
    {
        $count = $this->count();
        if ($count === 0) {
            if (false === $create) {
                return false;
            }
            return $this->pushStream($setActive);
        }
        
        return $this->getStreamObjectByOffset($count - 1, $setActive);
    }
    
    /**
     * Checks if the last content stream is active
     * 
     * @return boolean
     */
    public function isLastStreamActive()
    {
        return $this->_currentOffset === ($this->count() - 1);
    }

    /**
     * Method for adding streams to the Contents entry
     * 
     * @param integer|null $beforeIndex
     * @param boolean $setActive
     * @param SetaPDF_Core_Type_IndirectObjectInterface $streamObject
     * @return SetaPDF_Core_Type_IndirectObjectInterface
     */
    protected function _addStream($beforeIndex, $setActive = true, SetaPDF_Core_Type_IndirectObjectInterface $streamObject = null)
    {
        $pageDict = $this->_page->getPageObject(true)->ensure(true);
        $contents = $this->_page->getAttribute('Contents', false);
        if (null === $contents) {
        	$contents = new SetaPDF_Core_Type_Array();
        	$pageDict->offsetSet('Contents', $contents);
        } else {
        	if (!$contents->ensure(true) instanceof SetaPDF_Core_Type_Array) {
        		$contents = new SetaPDF_Core_Type_Array(array(clone $contents->getValue()));
        		$pageDict->offsetSet('Contents', $contents);
        	} else {
        	    $contents = $contents->ensure(true);
        	}
        }
        
        if (null === $streamObject) {
            $document = $this->_page->getPageObject()->getOwnerPdfDocument();
        	$stream = new SetaPDF_Core_Type_Stream();
        	$stream->getValue()->offsetSet('Filter', new SetaPDF_Core_Type_Name('FlateDecode', true));
        	$streamObject = $document->createNewObject($stream);
        }
        
        if ($beforeIndex === null) {
            $contents->push($streamObject);
        } else {
            $contents->insertBefore($streamObject, $beforeIndex);
        }
        
        if (true === $setActive) {
        	$this->_currentOffset = $beforeIndex === null ? $contents->count() - 1 : $beforeIndex;
        	$this->_currentStream = $streamObject->ensure();
        }
        
        return $streamObject;
    }
    
    /**
     * Append a stream to the end of the Contents array
     * 
     * @param boolean $setActive
     * @param SetaPDF_Core_Type_IndirectObjectInterface $streamObject
     * @return SetaPDF_Core_Type_IndirectObjectInterface
     */
    public function pushStream($setActive = true, SetaPDF_Core_Type_IndirectObjectInterface $streamObject = null)
    {
        return $this->_addStream(null, $setActive, $streamObject);
    }
    
    /**
     * Prepend a stream to the beginning of the Contents array
     * 
     * @param boolean $setActive
     * @param SetaPDF_Core_Type_IndirectObjectInterface $streamObject
     * @return SetaPDF_Core_Type_IndirectObjectInterface
     */
    public function prependStream($setActive = true, SetaPDF_Core_Type_IndirectObjectInterface $streamObject = null)
    {
        return $this->_addStream(0, $setActive, $streamObject);
    }
    
    /**
     * Encapsulate the existing content stream(s) in separate graphic state operators
     * 
     * @param boolean $force
     */
    public function encapsulateExistingContentInGraphicState($force = false)
    {
        if (false === $this->_encapsulatedInGraphicState || true === $force) {
            $documentId = $this->_page->getPageObject()->getOwnerPdfDocument()->getInstanceIdent();
            if (!isset(self::$_encapsulatedCache[$documentId])) {
                self::$_encapsulatedCache[$documentId] = array(
                    0 => $this->prependStream(),
                    #1 => $this->pushStream()
                );
                $stream = self::$_encapsulatedCache[$documentId][0]->ensure(true);
                $stream->setStream('q');
                
                #$stream = self::$_encapsulatedCache[$documentId][1]->ensure(true);
                #$stream->setStream('Q');
                
            } else {
                $this->prependStream(true, self::$_encapsulatedCache[$documentId][0]);
                #$this->pushStream(true, self::$_encapsulatedCache[$documentId][1]);
            }
            
            $stream = $this->pushStream()->ensure(true);
            $stream->setStream('Q');
            
            $this->_encapsulatedInGraphicState = true;
        }
    }
}