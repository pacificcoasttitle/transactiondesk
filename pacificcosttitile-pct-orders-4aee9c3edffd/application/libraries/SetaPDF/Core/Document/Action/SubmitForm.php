<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id$
 */

/**
 * Class representing a submit-form action
 *
 * See PDF 32000-1:2008 - 12.7.5.2 Submit-Form Action
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Action_SubmitForm extends SetaPDF_Core_Document_Action
{
    /**#@+
     * Action flags
     */
    const FLAG_EXCLUDE = 0x01; // 1
    const FLAG_INCLUDE_NO_VALUE_FIELDS = 0x02; // 2
    const FLAG_EXPORT_FORMAT = 0x04; // 3
    const FLAG_GET_METHOD = 0x08; // 4
    const FLAG_SUMBIT_COORDINATES = 0x10; // 5
    const FLAG_XFDF = 0x20; // 6
    const FLAG_INCLUDE_APPEND_SAVES = 0x40; // 7
    const FLAG_INCLUDE_ANNOTATIONS = 0x80; // 8
    const FLAG_SUBMIT_PDF = 0x100; // 9
    const FLAG_CONONICAL_FORMAT = 0x400; // 10
    const FLAG_EXCL_NON_USER_ANNOTS = 0x800; // 11
    const FLAG_EXCL_FKEY = 0x1000; // 12
    const FLAG_EMBED_FORM = 0x4000; // 14
    /**#@-*/

    /**
     * Create a Named Action dictionary
     *
     * @param string| $fileSpecification
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createActionDictionary($fileSpecification)
    {
        $dictionary = new SetaPDF_Core_Type_Dictionary();
        $dictionary->offsetSet('S', new SetaPDF_Core_Type_Name('SubmitForm', true));

        if (!$fileSpecification instanceof SetaPDF_Core_FileSpecification)
            $fileSpecification = new SetaPDF_Core_FileSpecification($fileSpecification);

        $dictionary->offsetSet('F', $fileSpecification->getDictionary());

        return $dictionary;
    }

    /**
     * The constructor
     *
     * @param string|SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_IndirectObjectInterface $objectOrDictionary
     * @throws InvalidArgumentException
     */
    public function __construct($objectOrDictionary)
    {
        $dictionary = $objectOrDictionary instanceof SetaPDF_Core_Type_Abstract
            ? $objectOrDictionary->ensure(true)
            : $objectOrDictionary;

        if (!($dictionary instanceof SetaPDF_Core_Type_Dictionary)) {
            $dictionary = $objectOrDictionary = self::createActionDictionary($dictionary);
        }

        if (!$dictionary->offsetExists('S') || $dictionary->getValue('S')->getValue() !== 'SubmitForm') {
            throw new InvalidArgumentException('The S entry in a submit-form action shall be "SubmitForm".');
        }

        if (!$dictionary->offsetExists('F')) {
            throw new InvalidArgumentException('Missing or incorrect type of F entry in subnit-form action dictionary.');
        }

        parent::__construct($objectOrDictionary);
    }

    /**
     * Get the file specification object
     *
     * @return SetaPDF_Core_FileSpecification
     */
    public function getFileSpecification()
    {
        if (!$this->_actionDictionary->offsetExists('F'))
            return null;

        return new SetaPDF_Core_FileSpecification($this->_actionDictionary->getValue('F')->ensure(true));
    }

    /**
     * Seta a file specification object
     *
     * @param string|SetaPDF_Core_FileSpecification $fileSpecification
     */
    public function setFileSpecification($fileSpecification)
    {
        if (!$fileSpecification instanceof SetaPDF_Core_FileSpecification)
            $fileSpecification = new SetaPDF_Core_FileSpecification($fileSpecification);

        $this->_actionDictionary->offsetSet('F', $fileSpecification->getDictionary());
    }

    /**
     * Set which fields to include in the submission or which to exclude, depending on the setting of the Include/Exclude flag
     *
     * @see setFlags()
     * @param array $fields An array of fully qualified names or an indirect object to a field dictionary
     * @param string $encoding
     */
    public function setFields(array $fields = null, $encoding = 'UTF-8')
    {
        if (null === $fields) {
            $this->_actionDictionary->offsetUnset('Fields');
            return;
        }

        $array = new SetaPDF_Core_Type_Array();
        foreach ($fields AS $fieldname) {
            if ($fieldname instanceof SetaPDF_Core_Type_IndirectObjectInterface) {
                $array[] = $fieldname;
            } else {
                $array[] = new SetaPDF_Core_Type_String(SetaPDF_Core_Encoding::toPdfString($fieldname, $encoding));
            }
        }

        $this->_actionDictionary->offsetSet('Fields', $array);
    }

    /**
     * Get the fields to include or exclude in the submission
     *
     * @param string $encoding
     * @return array|null An array of field names in the specific encoding
     */
    public function getFields($encoding = 'UTF-8')
    {
        if (!$this->_actionDictionary->offsetExists('Fields')) {
            return null;
        }

        $fieldnames = array();
        $array = $this->_actionDictionary->getValue('Fields')->ensure();
        foreach ($array AS $field) {
            if ($field instanceof SetaPDF_Core_Type_StringValue) {
                $fieldnames[] = SetaPDF_Core_Encoding::convertPdfString($field->getValue(), $encoding);
            } elseif ($field instanceof SetaPDF_Core_Type_IndirectObjectInterface) {
                $field = $field->ensure();
                $fieldname = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($field);
                $fieldnames[] = SetaPDF_Core_Encoding::convert($fieldname, 'UTF-8', $encoding);
            }
        }

        return $fieldnames;
    }

    /**
     * Sets a flag or flags
     *
     * @param integer $flags
     * @param boolean|null $add Add = true, remove = false, set = null
     */
    public function setFlags($flags, $add = true)
    {
        if (false === $add) {
            $this->unsetFieldFlags($flags);
            return;
        }

        $value = $this->_actionDictionary->getValue('Flags');
        if (null === $value) {
            $this->_actionDictionary->offsetSet('Flags', new SetaPDF_Core_Type_Numeric($flags));
        } else {
            if ($add === true) {
                $value->setValue($value->getValue() | $flags);
            } else {
                $value->setValue($flags);
            }
        }
    }

    /**
     * Removes a flag or flags
     *
     * @param integer $flags
     */
    public function unsetFlags($flags)
    {
        $value = $this->_actionDictionary->getValue('Flags');
        if (null === $value)
            return;

        $value->setValue($value->getValue() & ~$flags);
    }

    /**
     * Returns the current flags
     *
     * @return integer
     */
    public function getFlags()
    {
        $value = $this->_actionDictionary->getValue('Flags');
        if (null === $value) {
            return 0;
        }

        return $value->getValue();
    }

    /**
     * Checks if a specific flag is set
     *
     * @param integer $flag
     * @return boolean
     */
    public function isFlagSet($flag)
    {
        return ($this->getFlags() & $flag) !== 0;
    }

}