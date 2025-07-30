<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Abstract.php 502 2013-06-13 09:27:57Z jan.slabon $
 */

/**
 * Abstract form field
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    http://www.setasign.de/ Commercial
 */
abstract class SetaPDF_FormFiller_Field_Abstract
{
    /**
     * The name including a suffix if needed ("Text#1")
     * 
     * @var string
     */
    protected $_qualifiedName;
    
    /**
     * The name without the suffix
     * 
     * @var string
     */
    protected $_originalQualifiedName;
    
    /**
     * A reference to the fields instance
     * 
     * @var SetaPDF_FormFiller_Fields
     */
    protected $_fields;
    
    /**
     * The main field dictionary
     * 
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_fieldDictionary;
    
    /**
     * The fields indirect object
     * 
     * @var SetaPDF_Core_Type_IndirectObject
     */
    protected $_fieldObject;
    
    /**
     * An instance of the appearance helper
     * 
     * @var SetaPDF_FormFiller_Field_AppearanceHelper_Default
     */
    protected $_appearanceHelper;
    
    /**
     * The page instance on which the form field is placed 
     * 
     * @var SetaPDF_Core_Document_Page
     */
    protected $_page;
    
    /**
     * The font object, which should be used to create the appearance
     * 
     * @var SetaPDF_Core_Font
     */
    protected $_font = null;

    /**
     * An individual color object which should be used for drawing the text appearance
     *
     * @var SetaPDF_Core_DataStructure_Color
     */
    protected $_textColor = null;

    /**
     * The annotation object of this form field
     *
     * @var SetaPDF_Core_Document_Page_Annotation_Widget
     */
    protected $_annotation;

    /**
     * The constructor
     * 
     * @param SetaPDF_FormFiller_Fields $fields
     * @param string $qualifiedName
     * @param SetaPDF_Core_Type_IndirectReference|SetaPDF_Core_Type_IndirectObject $fieldObject
     * @param string $originalQualifiedName
     * @return SetaPDF_FormFiller_Field_Abstract
     */
    public function __construct(
        SetaPDF_FormFiller_Fields $fields,
        $qualifiedName,
        $fieldObject,
        $originalQualifiedName = null
    )
    {
        $this->_fields = $fields;
        $this->_qualifiedName = (string)$qualifiedName;
        $this->_fieldDictionary = $fieldObject->ensure(true);
        
        if ($fieldObject instanceof SetaPDF_Core_Type_IndirectReference)
            $fieldObject = $fieldObject->getValue();
        
        $this->_fieldObject = $fieldObject;
        
        $this->_originalQualifiedName = $originalQualifiedName === null
                                      ? (string)$qualifiedName
                                      : (string)$originalQualifiedName;
    }
    
    /**
     * Release cycled references and release memory
     * 
     * @return void
     */
    public function cleanUp()
    {
        $this->_fields = null;
        $this->_fieldDictionary = null;
        $this->_page = null;
        $this->_annotation = null;
    }
    
    /**
     * Returns the qualified name
     * 
     * @return string
     */
    public function getQualifiedName()
    {
        return $this->_qualifiedName;
    }
    
    /**
     * Alias for getQualifiedName()
     * 
     * @see getQualifiedName()
     * @return string
     */
    public function getName()
    {
        return $this->getQualifiedName();
    }
    
    /**
     * Get the original qualified name (without suffix)
     * 
     * @return string
     */
    public function getOriginalQualifiedName()
    {
        return $this->_originalQualifiedName;
    }
    
    /**
     * Sets a field flag
     * 
     * @param integer $flags
     * @param boolean|null $add Add = true, remove = false, set = null
     */
    public function setFieldFlags($flags, $add = true)
    {
        if (false === $add) {
            $this->unsetFieldFlags($flags);
            return;
        }
            
        $dict = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'Ff');
        
