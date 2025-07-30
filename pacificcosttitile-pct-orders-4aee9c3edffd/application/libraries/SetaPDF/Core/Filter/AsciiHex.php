<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: AsciiHex.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Class for handling ASCII hexadecimal data
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Filter_AsciiHex implements SetaPDF_Core_Filter_Interface
{
    /**
     * Converts an ASCII hexadecimal encoded string into it's binary representation
     *
     * @see SetaPDF_Core_Filter_Interface::decode()
     * @param string $data
     * @return string
     */
    public function decode($data)
    {
        $data = preg_replace('/[^0-9A-Fa-f]/', '', rtrim($data, '>'));
        if ((strlen($data) % 2) == 1) {
            $data .= '0';
        }

        return pack('H*', $data);
    }

    /**
     * Converts a string into ASCII hexadecimal representation
     *
     * @see SetaPDF_Core_Filter_Interface::encode()
     * @param string $data
     * @param boolean $leaveEOD
     * @return string
     */
    public function encode($data, $leaveEOD = false)
    {
        return current(unpack('H*', $data))
            . ($leaveEOD ? '' : '>');
    }
}