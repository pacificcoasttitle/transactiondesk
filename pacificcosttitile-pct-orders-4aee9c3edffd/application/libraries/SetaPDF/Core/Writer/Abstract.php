<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Abstract.php 198 2012-04-11 15:04:53Z jan $
 */

/**
 * Abstract class for a writer object
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
abstract class SetaPDF_Core_Writer_Abstract
{
    /**
     * Status property
     * 
     * @var string
     */
    protected $_status = SetaPDF_Core_Writer::INACTIVE;
    
    /**
     * Method which should/will be called when the writing process starts
     */
    public function start()
    {
        $this->_status = SetaPDF_Core_Writer::ACTIVE;
    }
    
    /**
     * Method which should/will be called when the writing process is finished
     */
    public function finish()
    {
        $this->_status = SetaPDF_Core_Writer::FINISHED;
    }
    
    /**
     * Get the current status of the writer object
     * 
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }
    
    /**
     * Method which should/will be called when the document objects cleanUp() method is called.
     */
    public function cleanUp()
    {
        $this->_status = SetaPDF_Core_Writer::CLEANED_UP;
    }
}