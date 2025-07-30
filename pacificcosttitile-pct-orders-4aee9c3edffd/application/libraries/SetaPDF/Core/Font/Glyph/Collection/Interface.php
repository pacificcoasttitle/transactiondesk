<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * An interface for glyph collections
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Font_Glyph_Collection_Interface
{
    /**
     * Get the glyph width of a single character
     * @param string $char
     * @param string $encoding
     */
    public function getGlyphWidth($char, $encoding = 'UTF-16BE');

    /**
     * Get the glyphs width of a string
     *
     * @param string $chars
     * @param string $encoding
     */
    public function getGlyphsWidth($chars, $encoding = 'UTF-16BE');
}