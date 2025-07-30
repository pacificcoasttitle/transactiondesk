<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Http.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * A writer class for HTTP delivery
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Writer_Http
    extends SetaPDF_Core_Writer_String
{
    /**
     * The document filename
     *
     * @var string
     */
    protected $_filename = 'document.pdf';

    /**
     * Flag saying that the file should be displayed inline or not
     *
     * @var boolean
     */
    protected $_inline = false;

    /**
     * The constructor
     *
     * @param string $filename
     * @param boolean $inline
     */
    public function __construct($filename = 'document.pdf', $inline = false)
    {
        $this->_filename = $filename;
        $this->_inline = $inline;
    }

    /**
     * This method is called when the writing process is finished
     *
     * It sends the HTTP headers and send the buffer to the client
     *
     * @throws SetaPDF_Core_Writer_Exception
     */
    public function finish()
    {
        if (headers_sent($filename, $line)) {
            throw new SetaPDF_Core_Writer_Exception(
                sprintf('Headers already been send in %s on line %s', $filename, $line)
            );
        }

        // TODO: basename is local aware!
        $filename = basename($this->_filename);
        $filename = str_replace(array('"'), '_', $filename);

        if (true === $this->_inline) {
            Header('Content-Type: application/pdf');
            Header('Content-Disposition: inline; filename="' . $filename . '"');
        } else {
            Header('Content-Type: application/download');
            Header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        Header('Expires: 0');
        Header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        Header('Pragma: public');
        Header('Content-Length: ' . $this->getPos());

        echo $this->getBuffer();
        flush();

        parent::finish();
    }
}