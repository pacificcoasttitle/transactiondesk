<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Boolean.php 423 2013-04-12 10:39:33Z jan.slabon $
 */

/**
 * Class representing a boolean value
 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_Boolean extends SetaPDF_Core_Type_Abstract
    implements SetaPDF_Core_Type_ScalarValue
{
    /**
     * The value
     * 
     * @var $_value boolean
     */
    protected $_value = false;

    /**
     * Parses a boolean value to a pdf boolean string and writes it into a writer
     *
     * @see SetaPDF_Core_Type_Abstract
     * @param SetaPDF_Core_WriteInterface $writer
     * @param boolean $value
     * @return void
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $value)
    {
        $writer->write($value ? ' true' : ' false');
    }
    
    /**
     * The constructor
     * 
     * @param boolean $value
     */
    public function __construct($value = null)
    {
        unset($this->_observed);
        if (!$value)
            unset($this->_value);
        else
            $this->_value = true;
    }
    
    public function __wakeup()
    {
        if (!$this->_value)
            unset($this->_value);
        
        parent::__wakeup();
    }
    
    /**
     * Set the value
     * 
     * @param boolean $value
     */
    public function setValue($value)
    {
        $value = (boolean)$value;
            
        if ($value === isset($this->_value))
            return;
            
        if ($value === true) {
            $this->_value = true;
        } else {
            unset($this->_value);
        }
        
        if (isset($this->_observed))
            $this->notify();
    }
    
    /**
     * Gets the value
     * 
     * @return boolean
     */
    public function getValue()
    {
        return isset($this->_value);
    }
    
    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        return isset($this->_value)
            ? ' true'
            : ' false';
    }
    
    /**
     * Converts the PDF data type to a PHP data type and returns it
     * 
     * @return boolean
     */
    public function toPhp()
    {
        return isset($this->_value);
    }
}