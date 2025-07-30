<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Reader
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: String.php 358 2012-12-03 16:14:03Z maximilian $
 */

/**
 * Class for a string reader
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Reader
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Reader_String
    extends SetaPDF_Core_Reader_Abstract
    implements SetaPDF_Core_Reader_Interface
{

    /**
     * The complete string
     *
     * @var string
     */
    protected $_string = '';

    /**
     * The constructor
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->setString($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getString();
    }

    /**
     * Set the string
     *
     * @param string $string
     */
    public function setString($string)
    {
        $this->_string = (string)$string;
        $this->_totalLength = strlen($this->_string);
        $this->reset();
    }

    /**
     * Get the complete string
     *
     * @return string
     */
    public function getString()
    {
        return $this->_string;
    }

    /**
     * Gets the total available length
     *
     * @return int
     */
    public function getTotalLength()
    {
        return $this->_totalLength;
    }

    /**
     * Resets the buffer to a specific position and reread the buffer with the given length
     *
     * The behavior of the arguments is the same like "substr" ($pos=$start; $length=$length).
     *
     * @see http://www.php.net/substr
     * @param int|null $pos Start position of the new buffer.
     * @param int $length Length of the new buffer.
     */
    public function reset($pos = 0, $length = 100)
    {
        if (null === $pos) {
            $pos = $this->_pos + $this->_offset;
        } else if ($pos < 0) {
            $pos = max(0, $this->getTotalLength() + $pos);
        }

        $this->_buffer = substr($this->_string, $pos, $length);
        $this->_pos = $pos;
        $this->_length = strlen($this->_buffer);
        $this->_offset = 0;
    }

    /**
     * Forcefully read more data into the buffer
     *
     * @param int $length
     * @return boolean;
     */
    public function increaseLength($length = 100)
    {
        if ($this->_totalLength == $this->_pos + $this->_length) {
            return false;
        }

        $this->_buffer = substr($this->_string, $this->_pos, $this->_length + $length);
        $this->_length = strlen($this->_buffer);

        return true;
    }

    /**
     * Copies the complete content to the writer
     *
     * @param SetaPDF_Core_WriteInterface $writer
     */
    public function copyTo(SetaPDF_Core_WriteInterface $writer)
    {
        $writer->write($this->_string);
    }

    /**
     * Implementation of SetaPDF_Core_Reader_Interface (empty body for this type of reader)
     *
     * @see SetaPDF_Core_Reader_Interface::sleep()
     */
    public function sleep()
    {
        // empty body...
    }

    /**
     * Implementation of SetaPDF_Core_Reader_Interface (empty body for this type of reader)
     *
     * @see SetaPDF_Core_Reader_Interface::sleep()
     */
    public function wakeUp()
    {
        // empty body...
    }

    /**
     * Implementation of SetaPDF_Core_Reader_Interface (empty body for this type of reader)
     *
     * @see SetaPDF_Core_Reader_Interface::sleep()
     */
    public function cleanUp()
    {
        // empty body...
    }
}