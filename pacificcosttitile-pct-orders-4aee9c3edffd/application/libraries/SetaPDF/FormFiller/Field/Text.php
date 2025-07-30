<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Text.php 508 2013-06-25 10:11:55Z jan.slabon $
 */

/**
 * A text field
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_FormFiller_Field_Text
    extends SetaPDF_FormFiller_Field_Abstract
    implements SetaPDF_FormFiller_Field_Interface
{
    /**
     * Returns the default value of the field.
     * 
     * This value is used if the form is reset
     * 
     * @param string $encoding 
     * @return null|string
     * @see SetaPDF_FormFiller_Field_Interface::getDefaultValue()
     */
    public function getDefaultValue($encoding = 'UTF-8')
    {
        $dv = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'DV');
        if (!$dv) {
            return null;    
        }
        
        return SetaPDF_Core_Encoding::convertPdfString($dv->getValue(), $encoding);  
    }
    
    /**
     * Get the field value
     * 
     * @param string $encoding
     * @return string
     * @see SetaPDF_FormFiller_Field_Interface::getValue()
     */
    public function getValue($encoding = 'UTF-8')
    {
        /* We have to get the V entry from the dictionary holding the T entry, because
         * some documents have errorious /V entries in their terminal fields (the
         * entries in a Kids array)
         * 
         * This way, we make sure, that the V entry is bound to the dictionary in
         * which the name (T) is defined.
         */
        $tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
        if (!$tObject->offsetExists('V')) {
            return '';
        }
        
        $value = $tObject->offsetGet('V')->ensure()->getValue();
        
        return SetaPDF_Core_Encoding::convertPdfString($value, $encoding);
    }

	/**
     * Set the field value
     * 
     * @param string $value
     * @param string $encoding
     * @return void
     */
    public function setValue($value, $encoding = 'UTF-8')
    {
        $this->_checkPermission();
        
        $originalValue = $value;
        // Convert value to UTF-16BE
        $value = SetaPDF_Core_Encoding::convert($value, $encoding, 'UTF-16BE');
        
        $multiline = $this->isMultiline();
        if (false === $multiline) {
            $value = strtr($value, array(
                // replace linebreaks with a space
            	"\x00\x0d\x00\x0a" => "\x00\x20",
                "\x00\x0d" => "\x00\x20",
                "\x00\x0a" => "\x00\x20",
            
                // tab to space
                "\x00\x09" => "\x00\x20",
            ));
        } else {
            // normalize linebreaks
            $value = strtr($value, array(
                "\x00\x0d\x00\x0a" => "\x00\x0a",
                "\x00\x0d" => "\x00\x0a",
            
                // tab to space
                "\x00\x09" => "\x00\x20",
            ));
        }
        
        $maxLength = $this->getMaxLength();
        if ($maxLength) {
        	$value = SetaPDF_Core_Encoding::substr($value, 0, $maxLength, 'UTF-16BE');
        }
        
		$currentValue = $this->getValue('UTF-16BE');
		if ($currentValue === $value && false === $this->_fields->isForwardSetValueActive()) {
		    return;
		}

		$value = "\xFE\xFF" . $value;
		
		$tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
        $tObject->offsetSet('V', new SetaPDF_Core_Type_String($value));
        
        $this->recreateAppearance();
        
        $this->_fields->forwardSetValueToRelated($originalValue, $this);
    }
    
    /**
     * Recreate or creates the Appearance of the form field
     * 
     * @return void
     */
    public function recreateAppearance()
    {
        // Render the border and background
        $canvas = parent::_recreateAppearance();
        $value = $this->getValue('UTF-16BE');
        
        if ($value) {
            // Password-Field?
            if ($this->isPasswordField()) {
                $value = str_repeat("\x00\x2A", SetaPDF_Core_Encoding::strlen($value, 'UTF-16BE'));
            }

            $annotation = $this->getAnnotation();
            $appearanceCharacteristics = $annotation->getAppearanceCharacteristics();
            $borderStyle = $annotation->getBorderStyle();

            $borderWidth = 0;
            $_borderStyle = SetaPDF_Core_Document_Page_Annotation_BorderStyle::SOLID;

            if ($borderStyle) {
                $_borderStyle = $borderStyle->getStyle();
                $borderWidth = $borderStyle->getWidth();
            }

            if ($borderWidth == 0 && $appearanceCharacteristics && $appearanceCharacteristics->getBorderColor() !== null) {
                $borderWidth = 1;
            }

            $borderDoubled = (
                $_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::BEVELED ||
                $_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::INSET
            );
            
            $offset = max(1, $borderWidth * ($borderDoubled ? 2 : 1));
            
            $width = $annotation->getWidth();
            $height = $annotation->getHeight();
    
            // Let's get some default appearance data
            $fontName = $fontSize = $textColor = null;
            $this->getDefaultAppearanceData($fontName, $fontSize, $textColor);

            // Resolve and make sure a Font Resource entry is available
            $nDictionary = $canvas->getContainer()->getObject(true)->ensure(true)->getValue();
            $fontRelation = $this->_getFontRelation($nDictionary, $fontName);
            
            $font = $this->getAppearanceFont();
            if (null === $font)
                $font = SetaPDF_Core_Font::get($fontRelation->getValue());

            $fontBBox = $font->getFontBBox();
            $heightFactor = ($fontBBox['ury'] - $fontBBox['lly']) / 1000;
            
            $left = $borderWidth == 0
                ? 2
                : $borderWidth * ($borderDoubled ? 4 : 2);
            
            // Calculate Font Size
            if (0 == $fontSize) {
                if ($this->isMultiline()) {
                    // Prepare the lines array
                    $lines = SetaPDF_Core_Text::getLines($value);
                    
                    // Calculate the maximum size
                    $fontSize = ($height - $borderWidth * 2) 
                              / count($lines)
                              / $heightFactor;
                              
                    if ($fontSize > 4) {
                        // Maximum is set to 12 for multiline text fields
                        if ($fontSize > 12)
                            $fontSize = 12;
                        
                        /**
                         * IDEA: Is it possible to take the line height as a basis?
                         */
                            
                        $stepSize = 0.15;
                        
                        for (; $fontSize > 4; $fontSize -= $stepSize) {
                            $tmpLines = SetaPDF_Core_Text::getLines(
                                $value,
                                $width - max(1, $borderWidth) * ($borderDoubled ? 8 : 4),
                                $font,
                                $fontSize
                            );
                            
                            if ((count($tmpLines) * $fontSize * $heightFactor) < 
                                (($height - $borderWidth * 2) - $fontSize * $heightFactor)
                            ) {
                                break;
                            }
                        }
                        $lines = $tmpLines;
                    } else {
                        $fontSize = 4; 
                    }
                } else {
                    // 1.4 was resolved by simply testing...
                    $maxSize = ($height
                               - ($borderWidth > 0 && !$this->isCombField()
                                   ? $borderWidth * ($borderDoubled ? 4 : 2) 
                                   : 0
                               )) / 1.4;
                    $maxWidth = $width  
                              - ($borderWidth * ($borderDoubled ? 8 : 4))
                              - ($borderWidth == 0 ? 4 : 0);
                    $glyphWidth = $font->getGlyphsWidth($value) / 1000;
                    $fontSize = min($maxWidth / $glyphWidth, $maxSize);
                    $fontSize = round($fontSize, 4);
                }
                
                $fontSize = max($fontSize, 4);
            }
            
            $leading = $fontSize * $heightFactor;
            
            $canvas->write('/Tx BMC');
            $canvas->saveGraphicState();
            // Clip
            $canvas->path()->rect(
                $offset,
                $offset,
                $width - $offset * 2,
                $height - $offset * 2
            )->clip()->endPath();
            $canvas->write(
                ' BT /' . $fontName . sprintf(" %.4F", $fontSize) . " Tf\n" .
                $textColor . "\n"
            );
            
            if ($this->isMultiline()) {
                if ($leading > ($height - $borderWidth * ($borderDoubled ? 8 : 4))) {
                    $top = $borderWidth * ($borderDoubled ? 4 : 2);
                    $top -= $fontSize * $font->getDescent() / 1000;
                } else {
                    $top = $height;
                    $top -= max(2, $borderWidth * ($borderDoubled ? 4 : 2));
                    $top -= $leading;
                }
            } else {
                $top = $height / 2;  
                $top -= $leading / 2;
                $top -= ($fontSize * $font->getDescent() / 1000);
            }
            
            // Comb 
            $maxLength = $this->getMaxLength();
            if ($this->isCombField() && $maxLength) {
                $combWidth = ($width) / $maxLength;
                
                $left = 0; # 0.75; // Some characters are still "jumping"...
                $len = SetaPDF_Core_Encoding::strlen($value, 'UTF-16BE');
                
                // Align
                $q = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'Q');
                if ($q && ($maxLength - $len != 0)) {
                	switch ($q->ensure()->getValue()) {
                		case 1: // center
                			$steps = floor(($maxLength / 2) - ($len / 2));
                			$left += $steps * $combWidth;
                			break;
                		case 2: // right
                			$left += ($maxLength - $len) * $combWidth;
                			break;
                	}
                }
                
                $canvas->write(sprintf(" %.4F %.4F Td\n", $left, $top));
                
                $prevTmpLeft = 0;
                
                for ($i = 0; $i < $len; $i++) {
                    $char = SetaPDF_Core_Encoding::substr($value, $i, 1, 'UTF-16BE');
                    
                    $charCode = $font->getCharCode($char);
                    $tmpLeft = ($combWidth 
                                - ($font->getGlyphWidth($char) / 1000  * $fontSize))
                             / 2;
                    
                    $_tmpLeft = $tmpLeft;                     
                    if ($i > 0) {
                        $tmpLeft += ($combWidth - $prevTmpLeft);
                    }
                    
                    $canvas->write(sprintf(' %.4F %.4F Td', $tmpLeft, 0));
                    SetaPDF_Core_Type_String::writePdfString($canvas, $charCode);
                    $canvas->write('Tj');
                        
                    $prevTmpLeft = $_tmpLeft;
                }
                
                // Draw inner border
                // Color, Border Width,... are already by parent method
                $borderColor = $appearanceCharacteristics
                             ? $appearanceCharacteristics->getBorderColor()
                             : null;

                if ($borderColor && (
                    $_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::SOLID ||
                    $_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::DASHED
                )) {
                    for ($i = 1, $c = $this->getMaxLength() - 1; $i <= $c; $i++) {
                        $canvas->draw()->line(
                            $combWidth * $i,
                            $height - $borderWidth,
                            $combWidth * $i,
                            $borderWidth / 2
                        );
                    }
                }
                
            // Normal
            } else {
                // Align
                $q = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'Q');
                
                if ($this->isMultiline()) {
                    if (!isset($lines)) {
                        $lines = SetaPDF_Core_Text::getLines(
                            $value,
                            $width - max(1, $borderWidth) * ($borderDoubled ? 8 : 4),
                            $font,
                            $fontSize
                        );
                    }
                    
                    // Position
                    $canvas->write(
                        sprintf(" 0 %.4F Td", $top) .
                        sprintf(" %.4F TL", $leading)
                    );
                    
                    $lineLeft = $left;
                    $prevLineLeft = 0;
                    foreach ($lines AS $line) {
                        if ($q && $line) {
                            // Trim white spaces from the right side
                            $lineLen = SetaPDF_Core_Encoding::strlen($line, 'UTF-16BE');
                            while (1) {
                                $lastChar = SetaPDF_Core_Encoding::substr($line, $lineLen - 1, 1, 'UTF-16BE');
                                switch ($lastChar) {
                                    case "\x00\x20":
                                        $lineLen--;
                                        $line = SetaPDF_Core_Encoding::substr($line, 0, $lineLen, 'UTF-16BE');
                                        continue 2;
                                }
                                
                                break;
                            }
                            
                            $glyphWidth = $font->getGlyphsWidth($line) / 1000;
                            // calculate the total string width
                            $stringWidth = $glyphWidth * $fontSize;
                            
                            switch ($q->ensure()->getValue()) {
                                case 1: // center
                                    $lineLeft = ($width / 2) - ($stringWidth / 2);
                                    break;
                                case 2: // right
                                    $lineLeft = 
                                        $width 
                                        - $stringWidth
                                        - ($borderWidth == 0
                                            ? 2
                                            : $borderWidth * ($borderDoubled ? 4 : 2)
                                        );
                                    break;
                            }
                        }
                        
                        $charCodes = $font->getCharCodes($line);
                        $charCodeString = join('', $charCodes);
                        if (($lineLeft - $prevLineLeft) != 0)
                            $canvas->write(sprintf(" %.4F 0 Td\n", $lineLeft - $prevLineLeft));
                        $prevLineLeft = $lineLeft;
                        SetaPDF_Core_Type_String::writePdfString($canvas, $charCodeString);
                        $canvas->write(' Tj T*');
                    }
                    
                } else {
                    if ($q) {
                        $glyphWidth = $font->getGlyphsWidth($value) / 1000;
                        
                        // calculate the total string width
                        $stringWidth = $glyphWidth * $fontSize;
                        
                        /**
                         * The left offset should never be lower than the initial offset.
                         * So at the end a text, which is longer than the available space
                         * will be left aligned.
                         */
                        switch ($q->ensure()->getValue()) {
                            case 1: // center
                                $left = max($left, ($width / 2) - ($stringWidth / 2));
                                break;
                            case 2: // right
                                $left = max
                                (
                                    $left, 
                                    $width 
                                    - $stringWidth
                                    - ($borderWidth == 0
                                        ? 2
                                        : $borderWidth * ($borderDoubled ? 4 : 2)
                                    )
                                );
                                break;
                        }
                    }
    
                    $canvas->write(sprintf(" %.4F %.4F Td\n", $left, $top));
                    
                    $charCodes = $font->getCharCodes($value);
                    $charCodeString = join('', $charCodes);
                    SetaPDF_Core_Type_String::writePdfString($canvas, $charCodeString);
                    $canvas->write(" Tj\n");
                }
            }
                  
            $canvas->write(' ET');
            $canvas->restoreGraphicState();
            $canvas->write(' EMC');
        }
    }
    
    /**
     * Get the max length property if available
     * 
     * @return boolean|integer
     */
    public function getMaxLength()
    {
        $v = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'MaxLen');
        if (!$v) {
            return false;    
        }
        
        $maxLength = $v->getValue(); 
        
        return $maxLength > 0 ? $maxLength : false;
    }
    
    /**
     * Set the max length property
     * 
     * @param integer $maxLength
     * @return void
     */
    public function setMaxLength($maxLength)
    {
    	$currentMaxLength = $this->getMaxLength();
    	
        $dict = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'MaxLen');
        if (!$dict) { 
            $dict = $this->_fieldDictionary;
        }
        
        $dict->offsetSet('MaxLen', new SetaPDF_Core_Type_Numeric($maxLength));
        
        if ($maxLength < $currentMaxLength) {
        	$this->setValue($this->getValue('UTF-16BE'), 'UTF-16BE');
        }
    }
    
  /* Additional text field flags */
    
    /**
     * Check if the multiline flag is set
     * 
     * @return boolean
     */
    public function isMultiline()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::MULTILINE);
    }
    
    /**
     * Set the multiline flag
     *
     * @param boolean $multiline
     * @return void
     */
    public function setMultiline($multiline = true)
    {
    	$currentMultiline = $this->isMultiline();
    	
    	if ($currentMultiline == $multiline)
            return;
    	
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::MULTILINE, $multiline);
        $this->recreateAppearance();
    }
    
    /**
     * Check if the comb field flag is set
     * 
     * @return boolean
     */
    public function isCombField()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::COMB);
    }
    
    /**
     * Set the comb field flag
     * 
     * @param boolean $comb
     * @return void
     * @throws SetaPDF_FormFiller_Field_Exception
     */
    public function setCombField($comb = true)
    {
        if (false === $this->getMaxLength()) {
            throw new SetaPDF_FormFiller_Field_Exception(
                'Comb flag can only be set, if the field has a MaxLength defined.'
            );
        }
        
        $currentComb = $this->isCombField();
        
        if ($currentComb == $comb)
            return;
        
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::COMB, $comb);
        $this->recreateAppearance();
    }
    
    /**
     * Check if password field flag is set
     * 
     * @return boolean
     */
    public function isPasswordField()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::PASSWORD);
    }
    
    /**
     * Set the password field flag
     *
     * @param boolean $password
     * @return void
     */
    public function setPasswordField($password = true)
    {
    	$currentPasswordField = $this->isPasswordField();

    	if ($currentPasswordField == $password)
    	    return;
    	    
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::PASSWORD, $password);
        $this->recreateAppearance();
    }
    
    /**
     * Check if the "do not spell check" flag is set
     * 
     * @return boolean
     */
    public function isDoNotSpellCheckSet()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::DO_NOT_SPELL_CHECK);
    }
    
    /**
     * Set the "do not spell check" flag
     * 
     * @param boolean $doNotSpellCheck
     * @return void
     */
    public function setDoNotSpellCheck($doNotSpellCheck = true)
    {
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::DO_NOT_SPELL_CHECK, $doNotSpellCheck);
    }
    
    /**
     * Check if the "do not scroll" flag is set
     * 
     * @return boolean
     */
    public function isDoNotScrollSet()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::DO_NOT_SCROLL);
    }
    
    /**
     * Set the "do not scroll" flag
     * 
     * @param boolean $doNotScroll
     * @return void
     */
    public function setDoNotScroll($doNotScroll = true)
    {
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::DO_NOT_SCROLL, $doNotScroll);
    }
}