<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: File.php 332 2012-11-12 16:42:55Z maximilian $
 */

/**
 * A writer class for files or writeable streams
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Writer_File
    extends SetaPDF_Core_Writer_Abstract
    implements SetaPDF_Core_Writer_Interface
{
    /**
     * Path to the output file
     *
     * @var string
     */
    protected $_path;

    /**
     * The file handle resource
     *
     * @var resource
     */
    protected $_handle;

    /**
     * The constructor
     *
     * @param string $path The path to the output file
     */
    public function __construct($path)
    {
        $this->_path = $path;
    }

    /**
     * Method called when the writing process starts
     *
     * It setups the file handle for this writer
     */
    public function start()
    {
        // TODO: Handle this without @-sign 
        $this->_handle = @fopen($this->_path, 'wb');
        if (false === $this->_handle) {
            throw new SetaPDF_Core_Writer_Exception(
                sprintf('Unable to open "%s" for writing.', $this->_path)
            );
        }

        parent::start();
    }

    /**
     * Write the content to the output file
     *
     * @param string $s
     */
    public function write($s)
    {
        fwrite($this->_handle, $s);
    }

    /**
     * This method is called when the writing process is finished
     *
     * It closes the file handle
     */
    public function finish()
    {
        fclose($this->_handle);
        parent::finish();
    }

    /**
     * Returns the current position of the output file
     *
     * @return integer
     */
    public function getPos()
    {
        return ftell($this->_handle);
    }

    /**
     * Copies an existing file into the taget file and resets the
     * file handle to the end of the file.
     *
     * @param string $path
     */
    public function copy($path)
    {
        copy($path, $this->_path);
        fseek($this->_handle, 0, SEEK_END);
    }

    /**
     * Close the file handle if needed
     *
     * @see SetaPDF_Core_Writer_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        if ($this->_status > SetaPDF_Core_Writer::FINISHED) {
            if (is_resource($this->_handle))
                fclose($this->_handle);
        }

        parent::cleanUp();
    }
}