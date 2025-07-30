<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Writer.php 378 2013-02-07 17:00:17Z jan $
 */

/**
 * Class for writer constants and short hand writer object
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Writer
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Writer
    implements SetaPDF_Core_WriteInterface
{
    /**#@+
     * Writer status flags
     *
     * @var integer
     */
    const ACTIVE = 1;
    const INACTIVE = 0;
    const FINISHED = -1;
    const CLEANED_UP = -2;
    /**#@-*/

    /**
     * The content of the writer
     *
     * @var string
     */
    public $content = '';

    /**
     * Writes bytes to the output
     *
     * @param string $bytes
     */
    public function write($bytes)
    {
        $this->content .= $bytes;
    }

    /**
     * Implementation of the __toString method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }

}