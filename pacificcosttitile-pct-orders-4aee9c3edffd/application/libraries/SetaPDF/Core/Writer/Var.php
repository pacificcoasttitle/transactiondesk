<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Var.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * A writer class for a referenced variable
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Writer_Var
    extends SetaPDF_Core_Writer_Abstract
    implements SetaPDF_Core_Writer_Interface
{
    /**
     * The variable reference
     *
     * @var string
     */
    protected $_var;

    /**
     * The current position
     *
     * @var integer
     */
    protected $_pos = 0;

    /**
     * The constructor
     *
     * @param string $var
     */
    public function __construct(&$var)
    {
        $this->_var =& $var;
    }

    /**
     * Initiate the referenced variable
     *
     * @see SetaPDF_Core_Writer_Abstract::start()
     */
    public function start()
    {
        $this->_var = '';
        parent::start();
    }

    /**
     * Adds content to the referenced variable
     *
     * @param string $s
     */
    public function write($s)
    {
        $this->_var .= $s;
        $this->_pos += strlen($s);
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

    /**
     * __toString()-implementation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_var;
    }

    /**
     * Unset the reference to the variable
     *
     * @see SetaPDF_Core_Writer_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        unset($this->_var);
    }
}