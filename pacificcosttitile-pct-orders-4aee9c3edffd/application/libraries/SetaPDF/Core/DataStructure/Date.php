<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage DataStructure
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Date.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Data structure class for date objects
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage DataStructure
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_DataStructure_Date
    implements SetaPDF_Core_DataStructure_Interface
{
    /**
     * The string object representing the date
     *
     * Format: (D:YYYYMMDDHHmmSSOHH'mm)
     *
     * @var SetaPDF_Core_Type_String
     */
    protected $_string;

    /**
     * Converts an PDF date time string into a DateTime object
     *
     * @param string $string
     * @return DateTime
     * @throws OutOfRangeException
     */
    static public function stringToDateTime($string)
    {
        $matches = array();
        // YYYYMMDDHHmmSSOHH'mm
        preg_match("/D:"
                . '(?P<year>\d{4})' // YYYY
                . '(?P<month>\d{2})?' // MM
                . '(?P<day>\d{2})?' // DD
                . '(?P<hour>\d{2})?' // HH
                . '(?P<minute>\d{2})?' // mm
                . '(?P<second>\d{2})?' // SS
                . '(?P<relationToUT>[\-\+Z])?' // O
                . '(?P<hoursFromUT>\d{2})?' // HH
                . '(\'(?P<minutesFromUT>\d{2})?)?' // 'mm
                . '/',
            $string, $matches);

        if (!isset($matches['year'])) {
            throw new OutOfRangeException(
                sprintf('A date could not be extracted from the string (%s)', $string)
            );
        }

        if (isset($matches['relationToUT']) && $matches['relationToUT'] === 'Z') {
            $matches['relationToUT'] = '+';
            $matches['hoursFromUT'] = '00';
            $matches['minutesFromUT'] = '00';
        }

        $date = new DateTime(
            $matches['year'] . '/' .
                (isset($matches['month']) ? $matches['month'] : '01') . '/' .
                (isset($matches['day']) ? $matches['day'] : '01') . ' ' .
                (isset($matches['hour']) ? $matches['hour'] : '00') . ':' .
                (isset($matches['minute']) ? $matches['minute'] : '00') . ':' .
                (isset($matches['second']) ? $matches['second'] : '00') .
                (isset($matches['relationToUT']) ? $matches['relationToUT'] : '+') .
                (isset($matches['hoursFromUT']) ? $matches['hoursFromUT'] : '00') .
                (isset($matches['minutesFromUT']) ? $matches['minutesFromUT'] : '00')
        );

        return $date;
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_String $string
     */
    public function __construct(SetaPDF_Core_Type_String $string = null)
    {
        if ($string === null) {
            $this->_string = new SetaPDF_Core_Type_String();
            $this->setByDateTime(new DateTime());
        } else {
            $this->_string = $string;
        }
    }

    /**
     * Get the PDF date as a DateTime object
     *
     * @return DateTime
     */
    public function getAsDateTime()
    {
        return self::stringToDateTime($this->_string->getValue());
    }

    /**
     * Set the date by a DateTime object
     *
     * @param DateTime $dateTime
     */
    public function setByDateTime(DateTime $dateTime)
    {
        // D:YYYYMMDDHHmmSSOHH'mm'
        $this->_string->setValue(
            substr_replace($dateTime->format('\D\:YmdHisO'), "'", -2, 0) . "'"
        );
    }

    /**
     * Get the PDF string object
     *
     * @see SetaPDF_Core_DataStructure_Interface::getValue()
     * @return SetaPDF_Core_Type_String
     */
    public function getValue()
    {
        return $this->_string;
    }

    /**
     * Get the date as a PHP string
     *
     * @see SetaPDF_Core_DataStructure_Interface::toPhp()
     */
    public function toPhp()
    {
        return $this->_string->toPhp();
    }
}