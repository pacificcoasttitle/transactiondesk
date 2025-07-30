<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Catalog.php 493 2013-06-03 15:02:17Z jan.slabon $
 */

/**
 * A class representing the document catalog
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Catalog
{
    /**
     * The document instance
     *
     * @var SetaPDF_Core_Document
     */
    protected $_document;

    /**
     * The viewer preferences object
     *
     * @var SetaPDF_Core_Document_Catalog_ViewerPreferences
     */
    protected $_viewerPreferences;

    /**
     * Pages instance
     *
     * @var SetaPDF_Core_Document_Catalog_Pages
     */
    protected $_pages;

    /**
     * Names instance
     *
     * @var SetaPDF_Core_Document_Catalog_Names
     */
    protected $_names;

    /**
     * The documents page labels object
     *
     * @var SetaPDF_Core_Document_Catalog_PageLabels
     */
    protected $_pageLabels;

    /**
     * The documents AcroForm obejct
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_acroForm;

    /**
     * The documents outlines object
     *
     * @var SetaPDF_Core_Document_Catalog_Outlines
     */
    protected $_outlines;
    
    /**
     * The optional content object
     * 
     * @var SetaPDF_Core_Document_Catalog_OptionalContent
     */
    protected $_optionalContent;

    /**
     * The output intent object
     *
     * @var SetaPDF_Core_Document_Catalog_OutputIntents
     */
    protected $_outputIntents;
    
    /**
     * Returns method names which should be available in a documents instance, too
     * 
     * @internal
     */
    static public function getDocumentMagicMethods()
    {
        return array(
            'getPageLayout',
            'setPageLayout',
            'getPageMode',
            'setPageMode',
            'getMetadata',
            'setMetadata',
            'getBaseUri',
            'setBaseUri',
            'getViewerPreferences',
            'setViewerPreferences',
            'getPages',
            'getNames',
            'getPageLabels',
            'getAcroForm',
            'getOutlines',
            'getOptionalContent',
            'getOutputIntents'
        );
    }
    
    /**
     * The constructor
     * 
     * @param SetaPDF_Core_Document $document
     */
    public function __construct(SetaPDF_Core_Document $document)
    {
        $this->_document = $document;
    }
    
    /**
     * Get the document instance
     *
     * @return SetaPDF_Core_Document
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * Release cycled references / memory
     */
    public function cleanUp()
    {
        if (null !== $this->_viewerPreferences) {
            $this->_viewerPreferences->cleanUp();
            $this->_viewerPreferences = null;
        }

        if (null !== $this->_pages) {
            $this->_pages->cleanUp();
            $this->_pages = null;
        }

        if (null !== $this->_names) {
            $this->_names->cleanUp();
            $this->_names = null;
        }

        if (null !== $this->_pageLabels) {
            $this->_pageLabels->cleanUp();
            $this->_pageLabels = null;
        }

        if (null !== $this->_acroForm) {
            $this->_acroForm->cleanUp();
            $this->_acroForm = null;
        }

        if (null !== $this->_outlines) {
            $this->_outlines->cleanUp();
            $this->_outlines = null;
        }

        if (null !== $this->_outputIntents) {
            $this->_outputIntents->cleanUp();
            $this->_outputIntents = null;
        }

        $this->_document = null;
    }

    /**
     * Get the catalog dictionary
     *
     * @param boolean $create
     * @return null|SetaPDF_Core_Type_Dictionary
     */
    public function getDictionary($create = false)
    {
        $trailer = $this->_document->getTrailer();
        if (!$trailer->offsetExists('Root')) {
            if (false === $create)
                return null;

            SetaPDF_Core_SecHandler::checkPermission($this->_document, SetaPDF_Core_SecHandler::PERM_MODIFY);

            $trailer->offsetSet('Root', $this->_document->createNewObject(
                new SetaPDF_Core_Type_Dictionary(array(
                    'Type' => new SetaPDF_Core_Type_Name('Catalog', true)
                ))
            ));
        }

        return $trailer->offsetGet('Root')->ensure();
    }

    /**
     * Get the page layout
     *
     * See PDF 32000-1:2008 - 7.7.2 Document Catalog
     *
     * @return string
     */
    public function getPageLayout()
    {
        $catalog = $this->getDictionary();
        if (
            null === $catalog
            || !$catalog->offsetExists('PageLayout')
        ) {
            return SetaPDF_Core_Document_PageLayout::SINGLE_PAGE;
        }

        return $catalog->getValue('PageLayout')->getValue();
    }

    /**
     * Set the page layout
     *
     * See PDF 32000-1:2008 - 7.7.2 Document Catalog
     *
     * @TODO Check for valid values
     * @param string $pageLayout
     */
    public function setPageLayout($pageLayout)
    {
        $catalog = $this->getDictionary(true);
        $catalog->offsetSet('PageLayout', new SetaPDF_Core_Type_Name($pageLayout));

        switch ($pageLayout) {
            case SetaPDF_Core_Document_PageLayout::TWO_PAGE_LEFT:
            case SetaPDF_Core_Document_PageLayout::TWO_PAGE_RIGHT:
                $this->getDocument()->setMinPdfVersion('1.5');
        }
    }

    /**
     * Get the page mode
     *
     * See PDF 32000-1:2008 - 7.7.2 Document Catalog
     *
     * @return string
     */
    public function getPageMode()
    {
        $catalog = $this->getDictionary();
        if (
            null === $catalog
            || !$catalog->offsetExists('PageMode')
        ) {
            return SetaPDF_Core_Document_PageMode::USE_NONE;
        }

        return $catalog->getValue('PageMode')->getValue();
    }

    /**
     * Set page mode
     *
     * See PDF 32000-1:2008 - 7.7.2 Document Catalog
     *
     * @todo Check for valid values
     * @param string $pageMode
     */
    public function setPageMode($pageMode)
    {
        $catalog = $this->getDictionary(true);
        $catalog->offsetSet('PageMode', new SetaPDF_Core_Type_Name($pageMode));

        switch ($pageMode) {
            case SetaPDF_Core_Document_PageMode::USE_OC:
                $this->getDocument()->setMinPdfVersion('1.5');
                break;
            case SetaPDF_Core_Document_PageMode::USE_ATTACHMENTS:
                $this->getDocument()->setMinPdfVersion('1.6');
                break;
        }
    }

    /**
     * Get the metadata stream
     *
     * @return null|string null if no document metadata are available, a string if the desired structure if available
     */
    public function getMetadata()
    {
        $catalog = $this->getDictionary();

        if (
            null === $catalog ||
            !$catalog->offsetExists('Metadata')
        ) {
            return null;
        }

        return $catalog->getValue('Metadata')->ensure()->getStream();
    }

    /**
     * Set the metadata stream
     *
     * @param string $metadata
     * @TODO Automatically remove the XML declaration in the first line
     */
    public function setMetadata($metadata)
    {
        $catalog = $this->getDictionary(true);
        $metadataExists = $catalog->offsetExists('Metadata');

        if (!$metadataExists && $metadata !== null) {
            $stream = new SetaPDF_Core_Type_Stream();
            $streamDictionary = new SetaPDF_Core_Type_Dictionary();
            $streamDictionary->offsetSet('Type', new SetaPDF_Core_Type_Name('Metadata', true));
            $streamDictionary->offsetSet('Subtype', new SetaPDF_Core_Type_Name('XML', true));
            $stream->setValue($streamDictionary);

            $catalog->offsetSet('Metadata', $this->_document->createNewObject($stream));
        }

        if ($metadata !== null) {
            $stream = $catalog->getValue('Metadata')->ensure();
            $stream->setStream($metadata);

        } else if ($metadataExists) {
            $streamReference = $catalog->getValue('Metadata');
            $this->getDocument()->deleteObject($streamReference->getValue());
            $catalog->offsetUnset('Metadata');
        }
    }

    /**
     * Get the base URI that shall be used in resolving relative URI references.
     *
     * URI actions within the document may specify URIs in partial form, to be
     * interpreted relative to this base address. If no base URI is specified,
     * such partial URIs shall be interpreted relative to the location of the
     * document itself.
     *
     * @return null|string
     */
    public function getBaseUri()
    {
        $catalog = $this->getDictionary();
        if (
            null === $catalog ||
            !$catalog->offsetExists('URI')
        ) {
            return null;
        }

        $uriDict = $catalog->offsetGet('URI')->ensure();

        return $uriDict->getValue('Base')->getValue();
    }

    /**
     * Set the base URI
     *
     * @see SetaPDF_FormFiller::getBaseUri()
     * @param string $uri
     * @return void
     */
    public function setBaseUri($uri)
    {
        $catalog = $this->getDictionary(true);

        if (!$catalog->offsetExists('URI')) {
            $catalog->offsetSet('URI', new SetaPDF_Core_Type_Dictionary());
        }

        $uriDict = $catalog->offsetGet('URI')->ensure();
        $uriDict->offsetSet('Base', new SetaPDF_Core_Type_String($uri));
    }

    /**
     * Get a viewer preferences object
     *
     * @return SetaPDF_Core_Document_Catalog_ViewerPreferences
     */
    public function getViewerPreferences()
    {
        if (null === $this->_viewerPreferences) {
            $this->_viewerPreferences = new SetaPDF_Core_Document_Catalog_ViewerPreferences($this);
        }

        return $this->_viewerPreferences;
    }

    /**
     * Get a pages object from the document
     *
     * @return SetaPDF_Core_Document_Catalog_Pages
     */
    public function getPages()
    {
        if (null === $this->_pages) {
            $this->_pages = new SetaPDF_Core_Document_Catalog_Pages($this);
        }

        return $this->_pages;
    }

    /**
     * Get a names object from the document
     *
     * @return SetaPDF_Core_Document_Catalog_Names
     */
    public function getNames()
    {
        if (null === $this->_names) {
            $this->_names = new SetaPDF_Core_Document_Catalog_Names($this);
        }

        return $this->_names;
    }

    /**
     * Get the documents page labels object
     *
     * @return SetaPDF_Core_Document_Catalog_PageLabels
     */
    public function getPageLabels()
    {
        if (null === $this->_pageLabels) {
            $this->_pageLabels = new SetaPDF_Core_Document_Catalog_PageLabels($this);
        }

        return $this->_pageLabels;
    }

    /**
     * Get the documents AcroForm object
     *
     * This method resolves or creates the AcroForm dictionary and returns it.
     *
     * @return SetaPDF_Core_Document_Catalog_AcroForm
     */
    public function getAcroForm()
    {
        if (null === $this->_acroForm) {
            $this->_acroForm = new SetaPDF_Core_Document_Catalog_AcroForm($this);
        }

        return $this->_acroForm;
    }

    /**
     * Get the documents outline object
     *
     * @return SetaPDF_Core_Document_Catalog_Outlines
     */
    public function getOutlines()
    {
        if (null === $this->_outlines)
            $this->_outlines = new SetaPDF_Core_Document_Catalog_Outlines($this);

        return $this->_outlines;
    }
    
    /**
     * Get the documents optional content object
     *
     * @return SetaPDF_Core_Document_Catalog_OptionalContent
     */
    public function getOptionalContent()
    {
        if (null === $this->_optionalContent)
            $this->_optionalContent = new SetaPDF_Core_Document_Catalog_OptionalContent($this);
    
        return $this->_optionalContent;
    }

    public function getOutputIntents()
    {
        if (null === $this->_outputIntents)
            $this->_outputIntents = new SetaPDF_Core_Document_Catalog_OutputIntents($this);

        return $this->_outputIntents;
    }

    public function getOpenAction()
    {
        $dictionary = $this->getDictionary();
        if (!$dictionary || !$dictionary->offsetExists('OpenAction'))
            return null;

        $openActionValue = $dictionary->getValue('OpenAction');
        $openAction = $openActionValue->ensure(true);
        if ($openAction instanceof SetaPDF_Core_Type_Array) {
            return new SetaPDF_Core_Document_Destination($openActionValue);
        } elseif ($openAction instanceof SetaPDF_Core_Type_Dictionary) {
            return SetaPDF_Core_Document_Action::byObjectOrDictionary($openActionValue);
        }

        throw new SetaPDF_Core_Exception('Unsupported OpenAction type: ' . get_class($openAction));
    }

    public function setOpenAction($openAction)
    {
        if (!($openAction instanceof SetaPDF_Core_Document_Destination) &&
            !($openAction instanceof SetaPDF_Core_Document_Action)
        ) {
            throw new InvalidArgumentException(
                'Open action parameter has to be an instance of SetaPDF_Core_Document_Destination or ' .
                'SetaPDF_Core_Document_Action'
            );
        }

        $dictionary = $this->getDictionary(true);
        $dictionary->offsetSet('OpenAction', $openAction->getPdfValue());
    }
}