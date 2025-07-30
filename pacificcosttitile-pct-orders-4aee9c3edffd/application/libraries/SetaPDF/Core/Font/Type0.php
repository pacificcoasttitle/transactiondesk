<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * Class for Type0 fonts
 *
 * @TODO Not fully implemented yet!
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Font_Type0 extends SetaPDF_Core_Font
    implements SetaPDF_Core_Font_Glyph_Collection_Interface
{
    /**
     * The font name
     *
     * @var string
     */
    protected $_fontName;

    protected function _getEncodingTable()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Get the font name
     *
     * @return string
     */
    public function getFontName()
    {
        if (null === $this->_fontName)
            $this->_fontName = $this->_dictionary->offsetGet('BaseFont')->ensure()->getValue();

        return $this->_fontName;
    }

    public function getFontFamily()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Checks if the font is bold
     *
     * @return boolean
     */
    public function isBold()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Checks if the font is italic
     *
     * @return boolean
     */
    public function isItalic()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Checks if the font is monospace
     *
     * @return boolean
     */
    public function isMonospace()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Returns the font bounding box
     *
     * @return array
     */
    public function getFontBBox()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Returns the italic angle
     *
     * @return float
     */
    public function getItalicAngle()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Returns the distance from baseline of highest ascender (Typographic ascent)
     *
     * @return float
     */
    public function getAscent()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Returns the distance from baseline of lowest descender (Typographic descent)
     *
     * @return float
     */
    public function getDescent()
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }

    /**
     * Get the width of a glyph/character
     *
     * @param string $char
     * @param string $encoding The input encoding
     * @return float|int
     */
    public function getGlyphWidth($char, $encoding = 'UTF-16BE')
    {
        throw new SetaPDF_Exception_NotImplemented('Type0 font support is not implemented.');
    }
}