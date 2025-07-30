<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Form.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Class representing a file specification
 *
 * @see PDF 32000-1:2008 - 7.11.2File Specification Strings
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_FileSpecification
{
    /**
     * The dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_dictionary;

    static public function createDictionary($fileSpecificationString)
    {
        return new SetaPDF_Core_Type_Dictionary(array(
            'Type' => new SetaPDF_Core_Type_String('Filespec', true),
            'F' => new SetaPDF_Core_Type_String($fileSpecificationString)
        ));
    }

    /**
     * The constructor
     *
     * @param $fileSpecification
     */
    public function __construct($fileSpecification)
    {
        if (!$fileSpecification instanceof SetaPDF_Core_Type_Dictionary) {
            $fileSpecification = self::createDictionary($fileSpecification);
        }

        $this->_dictionary = $fileSpecification;
    }

    /**
     * Get the dictionary
     *
     * @return SetaPDF_Core_Type_Dictionary
     */
    public function getDictionary()
    {
        return $this->_dictionary;
    }

    /**
     * Get the file specification value
     *
     * @return string|null
     */
    public function getFileSpecification()
    {
        if (!$this->_dictionary->offsetExists('F'))
            return null;

        return $this->_dictionary->getValue('F')->ensure(true)->getValue();
    }

    /**
     * Set the file specification value
     *
     * @param string|null string|null
     */
    public function setFileSpecification($fileSpecification)
    {
        if (null === $fileSpecification) {
            $this->_dictionary->offsetUnset('F');
            return;
        }

        $this->_dictionary->offsetSet('F', new SetaPDF_Core_Type_String($fileSpecification));
    }

    /**
     * Get the unicode text file specification value
     *
     * @return string|null
     */
    public function getUnicodeFileSpecification()
    {
        if (!$this->_dictionary->offsetExists('UF'))
            return null;

        return $this->_dictionary->getValue('UF')->ensure(true)->getValue();
    }

    /**
     * Set the unicode text file specification value
     *
     * @param string|null $fileSpecification
     */
    public function setUnicodeFileSpecification($fileSpecification)
    {
        if (null === $fileSpecification) {
            $this->_dictionary->offsetUnset('UF');
            return;
        }

        $this->_dictionary->offsetSet('UF', new SetaPDF_Core_Type_String($fileSpecification));
    }

    /**
     * Get the volatile flag
     *
     * @return boolean
     */
    public function getVolatile()
    {
        if (!$this->_dictionary->offsetExists('V'))
            return false;

        return $this->_dictionary->getValue('V')->ensure(true)->getValue();
    }

    /**
     * Set the volatile flag
     *
     * @param boolean|null $volatile
     */
    public function setVolatile($volatile)
    {
        if (null === $volatile) {
            $this->_dictionary->offsetUnset('V');
            return;
        }

        $this->_dictionary->offsetSet('V', new SetaPDF_Core_Type_Boolean($volatile));
    }
}