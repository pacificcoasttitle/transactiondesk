<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Pages.php 372 2013-01-16 22:32:54Z jan $
 */

/**
 * Class for handling PDF pages
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Catalog_Pages
    implements Countable
{
    /**
     * The catalog instance
     *
     * @var SetaPDF_Core_Document_Catalog
     */
    protected $_catalog;

    /**
     * The pages root object
     *
     * @var SetaPDF_Core_Type_IndirectObject
     */
    protected $_pagesRootObject;

    /**
     * The page count
     *
     * @var integer
     */
    protected $_pageCount;

    /**
     * The current pages object while walking through the page tree
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_currentPagesObject;

    /**
     * An array holding the native indirect objects of pages
     *
     * @var array
     */
    protected $_pageObjects = array();

    /**
     * A helper array matching objects to page numbers
     *
     * @var array
     */
    protected $_pageObjectsToPageNumbers = array();

    /**
     * An array holding page instances
     *
     * @var array
     */
    protected $_pages = array();

    /**
     * Caches annotation object identifiers to page numbers
     *
     * @var array
     */
    protected $_annotationCache = array();

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

    /**
     * Release memory / cycled references
     */
    public function cleanUp()
    {
        $this->_catalog = null;
        $this->_pagesRootObject = null;
        $this->_currentPagesObject = null;
        $this->_pages = array();
        $this->_pageObjects = array();
        $this->_pageObjectsToPageNumbers = array();
    }

    /**
     * Returns the page count of the document
     *
     * @see Countable::count()
     */
    public function count()
    {
        if (null === $this->_pageCount) {
            $this->resolvePagesRootObject();

            if (null === $this->_pagesRootObject)
                return 0;

            $this->_pageCount = (int)$this->_pagesRootObject->ensure()
                ->getValue('Count')->getValue();
        }

        return $this->_pageCount;
    }

    /**
     * Deletes a page
     *
     * @param integer $pageNumber
     * @throws SetaPDF_Core_SecHandler_Exception
     * TODO Throw an exception if pageNumber is invalid
     */
    public function deletePage($pageNumber)
    {
        if ($this->getDocument()->hasSecurityHandler()) {
            $secHandler = $this->getDocument()->getSecHandler();
            if (!$secHandler->getPermission(SetaPDF_Core_SecHandler::PERM_ASSEMBLE)) {
                throw new SetaPDF_Core_SecHandler_Exception(
                    sprintf('Deletion of pages is not allowed with this credentials (%s).', $secHandler->getAuthMode()),
                    SetaPDF_Core_SecHandler_Exception::NOT_ALLOWED
                );
            }
        }

        /* Workflow:
         * 1. Resolve the parent /Pages object
         * 2. Remove the reference to this page
         * 3. Substract 1 from the /Count entry
         * 4. Walk up and substract 1 from the parent /Count values
         *    until the root node is reached.
         * 5. Delete the page object
         */
        $pageObject = $this->getPagesIndirectObject($pageNumber);
        $pageDict = $pageObject->ensure();
        $parentPages = $pageDict->offsetGet('Parent')->ensure(true);
        $kids = $parentPages->offsetGet('Kids')->getValue();

        foreach ($kids->getValue() AS $key => $indirectReference) {
            if ($indirectReference->getObjectIdent() == $pageObject->getObjectIdent()) {
                break;
            }
            unset($key);
        }

        if (isset($key)) {
            $kids->offsetUnset($key);

            while ($parentPages) {
                $currentCount = $parentPages->offsetGet('Count')->getValue()->getValue();
                $parentPages->offsetSet('Count', new SetaPDF_Core_Type_Numeric($currentCount - 1));

                $parentPages = $parentPages->offsetExists('Parent')
                    ? $parentPages->offsetGet('Parent')->ensure()
                    : false;

                // TODO: If this node has no additional kids, it should be removed, too
            }

            unset($this->_pageObjectsToPageNumbers[$pageObject->getObjectIdent()]);

            $this->getDocument()->deleteObject($pageObject);
            $this->_pageCount--;
            unset($this->_pageObjects[$pageNumber - 1]);
            $this->_pageObjects = array_values($this->_pageObjects);
        }
    }

    /**
     * Get a pages indirect object
     *
     * @param integer $pageNumber
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function getPagesIndirectObject($pageNumber)
    {
        return $this->_ensurePageObject($pageNumber);
    }

    /**
     * Get a page
     *
     * @param integer $pageNumber
     * @return SetaPDF_Core_Document_Page
     */
    public function getPage($pageNumber)
    {
        if (!isset($this->_pages[$pageNumber])) {
            $this->_pages[$pageNumber] = new SetaPDF_Core_Document_Page(
                $this->_ensurePageObject($pageNumber)
            );
        }
        return $this->_pages[$pageNumber];
    }

    /**
     * Get the last page
     *
     * @return SetaPDF_Core_Document_Page
     */
    public function getLastPage()
    {
        return $this->getPage($this->count());
    }

    /**
     * Extracts a page and prepares it for the usage in another document
     *
     * This method is needed if a page should be extracted independently.
     * For example the original document should be modified after extraction
     * and the page itself will be edited in the new document
     * (inherited attributes get flattened).
     *
     * @param integer $pageNumber
     * @param SetaPDF_Core_Document $document
     * @param boolean $returnPageInstance
     * @return SetaPDF_Core_Document_Page|SetaPDF_Core_Type_IndirectObject
     */
    public function extract(
        $pageNumber, SetaPDF_Core_Document $document, $returnPageInstance = true
    )
    {
        $indirectObject = $document->cloneIndirectObject($this->getPagesIndirectObject($pageNumber));

        if (true === $returnPageInstance) {
            return new SetaPDF_Core_Document_Page($indirectObject);
        }

        return $indirectObject;
    }

    /**
     * Find the page of an annotation object
     *
     * @param SetaPDF_Core_Type_IndirectObject $annotationIndirectObject
     * @return boolean|SetaPDF_Core_Document_Page
     */
    public function getPageByAnnotation(SetaPDF_Core_Type_IndirectObject $annotationIndirectObject)
    {
        $searchFor = $annotationIndirectObject->getObjectIdent();
        if (isset($this->_annotationCache[$searchFor])) {
            return $this->getPage($this->_annotationCache[$searchFor]);
        }

        for ($pageNumber = 1, $n = $this->count(); $pageNumber <= $n; $pageNumber++) {
            $page = $this->getPage($pageNumber);
            $annots = $page->getAnnotations()->getArray();
            if (false === $annots)
                continue;

            foreach ($annots->getValue() AS $annot) {
                $ident = $annot->getObjectIdent();
                if (isset($this->_annotationCache[$ident]))
                    continue;

                $this->_annotationCache[$ident] = $pageNumber;
                if ($ident == $searchFor) {
                    return $page;
                }
            }
        }

        return false;
    }

    /**
     * Get the page number by a page indirect object
     *
     * If the object is not found in the page tree, false is returned.
     *
     * @param SetaPDF_Core_Type_IndirectObject|SetaPDF_Core_Type_IndirectReference $indirectObject
     * @return boolean|integer
     * @throws InvalidArgumentException
     */
    public function getPageNumberByIndirectObject($indirectObject)
    {
        if (
            !($indirectObject instanceof SetaPDF_Core_Type_IndirectObject) &&
            !($indirectObject instanceof SetaPDF_Core_Type_IndirectReference)
        ) {
            throw new InvalidArgumentException(
                'Argument has to be of type SetaPDF_Core_Type_IndirectObject or SetaPDF_Core_Type_IndirectReference.'
            );
        }

        $ident = $indirectObject->getObjectIdent();
        if (isset($this->_pageObjectsToPageNumbers[$ident]))
            return $this->_pageObjectsToPageNumbers[$ident] + 1;

        for ($pageNumber = 1, $n = $this->count(); $pageNumber <= $n; $pageNumber++) {
            if (isset($this->_pages[$pageNumber]))
                continue;

            $this->_ensurePageObject($pageNumber);
            if (isset($this->_pageObjectsToPageNumbers[$ident]))
                return $this->_pageObjectsToPageNumbers[$ident] + 1;
        }

        return false;
    }

    /**
     * Get a page by it's indirect object
     *
     * @param SetaPDF_Core_Type_IndirectObject|SetaPDF_Core_Type_IndirectReference $indirectObject
     * @return SetaPDF_Core_Document_Page
     */
    public function getPageByIndirectObject($indirectObject)
    {
        $pageNumber = $this->getPageNumberByIndirectObject($indirectObject);
        if (false === $pageNumber)
            return false;

        return $this->getPage($pageNumber);
    }

    /**
     * Get the page number by a page object
     *
     * If the object is not found in the page tree, false is returned.
     *
     * @param SetaPDF_Core_Document_Page $page
     * @return boolean|integer
     */
    public function getPageNumberByPageObject(SetaPDF_Core_Document_Page $page)
    {
        return $this->getPageNumberByIndirectObject($page->getPageObject());
    }

    /**
     * This method makes sure that all pages are read
     *
     * It walks the complete page tree to cache/get all
     * page objects in one iteration.
     * This method should be used if all pages of a document
     * should be handled. It is much faster than the using the
     * random access.
     *
     * @throws BadMethodCallException
     */
    public function ensureAllPageObjects()
    {
        if (count($this->_pageObjects) > 0) {
            throw new BadMethodCallException(
                sprintf(
                    'The method "%s" could only be called if no page object was resolved before.', __METHOD__
                )
            );
        }

        $queue = array($this->resolvePagesRootObject());

        while (null != ($node = array_pop($queue))) {
            $nodeDict = $node->ensure();
            $kids = $nodeDict->offsetGet('Kids')->ensure();
            for ($i = 0, $n = $kids->count(); $i < $n; $i++) {
                $node = $kids->offsetGet($i)->getValue();
                $nodeDict = $node->ensure();

                $type = $nodeDict->offsetGet('Type')->getValue()->getValue();
                if ($type != 'Pages') {
                    $this->_pageObjects[] = $node;
                    $this->_pageObjectsToPageNumbers[$node->getObjectIdent()] = count($this->_pageObjects) - 1;
                } else {
                    array_unshift($queue, $node);
                }
            }
        }
    }

    /**
     * Ensures that a page object is read and available in the $_pageObjects property
     *
     * @param integer $pageNumber
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function _ensurePageObject($pageNumber)
    {
        if ($pageNumber < 1 || $pageNumber > $this->count()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Page number "%s" out of available page range (1 - %s)',
                    $pageNumber, $this->count()
                )
            );
        }

        $pageNumber = $pageNumber - 1;

        if (!isset($this->_pageObjects[$pageNumber])) {
            if ($pageNumber > ($this->count() / 2)) {
                $this->_readPageBackwards($pageNumber);
            } else {
                $this->_readPage($pageNumber);
            }

            $pageObject = $this->_pageObjects[$pageNumber];
            $this->_pageObjectsToPageNumbers[$pageObject->getObjectIdent()] = $pageNumber;
        }

        return $this->_pageObjects[$pageNumber];
    }

    /**
     * Resolves a page object by walking forwards through the page tree
     *
     * This method is optimized, to take the fastest way through
     * the page tree, beginning at the pages root node.
     * The page tree will be walked forward.
     *
     * @param integer $pageNumber The original page number - 1
     */
    protected function _readPage($pageNumber)
    {
        if (isset($this->_pageObjects[$pageNumber]))
            return;

        $node = $this->resolvePagesRootObject();
        $nodeDict = $node->ensure();
        $offset = $tmpOffset = 0;

        // Let's try to find the node in the initial Kids-Array
        $kids = $nodeDict->offsetGet('Kids')->ensure();
        if ($kids->count() === $this->count()) {
            $node = $kids->offsetGet($pageNumber)->getValue();
            $nodeDict = $node->ensure();

            $type = $nodeDict->getValue('Type')->getValue();
            if ($type !== 'Pages') {
                $this->_pageObjects[$pageNumber] = $node;
                $this->_pageObjectsToPageNumbers[$node->getObjectIdent()] = $pageNumber;
                return;
            }

            $offset = $pageNumber;
        }

        while (1) {
            $kids = $nodeDict->offsetGet('Kids')->ensure();

            for ($i = 0, $n = $kids->count(); $i < $n; $i++) {
                /* TODO: It should be possible to check if the node
                 *       was already visited. 
                 */
                $node = $kids->offsetGet($i)->getValue();
                $nodeDict = $node->ensure();

                $type = $nodeDict->getValue('Type')->getValue();
                if ($type !== 'Pages') {
                    $this->_pageObjectsToPageNumbers[$node->getObjectIdent()] = $offset;
                    $this->_pageObjects[$offset++] = $node;
                    if ($offset - 1 == $pageNumber)
                        break 2;

                    continue;
                } else {
                    $tmpOffset = $nodeDict->getValue('Count')->getValue();
                }

                // Check if this Kids Array is the correct path/way.
                if ($offset + $tmpOffset > $pageNumber) {
                    break;
                }

                $offset += $tmpOffset;
            }
        }
    }

    /**
     * Resolves a page object by walking backwards through the page tree
     *
     * This method is optimized, to take the fastest way through
     * the page tree, beginning at the pages root node.
     * The page tree will be walked forward.
     *
     * @param integer $pageNumber
     */
    protected function _readPageBackwards($pageNumber)
    {
        if (isset($this->_pageObjects[$pageNumber]))
            return;

        $pageCount = $this->count();

        $node = $this->resolvePagesRootObject();
        $nodeDict = $node->ensure();
        $offset = $pageCount - 1;

        // Let's try to find the node in the initial Kids-Array
        $kids = $nodeDict->offsetGet('Kids')->ensure();
        if ($kids->count() === $pageCount) {
            $node = $kids->offsetGet($pageNumber)->getValue();
            $nodeDict = $node->ensure();

            $type = $nodeDict->getValue('Type')->getValue();
            if ($type !== 'Pages') {
                $this->_pageObjects[$pageNumber] = $node;
                $this->_pageObjectsToPageNumbers[$node->getObjectIdent()] = $pageNumber;
                return;
            }

            $offset = $pageNumber;
        }

        while (1) {
            $kids = $nodeDict->offsetGet('Kids')->ensure();

            for ($n = $kids->count() - 1; 0 <= $n; $n--) {
                $node = $kids->offsetGet($n)->getValue();
                $nodeDict = $node->ensure();

                $type = $nodeDict->getValue('Type')->getValue();
                if ($type !== 'Pages') {
                    $this->_pageObjectsToPageNumbers[$node->getObjectIdent()] = $offset;
                    $this->_pageObjects[$offset--] = $node;
                    if ($offset + 1 == $pageNumber)
                        break 2;

                    continue;
                } else {
                    $tmpOffset = $nodeDict->getValue('Count')->getValue();
                }

                if ($offset - $tmpOffset < $pageNumber) {
                    break;
                }

                $offset -= $tmpOffset;
            }
        }
    }

    /**
     * Resolves the root page tree node
     *
     * @param boolean $create
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function resolvePagesRootObject($create = false)
    {
        if (null === $this->_pagesRootObject) {
            $catalog = $this->getDocument()->getCatalog()->getDictionary($create);
            if ($catalog === null)
                return null;

            if (!$catalog->offsetExists('Pages')) {
                if (false === $create)
                    return null;

                $catalog->offsetSet('Pages', $this->getDocument()->createNewObject(
                    new SetaPDF_Core_Type_Dictionary(array(
                        'Type' => new SetaPDF_Core_Type_Name('Pages', true),
                        'Kids' => new SetaPDF_Core_Type_Array(),
                        'Count' => new SetaPDF_Core_Type_Numeric(0)
                    ))
                ));
            }

            $this->_pagesRootObject = $catalog->getValue('Pages')->getValue();
        }

        return $this->_pagesRootObject;
    }

    /**
     * @param string|array $format
     * @param string $orientation
     * @param boolean $append
     * @return SetaPDF_Core_Document_Page
     */
    public function create($format, $orientation = SetaPDF_Core_PageFormats::ORIENTATION_PORTRAIT, $append = true)
    {
        $page = SetaPDF_Core_Document_Page::create(
            $this->getDocument(),
            array(
                SetaPDF_Core_PageFormats::getAsBoundary($format, $orientation, SetaPDF_Core_PageBoundaries::MEDIA_BOX)
            )
        );

        if ($append) {
            $this->append($page);
        }

        return $page;
    }

    /**
     * Append pages to the existing pages
     *
     * @param SetaPDF_Core_Document_Page|SetaPDF_Core_Document_Catalog_Pages|array $pages
     * @throws SetaPDF_Core_SecHandler_Exception
     * @throws InvalidArgumentException
     */
    public function append($pages /*, $pageNumber = null*/)
    {
        if ($this->getDocument()->hasSecurityHandler()) {
            $secHandler = $this->getDocument()->getSecHandler();
            if (!$secHandler->getPermission(SetaPDF_Core_SecHandler::PERM_ASSEMBLE)) {
                throw new SetaPDF_Core_SecHandler_Exception(
                    sprintf('Adding pages is not allowed with this credentials (%s).', $secHandler->getAuthMode()),
                    SetaPDF_Core_SecHandler_Exception::NOT_ALLOWED
                );
            }
        }

        if ($pages instanceof SetaPDF_Core_Document_Catalog_Pages) {
            $_pages = array();
            for ($i = 1; $i <= $pages->count(); $i++) {
                $_pages[] = $pages->getPage($i);
            }
            $pages = $_pages;
            unset($_pages);
        }

        if (!is_array($pages))
            $pages = array($pages);

        // if (null == $pageNumber)
        $pageNumber = $this->count();

        $pageCount = $this->count();

        if ($pageCount === 0) {
            $this->resolvePagesRootObject(true);
            $parent = $this->_pagesRootObject;
        } else {
            $lastPage = $this->getPagesIndirectObject($pageNumber)->ensure();
            $parent = $lastPage->getValue('Parent')->getValue();
        }

        $parent->observe();

        $parentDict = $parent->ensure();
        $kids = $parentDict->offsetGet('Kids')->ensure();

        $newPagesCount = count($pages);

        for ($i = 0; $i < $newPagesCount; $i++) {
            if (!($pages[$i] instanceof SetaPDF_Core_Document_Page)) {
                throw new InvalidArgumentException(
                    'Parameter have to be an array of SetaPDF_Core_Document_Page instances.'
                );
            }

            $pageObject = $pages[$i]->getPageObject();
            $kids->offsetSet(null, $pageObject);
            $pageDict = $pageObject->ensure();
            if (!$pageDict->offsetExists('Parent') || $pageDict->getValue('Parent')->getObjectIdent() !== $parent->getObjectIdent()) {
                $pageDict->offsetSet(
                    'Parent', new SetaPDF_Core_Type_IndirectReference($parent)
                );
            }

            $this->_pageObjectsToPageNumbers[$pageObject->getObjectIdent()] = $this->_pageCount;
            $this->_pageObjects[$this->_pageCount++] = $pageObject;
        }

        // Update Count values
        while ($parentDict !== null) {
            $countValue = $parentDict->offsetGet('Count')->getValue();
            $countValue->setValue($countValue->getValue() + $newPagesCount);

            if ($parentDict->offsetExists('Parent')) {
                $parentDict = $parentDict->offsetGet('Parent')->ensure(true);
            } else {
                $parentDict = null;
            }
        }
    }

    /* Method was used in merger experiments
     * @see SetaPDF_Merger::writeFileBody()
     * 
    public function appendToKids(SetaPDF_Core_Type_IndirectObject $parent, $pages)
    {
        if (!is_array($pages))
            $pages = array($pages);
        
        $parent->observe();
        
        $parentDict = $parent->ensure();
        $kids = $parentDict->offsetGet('Kids')->ensure();
        
        $newPagesCount = count($pages);
        
        for ($i = 0; $i < $newPagesCount; $i++) {
            if (!($pages[$i] instanceof SetaPDF_Core_Document_Page)) {
                throw new InvalidArgumentException(
                    'Parameter have to be an array of SetaPDF_Core_Document_Page instances.'
                );
            }
            $pageObject = $pages[$i]->getPageObject();
            $kids->offsetSet(null, $pageObject);
            $pageDict = $pageObject->ensure();
            $pageDict->offsetSet(
            	'Parent', new SetaPDF_Core_Type_IndirectReference($parent)
            );
        }
        
        // Update Count values
        while ($parentDict !== null) {
            $countValue = $parentDict->offsetGet('Count')->getValue();
            $countValue->setValue($countValue->getValue() + $newPagesCount);
            
            if ($parentDict->offsetExists('Parent')) {
                $parentDict = $parentDict->offsetGet('Parent')->ensure(true);
            } else {
                $parentDict = null;
            }
        }
    }*/

    /**
     * Prepend pages to the existing pages
     *
     * @param $pages
     * @throws SetaPDF_Core_SecHandler_Exception
     * @throws InvalidArgumentException
     */
    public function prepend($pages /*, $pageNumber = null*/)
    {
        if ($this->getDocument()->hasSecurityHandler()) {
            $secHandler = $this->getDocument()->getSecHandler();
            if (!$secHandler->getPermission(SetaPDF_Core_SecHandler::PERM_ASSEMBLE)) {
                throw new SetaPDF_Core_SecHandler_Exception(
                    sprintf('Adding pages is not allowed with this credentials (%s).', $secHandler->getAuthMode()),
                    SetaPDF_Core_SecHandler_Exception::NOT_ALLOWED
                );
            }
        }

        $pageCount = $this->count();
        if ($pageCount === 0) {
            $this->append($pages);
            return;
        }

        if ($pages instanceof SetaPDF_Core_Document_Catalog_Pages) {
            $_pages = array();
            for ($i = 1; $i <= $pages->count(); $i++) {
                $_pages[] = $pages->getPage($i);
            }
            $pages = $_pages;
            unset($_pages);
        }

        if (!is_array($pages))
            $pages = array($pages);

        // if (null == $pageNumber)
        $pageNumber = 1;


        $lastPage = $this->getPagesIndirectObject($pageNumber)->ensure();
        $parent = $lastPage->getValue('Parent')->getValue();
        $parent->observe();
        $parentDict = $parent->ensure();
        $kids = $parentDict->offsetGet('Kids')->ensure();

        $newPagesCount = count($pages);
        $offset = $newPagesCount - 1;
        $pageObjects = $this->_pageObjects;
        $this->_pageObjects = array();
        foreach ($pageObjects AS $key => $page) {
            $this->_pageObjects[$key + $offset] = $page;
            $this->_pageObjectsToPageNumbers[$page->getObjectIdent()] = $key + $offset;
        }

        for ($i = $offset; 0 <= $i; $i--) {
            if (!($pages[$i] instanceof SetaPDF_Core_Document_Page)) {
                throw new InvalidArgumentException(
                    'Parameter have to be an array of SetaPDF_Core_Document_Page instances.'
                );
            }
            $pageObject = $pages[$i]->getPageObject();
            $kids->unshift($pageObject);
            $pageDict = $pageObject->ensure();
            $pageDict->offsetSet(
                'Parent', new SetaPDF_Core_Type_IndirectReference($parent)
            );
            $this->_pageObjects[$i] = $pageObject;
            $this->_pageObjectsToPageNumbers[$pageObject->getObjectIdent()] = $i;
            $this->_pageCount++;
        }

        // Update Count values
        while ($parentDict !== null) {
            $countValue = $parentDict->offsetGet('Count')->getValue();
            $countValue->setValue($countValue->getValue() + $newPagesCount);

            if ($parentDict->offsetExists('Parent')) {
                $parentDict = $parentDict->offsetGet('Parent')->ensure(true);
            } else {
                $parentDict = null;
            }
        }
    }
}