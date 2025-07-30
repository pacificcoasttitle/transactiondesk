<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Standard.php 378 2013-02-07 17:00:17Z jan $
 */

/**
 * Abstract class for standard PDF fonts
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 */
abstract class SetaPDF_Core_Font_Standard
extends SetaPDF_Core_Font
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
    protected $_fontBBox = array();

    /**
     * The italic angle
     *
     * @var float
     */
    protected $_italicAngle = 0;

    /**
     * The distance from baseline of highest ascender (Typographic ascent)
     *
     * @return float
     */
    protected $_ascent = 0;

    /**
     * The distance from baseline of lowest descender (Typographic descent)
     *
     * @return float
     */
    protected $_descent = 0;

    /**
     * The vertical coordinate of the top of flat capital letters, measured from the baseline.
     *
     * @var float
     */
    protected $_capHeight = 0;

    /**
     * The vertical coordinate of the top of flat nonascending lowercase letters (like the letter x), measured from the baseline
     *
     * @var float
     */
    protected $_xHeight = 0;

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
    protected $_widths = array();

    /**
     * Kerning pairs
     *
     * @var array
     */
    protected $_kerningPairs = array();

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
     * Helper method to get all available standart font names and their class mapping
     *
     * @return array
     */
    static public function getStandardFontsToClasses()
    {
        $prefix = 'SetaPDF_Core_Font_Standard_';

        return array(
            'Courier'               => $prefix . 'Courier',
            'Courier-Bold'          => $prefix . 'CourierBold',
            'Courier-BoldOblique'   => $prefix . 'CourierBoldOblique',
        	'Courier-Oblique'       => $prefix . 'CourierOblique',
            'Helvetica'             => $prefix . 'Helvetica',
            'Helvetica-Bold'        => $prefix . 'HelveticaBold',
            'Helvetica-BoldOblique' => $prefix . 'HelveticaBoldOblique',
            'Symbol'                => $prefix . 'Symbol',
            'Times-Bold'            => $prefix . 'TimesBold',
            'Times-BoldItalic'      => $prefix . 'TimesBoldItalic',
            'Times-Italic'          => $prefix . 'TimesItalic',
            'Times-Roman'           => $prefix . 'TimesRoman',
            'ZapfDingbats'          => $prefix . 'ZapfDingbats'
        );
    }

    static protected function _createDifferenceArray(
        SetaPDF_Core_Type_Dictionary $dictionary,
        $baseEncoding,
        array $diffEncoding
    )
    {
        if (count($diffEncoding) === 0) {
            return;
        }

        $baseEncoding = str_replace('Encoding', '', $baseEncoding);

        $encoding = new SetaPDF_Core_Type_Dictionary();
        $encoding->offsetSet('Type', new SetaPDF_Core_Type_Name('Encoding', true));
        $encoding->offsetSet('BaseEncoding', new SetaPDF_Core_Type_Name($baseEncoding . 'Encoding'));

        $differences = new SetaPDF_Core_Type_Array();
        $encoding->offsetSet('Differences', $differences);

        $currentCode = null;
        if (is_array($diffEncoding)) {
            foreach ($diffEncoding AS $code => $name) {
                if (null === $currentCode || $code !== $currentCode) {
                    $differences[] = new SetaPDF_Core_Type_Numeric($code);
                    $currentCode = $code;
                }

                $differences[] = new SetaPDF_Core_Type_Name($name);
                $currentCode++;
            }
        }

        $dictionary->offsetSet('Encoding', $encoding);
    }

    /**
     * Get the font name
     *
     * @return string
     */
    public function getFontName()
    {
        return $this->_fontName;
    }

    /**
     * Get the font family
     *
     * @return string
     */
    public function getFontFamily()
    {
        return $this->_fontFamily;
    }

    /**
     * Get the base encoding table
     *
     * The base encoding of all Standard Fonts is StandardEncoding
     * but Symbol and ZapfDingbats. They use their own encoding
     *
     * @return array
     * @see SetaPDF_Core_Encoding_Standard
     */
    public function getBaseEncodingTable()
    {
        return SetaPDF_Core_Encoding_Standard::$table;
    }

    /**
     * Returns the font bounding box
     *
     * @return array
     */
    public function getFontBBox()
    {
        return $this->_fontBBox;
    }

    /**
     * Returns the distance from baseline of highest ascender (Typographic ascent)
     *
     * @return float
     */
    public function getAscent()
    {
        return $this->_ascent;
    }

    /**
     * Returns the distance from baseline of lowest descender (Typographic descent)
     *
     * @return float
     */
    public function getDescent()
    {
        return $this->_descent;
    }

    /**
     * Get the vertical coordinate of the top of flat capital letters, measured from the baseline.
     *
     * @return float
     */
    public function getCapHeight()
    {
        return $this->_capHeight;
    }

    /**
     * Get the vertical coordinate of the top of flat nonascending lowercase letters (like the letter x), measured from the baseline,
     *
     * @return float
     */
    public function getXHeight()
    {
        return $this->_xHeight;
    }

    /**
     * Returns the italic angle
     *
     * @return float
     */
    public function getItalicAngle()
    {
        return $this->_italicAngle;
    }

    /**
     * Checks if the font is bold
     *
     * @return boolean
     */
    public function isBold()
    {
        return $this->_isBold;
    }

    /**
     * Checks if the font is italic
     *
     * @return boolean
     */
    public function isItalic()
    {
        return $this->_isItalic;
    }

    /**
     * Checks if the font is monospace.
     *
     * @return boolean
     */
    public function isMonospace()
    {
        return $this->_isMonospace;
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