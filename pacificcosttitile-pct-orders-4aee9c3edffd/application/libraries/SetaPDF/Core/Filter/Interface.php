<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * A filter interface
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Filter_Interface
{
    /**
     * Decode a string
     *
     * @param string $data
     * @return string
     */
    public function decode($data);

    /**
     * Encodes a string
     *
     * @param string $data
     * @return string
     */
    public function encode($data);
}