<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Echo.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * A writer class which uses simple echo calls
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Writer_Echo
    extends SetaPDF_Core_Writer_Abstract
    implements SetaPDF_Core_Writer_Interface
{
    /**
     * The current position
     *
     * @var integer
     */
    protected $_pos = 0;

    /**
     * Echo the string
     *
     * @param string $s
     */
    public function write($s)
    {
        echo $s;
        $this->_pos += strlen($s);
        flush();
    }

    /**
     * Returns the current position
     *
     * @return integer
     */
    public function getPos()
    {
        return $this->_pos;
    }
}