<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * A canvas helper class for marked content operators
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Canvas
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Canvas_MarkedContent 
{
    /**
     * The origin canvas object
     *
     * @var SetaPDF_Core_Canvas
     */
    protected $_canvas;
    
    /**
     * The constructor
     *
     * @param SetaPDF_Core_Canvas $canvas
     */
    public function __construct(SetaPDF_Core_Canvas $canvas)
    {
        $this->_canvas = $canvas;
    }
    
    /**
     * Release cycled references
     */
    public function cleanUp()
    {
        $this->_canvas = null;
    }
    
    /**
     * Begin a marked content sequence
     * 
     * @param string $tag
     * @param SetaPDF_Core_Resource $properties
     * @return SetaPDF_Core_Canvas_MarkedContent
     */
    public function begin($tag, SetaPDF_Core_Resource $properties = null)
    {
        SetaPDF_Core_Type_Name::writePdfString($this->_canvas, $tag);
        if (null === $properties) {
            $this->_canvas->write(" BMC\n");
        } else {
            $name = $this->_canvas->addResource($properties);
            SetaPDF_Core_Type_Name::writePdfString($this->_canvas, $name);
            $this->_canvas->write(" BDC\n");
        }
        
        return $this;
    }
    
    /**
     * End a marked content stream
     * 
     * @return SetaPDF_Core_Canvas_MarkedContent
     */
    public function end()
    {
        $this->_canvas->write("\nEMC\n");
        
        return $this;
    }
}