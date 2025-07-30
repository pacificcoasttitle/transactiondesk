<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Chain.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * A writer class which chains different writer objects
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Writer_Chain
    extends SetaPDF_Core_Writer_Abstract
    implements SetaPDF_Core_Writer_Interface
{
    /**
     * Writer instances
     *
     * @var array
     */
    protected $_writers = array();

    /**
     * The constructor
     *
     * @param array $writers
     */
    public function __construct(array $writers = array())
    {
        foreach ($writers AS $writer)
            $this->addWriter($writer);
    }

    /**
     * Add a writer object to the chain
     *
     * @param SetaPDF_Core_Writer_Interface $writer
     */
    public function addWriter(SetaPDF_Core_Writer_Interface $writer)
    {
        $this->_writers[] = $writer;
    }

    /**
     * Method which should/will be called when the writing process starts
     *
     * @throws SetaPDF_Core_Writer_Exception
     */
    public function start()
    {
        if (0 === count($this->_writers)) {
            throw new SetaPDF_Core_Writer_Exception('No writers found!');
        }

        foreach ($this->_writers AS $writer)
            $writer->start();

        parent::start();
    }

    /**
     * Forward the string to the registered writer objects
     *
     * @param string $s
     */
    public function write($s)
    {
        foreach ($this->_writers AS $writer)
            $writer->write($s);
    }

    /**
     * Forward the finish() call to the registered writer objects
     */
    public function finish()
    {
        foreach ($this->_writers AS $writer)
            $writer->finish();
        parent::finish();
    }

    /**
     * Proxy method for the getPos() method
     *
     * @see SetaPDF_Core_Writer_Interface::getPos()
     */
    public function getPos()
    {
        reset($this->_writers);
        $writer = current($this->_writers);
        return $writer->getPos();
    }

    /**
     * Forwards the cleanUp() call to the registered writer objects
     *
     * @see SetaPDF_Core_Writer_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        foreach ($this->_writers AS $writer)
            $writer->cleanUp();

        parent::cleanUp();
    }
}