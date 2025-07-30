<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Text.php 332 2012-11-12 16:42:55Z maximilian $
 */

/**
 * A canvas helper class for text operators
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Canvas
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Canvas_Text extends SetaPDF_Core_Canvas_StandardOperators
{
    /**#@+
     * Rendering mode
     *
     * @var integer
     */
    const RENDERING_MODE_FILL = 0;
    const RENDERING_MODE_STROKE = 1;
    const RENDERING_MODE_FILL_AND_STROKE = 2;
    const RENDERING_MODE_INVISIBLE = 3;
    const RENDERING_MODE_FILL_AND_CLIP = 4;
    const RENDERING_MODE_STROKE_AND_CLIP = 5;
    const RENDERING_MODE_FILL_STROKE_AND_CLIP = 6;
    const RENDERING_MODE_CLIP = 7;
    /**#@-*/

  /** Text State methods **/

    /**
     * Set the charspacing
     *
     * @param float $charSpacing
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setCharSpacing($charSpacing = 0.)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $charSpacing);
        $this->_canvas->write(' Tc');

        return $this;
    }

    /**
     * Set the word spacing
     *
     * @param float $wordSpacing
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setWordSpacing($wordSpacing = 0.)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $wordSpacing);
        $this->_canvas->write(' Tw');

        return $this;
    }

    /**
     * Set the horizontal scaling
     *
     * @param float $scaling
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setScaling($scaling = 100.)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $scaling);
        $this->_canvas->write(' Tz');

        return $this;
    }

    /**
     * Set the leading
     *
     * @param float $leading
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setLeading($leading = 0.)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $leading);
        $this->_canvas->write(' TL');

        return $this;
    }

    /**
     * Set the font
     *
     * @param string $name
     * @param float $size
     * @throws InvalidArgumentException
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setFont($name, $size = 12.)
    {
        if ($name instanceof SetaPDF_Core_Font)
            $name = $this->_canvas->addResource($name);

        $fonts = $this->_canvas->getResources(true, false, 'Font');
        if (false === $fonts || !$fonts->offsetExists($name)) {
            throw new InvalidArgumentException('Unknown font: ' . $name);
        }

        SetaPDF_Core_Type_Name::writePdfString($this->_canvas, $name, true);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $size);
        $this->_canvas->write(' Tf');

        return $this;
    }

    /**
     * Set the rendering mode
     *
     * @param integer $renderingMode
     * @see PDF reference 32000-1:2008 9.3.6 Text Rendering Mode
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setRenderingMode($renderingMode = 0)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $renderingMode);
        $this->_canvas->write(' Tr');

        return $this;
    }

    /**
     * Set text rise
     *
     * @param float $textRise
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setTextRise($textRise = 0.)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $textRise);
        $this->_canvas->write(' Ts');

        return $this;
    }

  /** Text Object operator methods **/

    /**
     * Begin a text object
     *
     * @return SetaPDF_Core_Canvas_Text
     */
    public function beginText()
    {
        $this->_canvas->write(' BT');

        return $this;
    }

    /**
     * End a text object
     *
     * @return SetaPDF_Core_Canvas_Text
     */
    public function endText()
    {
        $this->_canvas->write(' ET');

        return $this;
    }

  /** Text-positioning operator methods **/

    /**
     * Move to the next line
     *
     * @param float $x
     * @param float $y
     * @param boolean $setLeading
     * @return SetaPDF_Core_Canvas_Text
     */
    public function moveToNextLine($x, $y, $setLeading = false)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $x);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $y);
        $this->_canvas->write($setLeading ? ' TD' : ' Td');

        return $this;
    }

    /**
     * Move to the start of the next line
     *
     * @return SetaPDF_Core_Canvas_Text
     */
    public function moveToStartOfNextLine()
    {
        $this->_canvas->write(' T*');

        return $this;
    }

    /**
     * Set the text matrix
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @param float $d
     * @param float $e
     * @param float $f
     * @return SetaPDF_Core_Canvas_Text
     */
    public function setTextMatrix($a, $b, $c, $d, $e, $f)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $a);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $b);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $c);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $d);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $e);
        SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $f);

        $this->_canvas->write(' Tm');

        return $this;
    }

  /** Text-Showing operator methods **/

    /**
     * Show text
     *
     * @param string $text
     * @return SetaPDF_Core_Canvas_Text
     */
    public function showText($text)
    {
        SetaPDF_Core_Type_String::writePdfString($this->_canvas, $text);
        $this->_canvas->write(' Tj');

        return $this;
    }

    /**
     * Move to the next line and show text
     *
     * @param string $text
     * @param float $wordSpacing
     * @param float $charSpacing
     * @return SetaPDF_Core_Canvas_Text
     */
    public function moveToNextLineAndShowText($text, $wordSpacing = null, $charSpacing = null)
    {
        SetaPDF_Core_Type_String::writePdfString($this->_canvas, $text);
        if ($wordSpacing !== null && $charSpacing !== null) {
            SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $wordSpacing);
            SetaPDF_Core_Type_Numeric::writePdfString($this->_canvas, $charSpacing);
            $this->_canvas->write(' "');
        } else {
            $this->_canvas->write(" '");
        }

        return $this;
    }

    /**
     * Shows text strings
     *
     * @param array|string $textStrings
     * @return SetaPDF_Core_Canvas_Text
     */
    public function showTextStrings($textStrings)
    {
        if (!is_array($textStrings))
            $textStrings = array($textStrings);

        SetaPDF_Core_Type_Array::writePdfString($this->_canvas, $textStrings);
        $this->_canvas->write(' TJ');

        return $this;
    }
}