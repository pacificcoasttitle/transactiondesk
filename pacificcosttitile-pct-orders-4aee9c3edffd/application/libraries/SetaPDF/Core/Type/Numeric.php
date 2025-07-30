<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Numeric.php 423 2013-04-12 10:39:33Z jan.slabon $
 */

/**
 * Class representing a numeric object
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_Numeric extends SetaPDF_Core_Type_Abstract
    implements SetaPDF_Core_Type_ScalarValue
{
    /**
     * The numeric value
     * 
     * @var integer|float
     */
    protected $_value = 0.0;
    
    /**
     * This helper method simulates the overflow behaviour of a 32bit system on a 64bit system
     * 
     * @param integer $value
     * @return integer
     */
    static public function ensure32BitInteger($value)
    {
    	if (PHP_INT_SIZE === 4 || ($value) < (2147483647)) {
            return $value;
        }
        //x need to be a var otherwise a zend_guard 5.2 encrypted package will make this to -1
        $x = 4294967295;
        return ($value | ($x << 32));
    }
    
    /**
     * Parses a php integer or float value to a pdf numeric string and write it into a writer
     *
     * @see SetaPDF_Core_Type_Abstract
     * @param SetaPDF_Core_WriteInterface $writer
     * @param integer|float $value
     * @return void
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $value)
    {
    	$writer->write(
    	    $value != 0 
                ? ' ' . rtrim(rtrim(sprintf('%.5F', $value), '0'), '.')
                : ' 0'
    	);
    }
    
    /**
     * The constructor
     * 
     * @param integer|float $value
     */
    public function __construct($value = null)
    {
        unset($this->_observed);
        
        if (null !== $value)
            $this->_value = (float)$value;
    }
    
    /**
     * Set the numeric value
     * 
     * @param float|integer $value
     * @see SetaPDF_Core_Type_Abstract::setValue()
     */
    public function setValue($value)
    {
        $value = (float)$value;
            
        if ($value === $this->_value)
            return;
        
        $this->_value = $value;
        
        if (isset($this->_observed))
            $this->notify();
    }
    
    /**
     * Ger the numeric value
     *
     * @return float
     * @see SetaPDF_Core_Type_Abstract::getValue()
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        return  $this->_value != 0 
            ? ' ' . rtrim(rtrim(sprintf('%.5F', $this->_value), '0'), '.')
            : ' 0';
    }
    
    /**
     * Converts the PDF data type to a PHP data type and returns it
     *
     * @return float
     */
    public function toPhp()
    {
        return $this->_value;
    }
}