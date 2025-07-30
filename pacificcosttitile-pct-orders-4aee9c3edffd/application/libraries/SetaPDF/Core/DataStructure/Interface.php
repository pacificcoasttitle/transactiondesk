<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage DataStructure
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Interface for data structure classes
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage DataStructure
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_DataStructure_Interface
{
    /**
     * Get the PDF value object
     *
     * @return SetaPDF_Core_Type_Abstract
     */
    public function getValue();

    /**
     * Get the data as a PHP value
     *
     * @return mixed
     */
    public function toPhp();
}