<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: GraphicState.php 438 2013-05-15 08:53:15Z jan.slabon $
 */

/**
 * A canvas helper class for graphicState operators
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Canvas
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Canvas_GraphicState
{
    /**
     * Stack of all active transformation matrices
     *
     * @var array
     */
    protected $_matrixStack = array();

    /**
     * Add a transformation matrix to the stack of the current graphic state
     *
     * @see PDF-Reference PDF 32000-1:2008 8.3.4 Transformation Matrices
     * @param numeric $a
     * @param numeric $b
     * @param numeric $c
     * @param numeric $d
     * @param numeric $e
     * @param numeric $f
     */
    public function addCurrentTransformationMatrix($a, $b, $c, $d, $e, $f)
    {
        $count = count($this->_matrixStack);
        $key = ($count === 0) ? 0 : $count - 1;

        $this->_matrixStack[$key][] = array($a, $b, $c, $d, $e, $f);
    }

    /**
     * Open a new graphic state and copy the entire graphic state onto the stack of the new graphic state
     *
     * @throws BadMethodCallException
     */
    public function save()
    {
        if(count($this->_matrixStack) === 28) {
            throw new BadMethodCallException('Too many graphic states open!');
        }

        $this->_matrixStack[] = array();
    }

    /**
     * Restore the last graphic state and pop all matrices of the current graphic state out of the matrix stack
     *
     * @throws BadMethodCallException
     */
    public function restore()
    {
        if(count($this->_matrixStack) === 0) {
            throw new BadMethodCallException("Graphic state is empty!");
        }

        array_pop($this->_matrixStack);
    }

    /**
     * Returns the user space coordinates of the transformation matrix
     *
     * @param numeric $x
     * @param numeric $y
     * @return array('x' => $x, 'y' => $y)
     */
    public function getUserSpaceXY($x, $y)
    {
        for ($graphicState = count($this->_matrixStack) - 1; $graphicState >= 0; $graphicState--) {
            for ($matrixKey = count($this->_matrixStack[$graphicState]) - 1; $matrixKey >= 0; $matrixKey--) {
                $matrix = $this->_matrixStack[$graphicState][$matrixKey];
                list($a, $b, $c, $d, $e, $f) = $matrix;
                $_x = $x;
                $_y = $y;
                $x = $a * $_x + $c * $_y + $e;
                $y = $b * $_x + $d * $_y + $f;
            }
        }
    
        return array('x' => $x, 'y' => $y);
    }    
}