<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Font.php 497 2013-06-06 06:49:27Z jan.slabon $
 */

/**
 * Abstract class representing a Font
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 */
abstract class SetaPDF_Core_Font implements
    SetaPDF_Core_Font_Glyph_Collection_Interface,
    SetaPDF_Core_Resource
{
    /**
     * The font dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_dictionary;

    /**
     * The indirect object of the font
     *
     * @var SetaPDF_Core_Type_IndirectObject
     */
    protected $_indirectObject;

    /**
     * Array holding information about the font
     * 
     * @var array
     */
    protected $_info = array();

    /**#@+
     * Info constant
     *
     * @var string
     */
    const INFO_COPYRIGHT = 'copyright';
    const INFO_CREATION_DATE = 'creationDate';
    const INFO_UNIQUE_ID = 'uniqueId';
    const INFO_VERSION = 'version';
    /**#@-*/

    /**
     * An array caching font objects
     *
     * @var array
     */
    static protected $_fonts = array();

    /**
     * Get a font object by an indirect referece
     * 
     * The needed font object class is automatically resolve via the Subtype value
     * of the font dictionary.
     * 
     * @param SetaPDF_Core_Type_IndirectObjectInterface $indirectObject
     * @return SetaPDF_Core_Font_Standard_Courier|SetaPDF_Core_Font_Standard_CourierBold|SetaPDF_Core_Font_Standard_CourierBoldOblique|SetaPDF_Core_Font_Standard_CourierOblique|SetaPDF_Core_Font_Standard_Helvetica|SetaPDF_Core_Font_Standard_HelveticaBold|SetaPDF_Core_Font_Standard_HelveticaBoldOblique|SetaPDF_Core_Font_Standard_HelveticaOblique|SetaPDF_Core_Font_Standard_Symbol|SetaPDF_Core_Font_Standard_TimesBold|SetaPDF_Core_Font_Standard_TimesBoldItalic|SetaPDF_Core_Font_Standard_TimesItalic|SetaPDF_Core_Font_Standard_TimesRoman|SetaPDF_Core_Font_Standard_ZapfDingbats|SetaPDF_Core_Font_TrueType|SetaPDF_Core_Font_Type1
     * @throws SetaPDF_Exception_NotImplemented
     */
    static public function get(SetaPDF_Core_Type_IndirectObjectInterface $indirectObject)
    {
        $ident = $indirectObject->getObjectIdent();
        if (isset(self::$_fonts[$ident])) {
            return self::$_fonts[$ident];
        }

        $fontDict = $indirectObject->ensure();
        $subType = $fontDict->getValue('Subtype')->getValue();

        if($indirectObject instanceof SetaPDF_Core_Type_IndirectReference) {
            $indirectObject = $indirectObject->getValue();
        }

        switch ($subType) {
            case 'Type1':
                /* Check for Standard Fonts:
                 * Check the BaseFont entry (and make sure, that
                 * FirstChar, LastChar, Width and FontDescriptor
                 * are missing.)
                 * TODO: Really needed? Not documentated in PDF32000_2 anymore but in
                 * 	PDF Reference 1.6 (5.5.1 - Standard Type 1 Fonts)
                 */
                $baseFont = $fontDict->offsetGet('BaseFont')->ensure()->getValue();
                switch ($baseFont) {
                    case 'Courier':
                    case 'CourierNew':
                        $font = new SetaPDF_Core_Font_Standard_Courier($indirectObject);
                        break;
                    case 'Courier-Oblique':
                    case 'CourierNew,Italic':
                        $font = new SetaPDF_Core_Font_Standard_CourierOblique($indirectObject);
                        break;
                    case 'Courier-Bold':
                    case 'CourierNew,Bold':
                        $font = new SetaPDF_Core_Font_Standard_CourierBold($indirectObject);
                        break;
                    case 'Courier-BoldOblique':
                    case 'CourierNew,BoldItalic':
                        $font = new SetaPDF_Core_Font_Standard_CourierBoldOblique($indirectObject);
                        break;
                    case 'Helvetica':
                    case 'Arial':
                        $font = new SetaPDF_Core_Font_Standard_Helvetica($indirectObject);
                        break;
                    case 'Helvetica-Oblique':
                    case 'Arial,Italic':
                        $font = new SetaPDF_Core_Font_Standard_HelveticaOblique($indirectObject);
                        break;
                    case 'Helvetica-Bold':
                    case 'Arial,Bold':
                        $font = new SetaPDF_Core_Font_Standard_HelveticaBold($indirectObject);
                        break;
                    case 'Helvetica-BoldOblique':
                    case 'Arial,BoldItalic':
                        $font = new SetaPDF_Core_Font_Standard_HelveticaBoldOblique($indirectObject);
                        break;
                    case 'Times-Roman':
                    case 'TimesNewRoman':
                        $font = new SetaPDF_Core_Font_Standard_TimesRoman($indirectObject);
                        break;
                    case 'Times-Italic':
                    case 'TimesNewRoman,Italic':
                        $font = new SetaPDF_Core_Font_Standard_TimesItalic($indirectObject);
                        break;
                    case 'Times-Bold':
                    case 'TimesNewRoman,Bold':
                        $font = new SetaPDF_Core_Font_Standard_TimesBold($indirectObject);
                        break;
                    case 'Times-BoldItalic':
                    case 'TimesNewRoman,BoldItalic':
                        $font = new SetaPDF_Core_Font_Standard_TimesBoldItalic($indirectObject);
                        break;
                    case 'Symbol':
                        $font = new SetaPDF_Core_Font_Standard_Symbol($indirectObject);
                        break;
                    case 'ZapfDingbats':
                        $font = new SetaPDF_Core_Font_Standard_ZapfDingbats($indirectObject);
                        break;

                    default:
                        $font = new SetaPDF_Core_Font_Type1($indirectObject);
                        break;
                }

                break;
            case 'TrueType':
                $font = new SetaPDF_Core_Font_TrueType($indirectObject);
                break;

            case 'Type0':
                $font = new SetaPDF_Core_Font_Type0($indirectObject);
                break;


            default:
                throw new SetaPDF_Exception_NotImplemented('Not implemented yet. (Font: ' . $subType . ')');
        }

        self::$_fonts[$ident] = $font;
        return $font;
    }

    /**
     * @param $indirectObject
     */
    public function __construct($indirectObject)
    {
        $this->_indirectObject = $indirectObject;
        $this->_dictionary = $indirectObject->ensure();
    }

    /**
     * Get the indirect object/reference of this font
     *
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function getIndirectObject()
    {
        return $this->_indirectObject;
    }

    /**
     * Get the resource type
     * 
     * @see SetaPDF_Core_Resource::getResourceType()
     * @return string
     */
    public function getResourceType()
    {
        return SetaPDF_Core_Resource::TYPE_FONT;
    }

    /**
     * Get the Subtype entry of the font dictionary
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->_dictionary->getValue('Subtype')->getValue();
    }

    /**
     * Returns the conding table
     *
     * @return mixed
     */
    abstract protected function _getEncodingTable();

  /* Common methods to get information from the font descriptors dictionary */

    /**
     * Get the font name
     *
     * @return string
     */
    abstract public function getFontName();

    /**
     * Get the font family
     *
     * @return string
     */
    abstract public function getFontFamily();

    /**
     * Checks if the font is bold
     *
     * @return boolean
     */
    abstract public function isBold();

    /**
     * Checks if the font is italic
     *
     * @return boolean
     */
    abstract public function isItalic();

    /**
     * Checks if the font is monospace
     *
     * @return boolean
     */
    abstract public function isMonospace();

    /**
     * Returns the font bounding box
     *
     * @return array
     */
    abstract public function getFontBBox();

    /**
     * Returns the italic angle
     *
     * @return float
     */
    abstract public function getItalicAngle();

    /**
     * Returns the distance from baseline of highest ascender (Typographic ascent)
     *
     * @return float
     */
    abstract public function getAscent();

    /**
     * Returns the distance from baseline of lowest descender (Typographic descent)
     *
     * @return float
     */
    abstract public function getDescent();

    /**
     * Get the average glyph width
     *
     * @return integer|float
     */
    public function getAvgWidth()
    {
        return 0; // defaul value
    }

    /**
     * Get the max. glyph width
     *
     * @return integer|float
     */
    public function getMaxWidth()
    {
        return 0; // defaul value
    }

    /**
     * Get the missing glyph width
     *
     * @return integer|float
     */
    public function getMissingWidth()
    {
        return 600; // default value from the PDF reference is "0"
    }

  /* Properties which are not definied in a dictionary but only in the font program */

    /**
     * Get information about the font
     *
     * @param string $name
     * @return bool|string
     */
    public function getInfo($name)
    {
        if (array_key_exists($name, $this->_info)) {
            return $this->_info[$name];
        }

        return false;
    }

    /**
     * Get the underline position
     *
     * @param string $name
     *
    public function getUnderlinePosition()
    {
    return -100;
    }

    /**
     * Get the underline thickness
     *
     * @param string $name
     *
    public function getUnderlineThickness()
    {
    return 50;
    }
     */

  /* Handling of glyph widths */

    /**
     * Get the width of a glyph/character
     *
     * @param string $char
     * @param string $encoding The input encoding
     * @return float|int
     */
    public function getGlyphWidth($char, $encoding = 'UTF-16BE')
    {
        if ($encoding !== 'UTF-16BE')
            $char = SetaPDF_Core_Encoding::convert($char, $encoding, 'UTF-16BE');

        if (isset($this->_widths[$char])) {
            return $this->_widths[$char];
        }

        return $this->getMissingWidth();
    }

    /**
     * Get the width of the glyphs/characters
     * @param string $chars
     * @param string $encoding
     * @return float|int
     */
    public function getGlyphsWidth($chars, $encoding = 'UTF-16BE')
    {
        $cacheKey = $encoding . '|' . $chars;
        if (isset($this->_glyphsWidthCache[$cacheKey])) {
            return $this->_glyphsWidthCache[$cacheKey];
        }

        if ($encoding != 'UTF-16BE')
            $chars = SetaPDF_Core_Encoding::convert($chars, $encoding, 'UTF-16BE');

        $width = 0;
        $len = SetaPDF_Core_Encoding::strlen($chars, 'UTF-16BE');
        for ($i = 0; $i < $len; $i++) {
            $char = SetaPDF_Core_Encoding::substr($chars, $i, 1, 'UTF-16BE');
            $width += $this->getGlyphWidth($char);
        }

        $this->_glyphsWidthCache = array($cacheKey => $width);

        return $width;
    }

    /**
     * Get the final character code of a single character
     *
     * @param string $char The character
     * @param string $encoding
     * @return string
     */
    public function getCharCode($char, $encoding = 'UTF-16BE')
    {
        if ($encoding !== 'UTF-16BE')
            $char = SetaPDF_Core_Encoding::convert($char, $encoding, 'UTF-16BE');

        $table = $this->_getEncodingTable();

        return SetaPDF_Core_Encoding::fromUtf16Be($table, $char, false, true, $this->_substituteCharacter);
    }

    /**
     * Get the final character codes of a character string
     *
     * @param string $chars
     * @param string $encoding
     * @return array
     */
    public function getCharCodes($chars, $encoding = 'UTF-16BE')
    {
        if ($encoding !== 'UTF-16BE')
            $chars = SetaPDF_Core_Encoding::convert($chars, $encoding, 'UTF-16BE');

        $charCodes = array();
        $len = SetaPDF_Core_Encoding::strlen($chars, 'UTF-16BE');
        for ($i = 0; $i < $len; $i++) {
            $char = SetaPDF_Core_Encoding::substr($chars, $i, 1, 'UTF-16BE');
            $charCodes[] = $this->getCharCode($char);
        }

        return $charCodes;
    }
}