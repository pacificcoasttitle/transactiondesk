<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * The writer interface
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Writer_Interface
    extends SetaPDF_Core_WriteInterface
{
    /**
     * Method called when the writing process starts
     *
     * This method could send for example headers
     */
    public function start();

    /**
     * This method is called when the writing process is finished
     *
     * It could close a file handle for example or send headers and
     * flushs a buffer
     */
    public function finish();

    /**
     * Get the current writer status
     *
     * @see SetaPDF_Core_Writer
     * @return integer
     */
    public function getStatus();

    /**
     * Gets the current position/offset
     *
     * @return integer
     */
    public function getPos();

    /**
     * Method called if a documents cleanUp-method is called
     */
    public function cleanUp();
}