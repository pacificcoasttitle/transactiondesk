<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Canvas.php 493 2013-06-03 15:02:17Z jan.slabon $
 */

/**
 * A class representing a Canvas
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Canvas
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Canvas
    implements SetaPDF_Core_Canvas_StreamProxyInterface
{
    /**
     * The main dictionary of the canvas
     *
     * @var SetaPDF_Core_Canvas_ContainerInterface
     */
    protected $_canvasContainer;

    /**
     * The writer
     *
     * @var SetaPDF_Core_Canvas_StreamProxyInterface
     */
    protected $_streamProxy;

    /**
     * Draw helper instance
     *
     * @var SetaPDF_Core_Canvas_Draw
     */
    protected $_draw;

    /**
     * Path helper instance
     *
     * @var SetaPDF_Core_Canvas_Path
     */
    protected $_path;

    /**
     * Text helper instance
     *
     * @var SetaPDF_Core_Canvas_Text
     */
    protected $_text;

    /**
     * A helper instance for marked content
     * 
     * @var SetaPDF_Core_Canvas_MarkedContent
     */
    protected $_markedContent;

    /**
     * A graphic state instance
     *
     * @var SetaPDF_Core_Canvas_GraphicState
     */
    protected $_graphicState;
    
    /**
     * Cached written content
     * 
     * @var string
     */
    protected $_cache = '';
    
    /**
     * Should the output be cached or not
     * 
     * @var boolean
     */
    protected $_cacheOutput = false;
    
    /**
     * The constructor
     *
     * @param SetaPDF_Core_Canvas_ContainerInterface $canvasContainer
     */
    public function __construct(SetaPDF_Core_Canvas_ContainerInterface $canvasContainer)
    {
        $this->_canvasContainer = $canvasContainer;
        $this->_streamProxy = $canvasContainer->getStreamProxy();
    }

    /**
     * Release objects to free memory and cycled references
     *
     * After calling this method the instance of this object is unuseable!
     *
     * @return void
     */
    public function cleanUp()
    {
        $this->_streamProxy = null;
        $this->_canvasContainer = null;

        if (null !== $this->_draw) {
            $this->_draw->cleanUp();
            $this->_draw = null;
        }

        if (null !== $this->_path) {
            $this->_path->cleanUp();
            $this->_path = null;
        }

        if (null !== $this->_text) {
            $this->_text->cleanUp();
            $this->_text = null;
        }
        
        if (null !== $this->_markedContent) {
            $this->_markedContent->cleanUp();
            $this->_markedContent = null;
        }
    }

    /**
     * Get the draw helper
     *
     * @return SetaPDF_Core_Canvas_Draw
     */
    public function draw()
    {
        if (null === $this->_draw) {
            $this->_draw = new SetaPDF_Core_Canvas_Draw($this);
        }

        return $this->_draw;
    }

    /**
     * Get the path helper
     *
     * @return SetaPDF_Core_Canvas_Path
     */
    public function path()
    {
        if (null === $this->_path) {
            $this->_path = new SetaPDF_Core_Canvas_Path($this);
        }

        return $this->_path;
    }

    /**
     * Get the text helper
     *
     * @return SetaPDF_Core_Canvas_Text
     */
    public function text()
    {
        if (null === $this->_text) {
            $this->_text = new SetaPDF_Core_Canvas_Text($this);
        }

        return $this->_text;
    }
    
    /**
     * Get the marked content helper
     * 
     * @return SetaPDF_Core_Canvas_MarkedContent
     */
    public function markedContent()
    {
        if (null === $this->_markedContent) {
            $this->_markedContent = new SetaPDF_Core_Canvas_MarkedContent($this);
        }
    
        return $this->_markedContent;
    }

    /**
     * Return the graphic state object if no graphic state is defined an new instance will be initialized
     *
     * @return SetaPDF_Core_Canvas_GraphicState
     */
    public function graphicState()
    {
        if (null === $this->_graphicState) {
            $this->_graphicState = new SetaPDF_Core_Canvas_GraphicState();
        }

        return $this->_graphicState;
    }
    
    /**
     * Get the height of the canvas
     *
     * @return float
     */
    public function getHeight()
    {
        return $this->_canvasContainer->getHeight();
    }

    /**
     * Get the width of the canvas
     *
     * @return float
     */
    public function getWidth()
    {
        return $this->_canvasContainer->getWidth();
    }

    /**
     * Clears the complete canvas content
     */
    public function clear()
    {
        $this->_streamProxy->clear();
    }

    /**
     * Get the whole byte stream of the canvas
     *
     * @see SetaPDF_Core_Canvas_StreamProxyInterface::getStream()
     * @return string
     */
    public function getStream()
    {
        return $this->_streamProxy->getStream();
    }

    /**
     * Writes bytes to the canvas content stream
     *
     * @param string $bytes
     * @see SetaPDF_Core_WriteInterface::write()
     */
    public function write($bytes)
    {
        $this->_streamProxy->write($bytes);
        
        if (true === $this->_cacheOutput) {
            $this->_cache .= $bytes;
        }
    }

    /**
     * Get the stream proxy
     *
     * @return SetaPDF_Core_Canvas_StreamProxyInterface
     */
    public function getStreamProxy()
    {
        return $this->_streamProxy;
    }

    /**
     * Get the container of the canvas (origin object)
     *
     * @return SetaPDF_Core_Canvas_ContainerInterface
     */
    public function getContainer()
    {
        return $this->_canvasContainer;
    }

    /**
     * Start caching
     *
     * The output of write() will be cached.
     *
     * This will also clear the cache.
     */
    public function startCache()
    {
        $this->_cacheOutput = true;
        $this->_cache = '';
    }

    /**
     * Stop caching
     *
     * The output of write() won't be longer cached.
     *
     * This will also clear the cache.
     */
    public function stopCache()
    {
        $this->_cacheOutput = false;
        $this->_cache = '';
    }

    /**
     * Returns the cache
     *
     * @return string
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * Returns the resources dictionary or an entry of it
     *
     * If no resource dictionary existis it is possible to automatically
     * create it and/or the desired entry.
     *
     * @param boolean $inherited Check for a resources dictionary in parent nodes
     * @param boolean $create Create dictionary/ies if they do not exists
     * @param string $entryKey The entries key (Font, XObject,...)
     * @return bool|SetaPDF_Core_Type_Abstract
     */
    public function getResources($inherited = true, $create = false, $entryKey = null)
    {
        $mainDict = $this->_canvasContainer->getObject(true)->ensure(true);
        if ($mainDict instanceof SetaPDF_Core_Type_Stream) {
            $mainDict = $mainDict->getValue();
        }

        $dict = $mainDict;

        while (
            false === ($resourcesExists = $dict->offsetExists('Resources'))
            && true === $inherited
        ) {
            if ($dict->offsetExists('Parent')) {
                $dict = $dict->getValue('Parent')->ensure(true);
            } else {
                break;
            }
        }

        if (false === $resourcesExists) {
            if (false === $create) {
                return false;
            }

            $dict = $mainDict;
            $dict->offsetSet('Resources', new SetaPDF_Core_Type_Dictionary(array(
                new SetaPDF_Core_Type_Dictionary_Entry(
                    new SetaPDF_Core_Type_Name(SetaPDF_Core_Resource::TYPE_PROC_SET, true),
                    new SetaPDF_Core_Type_Array(array(
                        new SetaPDF_Core_Type_Name('PDF', true),
                        new SetaPDF_Core_Type_Name('Text', true),
                        new SetaPDF_Core_Type_Name('ImageB', true),
                        new SetaPDF_Core_Type_Name('ImageC', true),
                        new SetaPDF_Core_Type_Name('ImageI', true)
                    ))
                )
            )));
        }

        $resources = $dict->offsetGet('Resources')->ensure(true);

        // Get all resources
        if (null === $entryKey) {
            return $resources;
        }

        if (!$resources->offsetExists($entryKey)) {
            if (false === $create) {
                return false;
            }

            $resources->offsetSet($entryKey, new SetaPDF_Core_Type_Dictionary());
        }

        return $resources->offsetGet($entryKey)->ensure();
    }

    /**
     * Add a resource to the pages/xobjects resources dictionary
     *
     * @param string|SetaPDF_Core_Resource $type The resource type (Font, XObject, ExtGState,...) or an implementation of SetaPDF_Core_Resource
     * @param SetaPDF_Core_Resource|SetaPDF_Core_Type_IndirectObjectInterface $object
     * @param SetaPDF_Core_Document $document
     * @return string
     * @throws InvalidArgumentException
     */
    public function addResource($type, $object = null, SetaPDF_Core_Document $document = null)
    {
        if ($type instanceof SetaPDF_Core_Resource) {
            $object = $type->getIndirectObject();
            $type = $type->getResourceType();
        }
        
        if ($object instanceof SetaPDF_Core_Resource)
            $object = $object->getIndirectObject($document);

        if (!($object instanceof SetaPDF_Core_Type_IndirectObjectInterface)) {
            throw new InvalidArgumentException('$object has to be an instance of SetaPDF_Core_Type_IndirectObjectInterface or SetaPDF_Core_Resource');
        }

        $resources = $this->getResources(true, true, $type);
        
        foreach ($resources AS $name => $resourceValue) {
            if ($resourceValue instanceof SetaPDF_Core_Type_IndirectObjectInterface && 
                $resourceValue->getObjectIdent() === $object->getObjectIdent()
            ) {
                return $name;
            }
        }

        switch ($type) {
            case SetaPDF_Core_Resource::TYPE_FONT:
                $prefix = 'F';
                break;
            case SetaPDF_Core_Resource::TYPE_X_OBJECT:
                $prefix = 'I';
                break;
            case SetaPDF_Core_Resource::TYPE_EXT_G_STATE:
                $prefix = 'GS';
                break;
            case SetaPDF_Core_Resource::TYPE_COLOR_SPACE:
                $prefix = 'CS';
                break;
            case SetaPDF_Core_Resource::TYPE_PATTERN:
                $prefix = 'P';
                break;
            case SetaPDF_Core_Resource::TYPE_SHADING:
                $prefix = 'SH';
                break;
            case SetaPDF_Core_Resource::TYPE_PROPERTIES:
                $prefix = 'PR';
                break;
            case SetaPDF_Core_Resource::TYPE_PROC_SET:
                throw new InvalidArgumentException('Invalid resource type (' . $type . ')');
            default:
                $prefix = 'X';
        }

        $i = 0;
        while ($resources->offsetExists(($name = $prefix . ++$i))) ;

        $resources->offsetSet($name, $object);

        return $name;
    }

    /**
     * Set a resource for the canvas
     *
     * @param string $type
     * @param string $name
     * @param SetaPDF_Core_Resource|SetaPDF_Core_Type_IndirectObjectInterface $object
     * @param SetaPDF_Core_Document $document
     * @throws InvalidArgumentException
     * @return string
     */
    public function setResource($type, $name, $object, SetaPDF_Core_Document $document = null)
    {
        if ($object instanceof SetaPDF_Core_Resource)
            $object = $object->getIndirectObject($document);

        if (!($object instanceof SetaPDF_Core_Type_IndirectObjectInterface)) {
            throw new InvalidArgumentException('$object has to be an instance of SetaPDF_Core_Type_IndirectObjectInterface or SetaPDF_Core_Resource');
        }

        $resources = $this->getResources(true, true, $type);
        $resources->offsetSet($name, $object);

        return $name;
    }

  /** Setting Colors **/

    /**
     * Set the color
     *
     * @param SetaPDF_Core_DataStructure_Color|array $color
     * @param boolean $stroking
     * @return SetaPDF_Core_Canvas
     */
    public function setColor($color, $stroking = true)
    {
        if ($color instanceof SetaPDF_Core_DataStructure_Color) {
            $color->draw($this, $stroking);
        } else {
            SetaPDF_Core_DataStructure_Color::writePdfStringByComponents($this, $color, $stroking);
        }

        return $this;
    }

    /**
     * Set the stroking color
     *
     * @param SetaPDF_Core_DataStructure_Color|array $color
     * @return SetaPDF_Core_Canvas
     */
    public function setStrokingColor($color)
    {
        return $this->setColor($color, true);
    }

    /**
     * Set the non-stroking color
     *
     * @param SetaPDF_Core_DataStructure_Color|array $color
     * @return SetaPDF_Core_Canvas
     */
    public function setNonStrokingColor($color)
    {
        return $this->setColor($color, false);
    }

    /**
     * Set the current color space
     *
     * @param SetaPDF_Core_ColorSpace|SetaPDF_Core_Type_Name|string $colorSpace
     * @param bool $stroking
     * @return SetaPDF_Core_Canvas
     */
    public function setColorSpace($colorSpace, $stroking = true)
    {
        if (!$colorSpace instanceof SetaPDF_Core_ColorSpace) {
            if (!$colorSpace instanceof SetaPDF_Core_Type_Name)
                $colorSpace = new SetaPDF_Core_Type_Name($colorSpace);

            $colorSpace = SetaPDF_Core_ColorSpace::createByDefinition($colorSpace);
        }

        if ($colorSpace instanceof SetaPDF_Core_Resource) {
            $value = $this->addResource(SetaPDF_Core_Resource::TYPE_COLOR_SPACE, $colorSpace->getIndirectObject());
        } else {
            $value = $colorSpace->getPdfValue()->getValue();
        }

        SetaPDF_Core_Type_Name::writePdfString($this, $value);
        $this->write($stroking ? ' CS' : ' cs');

        return $this;
    }

    /**
     * Set the stroking color space
     *
     * @param SetaPDF_Core_ColorSpace|SetaPDF_Core_Type_Name|string $colorSpace
     * @return SetaPDF_Core_Canvas
     */
    public function setStrokingColorSpace($colorSpace)
    {
        return $this->setColorSpace($colorSpace, true);
    }

    /**
     * Set the non-stroking color space
     *
     * @param SetaPDF_Core_ColorSpace|SetaPDF_Core_Type_Name|string $colorSpace
     * @return SetaPDF_Core_Canvas
     */
    public function setNonStrokingColorSpace($colorSpace)
    {
        return $this->setColorSpace($colorSpace, false);
    }

  /** Graphic state **/

    /**
     * Set a named graphic state
     *
     * @param SetaPDF_Core_Resource_ExtGState $graphicState
     * @return SetaPDF_Core_Canvas
     * @throws InvalidArgumentException
     */
    public function setGraphicState(SetaPDF_Core_Resource_ExtGState $graphicState)
    {
        $name = $this->addResource($graphicState);
        SetaPDF_Core_Type_Name::writePdfString($this, $name, true);
        $this->write(' gs');

        return $this;
    }

    /**
     * Open a new graphic state and copy the entire graphic state onto the stack of the new graphic state.
     *
     * @return SetaPDF_Core_Canvas
     */
    public function saveGraphicState()
    {
        $this->write("\nq");

        $this->graphicState()->save();

        return $this;
    }

    /**
     * Restore the last graphic state and pop all matrices of the current graphic state out of the matrix stack.
     *
     * @return SetaPDF_Core_Canvas
     */
    public function restoreGraphicState()
    {
        $this->write("\nQ");

        $this->graphicState()->restore();

        return $this;
    }

    /**
     * Returns the user space coordinates of the transformation matrix.
     *
     * @param int $x x-coordinate
     * @param int $y y-coordinate
     * @return array ('x' => $x, 'y' => $y)
     */
    public function getUserSpaceXY($x, $y)
    {
        return $this->graphicState()->getUserSpaceXY($x, $y);
    }

    /**
     * Add a transformation matrix to the matrix stack of the current graphic state.
     *
     * @see PDF-Reference PDF 32000-1:2008 8.3.4 Transformation Matrices
     * @param $a
     * @param $b
     * @param $c
     * @param $d
     * @param $e
     * @param $f
     * @return SetaPDF_Core_Canvas
     */
    public function addCurrentTransformationMatrix($a, $b, $c, $d, $e, $f)
    {
        SetaPDF_Core_Type_Numeric::writePdfString($this, $a);
        SetaPDF_Core_Type_Numeric::writePdfString($this, $b);
        SetaPDF_Core_Type_Numeric::writePdfString($this, $c);
        SetaPDF_Core_Type_Numeric::writePdfString($this, $d);
        SetaPDF_Core_Type_Numeric::writePdfString($this, $e);
        SetaPDF_Core_Type_Numeric::writePdfString($this, $f);
        $this->write(' cm');

        $this->graphicState()->addCurrentTransformationMatrix($a, $b, $c, $d, $e, $f);

        return $this;
    }

    /**
     * Rotate the transformation matrix by $angle degrees at the origin defined by $x and $y.
     *
     * @param int|float $x X-coordinate of rotation point
     * @param int|float $y Y-coordinate of rotation point
     * @param float $angle Angle to rotate in degrees
     * @return SetaPDF_Core_Canvas
     */
    public function rotate($x, $y, $angle)
    {
        if ($angle == 0)
            return $this;

        $angle = deg2rad($angle);
        $c = cos($angle);
        $s = sin($angle);

        $this->addCurrentTransformationMatrix($c, $s, -$s, $c, $x, $y);

        return $this->translate(-$x, -$y);
    }

    /**
     * Scale the transformation matrix by the factor $scaleX and $scaleY.
     *
     * @param int|float $scaleX Scale factor on X
     * @param int|float $scaleY Scale factor on Y
     * @return SetaPDF_Core_Canvas
     */
    public function scale($scaleX, $scaleY)
    {
        return $this->addCurrentTransformationMatrix($scaleX, 0, 0, $scaleY, 0, 0);
    }

    /**
     * Move the transformation matrix by $shiftX and $shiftY on x-axis and y-axis.
     *
     * @param int|float $shiftX Points to move on x-axis
     * @param int|float $shiftY Points to move on y-axis
     * @return SetaPDF_Core_Canvas
     */
    public function translate($shiftX, $shiftY)
    {
        return $this->addCurrentTransformationMatrix(1, 0, 0, 1, $shiftX, $shiftY);
    }

    /**
     * Skew the transformation matrix
     *
     * @param float $angleX Angle to x-axis in degrees
     * @param float $angleY Angle to y-axis in degrees
     * @param int $x Points to stretch on x-axis
     * @param int $y Point to stretch on y-axis
     * @return SetaPDF_Core_Canvas
     */
    public function skew($angleX, $angleY, $x = 0, $y = 0)
    {
        $tX = tan(deg2rad($angleX));
        $tY = tan(deg2rad($angleY));

        return $this->addCurrentTransformationMatrix(1, $tX, $tY, 1, -$tY * $y, -$tX * $x);
    }

    /**
     * Draw an external object
     *
     * @param string $name or xobject
     * @throws InvalidArgumentException
     * @return SetaPDF_Core_Canvas
     */
    public function drawXObject($name)
    {
        if ($name instanceof SetaPDF_Core_XObject)
            $name = $this->addResource(SetaPDF_Core_Resource::TYPE_X_OBJECT, $name);

        $xObjects = $this->getResources(true, false, SetaPDF_Core_Resource::TYPE_X_OBJECT);
        if (false === $xObjects || !$xObjects->offsetExists($name)) {
            throw new InvalidArgumentException('Unknown XObject: ' . $name);
        }

        SetaPDF_Core_Type_Name::writePdfString($this, $name, true);
        $this->write(' Do');

        return $this;
    }
}