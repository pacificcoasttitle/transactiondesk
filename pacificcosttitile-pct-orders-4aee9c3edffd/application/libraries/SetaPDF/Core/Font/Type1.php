<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Type1.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Class for Type1 fonts
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Font_Type1
    extends SetaPDF_Core_Font
    implements SetaPDF_Core_Font_Glyph_Collection_Interface
{
    /**
     * The font name
     *
     * @var string
     */
    protected $_fontName;

    /**
     * The font family
     *
     * @var string
     */
    protected $_fontFamily;

    /**
     * The font bounding box
     *
     * @var array
     */
    protected $_fontBBox;

    /**
     * The italic angle
     *
     * @var float
     */
    protected $_italicAngle;

    /**
     * The distance from baseline of highest ascender (Typographic ascent)
     *
     * @return float
     */
    protected $_ascent;

    /**
     * The distance from baseline of lowest descender (Typographic descent)
     *
     * @return float
     */
    protected $_descent;

    /**
     * The average width of glyphs in the font.
     *
     * @var integer|float
     */
    protected $_avgWidth;

    /**
     * The maximum width of glyphs in the font
     *
     * @var integer|float
     */
    protected $_maxWidth;

    /**
     * The width to use for character codes whose widths are not specified in a font dictionary’s Widths array.
     *
     * @var integer|float
     */
    protected $_missingWidth;

    /**
     * Flag indicating if this font is bold.
     *
     * @var boolean
     */
    protected $_isBold = false;

    /**
     * Flag indicating if this font is italic.
     *
     * @var boolean
     */
    protected $_isItalic = false;

    /**
     * Flag indicating if this font is monospace.
     *
     * @var boolean
     */
    protected $_isMonospace = false;

    /**
     * Glyph widths
     *
     * @var array
     */
    protected $_widths;

    /**
     * The encoding table
     *
     * @var array
     */
    protected $_encodingTable = null;

    /**
     * The UTF-16BE unicode value for a substitute character
     *
     * @var null|string
     */
    protected $_substituteCharacter = null;

    /**
     * A cache of width values
     *
     * @var array
     */
    protected $_glyphsWidthCache = array();

    /**
     * Helper method to get a specific value from the font descriptor of the font
     *
     * @param string $name
     * @param mixed $default A default value returned if the $name doesn't exists
     * @return bool|mixed|null
     */
    protected function _getFontDescriptorValue($name, $default = null)
    {
        $descriptor = $this->_dictionary->offsetGet('FontDescriptor')->ensure();

        if ($descriptor->offsetExists($name)) {
            return $descriptor->offsetGet($name)->ensure()->toPhp();
        }

        return $default;
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

    /**
     * Get the font family
     *
     * @return string
     */
    public function getFontFamily()
    {
        if (null === $this->_fontFamily)
            $this->_fontFamily = $this->_getFontDescriptorValue('FontFamily');

        return $this->_fontFamily;
    }

    /**
     * Checks if the font is bold
     *
     * @return boolean
     */
    public function isBold()
    {
        if (null === $this->_isBold) {
            $fontWeigth = $this->_getFontDescriptorValue('FontWeight', 400);
            $this->_isBold = $fontWeigth >= 700;
        }

        return $this->_isBold;
    }

    /**
     * Checks if the font is italic
     *
     * @return boolean
     */
    public function isItalic()
    {
        if (null === $this->_isItalic)
            $this->_isItalic = $this->getItalicAngle() != 0;

        return $this->_isItalic;
    }

    /**
     * Checks if the font is monospace.
     *
     * @return boolean
     */
    public function isMonospace()
    {
        if (null === $this->_isMonospace) {
            $flags = $this->_getFontDescriptorValue('Flags', 0);
            $this->_isMonospace = ($flags & 1) == 1;
        }

        return $this->_isMonospace;
    }

    /**
     * Returns the font bounding box
     *
     * @return array
     */
    public function getFontBBox()
    {
        if (null === $this->_fontBBox) {
            $fontBBox = $this->_getFontDescriptorValue('FontBBox');
            $this->_fontBBox = array(
                'llx' => $fontBBox[0],
                'lly' => $fontBBox[1],
                'urx' => $fontBBox[2],
                'ury' => $fontBBox[3]
            );
        }
        return $this->_fontBBox;
    }

    /**
     * Returns the italic angle
     *
     * @return float
     */
    public function getItalicAngle()
    {
        if (null === $this->_italicAngle)
            $this->_italicAngle = $this->_getFontDescriptorValue('ItalicAngle', 0);

        return $this->_italicAngle;
    }

    /**
     * Returns the distance from baseline of highest ascender (Typographic ascent)
     *
     * @return float
     */
    public function getAscent()
    {
        if (null === $this->_ascent)
            $this->_ascent = $this->_getFontDescriptorValue('Ascent');

        return $this->_ascent;
    }

    /**
     * Returns the distance from baseline of lowest descender (Typographic descent)
     *
     * @return float
     */
    public function getDescent()
    {
        if (null === $this->_descent)
            $this->_descent = $this->_getFontDescriptorValue('Descent');

        return $this->_descent;
    }

    /**
     * Get the average glyph width
     *
     * @return integer|float
     */
    public function getAvgWidth()
    {
        if (null === $this->_avgWidth)
            $this->_avgWidth = $this->_getFontDescriptorValue('AvgWidth');

        return $this->_avgWidth;
    }

    /**
     * Get the max. glyph width
     *
     * @return integer|float
     */
    public function getMaxWidth()
    {
        if (null === $this->_maxWidth)
            $this->_maxWidth = $this->_getFontDescriptorValue('MaxWidth');

        return $this->_maxWidth;
    }

    /**
     * Get the missing glyph width
     *
     * @return integer|float
     */
    public function getMissingWidth()
    {
        if (null === $this->_missingWidth)
            $this->_missingWidth = $this->_getFontDescriptorValue('MissingWidth');

        return $this->_missingWidth;
    }

    /**
     * Resolves the width values from the font descriptor and fills the {@see $_width}-array
     */
    protected function _getWidths()
    {
        $firstChar = $this->_dictionary->offsetGet('FirstChar')->ensure()->toPhp();
        $lastChar = $this->_dictionary->offsetGet('LastChar')->ensure()->toPhp();
        $widths = $this->_dictionary->offsetGet('Widths')->ensure()->toPhp();
        $table = $this->_getEncodingTable();
        $this->_widths = array();

        for ($i = $firstChar; $i <= $lastChar; $i++) {
            $utf16BeCodePoint = SetaPDF_Core_Encoding::toUtf16Be($table, chr($i), false, true, $this->_substituteCharacter);
            $this->_widths[$utf16BeCodePoint] = $widths[$i - $firstChar];
        }
    }

    /**
     * Get the width of a glyph/character
     *
     * @see SetaPDF_Core_Font::getGlyphWidth()
     * @param string $char
     * @param string $encoding The input encoding
     * @return float|int
     */
    public function getGlyphWidth($char, $encoding = 'UTF-16BE')
    {
        if (null === $this->_widths) {
            $this->_getWidths();
        }

        return parent::getGlyphWidth($char, $encoding);
    }

    /**
     * Get the base encoding of the font
     *
     * If no BaseEncdoing entry is available we use the
     * Standard encoding for now. This should be extended
     * to get the fonts build in encoding later.
     *
     * @return array
     */
    public function getBaseEncodingTable()
    {
        return SetaPDF_Core_Encoding_Standard::$table;
    }

    /**
     * Get the encoding table based on the Encoding dictionary and it's Differences entry (if available)
     *
     * @return array
     */
    protected function _getEncodingTable()
    {
        if (null === $this->_encodingTable) {
            /* 1. Check for an existing encoding which
             *    overwrites the fonts build in encoding
             */
            $baseEncoding = false;
            $diff = array();

            if ($this->_dictionary->offsetExists('Encoding')) {
                $encoding = $this->_dictionary->offsetGet('Encoding')->ensure();
                if ($encoding instanceof SetaPDF_Core_Type_Name) {
                    $baseEncoding = $encoding->getValue();
                    $diff = array();
                } else {
                    $baseEncoding = $encoding->offsetExists('BaseEncoding')
                        ? $encoding->offsetGet('BaseEncoding')->getValue()->toPhp()
                        : false;

                    $diff = $encoding->offsetExists('Differences')
                        ? $encoding->offsetGet('Differences')->getValue()->toPhp()
                        : array();
                }
            }

            if ($baseEncoding) {
                $baseEncoding = substr($baseEncoding, 0, strpos($baseEncoding, 'Encoding'));
                $className = 'SetaPDF_Core_Encoding_' . $baseEncoding;
                $baseEncodingTable = call_user_func(array($className, 'getTable'));
            } else {
                $baseEncodingTable = $this->getBaseEncodingTable();
            }

            $currentCharCode = null;
            foreach ($diff AS $value) {
                if (is_double($value)) {
                    $currentCharCode = $value;
                    continue;
                }

                $utf16BeCodePoint = SetaPDF_Core_Font_Glyph_List::byName($value);
                $baseEncodingTable[$utf16BeCodePoint] = chr($currentCharCode++);
            }

            $this->_encodingTable = $baseEncodingTable;

            // Try to get the "?" as substitute character
            $this->_substituteCharacter = SetaPDF_Core_Encoding::fromUtf16Be($this->_encodingTable, "\x00\x3F", true);
        }

        return $this->_encodingTable;
    }
}