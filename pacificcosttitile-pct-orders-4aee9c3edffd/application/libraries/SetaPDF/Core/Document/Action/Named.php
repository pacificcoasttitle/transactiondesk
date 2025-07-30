<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Named.php 434 2013-05-15 08:31:53Z jan.slabon $
 */

/**
 * Class representing a Named action
 *
 * See PDF 32000-1:2008 - 12.6.4.11
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Action_Named extends SetaPDF_Core_Document_Action
{
    /**#@+
     * Name defined in PDF 32000-1:2008
     *
     * @var string
     */
    const NEXT_PAGE = 'NextPage';
    const PREV_PAGE = 'PrevPage';
    const FIRST_PAGE = 'FirstPage';
    const LAST_PAGE = 'LastPage';
    /**#@-*/

    /* Acrobat specific */

    /**#@+
     * Additional names used by Adobe Acrobat
     *
     * @var string
     */
    const PRINT_DOCUMENT = 'Print';
    const GO_TO_PAGE = 'GoToPage';

    /**#@-*/

    /**
     * Create a Named Action dictionary
     *
     * @param string $name
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createActionDictionary($name)
    {
        $dictionary = new SetaPDF_Core_Type_Dictionary();
        $dictionary->offsetSet('S', new SetaPDF_Core_Type_Name('Named', true));
        $dictionary->offsetSet('N', new SetaPDF_Core_Type_Name($name));

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

        if (!$dictionary->offsetExists('S') || $dictionary->getValue('S')->getValue() !== 'Named') {
            throw new InvalidArgumentException('The S entry in a named action shall be "Named".');
        }

        if (!$dictionary->offsetExists('N') || !($dictionary->getValue('N') instanceof SetaPDF_Core_Type_Name)) {
            throw new InvalidArgumentException('Missing or incorrect type of N entry in named action dictionary.');
        }

        parent::__construct($objectOrDictionary);
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_actionDictionary->getValue('N')->ensure(true)->getValue();
    }

    /**
     * Set the name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_actionDictionary->getValue('N')->ensure(true)->setValue($name);
    }

}