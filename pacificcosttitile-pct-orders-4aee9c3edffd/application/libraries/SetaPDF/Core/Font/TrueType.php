<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: TrueType.php 302 2012-11-01 11:26:30Z maximilian $
 */

/**
 * Class for TrueType fonts
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Font_TrueType
    extends SetaPDF_Core_Font_Type1
{
    /**
     * A cache for font descriptor objects.
     *
     * @var array
     */
    static protected $_fontDescriptors = array();

    /**
     * The TTF/OTF parser
     *
     * @var SetaPDF_Core_Font_TrueType_Parser
     */
    protected $_ttfParser = null;

    /**
     * Flag for handling automatic encoding
     *
     * @var boolean
     */
    protected $_autoEncoding = false;

    /**
     * A temporary encoding table holding used character codes
     *
     * This array is only used if the _autoEncoding property is used.
     *
     * @var array
     */
    protected $_tmpEncodingTable = array();

    /**
     * Creates a font object based on a TrueType font file
     *
     * @param SetaPDF_Core_Document $document
     * @param string $fontFile
     * @param string $baseEncoding
     * @param array|string $diffEncoding
     * @param boolean $embedded
     * @param bool $forceLicenseRestrictions
     * @return SetaPDF_Core_Font_TrueType
     * @throws SetaPDF_Core_Font_Exception
     */
    static public function create(
        SetaPDF_Core_Document $document,
        $fontFile,
        $baseEncoding = SetaPDF_Core_Encoding::WIN_ANSI,
        $diffEncoding = array(),
        $embedded = true,
        $forceLicenseRestrictions = false
    )
    {
        $ttfParser = new SetaPDF_Core_Font_TrueType_Parser($fontFile);

        // Check if embedding is allowed
        if ($embedded && false === $forceLicenseRestrictions && false === $ttfParser->isEmbeddable()) {
            throw new SetaPDF_Core_Font_Exception(
                'Due to license restrictions it is not allowed to embed this font file (%s).'
            );
        }

        $dictionary = new SetaPDF_Core_Type_Dictionary();
        $dictionary->offsetSet('Type', new SetaPDF_Core_Type_Name('Font', true));
        $dictionary->offsetSet('Subtype', new SetaPDF_Core_Type_Name('TrueType', true));

        $baseFont = $ttfParser->getPostScriptName();
        $dictionary->offsetSet('BaseFont', new SetaPDF_Core_Type_Name($baseFont));
        $dictionary->offsetSet('FirstChar', new SetaPDF_Core_Type_Numeric(0));
        $dictionary->offsetSet('LastChar', new SetaPDF_Core_Type_Numeric(0));

        $baseEncodingTable = SetaPDF_Core_Encoding::getPredefinedEncodingTable($baseEncoding);

        $factor = 1000 / $ttfParser->getUnitsPerEm();

        $encoding = new SetaPDF_Core_Type_Dictionary();
        $encoding->offsetSet('Type', new SetaPDF_Core_Type_Name('Encoding', true));
        $encoding->offsetSet('BaseEncoding', new SetaPDF_Core_Type_Name($baseEncoding));

        if (count($diffEncoding) > 0) {
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
        }

        $dictionary->offsetSet('Encoding', $encoding);

        $firstChar = ord(min($baseEncodingTable));
        $lastChar = ord(max($baseEncodingTable));
        $dictionary->getValue('FirstChar')->setValue($firstChar);
        $dictionary->getValue('LastChar')->setValue($lastChar);

        $chars = array();
        for ($i = $firstChar; $i <= $lastChar; $i++) {
            if (isset($diffEncoding[$i])) {
                $utf16 = SetaPDF_Core_Font_Glyph_List::byName($diffEncoding[$i]);
            } else {
                $utf16 = array_search(chr($i), $baseEncodingTable);
            }
            $chars[$i] = $utf16;
        }

        if ($diffEncoding !== 'auto' && false === $ttfParser->areCharsCovered($chars)) {
            throw new SetaPDF_Core_Font_Exception(
                sprintf('Font (%s) does not cover the defined encoding (%s) and given differences.', $fontFile, $baseEncoding)
            );
        }

        $widths = $ttfParser->getWidths($factor, $chars);
        $widthsArray = new SetaPDF_Core_Type_Array();
        foreach ($widths AS $width) {
            $widthsArray[] = new SetaPDF_Core_Type_Numeric($width);
        }

        $widthsObject = $document->createNewObject($widthsArray);
        $dictionary->offsetSet('Widths', $widthsObject);

        /* Handle/Prepare ToUnicode
         * This is required by the Adobe Reader if a predefined encoding is changed 
         */
        if (count($diffEncoding) > 0) {
            $toUnicodeStream = new SetaPDF_Core_Type_Stream();
            $toUnicodeStream->getValue()->offsetSet('Filter', new SetaPDF_Core_Type_Name('FlateDecode', true));
            $streamObject = $document->createNewObject($toUnicodeStream);
            $dictionary->offsetSet('ToUnicode', $streamObject);
            if ($diffEncoding !== 'auto') {
                $toUnicodeStream->setStream(self::_createToUnicodeStream($firstChar, $lastChar, $chars));
            }
        }

        $documentInstanceIdent = $document->getInstanceIdent();
        $fontKey = $fontFile . ($embedded ? '1' : '0');
        if (!isset(self::$_fontDescriptors[$documentInstanceIdent][$fontKey])) {
            $fontDescriptor = new SetaPDF_Core_Type_Dictionary();
            $fontDescriptor->offsetSet('Type', new SetaPDF_Core_Type_Name('FontDescriptor', true));
            $fontDescriptor->offsetSet('FontName', new SetaPDF_Core_Type_Name($baseFont));
            $fontDescriptor->offsetSet('FontFamily', new SetaPDF_Core_Type_String($ttfParser->getFontFamily()));

            $flags = 32; // Bit pos 6 = Nonsymbolic
            if ($ttfParser->isFixedPitch())
                $flags |= 1 << 0; // 1 = FixedPitch

            if ($ttfParser->isItalic())
                $flags |= 1 << 6; // 7 = Italic

            $fontDescriptor->offsetSet('Flags', new SetaPDF_Core_Type_Numeric($flags));
            $fontDescriptor->offsetSet('FontBBox', SetaPDF_Core_DataStructure_Rectangle::byArray($ttfParser->getBoundingBox($factor), true));
            $fontDescriptor->offsetSet('Ascent', new SetaPDF_Core_Type_Numeric($ttfParser->getAscender($factor)));
            $fontDescriptor->offsetSet('Descent', new SetaPDF_Core_Type_Numeric($ttfParser->getDescender($factor)));
            $fontDescriptor->offsetSet('CapHeight', new SetaPDF_Core_Type_Numeric($ttfParser->getCapHeight($factor)));
            $fontDescriptor->offsetSet('ItalicAngle', new SetaPDF_Core_Type_Numeric($ttfParser->getItalicAngle($factor)));
            $usWeightClass = $ttfParser->getUsWeightClass();
            $fontDescriptor->offsetSet('FontWeight', new SetaPDF_Core_Type_Numeric($usWeightClass));

            /* There seems to be no official way to receive the StemV value
             * of a TTF font. Some set it to 0 (unknown) or use their own
             * ways/fuzzy formulars (as we do).
             */
            $stemV = 50 + (int)(pow($usWeightClass / 65, 2));
            $fontDescriptor->offsetSet('StemV', new SetaPDF_Core_Type_Numeric($stemV));

            $fontDescriptor->offsetSet('MissingWidth', new SetaPDF_Core_Type_Numeric($ttfParser->getMissingWidth($factor)));

            if ($embedded) {
                $streamDict = new SetaPDF_Core_Type_Dictionary();
                $streamDict->offsetSet('Length1', new SetaPDF_Core_Type_Numeric(filesize($fontFile)));
                $streamDict->offsetSet('Filter', new SetaPDF_Core_Type_Name('FlateDecode', true));
                $stream = new SetaPDF_Core_Type_Stream($streamDict);
                $stream->setStream(file_get_contents($fontFile));

                $fontStreamObject = $document->createNewObject($stream);
                $fontDescriptor->offsetSet('FontFile2', $fontStreamObject);
            }

            self::$_fontDescriptors[$documentInstanceIdent][$fontKey] = $document->createNewObject($fontDescriptor);
        }

        $dictionary->offsetSet('FontDescriptor', self::$_fontDescriptors[$documentInstanceIdent][$fontKey]);

        $fontObject = $document->createNewObject($dictionary);

        $font = new self($fontObject);
        $font->_ttfParser = $ttfParser;
        if ($diffEncoding === 'auto') {
            $font->_autoEncoding = true;
            $dictionary->registerPdfStringCallback(array($font, 'updateToUnicodeStream'), 'updateToUnicodeStream');
        }

        return $font;
    }

    /**
     * Creates a standard /ToUnicode stream for TrueType fonts
     *
     * @param integer $firstChar
     * @param integer $lastChar
     * @param array $chars
     * @return string
     */
    static protected function _createToUnicodeStream($firstChar, $lastChar, $chars)
    {
        $stream = "/CIDInit /ProcSet findresource begin\n"
                . "10 dict begin\n" // 5014.CIDFont_Spec.pdf: Adobe advises, as was done here, that you define a dictionary
                // containing room for three or four additional entries.
                . "begincmap\n"
                . "/CIDSystemInfo\n"
                . "<<\n"
                . "/Registry (Adobe) def\n"
                . "/Ordering (UCS) def\n"
                . "/Supplement 0 def\n"
                . ">> def\n"
                . "/CMapName /Adobe-Identity-UCS def\n"
                . "/CMapType 2 def\n"
                . "1 begincodespacerange\n"
                . sprintf("<%02X> <%02X>\n", $firstChar, $lastChar)
                . "endcodespacerange\n"
                . "1 beginbfrange\n"
                . sprintf("<%02X> <%02X> [", $firstChar, $lastChar);

        foreach ($chars AS $utf16) {
            if (false !== $utf16) {
                $stream .= sprintf('<%04X>', SetaPDF_Core_Encoding::utf16BeToUnicodePoint($utf16));
            } else {
                $stream .= '<003F>';
            }
        }

        $stream .= "]\n"
                . "endbfrange\n"
                . "endcmap\n"
                . "CMapName currentdict /CMap defineresource pop\n"
                . "end\n"
                . "end\n";

        return $stream;
    }

    /**
     * Get the glyph width
     *
     * This method is a proxy method if the width-array is not initialized and
     * the font is build from a TTF font.
     *
     * @see SetaPDF_Core_Font_Type1::getGlyphWidth()
     */
    public function getGlyphWidth($char, $encoding = 'UTF-16BE')
    {
        if (null === $this->_ttfParser)
            return parent::getGlyphWidth($char, $encoding);

        if ($encoding !== 'UTF-16BE')
            $char = SetaPDF_Core_Encoding::convert($char, $encoding, 'UTF-16BE');

        $factor = 1000 / $this->_ttfParser->getUnitsPerEm();

        return $this->_ttfParser->getWidth($factor, $char);
    }

    /**
     * Get the final character code of a single character
     *
     * If the font is based on a TTF file and the $diffEncoding is set to 'auto'
     * this method will build the differences from the encoding automatically.
     * It will simply recreate a completely new encoding starting at 0.
     *
     * @param string $char The character
     * @param string $encoding
     * @return string
     * @throws SetaPDF_Core_Font_Exception
     */
    public function getCharCode($char, $encoding = 'UTF-16BE')
    {
        if (null === $this->_ttfParser || false === $this->_autoEncoding) {
            return parent::getCharCode($char, $encoding);
        }

        if ($encoding !== 'UTF-16BE')
            $char = SetaPDF_Core_Encoding::convert($char, $encoding, 'UTF-16BE');

        $offset = 0;
        $code = array_search($char, $this->_tmpEncodingTable);
        if ($code === false) {
            $code = count($this->_tmpEncodingTable);
            $position = $code + $offset;

            if ($position > 255) {
                throw new SetaPDF_Core_Font_Exception(
                    'Font with auto-encoding reaches chars limit of 255 chars!'
                );
            }

            if (false === $this->_ttfParser->isCharCovered($char)) {
                throw new SetaPDF_Core_Font_Exception(
                    sprintf(
                        'Font (%s) does not cover the needed glyph (%s).',
                        $this->getFontName(),
                        SetaPDF_Core_Font_Glyph_List::byUtf16Be($char)
                    )
                );
            }

            $this->_tmpEncodingTable[] = $char;

            $widths = $this->_dictionary->offsetGet('Widths')->ensure();
            $factor = 1000 / $this->_ttfParser->getUnitsPerEm();
            $widths->offsetGet($position)
                ->setValue($this->_ttfParser->getWidth($factor, $char));

            $encoding = $this->_dictionary->offsetGet('Encoding')->ensure();
            $differences = $encoding->offsetGet('Differences')->ensure();
            if ($differences->count() === 0) {
                $differences[] = new SetaPDF_Core_Type_Numeric($offset);
            }

            $differences[] = new SetaPDF_Core_Type_Name(SetaPDF_Core_Font_Glyph_List::byUtf16Be($char));
        }

        return chr($offset + $code);
    }

    /**
     * A callback function which will create the current ToUnicode CMap.
     *
     * This method should not be called manually. It is registered as a callback of the
     * font object, which was created in the create()-method.
     */
    public function updateToUnicodeStream()
    {
        if (false === $this->_autoEncoding) {
            throw new BadMethodCallException(
                'This method is only callable if the font encoding is set to "auto".'
            );
        }

        $firstChar = $this->_dictionary->getValue('FirstChar')->getValue();
        $lastChar = $this->_dictionary->getValue('LastChar')->getValue();

        $toUnicodeStream = $this->_dictionary->offsetGet('ToUnicode')->ensure();
        $toUnicodeStream->setStream(self::_createToUnicodeStream($firstChar, $lastChar, $this->_tmpEncodingTable));
    }
}