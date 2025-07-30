<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Encoding
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Interface for encoding tables
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Encoding
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Encoding_Interface
{
    /**
     * Returns the encoding table array
     *
     * Keys are the unicode values while the values are the code
     * points in the specific encoding.
     *
     * @return array
     */
    static public function getTable();
}