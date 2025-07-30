<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: WriteInterface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * A simple write interface
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_WriteInterface
{
    /**
     * Writes bytes to the output
     *
     * @param string $bytes
     */
    public function write($bytes);
}