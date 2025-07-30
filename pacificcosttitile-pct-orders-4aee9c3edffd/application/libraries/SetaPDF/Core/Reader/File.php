<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Reader
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: File.php 367 2013-01-16 20:19:25Z jan $
 */

/**
 * Class for a file reader
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Reader
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Reader_File
    extends SetaPDF_Core_Reader_Abstract
    implements SetaPDF_Core_Reader_Interface
{
    /**
     * The filename
     *
     * @var string
     */
    protected $_filename = '';

    /**
     * The file pointer
     *
     * @var resource
     */
    protected $_fp;

    /**
     * The position of the point before sleep() was called
     *
     * @var integer
     */
    protected $_sleepPosition;

    /**
     * The constructor
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    /**
     * The destruct method
     *
     * @see http://www.php.net/__destruct
     */
    public function __destruct()
    {
        $this->cleanUp();
    }

    /**
     * Opens the file
     *
     * Mainly used for testing purposes
     *
     * @param string $filename
     * @return resource
     */
    protected function _openFile($filename)
    {
        return fopen($filename, 'rb');
    }

    /**
     * Closes the file handler
     *
     * Mainly used for testing purposes
     *
     * @see SetaPDF_Core_Reader_File::_fp
     */
    protected function _closeFile()
    {
        @fclose($this->_fp);
    }

    /**
     * Wakeup method
     *
     * @see http://www.php.net/language.oop5.magic.php#language.oop5.magic.sleep
     */
    public function __wakeup()
    {
        $this->setFilename($this->_filename);
    }

    /**
     * Set the filename
     *
     * @param string $filename
     * @throws SetaPDF_Core_Reader_Exception
     */
    public function setFilename($filename)
    {
        if (is_resource($this->_fp)) {
            $this->_closeFile();
        }

        if (!file_exists($filename) || !is_readable($filename)) {
            throw new SetaPDF_Core_Reader_Exception(
                sprintf('Cannot open %s.', $filename)
            );
        }

        $fp = $this->_openFile($filename);
        if (false === $fp) {
            throw new SetaPDF_Core_Reader_Exception(
                sprintf('Cannot open %s.', $filename)
            );
        }

        $this->_filename = $filename;
        $this->_fp = $fp;
        $this->_totalLength = null;
        $this->reset();
    }

    /**
     * Returns the filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * Gets the total available length
     *
     * @return int
     */
    public function getTotalLength()
    {
        if (null === $this->_totalLength) {
            $stat = fstat($this->_fp);
            $this->_totalLength = $stat['size'];
        }

        return $this->_totalLength;
    }

    /**
     * Resets the buffer to a specific position and reread the buffer with the given length
     *
     * If the $pos is negative the start buffer position will be
     * the $pos'th position from the end of the file.
     *
     * If the $pos is negative and the absolute value is bigger then
     * the totalLength of the file $pos will set to zero.
     *
     * @param int|null $pos Start position of the new buffer.
     * @param int $length Length of the new buffer. Mustn't be negative
     */
    public function reset($pos = 0, $length = 200)
    {
        if (null === $pos) {
            $pos = $this->_pos + $this->_offset;
        } elseif ($pos < 0) {
            $pos = max(0, $this->getTotalLength() + $pos);
        }

        fseek($this->_fp, $pos);

        $this->_pos = $pos;
        $this->_buffer = $length > 0 ? fread($this->_fp, $length) : '';
        $this->_length = strlen($this->_buffer);
        $this->_offset = 0;

        // If a stream wrapper is in use it is possible that
        // length values > 8096 will be ignored, so use the
        // increaseLength()-method to correct that behaviour
        if ($this->_length < $length) {
            $this->increaseLength($length - $this->_length);
        }
    }

    /**
     * Forcefully read more data into the buffer
     *
     * @param int $length
     * @return boolean
     */
    public function increaseLength($length = 100)
    {
        if (feof($this->_fp) || $this->getTotalLength() == $this->_pos + $this->_length) {
            return false;
        }

        $newLength = $this->_length + $length;
        do {
            $this->_buffer .= fread($this->_fp, $newLength - $this->_length);
        } while ((($this->_length = strlen($this->_buffer)) != $newLength) && !feof($this->_fp));

        return true;
    }

    /**
     * Copies the complete content to a writer instance
     *
     * @param SetaPDF_Core_WriteInterface $writer
     */
    public function copyTo(SetaPDF_Core_WriteInterface $writer)
    {
        if ($writer instanceof SetaPDF_Core_Writer_File) {
            $writer->copy($this->_filename);

        } else {
            $currentPos = $this->getPos();
            fseek($this->_fp, 0);
            while (!feof($this->_fp)) {
                $writer->write(fread($this->_fp, 8192));
            }

            fseek($this->_fp, $currentPos);
            // $writer->write(file_get_contents($this->_filename));
        }
    }

    /**
     * Set the reader into sleep-state
     *
     * In this implementation the file handles will be closed to avoid
     * reaching the limit of open file handles.
     *
     * @see SetaPDF_Core_Reader_Interface::sleep()
     */
    public function sleep()
    {
        $this->_sleepPosition = ftell($this->_fp);
        $this->_closeFile();
        $this->_sleeping = true;
    }

    /**
     * Wake up the reader if it is in sleep-state
     *
     * Re-open the file handle
     *
     * @see SetaPDF_Core_Reader_Interface::wakeUp()
     * @throws SetaPDF_Core_Reader_Exception
     * @return void|boolean
     */
    public function wakeUp()
    {
        if (!$this->isSleeping()) {
            return true;
        }

        $fp = $this->_openFile($this->_filename);

        if (false === $fp) {
            throw new SetaPDF_Core_Reader_Exception(
                sprintf('Cannot open %s.', $this->_filename)
            );
        }

        $this->_fp = $fp;
        fseek($this->_fp, $this->_sleepPosition);
        $this->_sleeping = false;

        return true;
    }

    /**
     * Close the file handle
     *
     * @see SetaPDF_Core_Reader_Interface::cleanUp()
     */
    public function cleanUp()
    {
        if (is_resource($this->_fp)) {
            $this->_closeFile();
        }
    }
}