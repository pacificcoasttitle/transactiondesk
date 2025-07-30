<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Parser
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: CrossReferenceTable.php 489 2013-05-27 14:31:00Z jan.slabon $
 */

/**
 * A parser for PDF content
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Parser
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Parser_Content
{
    /**
     * The stream to parse
     *
     * @var string
     */
    protected $_stream;

    /**
     * Token stack
     *
     * @var array
     */
    protected $_stack = array();

    /**
     * Registered operators and their callbacks
     *
     * @var array
     */
    protected $_operators = array();

    /**
     * The constructor
     *
     * @param string $stream
     */
    public function __construct($stream)
    {
        $this->_stream = (string)$stream;
    }

    /**
     * Register a callback for an operator tolken
     *
     * @param string|array $operator
     * @param $callback
     */
    public function registerOperator($operator, $callback)
    {
        if (is_array($operator)) {
            foreach ($operator AS $_operator) {
                $this->registerOperator($_operator, $callback);
            }

            return;
        }

        $this->_operators[$operator] = $callback;
    }

    /**
     * Unregister an operator and its callback
     *
     * @param string $operator
     */
    public function unregisterOperator($operator)
    {
        unset($this->_operators[$operator]);
    }

    /**
     * Process the stream
     */
    public function process()
    {
        $parser = new SetaPDF_Core_Parser_Pdf(new SetaPDF_Core_Reader_String($this->_stream));

        while (($value = $parser->readValue()) !== false) {
            if (!$value instanceof SetaPDF_Core_Type_Token) {
                $this->_stack[] = $value;
                continue;
            }

            $operator = $value->getValue();
            if (!isset($this->_operators[$operator])) {
                $this->_stack = array();
                continue;
            }

            call_user_func($this->_operators[$operator], $this->_stack, $operator);

            $this->_stack = array();
        }
    }
}