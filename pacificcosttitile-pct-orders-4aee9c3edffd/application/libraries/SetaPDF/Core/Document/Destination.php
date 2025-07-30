<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Destination.php 508 2013-06-25 10:11:55Z jan.slabon $
 */

/**
 * Class for handling Destinations in a PDF document
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Destination
{
    /**
     * The destination array
     *
     * @var SetaPDF_Core_Type_Array
     */
    protected $_destination;

    /**
     * Find a destination by a name
     *
     * @param SetaPDF_Core_Document $document
     * @param string $name
     * @return bool|SetaPDF_Core_Document_Destination
     */
    static public function findByName(SetaPDF_Core_Document $document, $name)
    {
        $tree = $document->getCatalog()->getNames()->getTree(SetaPDF_Core_Document_Catalog_Names::DESTS);
        if (null === $tree) {
            $catalogDictionary = $document->getCatalog()->getDictionary();
            if (false === $catalogDictionary || !$catalogDictionary->offsetExists('Dests'))
                return false;

            $dests = $catalogDictionary->getValue('Dests')->ensure();
            if (!$dests->offsetExists($name))
                return false;

            return new self($dests->getValue($name));
        }

        $dest = $tree->get($name);
        if (null === $dest)
            return false;

        return new self($dest);
    }

    /**
     * Creates a explicit Destination array
     *
     * @param SetaPDF_Core_Type_IndirectObject $pageObject
     * @param string $fit
     * @return SetaPDF_Core_Type_Array
     * @throws InvalidArgumentException
     */
    static public function createDestinationArray(SetaPDF_Core_Type_IndirectObject $pageObject, $fit = 'Fit')
    {
        // Available modes and parameter count
        $availableFitModes = array(
            'XYZ' => 3, 'Fit' => 0, 'FitH' => 1, 'FitV' => 1,
            'FitR' => 4, 'FitB' => 0, 'FitBH' => 1, 'FitBV' => 1
        );

        if (!isset($availableFitModes[$fit])) {
            throw new InvalidArgumentException(sprintf('Unknown fit mode: %s', $fit));
        }

        $d = new SetaPDF_Core_Type_Array(array($pageObject));
        $d->offsetSet(null, new SetaPDF_Core_Type_Name($fit, true));

        $numArgs = func_num_args() - 1;
        for ($i = 2; $i <= $availableFitModes[$fit] + 1; $i++) {
            $arg = $numArgs >= $i ? func_get_arg($i) : false;
            if (false === $arg) {
                throw new InvalidArgumentException(
                    sprintf('Wrong parameter count for destination. %s needed', $availableFitModes[$fit])
                );
            }

            if ($arg === null) {
                $d->offsetSet(null, new SetaPDF_Core_Type_Null());
            } else {
                $d->offsetSet(null, new SetaPDF_Core_Type_Numeric($arg));
            }
        }

        return $d;
    }

    /**
     * Creates a destination by page number
     *
     * All additional arguments are passed to the createDestinationArray() method.
     *
     * @param SetaPDF_Core_Document $document
     * @param $pageNumber
     * @see createDestinationArray()
     *
     * @return SetaPDF_Core_Document_Destination
     */
    static public function createByPageNo(SetaPDF_Core_Document $document, $pageNumber)
    {
        $pages = $document->getCatalog()->getPages();

        $args = func_get_args();
        array_shift($args);
        $args[0] = $pages->getPage($pageNumber)->getPageObject();

        return new self(call_user_func_array(array('self', 'createDestinationArray'), $args));
    }

    /**
     * Creates a destination by a page object
     *
     * All additional arguments are passed to the createDestinationArray() method.
     *
     * @param SetaPDF_Core_Document_Page $page
     * @see createDestinationArray()
     *
     * @return SetaPDF_Core_Document_Destination
     */
    static public function createByPage(SetaPDF_Core_Document_Page $page)
    {
        $args = func_get_args();
        $args[0] = $page->getPageObject();

        return new self(call_user_func_array(array('self', 'createDestinationArray'), $args));
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_Abstract $destination
     * @throws InvalidArgumentException
     */
    public function __construct(SetaPDF_Core_Type_Abstract $destination)
    {
        $destination = $destination->ensure();

        if ($destination instanceof SetaPDF_Core_Type_Dictionary) {
            if ($destination->offsetExists('D'))
                $this->_destination = $destination->offsetGet('D')->getValue();
            return;
        } elseif ($destination instanceof SetaPDF_Core_Type_Array) {
            $this->_destination = $destination;
            return;
        }

        throw new InvalidArgumentException('Invalid $destination argument.');
    }

    /**
     * Get the target page number
     *
     * @param SetaPDF_Core_Document $document
     * @return integer|false
     */
    public function getPageNo(SetaPDF_Core_Document $document)
    {
        $pages = $document->getCatalog()->getPages();
        return $pages->getPageNumberByIndirectObject($this->_destination->offsetGet(0));
    }

    /**
     * Get the target page object
     *
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_Document_Page|false
     */
    public function getPage(SetaPDF_Core_Document $document)
    {
        $pages = $document->getCatalog()->getPages();
        return $pages->getPageByIndirectObject($this->_destination->offsetGet(0));
    }

    /**
     * Get the destination array
     *
     * @return SetaPDF_Core_Type_Array
     */
    public function getDestinationArray()
    {
        return $this->_destination;
    }

    /**
     * Get the PDF value of this destination
     *
     * @return SetaPDF_Core_Type_Array
     */
    public function getPdfValue()
    {
        return $this->getDestinationArray();
    }
}