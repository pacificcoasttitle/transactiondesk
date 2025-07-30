<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Reader
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Interface of a reader implementation 
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Reader
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Reader_Interface
{
    /**
     * Returns the byte length of the buffer
     *
     * @param boolean $atOffset
     * @return int
     */
    public function getLength($atOffset = false);
    
    /**
     * Gets the total available length
     * 
     * @return int
     */
    public function getTotalLength();
    
    /**
     * Gets the current position of the pointer
     * 
     * @return int
     */
    public function getPos();
    
    /**
     * Returns the current buffer
     *
     * @param boolean $atOffset
     * @return string
     */
    public function getBuffer($atOffset = true);
    
    /**
     * Get the byte at the current or at a specific offset position
     * and sets the internal pointer to the next byte
     * 
     * @param integer $pos
     * @return string
     */
    public function readByte($pos = null);
    
    /**
     * Get a specific byte count from the current or at a specific offset position
     * and set the internal pointer to the next byte
     * 
     * @param integer $length
     * @param integer $pos
     * @return string
     */
    public function readBytes($length, $pos = null);
    
    /**
     * Get the byte at the current or at a specific offset position
     *
     * @param $pos
     * @return string
     */
    public function getByte($pos = null);
    
    /**
     * Reads a line from the current buffer
     * 
     * @param $length integer
     * @return string
     */
    public function readLine($length = 1024);
    
    /**
     * Sets the offset of the current position
     *
     * @param int $offset
     */
    public function setOffset($offset);
    
    /**
     * Returns the current offset of the current position
     * 
     * @return integer
     */
    public function getOffset();
    
    /**
     * Adds an offset to the current offset
     *
     * @param integer $offset
     */
    public function addOffset($offset);
    
    /**
     * Resets the buffer to a specific position and reread the buffer with the given length
     *
     * @param int|null $pos
     * @param int $length
     */
    public function reset($pos = 0, $length = 100);
    
    /**
     * Make sure that there is at least one character beyond 
     * the current offset in the buffer.
     * 
     * @return boolean
     */
    public function ensureContent();
    
    /**
     * Forcefully read more data into the buffer
     *
     * @param int $length
     */
    public function increaseLength($length = 100);
    
    /**
     * Copies the complete content to the writer
     * @param SetaPDF_Core_WriteInterface $writer
     */
    public function copyTo(SetaPDF_Core_WriteInterface $writer);
    
    /**
     * Set the reader into sleep-state
     */
    public function sleep();
    
    /**
     * Called to wake up the reader if it is in sleep-state
     */
    public function wakeUp();
    
    /**
     * Checks if the reader is in sleep-state
     * 
     * @return boolean
     */
    public function isSleeping();    
    
    /**
     * Method which is called when a document is cleaned up
     */
    public function cleanUp();
}