<?php 
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: String.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Interface for string values
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @see SetaPDF_Core_Type_String, SetaPDF_Core_Type_HexString
 */
interface SetaPDF_Core_Type_StringValue
{
    
  /* We cannot defined the methods here because they are already declared to be abstract
   * in SetaPDF_Core_Type_Abstract.
   */
    
    /**
     * Get the string value
     * 
     * @return string
     */
    // public function getValue();
    
    /**
     * Set the string value
     * 
     * @param string $value
     */
    // public function setValue($value);
}