        if ($dict instanceof SetaPDF_Core_Type_Abstract) {
            $value = $dict->ensure()->getValue('Ff');
            if ($add === true) {
                $value->setValue($value->getValue() | $flags);
            } else {
                $value->setValue($flags);
            }
            
        } else {
            $this->_fieldDictionary->offsetSet('Ff', new SetaPDF_Core_Type_Numeric($flags));
        }
    }
    
    /**
     * Removes a field flag
     * 
     * @param integer $flags
     */
    public function unsetFieldFlags($flags)
    {
        $dict = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'Ff');
        
        if ($dict instanceof SetaPDF_Core_Type_Abstract) {
            $value = $dict->ensure()->getValue('Ff');
            $value->setValue($value->getValue() & ~$flags);
        }
    }
    
    /**
     * Returns the current field flags
     * 
     * @return integer
     */
    public function getFieldFlags()
    {
        $fieldFlags = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'Ff');
        if ($fieldFlags)
            return $fieldFlags->getValue();
            
        return 0;
    }
    
    /**
     * Checks if a specific field flag is set
     * 
     * @param integer $flag
     * @return boolean
     */
    public function isFieldFlagSet($flag)
    {
        return ($this->getFieldFlags() & $flag) !== 0;
    }
    
    /**
     * Checks if the field is set to read-only
     * 
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::READ_ONLY);
    }
    
    /**
     * Sets the read-only flag
     * 
     * @param boolean $readOnly
     */
    public function setReadOnly($readOnly = true)
    {
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::READ_ONLY, $readOnly);
    }
    
    /**
     * Checks if the field is set to be required
     * 
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::REQUIRED);
    }
    
    /**
     * Sets the required flag
     * 
     * @param boolean $required
     */
    public function setRequired($required = true)
    {
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::REQUIRED, $required);
    }
    
    /**
     * Checks if the no-export flag is set
     * 
     * @return boolean
     */
    public function getNoExport()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::NO_EXPORT);
    }
    
    /**
     * Set the no-export flag
     * 
     * @param boolean $noExport
     */
    public function setNoExport($noExport = true)
    {
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::NO_EXPORT, $noExport);
    }
    
    /**
     * Gets the page object on which the form field is placed
     * 
     * @throws SetaPDF_FormFiller_Field_Exception
     * @return SetaPDF_Core_Document_Page
     */
    public function getPage()
    {
        if (null === $this->_page) {
            $formFiller = $this->_fields->getFormFiller();
            $document = $formFiller->getDocument();
            
            $pageObjectReference = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute(
                $this->_fieldDictionary, 'P', null, false
            );

            $pages = $document->getCatalog()->getPages();
            $page = false;
            if ($pageObjectReference) {
                $pageObject = $pageObjectReference->getValue();
                if ($pageObject->ensure() instanceof SetaPDF_Core_Type_Dictionary) {
                    $page = $pages->getPageByIndirectObject($pageObject);
                }
            }
            
            if (false === $page) {
                $page = $pages->getPageByAnnotation($this->_fieldObject);
            }
            
            if (false === $page) {
                throw new SetaPDF_FormFiller_Field_Exception(
                    sprintf(
                    	'The page object of this form field (%s) could not be resolved.',
                        $this->_qualifiedName
                    )
                );
            }
            
            $this->_page = $page;
        }
        
        return $this->_page;
    }
    
    /**
     * Get the page number on which the field appears
     * 
     * @return integer
     */
    public function getPageNumber()
    {
        $formFiller = $this->_fields->getFormFiller();
        $document = $formFiller->getDocument();
        
        $pages = $document->getCatalog()->getPages();
        return $pages->getPageNumberByPageObject($this->getPage());
    }
    
    /**
     * Recreate or creates the background appearance of the form field
     * 
     * @return SetaPDF_Core_Canvas
     */
    protected function _recreateAppearance()
    {
        $n = $this->getNormalAppearanceObject();
        
        // We have to ignore the type, because it is not set for all terminal fields
        $canvas = new SetaPDF_Core_Canvas(SetaPDF_Core_XObject::get($n, 'Form'), null, true);
        $nDictionary = $canvas->getContainer()->getObject(true)->ensure(true)->getValue();
        $canvas->clear();
        
        // Ensure that resources entry was added
        $canvas->getResources(false, true);

        $annotation = $this->getAnnotation();
        $width = $annotation->getWidth();
        $height = $annotation->getHeight();

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

        // Handle Rotation
        $rotation = $appearanceCharacteristics
                  ? $appearanceCharacteristics->getRotation()
                  : 0;
        if ($rotation != 0) {
            $rotation = $rotation % 360;
            if ($rotation < 0)
                $rotation = $rotation + 360;

            $r = deg2rad($rotation);
            $a = $d = cos($r);
            $b = sin($r);
            $c = -$b;
            $e = 0;
            $f = 0;
            
            // INFO: The translate values ($e, $f) only take account if the field is flattened!
            if ($a == -1) {
                $e = $width;
                $f = $height;
            }
            
            if ($b == 1) 
                $e = $height;
            
            if ($c == 1)
                $f = $width;

            $nDictionary->offsetSet('Matrix', new SetaPDF_Core_Type_Array(array(
                new SetaPDF_Core_Type_Numeric($a),
                new SetaPDF_Core_Type_Numeric($b),
                new SetaPDF_Core_Type_Numeric($c),
                new SetaPDF_Core_Type_Numeric($d),
                new SetaPDF_Core_Type_Numeric($e),
                new SetaPDF_Core_Type_Numeric($f)
            )));
        }

        // Set the BBox
        $nDictionary->offsetSet('BBox', new SetaPDF_Core_Type_Array(array(
            new SetaPDF_Core_Type_Numeric(0),
            new SetaPDF_Core_Type_Numeric(0),
            new SetaPDF_Core_Type_Numeric($width),
            new SetaPDF_Core_Type_Numeric($height)
        )));

        // Draw Background
        $backgroundColor = $appearanceCharacteristics
                         ? $appearanceCharacteristics->getBackgroundColor()
                         : null;
        if ($backgroundColor) {
            $backgroundColor->draw($canvas, false);
            $canvas->draw()->rect(0, 0, $width, $height, SetaPDF_Core_Canvas_Draw::STYLE_FILL);
        }
        
        // Draw Border:
        $borderColor = $appearanceCharacteristics
                     ? $appearanceCharacteristics->getBorderColor()
                     : null;

        // It is possible to have no border but only a border style!
        
        // Beveld or Inset
        if ($_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::BEVELED ||
            $_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::INSET) {
            $colorLtValue = 1; //' 1 g';
            if ($_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::INSET)
                $colorLtValue = .5; // ' 0.5 g';
                
            /**
             * This color adjustment is not needed for list boxes.
             * The effect will only occur if the field is active
             * All other fields will use this effect.
             */
            if (
                !($this instanceof SetaPDF_FormFiller_Field_List) &&
                $_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::BEVELED && $backgroundColor
            ) {
                $tmpColor = clone $backgroundColor;
                $tmpColor->adjustAllComponents(-0.250977);
                $colorRb = $tmpColor;
            } else {
                $colorRb = new SetaPDF_Core_DataStructure_Color_Gray(.75);
            }
            
            // Draw the inner border
            $canvas->saveGraphicState();  // q
            SetaPDF_Core_DataStructure_Color_Gray::writePdfString($canvas, $colorLtValue, false);
            
            $_borderWidth = $borderWidth * 2;
            $canvas->write(
                sprintf(" %.4F %.4F m", $x = $_borderWidth / 2, $y = $height-$_borderWidth / 2) .
                sprintf(" %.4F %.4F l", $x = $width - $x, $y) .
                sprintf(" %.4F %.4F l", $x -= $_borderWidth / 2, $y -= $_borderWidth / 2) .
                sprintf(" %.4F %.4F l", $x = $_borderWidth, $y) .
                sprintf(" %.4F %.4F l", $x, $y = $_borderWidth) .
                sprintf(" %.4F %.4F l", $x /= 2, $y /= 2) .
                ' h f'
            );
            
            $colorRb->draw($canvas, false);
            $canvas->write(
                sprintf(" %.4F %.4F m", $x, $y) .
                sprintf(" %.4F %.4F l", $x *= 2, $y *= 2) . 
                sprintf(" %.4F %.4F l", $x = $width - $x, $y) . 
                sprintf(" %.4F %.4F l", $x, $y += $height - $_borderWidth * 2) .
                sprintf(" %.4F %.4F l", $x += $_borderWidth / 2, $y += $_borderWidth / 2) .
                sprintf(" %.4F %.4F l", $x, $_borderWidth / 2) .
                ' h f'
            );
            
            $canvas->restoreGraphicState(); // Q
        } 
        
        if ($borderColor) {
            $canvas->path()->setLineWidth($borderWidth);
            $borderColor->draw($canvas, true);

            // Dashed
            if ($_borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::DASHED) {
                $canvas->path()->setDashPattern($borderStyle->getDashPattern());
            }
            
            // Draw border 
            // NOT underline
            if ($_borderStyle !== SetaPDF_Core_Document_Page_Annotation_BorderStyle::UNDERLINE) {
                $canvas->draw()->rect(
                    $borderWidth * .5,
                    $borderWidth * .5,
                    $width - $borderWidth,
                    $height - $borderWidth
                );

                // underline
            } else {
                $y = $borderWidth / 2;
                $canvas->draw()->line(0, $y, $width, $y);
            }
        }
        
        return $canvas;
    }
    
    /**
     * Checks form form filling permissions 
     * 
     * @param integer $permission
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    protected function _checkPermission($permission = SetaPDF_Core_SecHandler::PERM_FILL_FORM)
    {
        SetaPDF_Core_SecHandler::checkPermission($this->_fields->getFormFiller()->getDocument(), $permission);
    }
    
    /**
     * Get the reference to the normal appearance stream object
     * 
     * @return SetaPDF_Core_Type_IndirectObject
     */
    protected function _getAppearanceReference()
    {
        $ap = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'AP');
        // Create N entry if it does not exists
        if (!$ap || !$ap->offsetExists('N')) {
            $this->recreateAppearance();
            $ap = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'AP');
        }
            
        // get the N entry
        return $ap->offsetGet('N')->getValue();
    }
    
    /**
     * Get the default appearance data of the DA value
     *
     * @param string $fontName
     * @param float|null $fontSize
     * @param string $textColor
     * @return void
     * @throws SetaPDF_FormFiller_Field_Exception
     */
    public function getDefaultAppearanceData(&$fontName, &$fontSize, &$textColor)
    {
        $formFiller = $this->_fields->getFormFiller();
            
        $da = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'DA');
        $da = $da ? $da : SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute(
            $formFiller->getDocument()->getCatalog()->getAcroForm()->getDictionary(),
            'DA'
        );
        if (!$da) {
            throw new SetaPDF_FormFiller_Field_Exception('No DA key found.');
        }
        
        $daString = $da->getValue();
        
        $matches = array();
        if (!preg_match('~/([a-z0-9#_\-,\.]*)[\s]+([0-9\.]*)[ ]*Tf[ ]*(.*)~i', $daString, $matches)) {
            // TODO: Optional change font to a standard font
            throw new SetaPDF_FormFiller_Field_Exception('Font name could not be found.');
        }
        
        $fontName  = $matches[1];
        $fontSize  = $matches[2];
        if ($this->getAppearanceTextColor() === null) {
            $textColor = $matches[3];
        } else {
            $textColor = new SetaPDF_Core_Writer();
            $this->getAppearanceTextColor()->draw($textColor, false);
            $textColor = (string)$textColor;
        }
    }
    
    /**
     * Set the appearance font object
     *
     * @param SetaPDF_Core_Font $font
	 * @return null|SetaPDF_Core_Font
     */
    public function setAppearanceFont(SetaPDF_Core_Font $font)
    {
        $this->_font = $font;
    }

    /**
     * Get the appearance font object
     * 
	 * @return null|SetaPDF_Core_Font
     */
    public function getAppearanceFont()
    {
        return $this->_font;
    }

    /**
     * Set an individual appearance text color
     *
     * @param SetaPDF_Core_DataStructure_Color $textColor
     */
    public function setAppearanceTextColor(SetaPDF_Core_DataStructure_Color $textColor = null)
    {
        $this->_textColor = $textColor;
    }

    /**
     * Get the individual appearance text color
     *
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getAppearanceTextColor()
    {
        return $this->_textColor;
    }

    /**
     * Get the font relation and copy the resources to the Resources entry if needed
     *
     * @param SetaPDF_Core_Type_Dictionary $nDictionary
     * @param string $fontName
     * @return SetaPDF_Core_Type_Dictionary_Entry
     * @throws SetaPDF_FormFiller_Exception
     */
    protected function _getFontRelation(SetaPDF_Core_Type_Dictionary $nDictionary, &$fontName)
    {
        $formFiller = $this->_fields->getFormFiller();
        
        $resources = $nDictionary->offsetGet('Resources')->ensure();
        if (!$resources->offsetExists('Font')) {
            $fonts = new SetaPDF_Core_Type_Dictionary();
            $resources->offsetSet('Font', $fonts);
        } else {
            $fonts = $resources->offsetGet('Font')->ensure();
        }
        
        if (null !== $this->getAppearanceFont()) {
            $fontName = null;
            
            $fontObject = $this->getAppearanceFont()->getIndirectObject();
            // Check if font object is already added to fonts dictionary
            foreach ($fonts AS $tmpFontName => $tmpFontObject) {
                if ($tmpFontObject->getObjectIdent() === $fontObject->getObjectIdent()) {
                    $fontName = $tmpFontName;
                }
            }

            // font was not added - add it now
            if (null === $fontName) {
                $i = 0;
                while ($fonts->offsetExists(($fontName = 'F' . ++$i)));
                $fonts->offsetSet($fontName, $fontObject);
            }
        }
        
        $fontRelation = null;
        
        if ($fonts->offsetExists($fontName)) {
            $fontRelation = $fonts->offsetGet($fontName);
        } else {
            // 1. Check in the fields DR entry
            /*
            $dr = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'DR');
            if ($dr instanceof SetaPDF_Core_Type_Dictionary &&
                $dr->offsetExists('Font') &&
                $dr->offsetGet('Font')->ensure()->offsetExists($fontName)
            ) {
                $defaultFonts = $dr->offsetGet('Font')->ensure();
                
            // 2. Check in the AcroForm DR entry
            } else {
            */
                $acroForm = $formFiller->getDocument()->getCatalog()->getAcroForm();
                $acroForm->addDefaultEntriesAndValues();
                $acroFormDictionary = $acroForm->getDictionary();
                $dr = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($acroFormDictionary, 'DR');

                $defaultFonts = $dr->offsetGet('Font')->ensure();

                // Let's check for a fallback or create one
                if (!$defaultFonts->offsetExists($fontName))
                {
                    // Some documents have default fonts present
                    if ($defaultFonts->offsetExists('Helv')) {
                        $fontName = 'Helv';
                        
                    } else {
                        // TODO: Add some aliases like "Helv", "Cour", "Times", "Time",...
                        
                        // Try to find a core-font by the font name
                        $mapping = SetaPDF_Core_Font_Standard::getStandardFontsToClasses();
                        // If it doesn't exists map to Helvetica
                        if (!isset($mapping[$fontName])) {
                            $fontName = 'Helvetica';
                        }
                        
                        $fontDict = call_user_func(array(
                            $mapping[$fontName], 'getDefaultDictionary'));
                        
                        $document = $formFiller->getDocument();
                        $fontObject = $document->createNewObject($fontDict);
                        $defaultFonts->offsetSet($fontName, $fontObject);
                    }
                }
            //}
            
            $fontRelation = clone $defaultFonts->offsetGet($fontName);
            $fonts->offsetSet(null, $fontRelation);
        }
        
        return $fontRelation;
    }
    
    /**
     * Get or create the normal appearance object (the object referenced in the N entry)
     *
     * @param boolean $createNew
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function getNormalAppearanceObject($createNew = false)
    {
        $formFiller = $this->_fields->getFormFiller();
        $document = $formFiller->getDocument();
            
        // get or create AP entry
        $ap = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'AP');
        if (null === $ap) {
            $ap = new SetaPDF_Core_Type_Dictionary();
            $this->_fieldDictionary->offsetSet('AP', $ap);
        }
        
        // get or create N entry
        $n = $ap->getValue('N');
        
        // TODO: Check if the appearance stream is shared with other fields
        
        if ($createNew || null === $n || !($n->ensure() instanceof SetaPDF_Core_Type_Stream)) {
            $nDictionary = new SetaPDF_Core_Type_Dictionary();
            $nDictionary->offsetSet('Type', new SetaPDF_Core_Type_Name('XObject', true));
            $nDictionary->offsetSet('Subtype', new SetaPDF_Core_Type_Name('Form', true));
            $nDictionary->offsetSet('FormType', new SetaPDF_Core_Type_Numeric(1));
            
            $n = $document->createNewObject(
                new SetaPDF_Core_Type_Stream($nDictionary)
            );
            
            $ap->offsetSet('N', $n);
        }
        
        return $n;
    }
    
    /**
     * Flatten the field to the pages content stream
     * 
     * @see SetaPDF_FormFiller_Field_Abstract::delete()
     */
    public function flatten()
    {
        $page = $this->getPage();
        
        $nStreamRef = $this->_getAppearanceReference();
        
        // Make sure that an appearance stream exists
        if ($nStreamRef !== false) {
            $xObject = new SetaPDF_Core_XObject_Form($nStreamRef);

            $name = $page->getCanvas()->addResource($xObject);

            // Add XObject definition to the N object
            // (could be missing if the field was filled by another programm)
            $xObject->ensureDefaultKeys();

            // 2. Get the pages canvas object
            $canvas = $page->getCanvas();

            // 2b. Encapsulate the existing content stream
            $page->getContents()->encapsulateExistingContentInGraphicState();
            
            // 3. Let's display the XObject by appending the needed 
            // commands to the content stream.
            $rect = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'Rect');
            $rect = new SetaPDF_Core_DataStructure_Rectangle($rect);

            if($xObject->getWidth() != 0) {
                $width = $rect->getWidth() / $xObject->getWidth();
            } else {
                $width = '';
            }
            if($xObject->getHeight() != 0) {
                $height = $rect->getHeight() / $xObject->getHeight();
            } else {
                $height = '';
            }

            // Fix rotations via Matrix
            $dict = $xObject->getIndirectObject()->ensure()->getValue();
            if ($dict->offsetExists('Matrix')) {
                $matrix = $dict->getValue('Matrix');
                if ($matrix[1]->getValue() == -1 || $matrix[2]->getValue() == -1) {
                    $annotation = $this->getAnnotation();
                    $width = $annotation->getWidth() / $xObject->getWidth();
                    $height = $annotation->getHeight() / $xObject->getHeight();
                }
            }

            $stream = ' q 0 J 1 w 0 j 0 G 0 g [] 0 d' // Reset
                    . sprintf(' %.4F 0 0 %.4F %.4F %.4F cm',
                        $width,
                        $height,
                        $rect->getLlx(),
                        $rect->getLly()
                    )

                    . sprintf('/%s Do ', $name)
                    . ' Q';
            $canvas->write($stream);
            if ($canvas->getStream() instanceof SetaPDF_Core_Document_Page_Contents) {
                // TODO: This should be automated
                $canvas->getStream()
                    ->getStream()->getValue()
                    ->offsetSet('Filter', new SetaPDF_Core_Type_Name('FlateDecode', true));
            }
        }
        
        $this->_fields->delete($this);
    }
    
    /**
     * Delete the field
     * 
     * @return void
     */
    public function delete()
    {
        $formFiller = $this->_fields->getFormFiller();
        $document = $formFiller->getDocument();
        
        $page = $this->getPage();
        
        $annotsArray = $page->getAnnotations()->getArray();
        $objectId = $this->_fieldObject->getObjectId();
        $key = $annotsArray->indexOf($this->_fieldObject);
        if (-1 !== $key) {
            $annotsArray->offsetUnset($key);
        }
        
        // Delete field object(s)
        $fieldObject = $this->_fieldObject;
        $objectsToDelete = array($fieldObject);
        $currentObject = $fieldObject;
        $removeFieldsEntry = true;
        while ($currentObject->getValue()->offsetExists('Parent')) {
            $lastObject = $currentObject;
            $currentObject = $currentObject->getValue()->getValue('Parent')->getValue();
            $kids = $currentObject->getValue()->offsetGet('Kids')->ensure();
            foreach ($kids as $key => $value) {
                if ($lastObject->getObjectId() === $value->getObjectId()) {
                    $kids->offsetUnset($key);
                }
            }
            
            // If there are still fields in the kids array, stop here
            if ($kids->count() > 0) {
                $removeFieldsEntry = false;
                break;
            }

            $objectsToDelete[] = $currentObject;
        }
        
        foreach ($objectsToDelete AS $objectToDelete) {
            $document->deleteObject($objectToDelete);    
        }
        
        // Delete reference in the the AcroForm array
        if (
            $removeFieldsEntry && 
            false !== ($fieldsArray = $document->getCatalog()->getAcroForm()->getFieldsArray())
        ) {
            foreach ($fieldsArray AS $key => $value) {
                if ($currentObject->getObjectId() === $value->getObjectId()) {
                    $fieldsArray->offsetUnset($key);
                    break;
                }
            }
            
            // If no fields left delete AcroForm object and reference to it
            if ($fieldsArray->count() == 0) {
                $trailer = $document->getTrailer();
                if ($trailer->offsetExists('Root')) {
                    $root = $trailer->offsetGet('Root')->getValue()->ensure(true);
                    
                    if ($root->offsetExists('AcroForm')) {
                        $acroForm = $root->getValue('AcroForm');
                        if ($acroForm instanceof SetaPDF_Core_Type_IndirectObjectInterface)
                            $document->deleteObject($acroForm);
                        $root->offsetUnset('AcroForm');
                    }
                }
            }
        }
        
        $this->_postDelete();
    }
    
    /**
     * A method called after deleting a field
     * 
     * This method forwards the deletion info to the fields instance.
     * 
     * @return void
     */
    protected function _postDelete()
    {
        $this->_fields->onFieldDeleted($this);
    }

    /**
     * Get the widget annotation object from this field
     *
     * @return SetaPDF_Core_Document_Page_Annotation_Widget
     */
    public function getAnnotation()
    {
        if (null === $this->_annotation) {
            $this->_annotation = SetaPDF_Core_Document_Page_Annotation::byObjectOrDictionary($this->_fieldObject);
        }

        return $this->_annotation;
    }
}