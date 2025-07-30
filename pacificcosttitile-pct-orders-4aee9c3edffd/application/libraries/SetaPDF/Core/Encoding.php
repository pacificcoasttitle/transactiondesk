<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Encoding
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Encoding.php 497 2013-06-06 06:49:27Z jan.slabon $
 */

/**
 * A wrapper class for handling PDF specific encodings
 *
 * This class is a wrapper around iconv/mb_*-functions to offer a transparent
 * support of PDF specific and independent, unknown encodings.
 *
 * By default the class will use iconv functions. To use mb_* functions instead just set the static property
 * SetaPDF_Core_Encoding::$library to 'mb';
 *
 * <code>
 * SetaPDF_Core_Encoding::$library = 'mb';
 * </code>
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Encoding
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Encoding
{
  /* PDF specific predefined encodings */

    /**
     * WinAnsiEncoding
     *
     * @var string
     */
    const WIN_ANSI = 'WinAnsiEncoding';

    /**
     * PDFDocEncoding
     *
     * @var string
     */
    const PDF_DOC = 'PDFDocEncoding';

    /**
     * StandardEncoding
     *
     * @var string
     */
    const STANDARD = 'StandardEncoding';

    /**
     * MacRomanEncoding
     *
     * @var string
     */
    const MAC_ROMAN = 'MacRomanEncoding';

    /**
     * MacExpertEncoding
     *
     * @var string
     */
    const MAX_EXPERT = 'MacExpertEncoding';

    /**
     * ZapfDingbats
     *
     * @var string
     */
    const ZAPF_DINGBATS = 'ZapfDingbats';

    /**
     * Symbol
     *
     * @var string
     */
    const SYMBOL = 'Symbol';

    /**
     * Library to use for convertions between encodings
     *
     * @var string
     */
    static public $library = 'iconv';

    /**
     * Checks if an encoding is a PDF specific predefined encoding
     *
     * @param string $encoding
     * @return boolean
     */
    static public function isPredefinedEncoding($encoding)
    {
        $encoding = str_replace('Encoding', '', $encoding);

        switch ($encoding) {
            case 'WinAnsi':
            case 'PDFDoc':
            case 'Standard':
            case 'MacRoman':
            case 'MacExpert':
            case 'ZapfDingbats':
            case 'Symbol':
                return true;
        }

        return false;
    }

    /**
     * Get the translation table of a predefined PDF specific encodings
     *
     * @param string $encoding
     * @return array
     * @throws InvalidArgumentException
     */
    static public function getPredefinedEncodingTable($encoding)
    {
        $encoding = str_replace('Encoding', '', $encoding);

        switch ($encoding) {
            case 'PDFDoc':
                $encoding = 'PdfDoc';
            case 'WinAnsi':
            case 'Standard':
            case 'MacRoman':
            case 'MacExpert':
            case 'ZapfDingbats':
            case 'Symbol':
                $className = 'SetaPDF_Core_Encoding_' . $encoding;
                return call_user_func(array($className, 'getTable'));
        }

        throw new InvalidArgumentException(
            sprintf('The encoding "%s" is not a predefined encoding.', $encoding)
        );
    }

    /**
     * Converts a string from one to another encoding
     *
     * A kind of wrapper around iconv/mb_convert_encoding plus the seperate processing of
     * PDF related encodings
     *
     * @param string $string        The string to convert in $inEncoding
     * @param string $inEncoding    The "in"-encoding
     * @param string $outEncoding    The "out"-encoding
     * @return string
     */
    static public function convert($string, $inEncoding, $outEncoding)
    {
        $_outEncoding = explode('//', $outEncoding);

        $string = (string)$string;

        if ($inEncoding === $_outEncoding[0]) {
            return $string;
        }

        if (count($_outEncoding) > 1) {
            $ignore = in_array('IGNORE', $_outEncoding);
            $translit = in_array('TRANSLIT', $_outEncoding);
        } else {
            $ignore = $translit = false;
        }

        // IN
        switch ($inEncoding) {
            case 'PDFDoc':
            case 'PDFDocEncoding':
                $inEncoding = 'PdfDoc';
            case 'PdfDoc':
            case 'Standard':
            case 'StandardEncoding':
            case 'MacRoman':
            case 'MacRomanEncoding':
            case 'WinAnsi':
            case 'WinAnsiEncoding':
            case 'MacExpert':
            case 'MacExpertEncoding':
                $inEncoding = str_replace('Encoding', '', $inEncoding);
                $className = 'SetaPDF_Core_Encoding_' . $inEncoding;
                $string = call_user_func(array($className, 'toUtf16Be'), $string, $ignore, $translit);
                $inEncoding = 'UTF-16BE';
                break;
        }

        // OUT
        switch ($_outEncoding[0]) {
            case 'PDFDoc':
            case 'PDFDocEncoding':
                $_outEncoding[0] = 'PdfDoc';
            case 'PdfDoc':
            case 'Standard':
            case 'StandardEncoding':
            case 'MacRoman':
            case 'MacRomanEncoding':
            case 'WinAnsi':
            case 'WinAnsiEncoding':
            case 'MacExpert':
            case 'MacExpertEncoding':
                if ($inEncoding !== 'UTF-16BE') {
                    if ('mb' === self::$library) {
                        $string = mb_convert_encoding($string, 'UTF-16BE', $inEncoding);
                    } else {
                        $string = iconv($inEncoding, 'UTF-16BE' . ($ignore ? '//IGNORE' : '') . ($translit ? '//TRANSLIT' : ''), $string);
                    }
                }

                $_outEncoding[0] = str_replace('Encoding', '', $_outEncoding[0]);

                $className = 'SetaPDF_Core_Encoding_' . $_outEncoding[0];
                return call_user_func(array($className, 'fromUtf16Be'), $string, $ignore, $translit);

            default:
                if ($inEncoding === $_outEncoding[0]) {
                    return $string;
                }

                if ('mb' === self::$library) {
                    return mb_convert_encoding($string, $_outEncoding[0], $inEncoding);
                } else {
                    return iconv($inEncoding, implode('//', $_outEncoding), $string);
                }
        }
    }

    /**
     * Converts a PDF string (in PDFDocEncoding or UTF-16BE) to another encoding
     *
     * This mehtod automatically detects UTF-16BE encoding in the input string and
     * removes the BOM.
     *
     * @param string $string The string to convert in PDFDocEncoding or UTF-16BE
     * @param string $outEncoding The "out"-encoding
     * @return string
     */
    static public function convertPdfString($string, $outEncoding = 'UTF-8')
    {
        $inEncoding = 'PdfDoc';
        /* There are corrupted documents (for example created by "Microsoft® Word 2010")
         * which really uses UTF-16LE in metadata!
         */
        if (strpos($string, "\xFE\xFF") === 0 || strpos($string, "\xFF\xFE") === 0) {
            $inEncoding = 'UTF-16';
        }

        $outEncoding = str_replace('Encoding', '', $outEncoding);

        return self::convert($string, $inEncoding, $outEncoding);
    }

    /**
     * Converts a string into PdfDocEncoding or UTF-16BE
     *
     * Actually directly converts to UTF-16BE to support unicode.
     * Method should be optimized to choose the correct encoding (PdfDoc or UTF-16BE)
     * depending on the characters used.
     *
     * @todo Implement auto-detection of needed encoding
     * @param string $string
     * @param string $inEncoding
     * @return string
     */
    static public function toPdfString($string, $inEncoding = 'UTF-8')
    {
        $utf16Be = self::convert($string, $inEncoding, 'UTF-16BE');
        return ($utf16Be ? "\xFE\xFF" : '') . $utf16Be;
    }

    /**
     * Converts a string from UTF-16BE to another predefined encoding
     *
     * @param array $table The translation table
     * @param string $string The input string
     * @param boolean $ignore Characters that cannot be represented in the target charset are silently discarded
     * @param boolean $translit Transliteration activated
     * @param string $substituteChar
     * @return string
     */
    static public function fromUtf16Be($table, $string, $ignore = false, $translit = false, $substituteChar = "\x1A")
    {
        $newString = '';

        $len = self::strlen($string, 'UTF-16BE');
        for ($i = 0; $i < $len; $i++) {
            $search = self::substr($string, $i, 1, 'UTF-16BE');

            $res = isset($table[$search])
                ? $table[$search]
                : false;

            if ($res !== false) {
                $newString .= $res;
            } else if ($ignore === false) {
                if ($translit === true) {
                    $newString .= $substituteChar;
                } else {
                    trigger_error(__METHOD__ . '(): Detected an illegal character in input string', E_USER_NOTICE);
                }
            }
        }

        return $newString;
    }

    /**
     * Converts a string to UTF-16BE from another predefined 1-byte encoding
     *
     * @param array $table The translation table
     * @param string $string The input string
     * @param boolean $ignore Characters that cannot be represented in the target charset are silently discarded
     * @param boolean $translit Transliteration activated
     * @return string
     */
    static public function toUtf16Be($table, $string, $ignore = false, $translit = false)
    {
        $newString = '';

        for ($i = 0, $len = strlen($string); $i < $len; $i++) {
            $res = array_search($string[$i], $table);

            if ($res !== false) {
                $newString .= $res;
            } else if ($ignore === false) {
                // Check for control characters
                if ($string[$i] <= "\x1F") {
                    $newString .= "\x00" . $string[$i];
                } elseif ($translit === true) {
                    $newString .= "\xFF\xFD"; // REPLACEMENT CHARACTER
                } else {
                    trigger_error(sprintf(__METHOD__ . '(): Detected an illegal character (0x%s) in input string (%s)', bin2hex($string[$i]), $string), E_USER_NOTICE);
                }
            }
        }

        return $newString;
    }

    /**
     * Converts an unicode point to UTF16Be
     *
     * @param string $unicodePoint
     * @return string
     */
    static public function unicodePointToUtf16Be($unicodePoint)
    {
        // UTF-32 to UTF-16BE mapping
        if ('mb' === self::$library) {
            return mb_convert_encoding(pack('N', $unicodePoint), 'UTF-16BE', 'UTF-32BE');
        } else {
            return iconv('UTF-32BE', 'UTF-16BE', pack('N', $unicodePoint));
        }
    }

    /**
     * Converts a UTF16BE character to a unicode point
     *
     * @param string $utf16
     * @return string
     */
    static public function utf16BeToUnicodePoint($utf16)
    {
        if ('mb' === self::$library) {
            return current(unpack('N', mb_convert_encoding($utf16, 'UTF-32BE', 'UTF-16BE')));
        } else {
            return current(unpack('N', iconv('UTF-16BE', 'UTF-32BE', $utf16)));
        }
    }

    /**
     * Checks a string for UTF-16BE bom
     *
     * @param string $string
     * @return bool
     */
    static public function isUtf16Be($string)
    {
        return strpos($string, "\xFE\xFF") === 0;
    }

    /**
     * Get the length of a string in a specific encoding
     *
     * @param $string
     * @param string $encoding
     *
     * @return int
     */
    static public function strlen($string, $encoding = 'UTF-8')
    {
        if (self::isPredefinedEncoding($encoding)) {
            return strlen($string);
        }

        if ('mb' === self::$library) {
            return mb_strlen($string, $encoding);
        } else {
            return iconv_strlen($string, $encoding);
        }
    }

    /**
     * Return part of a string
     *
     * @param $string
     * @param $start
     * @param $length
     * @param string $encoding
     *
     * @return string
     */
    static public function substr($string, $start, $length, $encoding = 'UTF-8')
    {
        if (self::isPredefinedEncoding($encoding)) {
            return substr($string, $start, $length);
        }

        if ('mb' === self::$library) {
            return mb_substr($string, $start, $length, $encoding);
        } else {
            return iconv_substr($string, $start, $length,$encoding);
        }
    }
}