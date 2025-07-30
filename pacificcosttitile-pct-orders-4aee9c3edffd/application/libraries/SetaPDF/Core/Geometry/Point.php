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
 * Class representing a point
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Geometry
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Geometry_Point
{
    /**
     * The x coordinate value
     * 
     * @var float
     */
    protected $_x = 0.0;
    
    /**
     * The y coordinate value
     * 
     * @var float
     */
    protected $_y = 0.0;
    
    /**
     * The consturctor
     * 
     * @param float $x
     * @param float $y
     */
    public function __construct($x, $y)
    {
        $this->_x = (float)$x;
        $this->_y = (float)$y;
    }
    
    /**
     * Get the x coordinate value
     * 
     * @return float
     */
    public function getX()
    {
        return $this->_x;
    }
    
    /**
     * Set the x coordinate value
     *
     * @param float $x
     */
    public function setX($x)
    {
        $this->_x = (float)$x;
    }
    
    /**
     * Get the y coordinate value
     *
     * @return float
     */
    public function getY()
    {
        return $this->_y;
    }
    
    /**
     * Set the y coordinate value
     *
     * @param float $y
     */
    public function setY($y)
    {
    	$this->_y = (float)$y;
    }
    
    /**
     * Compares a point agains this one
     * 
     * @param SetaPDF_Core_Geometry_Point $point
     * @return boolean
     */
    public function isEqual(SetaPDF_Core_Geometry_Point $point)
    {
    	return (
    	    (abs($this->_x - $point->getX()) < SetaPDF_Core::FLOAT_COMPARSION_PRECISION) &&
			(abs($this->_y - $point->getY()) < SetaPDF_Core::FLOAT_COMPARSION_PRECISION)
    	);
    }
    
    
    /* TODO: implement further methods:
     * 
    public function inRect(SetaPDF_Core_Geometry_Rect $rect)
    {
    
    }
    
    */
}