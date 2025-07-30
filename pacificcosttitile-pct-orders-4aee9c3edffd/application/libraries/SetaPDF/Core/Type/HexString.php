<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: HexString.php 423 2013-04-12 10:39:33Z jan.slabon $
 */

/**
 * Class representing a hexadecimal string
 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_HexString extends SetaPDF_Core_Type_Abstract
    implements SetaPDF_Core_Type_StringValue, SetaPDF_Core_Type_ScalarValue
{
    /**
     * The value
     * 
     * @var string
     */
    protected $_value = '';
    
    /**
     * The owning object
     * 
     * @var SetaPDF_Core_Type_Abstract
     */
    protected $_owningObject;
    
    /**
     * Flag indicating if the string is currently encrypted
     * 
     * @var boolean
     */
    protected $_encrypted = false;
    
    /**
     * Flag indicating that the object should bypass the security handler
     * 
     * @var boolean
     */
    protected $_bypassSecHandler = false;
    
    /**
     * A singleton AsciiHex filter instance
     * 
     * @var SetaPDF_Core_Filter_AsciiHex
     */
    static private $_filter;
    
    /**
     * Singleton method to get an AsciiHex filter instance
     * 
     * @return SetaPDF_Core_Filter_AsciiHex
     */
    static private function _getFilter()
    {
        if (null == self::$_filter)
            self::$_filter = new SetaPDF_Core_Filter_AsciiHex();
            
        return self::$_filter;
    }
    
    /**
     * Converts a hex encoded string to a normal string
     * 
     * @param string $hex
     * @return string
     */
    static public function hex2str($hex)
    {
        return self::_getFilter()->decode($hex);
    }
    
    /**
     * Converts a string to a hex encoded string
     * 
     * @param string $str
     * @return string
     */
    static public function str2hex($str)
    {
        return self::_getFilter()->encode($str, true);
    }

    /**
     *
     *
     * @see SetaPDF_Core_Type_Abstract
     * @param SetaPDF_Core_WriteInterface $writer
     * @param $value
     * @param boolean $fromString
     * @return string|void
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $value, $fromString = true)
    {
        if (true === $fromString) {
        	$value = self::str2hex($value);
        }
        
        $writer->write('<' . $value . '>');
    }
    
    /**
     * The constructor
     * 
     * @param string $value
     * @param boolean $fromString
     * @param SetaPDF_Core_Type_Abstract $owningObject
     */
    public function __construct($value = null, $fromString = true, $owningObject = null)
    {
        if (null !== $value) {
            if (true === $fromString) {
                $value = self::str2hex($value);
                unset($this->_owningObject, $this->_encrypted);
            } else {
                if (null !== $owningObject) {
                    $this->_encrypted = true;
                    $this->_owningObject = $owningObject;
                } else { 
                    unset($this->_encrypted, $this->_owningObject);
                }
            }
            
            $this->_value = $value;
        } else {
            unset($this->_owningObject, $this->_encrypted);
        }
        
        unset($this->_observed, $this->_bypassSecHandler); // save memory
    }
    
    public function __wakeup()
    {
    	if ($this->_encrypted === false)
    		unset($this->_encrypted);
    
    	if ($this->_owningObject === null)
    		unset($this->_owningObject);
    
    	if ($this->_bypassSecHandler === false)
    		unset($this->_bypassSecHandler);
    
    	parent::__wakeup();
    }
    
    /**
     * Set the value
     * 
     * @param string $value
     * @param boolean $fromString
     */
    public function setValue($value, $fromString = true)
    {
        if (true === $fromString)
            $value = self::str2hex($value);
            
        if ($this->_value === $value)
            return;
            
        $this->_value = $value;
        
        unset($this->_owningObject, $this->_encrypted);
        
        if (isset($this->_observed))
            $this->notify();
    }

    /**
     * Get the value
     * 
     * If $asString is set to true the value will be passed to the {@link hex2str()} method
     * before it is returned.
     * 
     * @param boolean $asString
     * @return string
     */
    public function getValue($asString = true)
    {
        if (!isset($this->_encrypted)) {
            if (false === $asString) {
                return $this->_value;
            }
            
            return self::hex2str($this->_value);
        }
        
        $value = $this->_decrypt();
        if (true === $asString) {
            return $value;
        }
        
        return self::str2hex($value);
    }
    
    /**
     * Bypass the security handler or not
     * 
     * @param boolean $bypassSecHandler
     */
    public function setBypassSecHandler($bypassSecHandler = true)
    {
        if ($bypassSecHandler)
        	$this->_bypassSecHandler = true;
        else
        	unset($this->_bypassSecHandler);
        
        // If defined to bypass the security handler decrypt if necessary
        if (isset($this->_bypassSecHandler) && isset($this->_encrypted)) {
            $this->setValue($this->_decrypt(), true);
        }
    }
    
    /**
     * Decrypts the value
     * 
     * @return string
     */
    protected function _decrypt()
    {
        $value = self::hex2str($this->_value);
        if (isset($this->_encrypted)) {
            $secHandler = $this->_owningObject->getOwnerPdfDocument()->getSecHandlerIn();
            return $secHandler->decryptString($value, $this->_owningObject);
        }
        
        return $value;
    }
    
    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        $pdfDocument->getCurrentObjectDocument()->handleWriteCallback($this);
        
        $secHandler = $pdfDocument->getSecHandler();
                
        if (!isset($this->_encrypted)) {
            // encrypt it through the document security handler
            if ($secHandler && !isset($this->_bypassSecHandler)) {
                $value = $secHandler->encryptString(
                    self::hex2str($this->_value),
                    $pdfDocument->getCurrentObject()
                );
                return '<' . self::str2hex($value) . '>';
                
            // no need to de- or encrypt anything
            } else {
                return '<' . $this->_value . '>';
            }
        }
        
        /** 
         * String is already encrypted. 
         *
         * IF     the string is based on the same document, object 
         *        and security handlers encryption key just pass it back
         * ELSE   decrypt it using the owning documents security handler
         *        and encrypt it using the security handler of the new 
         *        document.
         */
        $owningDocument = $this->_owningObject->getOwnerPdfDocument();
        $currentObjectData = $pdfDocument->getCurrentObjectData();
        $secHandlerIn = $owningDocument->getSecHandlerIn();
        if (
            $pdfDocument->getInstanceIdent() == $owningDocument->getInstanceIdent()
            &&
            $this->_owningObject->getObjectId() === $currentObjectData[0] &&
            null !== $secHandler && 
            $secHandlerIn->getEncryptionKey() === $secHandler->getEncryptionKey()
        ) {
            return '<' . $this->_value . '>';
        }
        
        $value = $this->_decrypt();
        if (null !== $secHandler) {
            $value = $secHandler->encryptString($value, $pdfDocument->getCurrentObject());
        }

        return '<' . self::str2hex($value) . '>';
    }

    /**
     * Release memory
     * 
     * @see SetaPDF_Core_Type_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        if (!isset($this->_observed))
            $this->_owningObject = null;
    }
    
    /**
     * Converts the PDF data type to a PHP data type and returns it
     *
     * @see SetaPDF_Core_Type_Abstract::toPhp()
     * @return string
     */
    public function toPhp()
    {
        return $this->getValue();
    }
}