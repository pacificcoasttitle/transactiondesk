<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Png.php 389 2013-02-14 14:07:15Z maximilian.kresse $
 */

/**
 * Class representing an PNG image
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Image
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Image_Png extends SetaPDF_Core_Image
{
    /**
     * Palette data
     * 
     * @var string
     */
    protected $_palette = '';
    
    /**
     * Transparency data
     * 
     * @var array
     */
    protected $_transparency = array();
    
    /**
     * Image data
     * 
     * @var string
     */
    protected $_imageData = '';
    
    /**
     * Processes the image data so all needed informations are available
     */
    protected function _process()
    {
        $this->_binaryReader->reset(8, 4);
        
        $dataLength = $this->_binaryReader->readUInt32();
        if (13 !== $dataLength) {
        	throw new SetaPDF_Core_Image_Exception(sprintf('Invalid IHDR chunk length (%s).', $dataLength));
        }
        
        $chunkName = $this->_binaryReader->readBytes(4);
        if ('IHDR' !== $chunkName) {
            throw new SetaPDF_Core_Image_Exception(sprintf('Invalid chunk name (%s).', $chunkName));    
        }
        
        $this->_width = $this->_binaryReader->readUInt32();
        $this->_height = $this->_binaryReader->readUInt32();
        $this->_bitsPerComponent = $this->_binaryReader->readUInt8();
        $this->_colorSpace = $this->_binaryReader->readUInt8();
        
        if (0 !== $this->_binaryReader->readUInt8()) {
            throw new SetaPDF_Core_Image_Exception('Unknown compression method.');
        }
        
        if (0 !== $this->_binaryReader->readUInt8()) {
        	throw new SetaPDF_Core_Image_Exception('Unknown filter method.');
        }

        if (0 !== $this->_binaryReader->readUInt8()) {
            throw new SetaPDF_Core_Image_Exception('Interlaced PNG images are not supported.');
        }
        
        $this->_binaryReader->skip(4);
        
        do {
        	$dataLength = $this->_binaryReader->readUInt32();
        	$chunkName = $this->_binaryReader->readBytes(4);
        	
    		switch($chunkName) {
    			case 'PLTE':
        			$this->_palette = $this->_binaryReader->readBytes($dataLength);
        			$this->_binaryReader->skip(4);
        			break;
    			
    			case 'tRNS':
        			$transparency = $this->_binaryReader->readBytes($dataLength);
        			
        			if ($this->_colorSpace === 0) {
        				$this->_transparency = array(ord($transparency[1]));
        			} elseif($this->_colorSpace == 2) {
        				$this->_transparency = array(ord($transparency[1]), ord($transparency[3]), ord($transparency[5]));
        			} else {
        				$pos = strpos($transparency, "\x00");
        				if (false !== $pos)
        					$this->_transparency = array($pos);
        			}
        			$this->_binaryReader->skip(4);
        			break;
        			
        		case 'IDAT':
        			$this->_imageData .= $this->_binaryReader->readBytes($dataLength);
        			$this->_binaryReader->skip(4);
        			break;
        			
        		case 'pHYs':
        		    $densityX = $this->_binaryReader->readUInt32();
        		    $densityY = $this->_binaryReader->readUInt32();
        		    $units = $this->_binaryReader->readUInt8();
        		    
        		    // units per meter
        		    if (1 === $units) {
        		        $this->_dpiX = $densityX * 0.0254;
        		        $this->_dpiY = $densityY * 0.0254;
        		    }
        		    
        		    // else it is a ratio
        		    
        		    $this->_binaryReader->skip(4);
        		    break;
        		    
        		case 'IEND':
        			break;
        			
        		default:
        		    $this->_binaryReader->skip($dataLength + 4);        			
        	}
        		
        } while($dataLength);
    }
    
    /**
     * Converts the PNG image to an external object
     *
     * @see SetaPDF_Core_Image::toXObject()
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_XObject_Image
     * @throws SetaPDF_Core_Image_Exception
     */
    public function toXObject(SetaPDF_Core_Document $document)
    {
        $bitsPerComponent = $this->getBitsPerComponent();
        if ($bitsPerComponent > 8) {
            throw new SetaPDF_Core_Image_Exception('16-bit depth for PNG images is not supported.');
        }
        
        $colorSpace = $this->getColorSpace();
        switch ($colorSpace) {
            case 0:
            case 4:
                $colorSpace = 'DeviceGray';
                break;
            case 2:
            case 6:
                $colorSpace = 'DeviceRGB';
                break;
            case 3:
                $colorSpace = 'Indexed';
                if ('' === $this->_palette) {
                    throw new SetaPDF_Core_Image_Exception('Palette missing in PNG image.');
                }
                break;
            default:
                throw new SetaPDF_Core_Image_Exception('Unknown color type: ' . $colorSpace);
        }
        
        
        $decodeParameters = new SetaPDF_Core_Type_Dictionary();
        $decodeParameters->offsetSet('Predictor', new SetaPDF_Core_Type_Numeric(15));
        $decodeParameters->offsetSet('Colors', new SetaPDF_Core_Type_Numeric($colorSpace == 'DeviceRGB' ? 3 : 1));
        $decodeParameters->offsetSet('BitsPerComponent', new SetaPDF_Core_Type_Numeric($bitsPerComponent));
        $decodeParameters->offsetSet('Columns', new SetaPDF_Core_Type_Numeric($this->getWidth()));

        // temp var needed because of a bug in zend_guard 5.3 otherwise there will be a segmentation fault
        $imageData = $this->_extractAlphaChannel();
        list($colorImageData, $alphaImageData) = $imageData;
        unset($imageData);

        $dictionary = new SetaPDF_Core_Type_Dictionary();
        $dictionary->offsetSet('Type', new SetaPDF_Core_Type_Name('XObject', true));
        $dictionary->offsetSet('Subtype', new SetaPDF_Core_Type_Name('Image', true));
        $dictionary->offsetSet('Width', new SetaPDF_Core_Type_Numeric($this->getWidth()));
        $dictionary->offsetSet('Height', new SetaPDF_Core_Type_Numeric($this->getHeight()));
        $dictionary->offsetSet('BitsPerComponent', new SetaPDF_Core_Type_Numeric($bitsPerComponent));
        $dictionary->offsetSet('DecodeParms', $decodeParameters); 
        $dictionary->offsetSet('Filter', new SetaPDF_Core_Type_Name('FlateDecode', true));
        
        if (count($this->_transparency) > 0) {
            $mask = new SetaPDF_Core_Type_Array();
            foreach ($this->_transparency AS $value) {
                $mask->offsetSet(null, new SetaPDF_Core_Type_Numeric($value));
                $mask->offsetSet(null, new SetaPDF_Core_Type_Numeric($value));
            }
            
            $dictionary->offsetSet('Mask', $mask);
        }
        
        if (false !== $alphaImageData) {
            $sMask = new self();
            $sMask->_bitsPerComponent = 8;
            $sMask->_colorSpace = 0;
            $sMask->_width = $this->getWidth();
            $sMask->_height = $this->getHeight();
            $sMask->_imageData = $alphaImageData; 
            
            $sMaskObject = $sMask->toXObject($document)->getIndirectObject();
            $dictionary->offsetSet('SMask', $sMaskObject);
        }
        
        if ('Indexed' === $colorSpace) {
            $palette = new SetaPDF_Core_Type_Stream(
                new SetaPDF_Core_Type_Dictionary(array(
                    'Filter' => new SetaPDF_Core_Type_Name('FlateDecode', true)
                ))
            );
            $palette->setStream($this->_palette);
            $paletteObject = $document->createNewObject($palette);
            
            $colorSpaceArray = new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Name('Indexed', true),
                new SetaPDF_Core_Type_Name('DeviceRGB', true),
                new SetaPDF_Core_Type_Numeric(strlen($this->_palette) / 3 - 1),
                $paletteObject
            ));
            $dictionary->offsetSet('ColorSpace', $colorSpaceArray);
            
        } else {
            $dictionary->offsetSet('ColorSpace', new SetaPDF_Core_Type_Name($colorSpace, true));
            if ($colorSpace === 'DeviceCMYK') {
            	$dictionary->offsetSet('Decode', new SetaPDF_Core_Type_Array(array(
        			new SetaPDF_Core_Type_Numeric(1),
        			new SetaPDF_Core_Type_Numeric(0),
        			new SetaPDF_Core_Type_Numeric(1),
        			new SetaPDF_Core_Type_Numeric(0),
        			new SetaPDF_Core_Type_Numeric(1),
        			new SetaPDF_Core_Type_Numeric(0)
            	)));
            }
        }
        
    	$stream = new SetaPDF_Core_Type_Stream($dictionary, $colorImageData);
    	$object = $document->createNewObject($stream);
    
    	return new SetaPDF_Core_XObject_Image($object);
    }
    
    /**
     * Extracts the alpha channel from the image data
     * 
     * @return array
     */
    protected function _extractAlphaChannel()
    {
        $colorSpace = $this->getColorSpace();
        
        if ($colorSpace < 4) {
        	return array($this->_imageData, false);
        }
        
        $imageData = gzuncompress($this->_imageData);
        
        $colorImageData = $alphaImageData = '';
        
        // gray 
        if ($colorSpace === 4) {
            $length = 2 * $this->getWidth();
            for($i = 0, $h = $this->getHeight(); $i < $h; $i++) {
            	$offset = ($length + 1) * $i;
            
            	$colorImageData .= $imageData[$offset];
            	$alphaImageData .= $imageData[$offset];
            
            	$segments = str_split(substr($imageData, $offset + 1, $length), 2);
            	foreach ($segments AS $segment) {
            		$colorImageData .= $segment[0];
            		$alphaImageData .= $segment[1];
            	}
            }
        // rgb
        } elseif ($colorSpace === 6) {
            
            $length = 4 * $this->getWidth();
            for($i = 0, $h = $this->getHeight(); $i < $h; $i++) {
                $offset = ($length + 1) * $i;
                
                $colorImageData .= $imageData[$offset];
                $alphaImageData .= $imageData[$offset];
                
                $segments = str_split(substr($imageData, $offset + 1, $length), 4);
                foreach ($segments AS $segment) {
                    $colorImageData .= substr($segment, 0, 3);
                    $alphaImageData .= substr($segment, 3);
                }
            }
        }
        unset($imageData);
        
        return array(gzcompress($colorImageData), gzcompress($alphaImageData));
    }
}