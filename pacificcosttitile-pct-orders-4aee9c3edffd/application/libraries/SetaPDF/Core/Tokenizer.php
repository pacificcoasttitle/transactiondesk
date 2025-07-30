<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Tokenizer.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Tokenizer class for PDF documents
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Tokenizer
{
    /**
     * The reader object
     *
     * @var SetaPDF_Core_Reader_Interface
     */
    protected $_reader;

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Reader_Interface $reader
     * @return SetaPDF_Core_Tokenizer
     */
    public function __construct(SetaPDF_Core_Reader_Interface &$reader)
    {
        $this->setReader($reader);
    }

    /**
     * Clean up resources and release cycled references
     */
    public function cleanUp()
    {
        $this->_reader->cleanUp();
        $this->_reader = null;
    }

    /**
     * Set the reader class
     *
     * @param SetaPDF_Core_Reader_Interface $reader
     */
    public function setReader(SetaPDF_Core_Reader_Interface &$reader)
    {
        $this->_reader = & $reader;
    }

    /**
     * Get the reader class
     *
     * @return SetaPDF_Core_Reader_Interface
     */
    public function getReader()
    {
        return $this->_reader;
    }

    /**
     * Read a token from the reader
     *
     * @return string
     */
    public function readToken()
    {
        if (
            false == $this->_reader->ensureContent() ||
            false == $this->leapWhiteSpaces()
        ) {
            return false;
        }

        $char = $this->_reader->readByte();

        if (strspn($char, '/[]()%{}') == 1) {
            return $char;
        } elseif (strspn($char, '<>') == 1) {
            if ($this->_reader->getByte() === $char) {
                $this->_reader->addOffset(1);
                return $char . $char;
            } else {
                return $char;
            }
        }

        $bufferOffset = $this->_reader->getOffset();
        $lastBuffer = '';
        do {
            $pos = strcspn(
                $lastBuffer = $this->_reader->getBuffer(false),
                "\x00\x09\x0A\x0C\x0D\x20()<>[]{}/%",
                $bufferOffset
            );
        } while (
            // Break the loop if a delemitter or white space char is matched
            // in the current buffer or increase the buffers length
            (
            !(
                $bufferOffset + $pos < $this->_reader->getLength() ||
                !$this->_reader->increaseLength()
            )
            ) && $lastBuffer !== false
        );

        $result = substr($lastBuffer, $bufferOffset - 1, $pos + 1);
        $this->_reader->addOffset($pos);

        return $result;
    }

    /**
     * Leap white spaces
     *
     * @return boolean
     */
    public function leapWhiteSpaces()
    {
        $char = $this->_reader->getByte();
        if (strspn($char, "\x00\x09\x0A\x0C\x0D\x20") == 0) {
            return true;
        }

        $offset = $this->_reader->getOffset();
        do {
            $addToOffset = strspn(
                $this->_reader->getBuffer(false),
                "\x00\x09\x0A\x0C\x0D\x20",
                $offset
            );

            if (0 == $addToOffset) {
                return true;
            }

            $this->_reader->setOffset($offset += $addToOffset);

            // Check that there's a least one non-white-space after the new offset
            if ($this->_reader->getLength(true) >= $addToOffset) {
                return true;
            }

            if (!$this->_reader->ensureContent()) {
                return false;
            }
        } while (true);
        return false;
    }

    /**
     * Check if the current byte is a regular character
     *
     * @return boolean
     */
    public function isCurrentByteRegularCharacter()
    {
        return strspn($this->_reader->getByte(), "\x00\x09\x0A\x0C\x0D\x20()<>[]{}/%") == 0;
    }
}