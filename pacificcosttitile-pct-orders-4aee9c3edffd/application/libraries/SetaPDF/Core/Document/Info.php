<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Info.php 405 2013-02-26 10:05:18Z jan.slabon $
 */

/**
 * Class for handling the documents info dictionary
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Info
{
    /**#@+
     * Value for the Trapped property
     *
     * @see setTrapped
     * @var string
     */
    const TRAPPED_TRUE = 'True';
    const TRAPPED_FALSE = 'False';
    const TRAPPED_UNKNOWN = 'Unknown';
    /**#@-*/

    /**
     * The document instance
     *
     * @var SetaPDF_Core_Document
     */
    protected $_document;

    /**
     * The consturctor
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
     * Release memory
     */
    public function cleanUp()
    {
        // Empty body
    }

    /**
     * Get the document’s title.
     *
     * @param string $encoding
     * @return string|null
     */
    public function getTitle($encoding = 'UTF-8')
    {
        return $this->_getStringValue('Title', $encoding);
    }

    /**
     * Set the document’s title.
     *
     * @param string $title
     * @param string $encoding
     * @return SetaPDF_Core_Document_Info
     */
    public function setTitle($title, $encoding = 'UTF-8')
    {
        $this->_setStringValue('Title', $title, $encoding);

        return $this;
    }

    /**
     * Get the name of the person who created the document
     *
     * @param string $encoding
     * @return string
     */
    public function getAuthor($encoding = 'UTF-8')
    {
        return $this->_getStringValue('Author', $encoding);
    }

    /**
     * Set the name of the person who created the document
     *
     * @param string $author
     * @param string $encoding
     * @return SetaPDF_Core_Document_Info
     */
    public function setAuthor($author, $encoding = 'UTF-8')
    {
        $this->_setStringValue('Author', $author, $encoding);

        return $this;
    }

    /**
     * Get the subject of the document
     *
     * @param string $encoding
     * @return string
     */
    public function getSubject($encoding = 'UTF-8')
    {
        return $this->_getStringValue('Subject', $encoding);
    }

    /**
     * Set the subject of the document
     *
     * @param string $subject
     * @param string $encoding
     * @return SetaPDF_Core_Document_Info
     */
    public function setSubject($subject, $encoding = 'UTF-8')
    {
        $this->_setStringValue('Subject', $subject, $encoding);

        return $this;
    }

    /**
     * Get keywords associated with the document
     *
     * @param string $encoding
     * @return string
     */
    public function getKeywords($encoding = 'UTF-8')
    {
        return $this->_getStringValue('Keywords', $encoding);
    }

    /**
     * Set keywords associated with the document
     *
     * @param string $keywords
     * @param string $encoding
     * @return SetaPDF_Core_Document_Info
     */
    public function setKeywords($keywords, $encoding = 'UTF-8')
    {
        $this->_setStringValue('Keywords', $keywords, $encoding);

        return $this;
    }

    /**
     * Get the name of the product that created the original document from which it was converted
     *
     * @param string $encoding
     * @return string
     */
    public function getCreator($encoding = 'UTF-8')
    {
        return $this->_getStringValue('Creator', $encoding);
    }

    /**
     * Set the name of the product that created the original document from which it was converted
     *
     * @param string $creator
     * @param string $encoding
     * @return SetaPDF_Core_Document_Info
     */
    public function setCreator($creator, $encoding = 'UTF-8')
    {
        $this->_setStringValue('Creator', $creator, $encoding);

        return $this;
    }

    /**
     * Get the name of the product that converted the original document to PDF
     *
     * @param string $encoding
     * @return string
     */
    public function getProducer($encoding = 'UTF-8')
    {
        return $this->_getStringValue('Producer', $encoding);
    }

    /**
     * Set the name of the product that converted the original document to PDF
     *
     * @param string $producer
     * @param string $encoding
     * @return SetaPDF_Core_Document_Info
     */
    public function setProducer($producer, $encoding = 'UTF-8')
    {
        $this->_setStringValue('Producer', $producer, $encoding);

        return $this;
    }

    /**
     * Get the date and time the document was created
     *
     * @param boolean $asString
     * @return null|string|SetaPDF_Core_DataStructure_Date
     */
    public function getCreationDate($asString = true)
    {
        $dictionary = $this->getInfoDictionary();
        if (null === $dictionary ||
            !$dictionary->offsetExists('CreationDate')
        )
            return null;

        if (true === $asString) {
            return $dictionary->getValue('CreationDate')->ensure()->getValue();
        }

        return new SetaPDF_Core_DataStructure_Date($dictionary->getValue('CreationDate')->ensure());
    }

    /**
     * Set the date and time the document was created
     *
     * @param string|SetaPDF_Core_DataStructure_Date $date
     * @return SetaPDF_Core_Document_Info
     */
    public function setCreationDate($date)
    {
        SetaPDF_Core_SecHandler::checkPermission($this->_document, SetaPDF_Core_SecHandler::PERM_MODIFY);

        $dictionary = $this->getInfoDictionary($date !== null);

        if ($dictionary === null)
            return $this;

        if ($date === null) {
            $dictionary->offsetUnset('CreationDate');

        } else {
            if (!($date instanceof SetaPDF_Core_DataStructure_Date))
                $date = new SetaPDF_Core_DataStructure_Date(new SetaPDF_Core_Type_String($date));

            $dictionary->offsetSet('CreationDate', $date->getValue());
        }

        return $this;
    }

    /**
     * Get the date and time the document was most recently modified
     *
     * @param bool $asString
     * @return null|SetaPDF_Core_DataStructure_Date
     */
    public function getModDate($asString = true)
    {
        $dictionary = $this->getInfoDictionary();
        if (null === $dictionary ||
            !$dictionary->offsetExists('ModDate')
        )
            return null;

        if (true === $asString) {
            return $dictionary->getValue('ModDate')->ensure()->getValue();
        }

        return new SetaPDF_Core_DataStructure_Date($dictionary->getValue('ModDate')->ensure());
    }

    /**
     * Set the date and time the document was most recently modified
     *
     * @param string|SetaPDF_Core_DataStructure_Date $date
     * @return SetaPDF_Core_Document_Info
     */
    public function setModDate($date)
    {
        SetaPDF_Core_SecHandler::checkPermission($this->_document, SetaPDF_Core_SecHandler::PERM_MODIFY);

        $dictionary = $this->getInfoDictionary($date !== null);

        if ($dictionary === null)
            return $this;

        if ($date === null) {
            $dictionary->offsetUnset('ModDate');

        } else {
            if (!($date instanceof SetaPDF_Core_DataStructure_Date))
                $date = new SetaPDF_Core_DataStructure_Date(new SetaPDF_Core_Type_String($date));

            $dictionary->offsetSet('ModDate', $date->getValue());
        }

        return $this;
    }

    /**
     * Get information whether the document has been modified to include trapping information
     *
     * @param boolean $default
     * @return string
     */
    public function getTrapped($default = true)
    {
        $dictionary = $this->getInfoDictionary();
        if (null === $dictionary ||
            !$dictionary->offsetExists('Trapped')
        ) {
            return $default ? self::TRAPPED_UNKNOWN : null;
        }
        
        return $dictionary->getValue('Trapped')->ensure()->getValue();
    }

    /**
     * Set information whether the document has been modified to include trapping information
     *
     * Pass null to remove this entry from the info dictionary
     *
     * @param string $trapped
     * @return SetaPDF_Core_Document_Info
     */
    public function setTrapped($trapped)
    {
        SetaPDF_Core_SecHandler::checkPermission($this->_document, SetaPDF_Core_SecHandler::PERM_MODIFY);

        $dictionary = $this->getInfoDictionary($trapped !== null);
        if ($dictionary === null)
            return $this;

        if ($trapped === null) {
            $dictionary->offsetUnset('Trapped');
        } else {
            $dictionary->offsetSet('Trapped', new SetaPDF_Core_Type_Name($trapped));
        }

        return $this;
    }

    /**
     * Get a custom metadata value
     *
     * @param string $name
     * @param string $encoding
     * @return null|string
     */
    public function getCustomMetadata($name, $encoding = 'UTF-8')
    {
        return $this->_getStringValue($name, $encoding);
    }

    /**
     * Set custom metadata
     *
     * Pass $value as null to remove this entry from the info dictionary
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     * @throws InvalidArgumentException
     * @return SetaPDF_Core_Document_Info
     */
    public function setCustomMetadata($name, $value, $encoding = 'UTF-8')
    {
        switch ($name) {
            case 'Title':
            case 'Author':
            case 'Subject':
            case 'Keywords':
            case 'Creator':
            case 'Producer':
            case 'CreationDate':
            case 'ModDate':
            case 'Trapped':
                throw new InvalidArgumentException('Key (%s) cannot be used as custom metadata.');
        }

        $this->_setStringValue($name, $value, $encoding);

        return $this;
    }

    /**
     * Get all data from the info dictionary
     *
     * @param string $encoding
     * @return array
     */
    public function getAll($encoding = 'UTF-8')
    {
        $dictionary = $this->getInfoDictionary(true);
        $data = array();
        foreach ($dictionary AS $name => $value) {
            switch ($name) {
                case 'CreationDate':
                case 'ModDate':
                case 'Trapped':
                    $method = 'get' . $name;
                    $data[$name] = $this->$method();
                    continue;
                default:
                    $value = $value->ensure();
                    if ($value instanceof SetaPDF_Core_Type_StringValue)
                        $data[$name] = SetaPDF_Core_Encoding::convertPdfString($value->getValue(), $encoding);
                    else
                        $data[$name] = $value->toPhp();
            }
        }

        return $data;
    }

    /**
     * Get all custom metadata
     *
     * @param string $encoding
     * @return array
     */
    public function getAllCustomMetadata($encoding = 'UTF-8')
    {
        $dictionary = $this->getInfoDictionary(true);
        $data = array();
        foreach ($dictionary AS $name => $value) {
            switch ($name) {
                case 'Title':
                case 'Author':
                case 'Subject':
                case 'Keywords':
                case 'Creator':
                case 'Producer':
                case 'CreationDate':
                case 'ModDate':
                case 'Trapped':
                    continue;
                default:
                    $value = $value->ensure();
                    if ($value instanceof SetaPDF_Core_Type_StringValue)
                        $data[$name] = SetaPDF_Core_Encoding::convertPdfString($value->getValue(), $encoding);
                    else
                        $data[$name] = $value->toPhp();
            }
        }

        return $data;
    }

    /**
     * Get and/or creates the info dictionary
     *
     * @param boolean $create
     * @return SetaPDF_Core_Type_Dictionary
     */
    public function getInfoDictionary($create = false)
    {
        $trailer = $this->getDocument()->getTrailer();

        if (!$trailer->offsetExists('Info')) {
            if (false === $create)
                return null;

            $object = $this->getDocument()->createNewObject(new SetaPDF_Core_Type_Dictionary());
            $trailer->offsetSet('Info', $object);
        }

        return $trailer->offsetGet('Info')->ensure();
    }

    /**
     * Get a string value from the info dictionary
     *
     * @param string $name
     * @param string $encoding
     * @return null|string
     */
    protected function _getStringValue($name, $encoding)
    {
        $dictionary = $this->getInfoDictionary();
        if (null === $dictionary || !$dictionary->offsetExists($name))
            return null;

        return SetaPDF_Core_Encoding::convertPdfString($dictionary->getValue($name)->ensure()->getValue(), $encoding);
    }

    /**
     * Set a string value in the info dictionary
     *
     * @param string $name
     * @param string $value
     * @param string $encoding
     */
    protected function _setStringValue($name, $value, $encoding)
    {
        SetaPDF_Core_SecHandler::checkPermission($this->_document, SetaPDF_Core_SecHandler::PERM_MODIFY);

        $dictionary = $this->getInfoDictionary($value !== null);

        if ($dictionary === null)
            return;

        if ($value === null) {
            $dictionary->offsetUnset($name);

        } else {

            $dictionary->offsetSet($name, new SetaPDF_Core_Type_String(
                SetaPDF_Core_Encoding::toPdfString($value, $encoding)
            ));
        }
    }
}