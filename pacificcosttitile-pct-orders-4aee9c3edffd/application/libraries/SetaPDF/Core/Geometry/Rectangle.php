<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Geometry
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Point.php 324 2012-11-08 10:28:41Z jan $
 */

/**
 * Class representing a reclangle
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Geometry
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Geometry_Rectangle
{
    /**
     * ll => lower left
     * ur => upper right
     *
     * @var float
     */
    private $_llX, $_llY, $_urX, $_urY;

    /**
     * The constructor
     *
     * There three ways to create a rectangle:
     *
     * <code>
     * - 2 params   SetaPDF_Core_Geometry_Point $a  point1
     *              SetaPDF_Core_Geometry_Point $b  point2
     *
     * - 3 params   SetaPDF_Core_Geometry_Point $a pointLL
     *              int|float $b width
     *              int|float $c height
     *
     * - 4 params   int|float $a x of point1
     *              int|float $b y of point1
     *              int|float $c x of point2
     *              int|float $d y of point2
     * </code>
     * 
     * @param int|float|SetaPDF_Core_Geometry_Point $a  point1 OR pointLL OR x of point1
     * @param int|float|SetaPDF_Core_Geometry_Point $b  point2 OR width OR y of point1
     * @param int|float $c  height OR x of point2
     * @param int|float $d  none OR y of point2
     * @throws InvalidArgumentException
     */
    public function __construct($a, $b, $c = null, $d = null)
    {
        if ($a instanceof SetaPDF_Core_Geometry_Point && $b instanceof SetaPDF_Core_Geometry_Point) {
            $point1 = $a;
            $point2 = $b;

            $x1 = $point1->getX();
            $y1 = $point1->getY();
            $x2 = $point2->getX();
            $y2 = $point2->getY();
        } else if ($a instanceof SetaPDF_Core_Geometry_Point) {
            $ll = $a;
            $width = $b;
            $height = $c;

            if (!is_numeric($width) || !is_numeric($height) || $width <= 0 || $height <= 0) {
                throw new InvalidArgumentException('width and height need to be positive numerics');
            }

            $x1 = $ll->getX();
            $y1 = $ll->getY();
            $x2 = $x1 + $width;
            $y2 = $y1 + $height;
        } else {
            $x1 = $a;
            $y1 = $b;
            $x2 = $c;
            $y2 = $d;
        }

        $this->init($x1, $y1, $x2, $y2);
    }

    /**
     * Reset the complete rectangle by using two oposite points of the new rectangle
     *
     * @param int|float $x1
     * @param int|float $y1
     * @param int|float $x2
     * @param int|float $y2
     * @throws InvalidArgumentException
     */
    public function init($x1, $y1, $x2, $y2)
    {
        if (!is_numeric($x1) || !is_numeric($y1) && !is_numeric($x2) && !is_numeric($y2)) {
            throw new InvalidArgumentException('the params need to be numeric');
        }

        if (abs($x1 - $x2) <= SetaPDF_Core::FLOAT_COMPARSION_PRECISION || abs($y1 - $y2) <= SetaPDF_Core::FLOAT_COMPARSION_PRECISION) {
            throw new InvalidArgumentException('the size of the rectangle can\'t be zero');
        }

        if ($x2 < $x1) {
            $t = $x1;
            $x1 = $x2;
            $x2 = $t;
            unset($t);
        }

        if ($y2 < $y1) {
            $t = $y1;
            $y1 = $y2;
            $y2 = $t;
            unset($t);
        }

        $this->_llX = (float)$x1;
        $this->_llY = (float)$y1;
        $this->_urX = (float)$x2;
        $this->_urY = (float)$y2;
    }

    /**
     * Set the height of the rectangle
     * the lower left point couldn't be moved by this method
     *
     * @param int|float $height
     * @throws InvalidArgumentException
     */
    public function setHeight($height)
    {
        if (!is_numeric($height) || $height <= 0) {
            throw new InvalidArgumentException('height need to be positive numeric');
        }

        $this->_urY = (float)$this->_llY + $height;
    }

    /**
     * Set the width of the rectangle
     * the lower left point couldn't be moved by this method
     *
     * @param int|float $width
     * @throws InvalidArgumentException
     */
    public function setWidth($width)
    {
        if (!is_numeric($width) || $width <= 0) {
            throw new InvalidArgumentException('width need to be positive numeric');
        }

        $this->_urX = (float)$this->_llX + $width;
    }

    /**
     * Set the width and the height of the rectangle
     * the lower left point couldn't be moved by this method
     *
     * @param int|float $width
     * @param int|float $height
     * @throws InvalidArgumentException
     */
    public function setDimensions($width, $height)
    {
        if (!is_numeric($width) || !is_numeric($height) || $width <= 0 || $height <= 0) {
            throw new InvalidArgumentException('width and height need to be positive numerics');
        }

        $this->_urX = (float)$this->_llX + $width;
        $this->_urY = (float)$this->_llY + $height;
    }

    /**
     * Set the lower left point of the rectangle
     *
     * if you don't move this point over the x of the lower right or the y of the upper left this point stay the lower left
     * if you move this point over only one of them this point will replace them and the other point will be lower left
     * if you move this point over both this point will be the new upper right and upper right the new lower left
     *
     * @param int|float|SetaPDF_Core_Geometry_Point $a
     * @param int|float $b
     */
    public function setLl($a, $b = null)
    {
        if ($a instanceof SetaPDF_Core_Geometry_Point) {
            $x1 = $a->getX();
            $y1 = $a->getY();
        } else {
            $x1 = $a;
            $y1 = $b;
        }

        $x2 = $this->_urX;
        $y2 = $this->_urY;

        $this->init($x1, $y1, $x2, $y2);
    }

    /**
     * Set the lower right point of the rectangle
     *
     * @see setLl
     * @param int|float|SetaPDF_Core_Geometry_Point $a
     * @param int|float $b
     */
    public function setLr($a, $b = null)
    {
        if ($a instanceof SetaPDF_Core_Geometry_Point) {
            $x2 = $a->getX();
            $y1 = $a->getY();
        } else {
            $x2 = $a;
            $y1 = $b;
        }

        $x1 = $this->_llX;
        $y2 = $this->_urY;

        $this->init($x1, $y1, $x2, $y2);
    }

    /**
     * Set the upper left point of the rectangle
     *
     * @see setLl
     * @param int|float|SetaPDF_Core_Geometry_Point $a
     * @param int|float $b
     */
    public function setUl($a, $b = null)
    {
        if ($a instanceof SetaPDF_Core_Geometry_Point) {
            $x1 = $a->getX();
            $y2 = $a->getY();
        } else {
            $x1 = $a;
            $y2 = $b;
        }

        $y1 = $this->_llY;
        $x2 = $this->_urX;

        $this->init($x1, $y1, $x2, $y2);
    }

    /**
     * Set the upper right point of the rectangle
     *
     * @see setLl
     * @param int|float|SetaPDF_Core_Geometry_Point $a
     * @param int|float $b
     */
    public function setUr($a, $b = null)
    {
        if ($a instanceof SetaPDF_Core_Geometry_Point) {
            $x2 = $a->getX();
            $y2 = $a->getY();
        } else {
            $x2 = $a;
            $y2 = $b;
        }

        $x1 = $this->_llX;
        $y1 = $this->_llY;

        $this->init($x1, $y1, $x2, $y2);
    }

    /**
     * Returns the lower left point of the rectangle
     * 
     * Note: changing the returned point object don't changing the rectangle
     *
     * @return SetaPDF_Core_Geometry_Point
     */
    public function getLl()
    {
        return new SetaPDF_Core_Geometry_Point($this->_llX, $this->_llY);
    }

    /**
     * Returns the lower right point of the rectangle
     * 
     * Note: changing the returned point object don't changing the rectangle
     *
     * @return SetaPDF_Core_Geometry_Point
     */
    public function getLr()
    {
        return new SetaPDF_Core_Geometry_Point($this->_urX, $this->_llY);
    }

    /**
     * Returns the upper left point of the rectangle
     * 
     * Note: changing the returned point object don't changing the rectangle
     *
     * @return SetaPDF_Core_Geometry_Point
     */
    public function getUl()
    {
        return new SetaPDF_Core_Geometry_Point($this->_llX, $this->_urY);
    }

    /**
     * Returns the upper right point of the rectangle
     * 
     * Note: changing the returned point object don't changing the rectangle
     *
     * @return SetaPDF_Core_Geometry_Point
     */
    public function getUr()
    {
        return new SetaPDF_Core_Geometry_Point($this->_urX, $this->_urY);
    }

    /**
     * Returns the actual width of the rectangle
     *
     * @return float
     */
    public function getWidth()
    {
        return ($this->_urX - $this->_llX);
    }

    /**
     * Returns the actual height of the rectangle
     *
     * @return float
     */
    public function getHeight()
    {
        return ($this->_urY - $this->_llY);
    }

    /**
     * Returns the width and height of the rectangle
     * 
     * @return array
     */
    public function getDimensions()
    {
        return array('width' => $this->getWidth(), 'height' => $this->getHeight());
    }

    /**
     * Checks whether a point is inside or on the border of this rectangle
     *
     * @param int|float $x
     * @param int|float $y
     * @param boolean $ignoreEqual If the point lays on the border and this is true false will returned
     * @return boolean
     */
    private function _pointInside($x, $y, $ignoreEqual = false)
    {
        if ($ignoreEqual) {
            return (
                $x > $this->_llX && $x < $this->_urX
                    && $y > $this->_llY && $y < $this->_urY
            );
        }
        
        return (
            $x >= $this->_llX && $x <= $this->_urX
                && $y >= $this->_llY && $y <= $this->_urY
        );
    }

    /**
     * return all lines of this rectangle
     *
     * @return array
     */
    private function _getLines()
    {
        $ll = $this->getLl();
        $lr = $this->getLr();
        $ul = $this->getUl();
        $ur = $this->getUr();

        return array(
            array($ll->getX(), $ll->getY(), $lr->getX(), $lr->getY()),
            array($lr->getX(), $lr->getY(), $ur->getX(), $ur->getY()),
            array($ur->getX(), $ur->getY(), $ul->getX(), $ul->getY()),
            array($ul->getX(), $ul->getY(), $ll->getX(), $ll->getY())
        );
    }

    /**
     * checks whether the two points lay around the rectangle
     *
     * @param $l1x1
     * @param $l1y1
     * @param $l1x2
     * @param $l1y2
     * @param boolean $ignoreEqual
     * @return boolean
     */
    private function _lineIntersect($l1x1, $l1y1, $l1x2, $l1y2, $ignoreEqual = false)
    {
        $linesOfThisRect = $this->_getLines();

        $result = false;

        foreach ($linesOfThisRect as $l2) {
            $l2x1 = $l2[0];
            $l2y1 = $l2[1];
            $l2x2 = $l2[2];
            $l2y2 = $l2[3];

            $A1 = $l1y2 - $l1y1;
            $A2 = $l2y2 - $l2y1;
            $B1 = $l1x1 - $l1x2;
            $B2 = $l2x1 - $l2x2;
            $C1 = $A1 * $l1x1 + $B1 * $l1y1;
            $C2 = $A2 * $l2x1 + $B2 * $l2y1;

            $det = $A1 * $B2 - $A2 * $B1;
            if ($det == 0) {
                //lines are parallel
                continue;
            }

            $x = ($B2 * $C1 - $B1 * $C2) / $det;
            $y = ($A1 * $C2 - $A2 * $C1) / $det;
            if ($ignoreEqual) {
                $result = (
                    $x >= $l2x1 && $x <= $l2x2 && $y > $l2y1 && $y < $l2y2
                        || $x > $l2x1 && $x < $l2x2 && $y >= $l2y1 && $y <= $l2y2
                );
            } else {
                $result = ($x >= $l2x1 && $x <= $l2x2 && $y >= $l2y1 && $y <= $l2y2);
            }

            if ($result) {
                break;
            }
        }
        return $result;
    }

    /**
     * Checks wether this rectangle contains another geometric object
     *   
     * @param SetaPDF_Core_Geometry_Point|SetaPDF_Core_Geometry_Rectangle $geometry
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function contains($geometry)
    {
        if (!is_object($geometry)) {
            throw new InvalidArgumentException('Invalid param');
        }

        $result = false;

        switch (get_class($geometry)) {
            case 'SetaPDF_Core_Geometry_Point':
                $x = $geometry->getX();
                $y = $geometry->getY();

                $result = $this->_pointInside($x, $y);
                break;

            case 'SetaPDF_Core_Geometry_Rectangle':
                $ll = $geometry->getLL();
                $ur = $geometry->getUR();

                $x1 = $ll->getX();
                $y1 = $ll->getY();
                $x2 = $ur->getX();
                $y2 = $ur->getY();

                $result = ($this->_pointInside($x1, $y1) && $this->_pointInside($x2, $y2));
                break;

            default:
                throw new InvalidArgumentException('Invalid param');
        }

        return $result;
    }

    /**
     * Checks whether the geometry shape intersect this rectangle
     *
     * @param $geometry
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function intersect($geometry)
    {
        $result = false;

        switch (1) {
            case $geometry instanceof SetaPDF_Core_Geometry_Rectangle:
                //checks whether this rectangle is inside of the other rectangle
                $result = ($geometry->contains($this) || $this->contains($geometry));
                if ($result) {
                    break;
                }

                $ll = $geometry->getLl();
                $lr = $geometry->getLr();
                $ul = $geometry->getUl();
                $ur = $geometry->getUr();

                //checks whether one of the corners of the other rectangle is inside of this one
                $result = (
                    $this->_pointInside($ll->getX(), $ll->getY(), true) || $this->_pointInside($lr->getX(), $lr->getY(), true)
                        || $this->_pointInside($ul->getX(), $ul->getY(), true) || $this->_pointInside($ur->getX(), $ur->getY(), true)
                );

                if ($result) {
                    break;
                }

                $linesOfOtherRect = $geometry->_getLines();

                foreach ($linesOfOtherRect as $lineOfOtherRect) {
                    $result = $this->_lineIntersect(
                        $lineOfOtherRect[0],
                        $lineOfOtherRect[1],
                        $lineOfOtherRect[2],
                        $lineOfOtherRect[3],
                        true
                    );

                    if ($result) {
                        break;
                    }
                }

                break;
                
            default:
                throw new InvalidArgumentException('Invalid param');
        }

        return $result;
    }
}