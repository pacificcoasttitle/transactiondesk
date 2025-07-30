<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Text
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * Helper class for writing and handling text
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Text
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Text
{
    /**#@+
     * Alignment constant
     * 
     * @var string
     */
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_RIGHT = 'right';
    const ALIGN_JUSTIFY = 'justify';
    /**#@-*/
    
    /**
     * Splits a UTF-16BE encoded string into lines based on a specific font and width
     *
     * @param string $text The text encoded in UTF-16BE
     * @param float $width
     * @param SetaPDF_Core_Font_Glyph_Collection_Interface $font
     * @param float $fontSize
     * @param int $charSpacing
     * @param int $wordSpacing
     * @return array An array of UTF-16BE encoded strings
     * @throws InvalidArgumentException
     */
    static public function getLines(
        $text,
        $width = null,
        SetaPDF_Core_Font_Glyph_Collection_Interface $font = null,
        $fontSize = null,
        $charSpacing = 0,
        $wordSpacing = 0
    )
    {
        if (null === $width) {
            return explode("\x00\x0a", $text);
        }
    
        $possibleDelemitter = array(
            // true == after | false == before
            "\x00\x20" => true, // space
            "\x00\x21" => true, // !
            "\x00\x25" => true, // %
            "\x00\x3F" => true, // ?
            "\x00\x29" => true, // )
            "\x00\x28" => false, // (
            "\x00\x24" => false, // $
            "\x20\xAC" => false, // €
            "\x00\x2B" => false, // +
        );
    
        $currentLine = 0;
        $lines = array(0 => '');
        $lineWidth = 0;
        $linePosition = 0;
        $lastDelemitterPos = null;
        $lastDelemitterDirection = true;
    
        $len = SetaPDF_Core_Encoding::strlen($text, 'UTF-16BE');
        for ($i = 0; $i < $len; $i++) {
            $char = SetaPDF_Core_Encoding::substr($text, $i, 1, 'UTF-16BE');
    
            if ($char == "\x00\x0a") {
                $lines[++$currentLine] = '';
                $lineWidth = 0;
                $linePosition = 0;
                $lastDelemitterPos = null;
                continue;
            }
    
            if (isset($possibleDelemitter[$char])) {
                $lastDelemitterPos = $linePosition;
                $lastDelemitterDirection = $possibleDelemitter[$char];
            }
            
            $charWidth = $font->getGlyphWidth($char) / 1000 * $fontSize;
            
            if ($char !== "\x00\x20" && 
                (abs($charWidth + $lineWidth) - $width > SetaPDF_Core::FLOAT_COMPARSION_PRECISION)
            ) {
                if (0 === $i) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'A single character (%s) does not fits into the given $width (%F).',
                            SetaPDF_Core_Encoding::convert($char, 'UTF-16BE', 'UTF-8'),
                            $width
                        )
                    );
                }
                
                // If no delemiter exists in the current line, simply add a line break
                if (is_null($lastDelemitterPos)) {
                    $lines[++$currentLine] = '';
                    $lineWidth = 0;
                    $linePosition = 0;
    
                    // Else cut the last "word" and shift it to the next line
                } else {
                    // save last "word"
                    $tmpLine = SetaPDF_Core_Encoding::substr(
                        $lines[$currentLine],
                        $lastDelemitterPos + ($lastDelemitterDirection ? 1 : 0),
                        SetaPDF_Core_Encoding::strlen($lines[$currentLine], 'UTF-16BE'),
                        'UTF-16BE'
                    );
    
                    // Remove last "word"
                    $lines[$currentLine] = SetaPDF_Core_Encoding::substr(
                        $lines[$currentLine],
                        0,
                        $lastDelemitterPos + ($lastDelemitterDirection ? 1 : 0),
                        'UTF-16BE'
                    );
    
                    // Init next line with the last "word" of the previous line
                    $lines[++$currentLine] = $tmpLine;
                    $lineWidth = $font->getGlyphsWidth($tmpLine) / 1000 * $fontSize;
                    $linePosition = SetaPDF_Core_Encoding::strlen($tmpLine, 'UTF-16BE');
                    if ($charSpacing != 0)
                        $lineWidth += $linePosition * $charSpacing;
                    
                    $lastDelemitterPos = null;
                }
            }
    
            if ($wordSpacing != 0 && $char === "\x00\x20") {
                $lineWidth += $wordSpacing;
            }
            
            if ($charSpacing != 0) {
                $lineWidth += $charSpacing;
            }
            
            $lineWidth += $charWidth;
            $lines[$currentLine] .= $char;
    
            $linePosition++;
        }
    
        return $lines;
    }
}