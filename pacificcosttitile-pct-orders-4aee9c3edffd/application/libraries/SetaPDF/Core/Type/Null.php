<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Null.php 423 2013-04-12 10:39:33Z jan.slabon $
 */

/**
 * Class representing a null object
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_Null extends SetaPDF_Core_Type_Abstract
    implements SetaPDF_Core_Type_ScalarValue
{
    /**
     * Parses a php null value to a pdf null string and writes it into a writer
     *
     * @see SetaPDF_Core_Type_Abstract
     * @param SetaPDF_Core_WriteInterface $writer
     * @param null $value
     * @return void
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $value)
    {
        $writer->write(' null');
    }
    
    /**
     * Implementation of the abstract setValue() method which is useless for this object type
     * 
     * @see SetaPDF_Core_Type_Abstract::setValue()
     * @throws SetaPDF_Core_Type_Exception
     */
    public function setValue($value)
    {
        throw new SetaPDF_Core_Type_Exception('PDF Type of NULL cannot have a value.');
    }
    
    /**
     * Get the null value
     *
     * @see SetaPDF_Core_Type_Abstract::getValue()
     * @return null
     */
    public function getValue()
    {
        return null;
    }
    
    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        return ' null';
    }
    
    /**
     * Converts the PDF data type to a PHP data type and returns it
     *
     * @return null
     */
    public function toPhp()
    {
        return null;
    }    
}