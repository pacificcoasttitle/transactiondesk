<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * DeviceCMYK Color Space
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage ColorSpace
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_ColorSpace_DeviceCmyk
    extends SetaPDF_Core_ColorSpace
{
    /**
     * Creates an instance of this color space
     *
     * @return SetaPDF_Core_ColorSpace_DeviceCmyk
     */
    static public function create()
    {
        return new self(new SetaPDF_Core_Type_Name('DeviceCMYK'));
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Abstract $name
     * @throws InvalidArgumentException
     */
    public function __construct(SetaPDF_Core_Type_Abstract $name)
    {
        parent::__construct($name);

        if ($this->getPdfValue()->getValue() !== 'DeviceCMYK') {
            throw new InvalidArgumentException('DeviceCmyk color space has to be named "DeviceCMYK.');
        }
    }
}