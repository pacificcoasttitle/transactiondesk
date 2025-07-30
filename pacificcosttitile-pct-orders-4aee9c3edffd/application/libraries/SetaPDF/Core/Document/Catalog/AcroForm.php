<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: AcroForm.php 508 2013-06-25 10:11:55Z jan.slabon $
 */

/**
 * Class representing a basic AcroForm
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Catalog_AcroForm
{
    /**
     * The documents catalog instance
     *
     * @var SetaPDF_Core_Document
     */
    protected $_catalog;

    /**
     * The AcroForm dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_dictionary;

    /**
     * Resolves the qualified name of a form field.
     *
     * @param SetaPDF_Core_Type_Dictionary $terminalFieldDictionary The terminal field of the form field
     * @return string
     */
    static public function resolveFieldName(SetaPDF_Core_Type_Dictionary $terminalFieldDictionary)
    {
        $names = array();

        $p = $terminalFieldDictionary;
        while (false !== $p) {
            if ($p->offsetExists('T'))
                $names[] = $p->getValue('T')->ensure()->getValue();

            if ($p->offsetExists('Parent')) {
                $p = $p->getValue('Parent')->ensure();
            } else {
                $p = false;
            }
        }

        $names = array_map(array('SetaPDF_Core_Encoding', 'convertPdfString'), $names);

        return join('.', array_reverse($names));
    }

    /**
     * The constructor
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

    /**
     * Release cycled references
     */
    public function cleanUp()
    {
        $this->_dictionary = null;
        $this->_catalog = null;
    }

    /**
     * Get and creates the AcroForm dictionary
     *
     * @param boolean $create
     * @return boolean|SetaPDF_Core_Type_Dictionary
     */
    public function getDictionary($create = false)
    {
        $root = $this->getDocument()->getCatalog()->getDictionary($create);
        if ($root) {
            if ($root->offsetExists('AcroForm')) {
                try {
                    $this->_dictionary = $root->offsetGet('AcroForm')->ensure(true);
                } catch (SetaPDF_Core_Type_IndirectReference_Exception $e) {
                    // In that case the AcroForm could not be resolved
                }
            }

            if (true === $create && $this->_dictionary === null) {
                $this->_dictionary = new SetaPDF_Core_Type_Dictionary();
                $object = $this->getDocument()->createNewObject($this->_dictionary);
                $root->offsetSet('AcroForm', $object);
            }
        }

        return null === $this->_dictionary
            ? false
            : $this->_dictionary;
    }

    /**
     * Get and creates the fields array
     *
     * @param boolean $create
     * @return SetaPDF_Core_Type_Array
     */
    public function getFieldsArray($create = false)
    {
        $dictionary = $this->getDictionary($create);
        if (false === $dictionary)
            return false;

        if (false === $dictionary->offsetExists('Fields')) {
            $fields = new SetaPDF_Core_Type_Array();
            $dictionary->offsetSet('Fields', $fields);
            return $fields;
        }

        return $dictionary->getValue('Fields')->ensure();
    }

    /**
     * Get and creates the calculation order array
     *
     * @param boolean $create
     * @return SetaPDF_Core_Type_Array
     */
    public function getCalculationOrderArray($create = false)
    {
        $dictionary = $this->getDictionary($create);
        if (false === $dictionary)
            return false;

        if (false === $dictionary->offsetExists('CO')) {
            $co = new SetaPDF_Core_Type_Array();
            $dictionary->offsetSet('CO', $co);
            return $co;
        }

        return $dictionary->getValue('CO')->ensure();
    }

    /**
     * Checks if the NeedAppearances flag is set or not
     *
     * @return boolean
     */
    public function isNeedAppearancesSet()
    {
        $dictionary = $this->getDictionary();
        if ($dictionary && $dictionary->offsetExists('NeedAppearances')) {
            return $dictionary->offsetGet('NeedAppearances')->ensure()->getValue();
        }

        return false;
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
        if ($needAppearances == $this->isNeedAppearancesSet())
            return;

        $dictionary = $this->getDictionary(true);
        $dictionary->offsetSet('NeedAppearances', new SetaPDF_Core_Type_Boolean($needAppearances));
    }

    /**
     * Add default values and resources to the AcroForm dictionary
     *
     * This is needed to avoid undefinied behaviour in adobe reader.
     * If for example base fonts are missing, the file is digital signed and
     * include links, the signature panel will never be displayed.
     */
    public function addDefaultEntriesAndValues()
    {
        $dictionary = $this->getDictionary(true);

        if (false === $dictionary->offsetExists('Fields')) {
            $dictionary->offsetSet('Fields', new SetaPDF_Core_Type_Array());
        }

        if (false === $dictionary->offsetExists('CO')) {
            $dictionary->offsetSet('CO', new SetaPDF_Core_Type_Array());
        }

        // Create default resources and default appearance commands
        if (false === $dictionary->offsetExists('DR')) {
            $helvetica = $this->getDocument()->createNewObject(SetaPDF_Core_Font_Standard_Helvetica::getDefaultDictionary());
            $zapfDingbats = $this->getDocument()->createNewObject(SetaPDF_Core_Font_Standard_ZapfDingbats::getDefaultDictionary());

            $drDictionary = new SetaPDF_Core_Type_Dictionary();
            $fontDictionary = new SetaPDF_Core_Type_Dictionary();
            $fontDictionary->offsetSet('Helv', $helvetica);
            $fontDictionary->offsetSet('ZaDb', $zapfDingbats);

            $drDictionary->offsetSet('Font', $fontDictionary);

            $dictionary->offsetSet('DR', $drDictionary);

            $dictionary->offsetSet('DA', new SetaPDF_Core_Type_String('/Helv 0 Tf 0 g '));
        }
    }

    /**
     * Get the default resources of the AcroForm
     *
     * @param bool $create
     * @param null $entryKey
     * @return array|bool|SetaPDF_Core_Type_Abstract
     */
    public function getDefaultResources($create = false, $entryKey = null)
    {
        $dictionary = $this->getDictionary($create);
        if (false === $dictionary)
            return false;

        if (!$dictionary->offsetExists('DR')) {
            if (false === $create)
                return false;

            $dictionary->offsetSet('DR', new SetaPDF_Core_Type_Dictionary());
        }

        $resources = $dictionary->getValue('DR');
        if (null === $entryKey)
            return $resources;

        if (!$resources->offsetExists($entryKey)) {
            if (false === $create)
                return false;

            $resources->offsetSet($entryKey, new SetaPDF_Core_Type_Dictionary());
        }

        return $resources->offsetGet($entryKey)->ensure();
    }

    public function addResource($type, $object = null)
    {
        if ($type instanceof SetaPDF_Core_Resource) {
            $object = $type->getIndirectObject();
            $type = $type->getResourceType();
        }

        if ($object instanceof SetaPDF_Core_Resource)
            $object = $object->getIndirectObject($this->getDocument());

        if (!($object instanceof SetaPDF_Core_Type_IndirectObjectInterface)) {
            throw new InvalidArgumentException('$object has to be an instance of SetaPDF_Core_Type_IndirectObjectInterface or SetaPDF_Core_Resource');
        }

        $resources = $this->getDefaultResources(true, $type);

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
     * Get the terminal fields objects of a document
     *
     * @return array
     */
    public function getTerminalFieldsObjects()
    {
        $objects = array();
        $this->_readTerminalFieldsObjects(null, $objects);

        return $objects;
    }

    /**
     * Read all terminal fields objects
     *
     * @param array|null $fields
     * @param array $objects
     */
    private function _readTerminalFieldsObjects(array $fields = null, array &$objects)
    {
        if (null === $fields) {
            $acroForm = $this->getDictionary();

            if ($acroForm && $acroForm->offsetExists('Fields')) {
                // A field that has children that are fields is called a non-terminal field.
                // A field that does not have children that are fields is called a terminal field.
                $fields = $acroForm->offsetGet('Fields')->getValue()->ensure(true);
                $this->_readTerminalFieldsObjects($fields->getValue(), $objects);
            }

            return;
        }

        foreach ($fields AS $field) {
            $fieldsDict = $field->ensure(true);

            if ($fieldsDict->offsetExists('Kids')) {
                $kids = $fieldsDict->offsetGet('Kids')->getValue()->ensure(true)->getValue();
                $this->_readTerminalFieldsObjects($kids, $objects);

            } else {
                // Check for validity
                if (
                    !$fieldsDict->offsetExists('Subtype')
                    || $fieldsDict->getValue('Subtype')->getValue() !== 'Widget'
                ) {
                    continue;
                }

                $objects[] = $field->getValue();
            }
        }
    }
}