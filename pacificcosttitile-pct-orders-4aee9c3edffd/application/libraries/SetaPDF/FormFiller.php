<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: FormFiller.php 504 2013-06-13 12:30:39Z jan.slabon $
 */

/**
 * The main class of the SetaPDF-FormFiller Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_FormFiller
{
    /**
     * Version
     *
     * @var string
     */
    const VERSION = '2.1.1.523';

    /**
     * The document instance
     *
     * @var SetaPDF_Core_Document
     */
    protected $_document;

    /**
     * The object representing the form fields collection
     *
     * @var SetaPDF_FormFiller_Fields
     */
    protected $_fields;

    /**
     * Flag defining how to handle XFA information if found
     *
     * @var boolean
     */
    protected $_removeXfaInformation = false;

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_FormFiller
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
     * Get the fields collection object
     *
     * @return SetaPDF_FormFiller_Fields
     */
    public function getFields()
    {
        if (null === $this->_fields)
            $this->_fields = new SetaPDF_FormFiller_Fields($this);

        return $this->_fields;
    }


    /**
     * Get the AcroForm object from the document
     *
     * This method resolves or creates the AcroForm dictionary and returns it.
     * This method is normally only used internally
     *
     * @return bool|SetaPDF_Core_Type_Dictionary
     * @throws SetaPDF_FormFiller_Exception_Xfa
     */
    public function getAcroForm()
    {
        $acroForm = $this->getDocument()->getCatalog()->getAcroForm();

        $dictionary = $acroForm->getDictionary();
        if ($dictionary !== false && $dictionary->offsetExists('XFA')) {
            if ($this->_removeXfaInformation === false) {
                throw new SetaPDF_FormFiller_Exception_Xfa(
                    'The document is a XFA form and the removeXfaInformation-flag is ' .
                        'not set. It is not possible to handle this type of form atm.'
                );
            }

            $dictionary->offsetUnset('XFA');
        }

        return false === $dictionary
            ? false
            : $acroForm;
    }

    /**
     * Checks if the NeedAppearances flag is set or not
     *
     * @return boolean
     */
    public function isNeedAppearancesSet()
    {
        return $this->_document->getCatalog()->getAcroForm()->isNeedAppearancesSet();
    }

    /**
     * Set the NeedAppearances flag
     *
     * This flag inidcates the viewer to rerender the form field appearances
     *
     * @param boolean $needAppearances
     * @return void
     */
    public function setNeedAppearances($needAppearances = true)
    {
        $this->_document->getCatalog()->getAcroForm()->setNeedAppearances($needAppearances);
    }

    /**
     * Set the flag for handling XFA information
     *
     * @param boolean $removeXfaInformation
     */
    public function setRemoveXfaInformation($removeXfaInformation)
    {
        $this->_removeXfaInformation = (boolean)$removeXfaInformation;
    }

    /**
     * Get the flag how to handling XFA information
     *
     * @return boolean
     */
    public function getRemoveXfaInformation()
    {
        return $this->_removeXfaInformation;
    }

    /**
     * Save the document
     *
     * A proxy method which will forward the save()-call to the document instance.
     * This method will automatically update the modification date and the producer property
     * in the Info dictionary (@see SetaPDF_Core_Document_Info).
     *
     * @see SetaPDF_Core_Document::save()
     * @param boolean $update Update the document or rewrite it completely
     */
    public function save($update = true)
    {
        $acroForm = $this->getDocument()->getCatalog()->getAcroForm();

        $dictionary = $acroForm->getDictionary();
        if ($dictionary !== false && $dictionary->offsetExists('XFA') && $this->_removeXfaInformation === true) {
            $dictionary->offsetUnset('XFA');
        }

        $info = $this->_document->getInfo();
        $info->setModDate(new SetaPDF_Core_DataStructure_Date());
        $info->setProducer('SetaPDF-FormFiller Component v' . self::VERSION . ' ©Setasign 2005-' . date('Y') . ' (www.setasign.com)');

        $this->_document->save($update);
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
        if (null !== $this->_fields)
            $this->_fields->cleanUp();
        $this->_fields = null;

        /* This should be called manually to let different
         * instances work on the document
         */
        // $this->_document->cleanUp();
        $this->_document = null;
    }
}