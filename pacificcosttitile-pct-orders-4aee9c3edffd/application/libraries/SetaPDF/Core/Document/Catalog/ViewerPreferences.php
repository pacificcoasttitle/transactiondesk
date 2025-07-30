<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: ViewerPreferences.php 324 2012-11-08 10:28:41Z jan $
 */

/**
 * Class representing the access to the ViewerPrerferences dictionary of a document
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Catalog_ViewerPreferences
{
    /**#@+
     * Constant value specifying how to display the documen on exiting full-screen mode.
     *
     * @var string
     */
    const NON_FULL_SCREEN_PAGE_MODE_USE_NONE = 'UseNone';
    const NON_FULL_SCREEN_PAGE_MODE_USE_OUTLINES = 'UseOutlines';
    const NON_FULL_SCREEN_PAGE_MODE_USE_THUMBS = 'UseThumbs';
    const NON_FULL_SCREEN_PAGE_MODE_USE_OC = 'UseOC';
    /**#@-*/

    /**#@+
     * Constant value for predominant reading order for text
     *
     * @var string
     */
    const DIRECTION_L2R = 'L2R';
    const DIRECTION_R2L = 'R2L';
    /**#@-*/

    /**#@+
     * Constant value of the the page scaling option that shall be selected when a print dialog is displayed for this document.
     *
     * @var string
     */
    const PRINT_SCALLING_NONE = 'None';
    const PRINT_SCALLING_APP_DEFAULT = 'AppDefault';
    /**#@-*/

    /**#@+
     * Constant value of the paper handling option that shall be used when printing the file from the print dialog.
     *
     * @var string
     */
    const DUPLEX_SIMPLEX = 'Simplex';
    const DUPLEX_FLIP_SHORT_EDGE = 'DuplexFlipShortEdge';
    const DUPLEX_FLIP_LONG_EDGE = 'DuplexFlipLongEdge';
    /**#@-*/
    
    /**
     * The catalog instance
     *
     * @var SetaPDF_Core_Document_Catalog
     */
    protected $_catalog;

    /**
     * The consturctor
     *
     * @param SetaPDF_Core_Document_Catalog $catalog
     */
    public function __construct(SetaPDF_Core_Document_Catalog $catalog)
    {
    	$this->_catalog = $catalog;
    }

    /**
     * Get the document instance
     *
     * @return SetaPDF_Core_Document
     */
    public function getDocument()
    {
        return $this->_catalog->getDocument();
    }

    public function cleanUp()
    {
    	$this->_catalog = null;
    }

    /**
     * Set the flag specifying whether to hide the conforming reader’s tool bars when the document is active.
     *
     * @param boolean $value
     */
    public function setHideToolbar($value = true)
    {
        $this->_setBooleanValue('HideToolbar', $value);
    }

    /**
     * Get the flag specifying whether to hide the conforming reader’s tool bars when the document is active.
     *
     * @return boolean
     */
    public function getHideToolbar()
    {
    	return $this->_getValue('HideToolbar');
    }

    /**
     * Set the flag specifying whether to hide the conforming reader’s menu bar when the document is active.
     *
     * Does not affect the diaplay through a browser plugin
     *
     * @param boolean $value
     */
    public function setHideMenubar($value = true)
    {
        $this->_setBooleanValue('HideMenubar', $value);
    }

    /**
     * Get the flag specifying whether to hide the conforming reader’s menu bar when the document is active.
     *
     * @return boolean
     */
    public function getHideMenubar()
    {
    	return $this->_getValue('HideMenubar');
    }

    /**
     * Set flag specifying whether to hide user interface elements in the document’s window (such as scroll bars and navigation controls), leaving only the document’s contents displayed.
     *
     * @param boolean $value
     */
    public function setHideWindowUI($value = true)
    {
    	$this->_setBooleanValue('HideWindowUI', $value);
    }

    /**
     * Get flag specifying whether to hide user interface elements in the document’s window (such as scroll bars and navigation controls), leaving only the document’s contents displayed.
     *
     * @return boolean
     */
    public function getHideWindowUI()
    {
    	return $this->_getValue('HideWindowUI');
    }

    /**
     * Set the flag specifying whether to resize the document’s window to fit the size of the first displayed page.
     *
     * @param boolean $value
     */
    public function setFitWindow($value = true)
    {
    	$this->_setBooleanValue('FitWindow', $value);
    }

    /**
     * Get the flag specifying whether to resize the document’s window to fit the size of the first displayed page.
     *
     * @return boolelan
     */
    public function getFitWindow()
    {
    	return $this->_getValue('FitWindow');
    }

    /**
     * Set the flag specifying whether to position the document’s window in the center of the screen.
     *
     * @param boolean $value
     */
    public function setCenterWindow($value = true)
    {
    	$this->_setBooleanValue('CenterWindow', $value);
    }

    /**
     * Get the flag specifying whether to position the document’s window in the center of the screen.
     *
     * @return boolean
     */
    public function getCenterWindow()
    {
    	return $this->_getValue('CenterWindow');
    }

    /**
     * Set the flag specifying if the Title of the document should be displayed in the window’s title bar (true) or the filename (false)
     *
     * @param boolean $value
     */
    public function setDisplayDocTitle($value = true)
    {
    	$this->_setBooleanValue('DisplayDocTitle', $value);
    	if (true === $value) {
    	    $this->getDocument()->setMinPdfVersion('1.4');
    	}
    }

    /**
     * Get the flag specifying if the Title of the document should be displayed in the window’s title bar (true) or the filename (false)
     *
     * @return boolean
     */
    public function getDisplayDocTitle()
    {
    	return $this->_getValue('DisplayDocTitle');
    }

    /**
     * Set the document’s page mode, specifying how to display the document on exiting full-screen mode
     *
     * @param string $name
     */
    public function setNonFullScreenPageMode($name = self::NON_FULL_SCREEN_PAGE_MODE_USE_NONE)
    {
        // TODO: Check for allowed values
        $this->_setNameValue('NonFullScreenPageMode', $name);
    }

    /**
     * Get the document’s page mode, specifying how to display the document on exiting full-screen mode
     *
     * @return string
     */
    public function getNonFullScreenPageMode()
    {
        return $this->_getValue('NonFullScreenPageMode', self::NON_FULL_SCREEN_PAGE_MODE_USE_NONE);
    }

    /**
     * Set the predominant reading order for text
     *
     * @param string $name
     */
    public function setDirection($name)
    {
        // TODO: Check for allowed values
        $this->_setNameValue('Direction', $name);
        $this->getDocument()->setMinPdfVersion('1.3');
    }

    /**
     * Get the predominant reading order for text
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->_getValue('Direction', self::DIRECTION_L2R);
    }

    /**
     * Set the page boundary representing the area of a page that shall be displyed when viewing the documen on the screen
     *
     * @param string $boundaryName
     * @throws InvalidArgumentException
     */
    public function setViewArea($boundaryName)
    {
        if (!SetaPDF_Core_PageBoundaries::isValidName($boundaryName)) {
            throw new InvalidArgumentException(
                "'%' is an invalid page boundary."
            );
        }
        $this->_setNameValue('ViewArea', $boundaryName);
        $this->getDocument()->setMinPdfVersion('1.4');
    }

    /**
     * Get the page boundary representing the area of a page that shall be displyed when viewing the documen on the screen
     *
     * @return string
     */
    public function getViewArea()
    {
        return $this->_getValue('ViewArea', SetaPDF_Core_PageBoundaries::CROP_BOX);
    }

    /**
     * Set the name of the page boundary to which the contents of a page shall be clipped when viewing the document on the screen.
     *
     * @param string $boundaryName
     * @throws InvalidArgumentException
     */
    public function setViewClip($boundaryName)
    {
        if (!SetaPDF_Core_PageBoundaries::isValidName($boundaryName)) {
            throw new InvalidArgumentException(
                "'%' is an invalid page boundary."
            );
        }
    	$this->_setNameValue('ViewClip', $boundaryName);
    	$this->getDocument()->setMinPdfVersion('1.4');
    }

    /**
     * Get the name of the page boundary to which the contents of a page shall be clipped when viewing the document on the screen.
     *
     * @return string
     */
    public function getViewClip()
    {
    	return $this->_getValue('ViewClip', SetaPDF_Core_PageBoundaries::CROP_BOX);
    }

    /**
     * Set the name of the page boundary representing the area of a page that shall be rendered when printing the document.
     *
     * @param string $boundaryName
     * @throws InvalidArgumentException
     */
    public function setPrintArea($boundaryName)
    {
        if (!SetaPDF_Core_PageBoundaries::isValidName($boundaryName)) {
            throw new InvalidArgumentException(
                "'%' is an invalid page boundary."
            );
        }
    	$this->_setNameValue('PrintArea', $boundaryName);
    	$this->getDocument()->setMinPdfVersion('1.4');
    }

    /**
     * Get the name of the page boundary representing the area of a page that shall be rendered when printing the document.
     *
     * @return string
     */
    public function getPrintArea()
    {
    	return $this->_getValue('PrintArea', SetaPDF_Core_PageBoundaries::CROP_BOX);
    }

    /**
     * Set the name of the page boundary to which the contents of a page shall be clipped when printing the document.
     *
     * @param string $boundaryName
     * @throws InvalidArgumentException
     */
    public function setPrintClip($boundaryName)
    {
    	if (!SetaPDF_Core_PageBoundaries::isValidName($boundaryName)) {
    		throw new InvalidArgumentException(
    				"'%' is an invalid page boundary."
    		);
    	}
    	$this->_setNameValue('PrintClip', $boundaryName);
    	$this->getDocument()->setMinPdfVersion('1.4');
    }

    /**
     * Get the name of the page boundary to which the contents of a page shall be clipped when printing the document.
     *
     * @return string
     */
    public function getPrintClip()
    {
    	return $this->_getValue('PrintClip', SetaPDF_Core_PageBoundaries::CROP_BOX);
    }

    /**
     * Set the page scaling option that shall be selected when a print dialog is displayed for this document.
     *
     * @param string $name
     */
    public function setPrintScaling($name)
    {
        $this->_setNameValue('PrintScaling', $name);
        $this->getDocument()->setMinPdfVersion('1.6');
    }

    /**
     * Get the page scaling option that shall be selected when a print dialog is displayed for this document.
     *
     * @return string
     */
    public function getPrintScaling()
    {
        return $this->_getValue('PrintScaling', self::PRINT_SCALLING_APP_DEFAULT);
    }

    /**
     * Set the paper handling option that shall be used when printing the file from the print dialog.
     *
     * @param string|false $name
     */
    public function setDuplex($name)
    {
        if (!$name) {
            $this->_removeKey('Duplex');
            return;
        }

        // TODO: Check for allowed values
        $this->_setNameValue('Duplex', $name);
    }

    /**
     * Get the paper handling option that shall be used when printing the file from the print dialog.
     *
     * @return string|null
     */
    public function getDuplex()
    {
        return $this->_getValue('Duplex', null);
    }

    /**
     * Set the flag specifying whether the PDF page size shall be used to select the input paper tray.
     *
     * @param boolean $value
     */
    public function setPickTrayByPdfSize($value = true)
    {
        $this->_setBooleanValue('PickTrayByPDFSize', $value);
        $this->getDocument()->setMinPdfVersion('1.7');
    }

    /**
     * Get the flag specifying whether the PDF page size shall be used to select the input paper tray.
     *
     * @param null|boolean $defaultValue
     * @return bool|mixed
     */
    public function getPickTrayByPdfSize($defaultValue = null)
    {
        return $this->_getValue('PickTrayByPDFSize', $defaultValue);
    }

    /**
     * Set the page numbers used to initialize the print dialog box when the file is printed.
     *
     * @param array|null $pageRange
     */
    public function setPrintPageRange(array $pageRange = null)
    {
        $count = count($pageRange);
        if ($pageRange === null || $count === 0) {
            $this->_removeKey('PrintPageRange');
            return;
        }

        $pageRange = array_map('intval', $pageRange);
        if (($count % 2) !== 0) {
            $pageRange[] = $pageRange[$count - 1];
        }

        $value = new SetaPDF_Core_Type_Array();
        foreach ($pageRange AS $pageNumber) {
            $value->offsetSet(null, new SetaPDF_Core_Type_Numeric($pageNumber - 1));
        }

        $this->_setValue('PrintPageRange', $value);
        $this->getDocument()->setMinPdfVersion('1.7');
    }

    /**
     * Get the page numbers used to initialize the print dialog box when the file is printed.
     *
     * @param array $defaultValue
     * @return array
     */
    public function getPrintPageRange(array $defaultValue = array())
    {
        $value = $this->_getValue('PrintPageRange', $defaultValue, true);
        if ($value instanceof SetaPDF_Core_Type_Abstract)
            $value = $value->toPhp();

        return $value;
    }

    /**
     * Set the number of copies that shall be printed when the print dialog is opened for this file.
     *
     * @param integer $numCopies
     */
    public function setNumCopies($numCopies)
    {
        $this->_setValue('NumCopies', new SetaPDF_Core_Type_Numeric((int)$numCopies));
    }

    /**
     * Get the number of copies that shall be printed when the print dialog is opened for this file.
     *
     * @param integer $defaultValue
     */

    /**
     * @param int $defaultValue
     * @return bool|mixed
     */
    public function getNumCopies($defaultValue = 1)
    {
        return $this->_getValue('NumCopies', $defaultValue);
    }


  /* Helper methods to get and set common types */

    /**
     * Hepler method to get a value of the ViewerPreferences dictionary
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $pdfObject
     * @return bool
     */
    protected function _getValue($key, $default = false, $pdfObject = false)
    {
    	$catalog = $this->getDocument()->getCatalog()->getDictionary();
    	if (null === $catalog)
    		return $default;

        if (!$catalog->offsetExists('ViewerPreferences'))
            return $default;

        $viewerPreferences = $catalog->getValue('ViewerPreferences')->ensure(true);
        if (!$viewerPreferences->offsetExists($key))
    		return $default;

    	if (false === $pdfObject)
    	    return $viewerPreferences->getValue($key)->ensure()->getValue();

    	return $viewerPreferences->getValue($key)->ensure();
    }

    /**
     * Helper method for setting boolean values
     *
     * @param string $key
     * @param boolean $value
     */
    protected function _setBooleanValue($key, $value)
    {
    	$this->_setValue($key, new SetaPDF_Core_Type_Boolean($value));
    }

    /**
     * Helper method for setting a name value
     *
     * @param string $key
     * @param string $name
     */
    protected function _setNameValue($key, $name)
    {
        $this->_setValue($key, new SetaPDF_Core_Type_Name($name));
    }

    /**
     * Helper method for setting a value
     *
     * @param string $key
     * @param SetaPDF_Core_Type_Abstract $value
     */
    protected function _setValue($key, SetaPDF_Core_Type_Abstract $value)
    {
        $catalog = $this->getDocument()->getCatalog()->getDictionary(true);
        if (!$catalog->offsetExists('ViewerPreferences'))
            $catalog->offsetSet('ViewerPreferences', new SetaPDF_Core_Type_Dictionary());

        $viewerPreferences = $catalog->getValue('ViewerPreferences')->ensure(true);
        $viewerPreferences->offsetSet($key, $value);
    }

    /**
     * Helper method for removing a key from the ViewerPreferences dictionary
     *
     * @param string $key
     */
    protected function _removeKey($key)
    {
        $catalog = $this->getDocument()->getCatalog()->getDictionary(true);
        if (!$catalog->offsetExists('ViewerPreferences'))
            return;

        $viewerPreferences = $catalog->getValue('ViewerPreferences')->ensure(true);
        $viewerPreferences->offsetUnset($key);
    }
}