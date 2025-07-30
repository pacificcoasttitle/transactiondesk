<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage DataStructure
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Color.php 493 2013-06-03 15:02:17Z jan.slabon $
 */

/**
 * Abstract class for color structures
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage DataStructure
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_DataStructure_Color
{
    /**
     * The array of color components
     *
     * @var SetaPDF_Core_Type_Array
     */
    protected $_components;

    /**
     * Writes the colors components to a writer
     *
     * @param SetaPDF_Core_WriteInterface $writer
     * @param array $components
     * @param boolean|null $stroking
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $components, $stroking = true)
    {
        foreach ($components AS $value)
            SetaPDF_Core_Type_Numeric::writePdfString($writer, $value);

        if ($stroking !== null) {
            $writer->write($stroking ? ' SC' : ' sc');
        }
    }

    /**
     * @param SetaPDF_Core_WriteInterface $writer
     * @param array $components
     * @param bool $stroking
     * @throws InvalidArgumentException
     */
    static public function writePdfStringByComponents(SetaPDF_Core_WriteInterface $writer, array $components, $stroking = true)
    {
        switch (count($components)) {
            case 1:
                SetaPDF_Core_DataStructure_Color_Gray::writePdfString($writer, $components, $stroking);
                return;
            case 3:
                SetaPDF_Core_DataStructure_Color_Rgb::writePdfString($writer, $components, $stroking);
                return;
            case 4:
                SetaPDF_Core_DataStructure_Color_Cmyk::writePdfString($writer, $components, $stroking);
                return;
        }

        throw new InvalidArgumentException(
            'Invalid components count (' . count($components) . ')'
        );
    }

    /**
     * Create an instance by a PDF array object or PHP array
     * The count of $components deside the color type (1 - gray, 3 - RGB, 4 - CMYK)
     * The color values must be between 0 and 1
     *
     * @param array|number|SetaPDF_Core_Type_Array $components
     * @return SetaPDF_Core_DataStructure_Color
     * @throws InvalidArgumentException
     */
    static public function createByComponents($components)
    {
        if (!$components instanceof SetaPDF_Core_Type_Array) {
            if (is_scalar($components))
                $components = func_get_args();

            if (!is_array($components)) {
                throw new InvalidArgumentException(
                    '$components parameter has to be an array or an instance of SetaPDF_Core_Type_Array.'
                );
            }

            $_components = new SetaPDF_Core_Type_Array();
            foreach ($components AS $component) {
                if ($component instanceof SetaPDF_Core_Type_Numeric) {
                    $_components->push($component);
                } else {
                    $_components->push(new SetaPDF_Core_Type_Numeric($component));
                }
            }

            $components = $_components;
        }

        switch (count($components)) {
            case 1:
                return new SetaPDF_Core_DataStructure_Color_Gray($components);
            case 3:
                return new SetaPDF_Core_DataStructure_Color_Rgb($components);
            case 4:
                return new SetaPDF_Core_DataStructure_Color_Cmyk($components);
        }

        throw new InvalidArgumentException(
            'Invalid components count (' . count($components) . ')'
        );
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Array|array|float $components
     */
    public function __construct($components)
    {
        if (!$components instanceof SetaPDF_Core_Type_Array) {
            $_components = new SetaPDF_Core_Type_Array();
            //is gray-color
            if (is_scalar($components)) {
                $_components->push(new SetaPDF_Core_Type_Numeric($components));
            } elseif (is_array($components)) {
                foreach ($components AS $component) {
                    if ($component instanceof SetaPDF_Core_Type_Numeric) {
                        $_components->push($component);
                    } else {
                        $_components->push(new SetaPDF_Core_Type_Numeric($component));
                    }
                }
            }

            $components = $_components;
        }

        $this->_components = $components;
    }

    /**
     * Implementation of __clone()
     */
    public function __clone()
    {
        $this->_components = clone $this->_components;
    }

    /**
     * Adjust all color components by a specific value
     *
     * @param $by
     */
    public function adjustAllComponents($by)
    {
        foreach ($this->_components AS $component) {
            if (!$component instanceof SetaPDF_Core_Type_Numeric)
                continue;

            $oValue = $component->getValue();
            $component->setValue(max(0, $oValue + ((float)$by)));
        }
    }

    /**
     * Get the components of the color
     *
     * @return SetaPDF_Core_Type_Array
     */
    public function getValue()
    {
        return $this->_components;
    }

    /**
     * Get the data as a PHP value
     *
     * @return array
     */
    public function toPhp()
    {
        return $this->_components->toPhp();
    }

    /**
     * Write the color as a PDF string to a writer
     *
     * @param SetaPDF_Core_WriteInterface $writer
     * @param boolean $stroking
     */
    public function draw(SetaPDF_Core_WriteInterface $writer, $stroking = true)
    {
        self::writePdfString($writer, $this->toPhp(), $stroking);
    }
}