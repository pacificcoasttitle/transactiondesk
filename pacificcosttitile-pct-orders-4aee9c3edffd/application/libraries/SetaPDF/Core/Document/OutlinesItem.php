<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: OutlinesItem.php 439 2013-05-15 08:54:12Z jan.slabon $
 */

/**
 * Class representing an outline item
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_OutlinesItem implements RecursiveIterator, ArrayAccess
{
    /**
     * Confugration key
     *
     * @var string
     */
    const DEST = 'destination';

    /**
     * Confugration key
     *
     * @var string
     */
    const ACTION = 'action';

    /**
     * Confugration key
     *
     * @var string
     */
    const COLOR = 'color';

    /**
     * Confugration key
     *
     * @var string
     */
    const BOLD = 'bold';

    /**
     * Confugration key
     *
     * @var string
     */
    const ITALIC = 'italic';

    /**
     * Confugration key
     *
     * @var string
     */
    const TITLE = 'title';

    /**
     * Append mode value
     *
     * @var string
     */
    const APPEND_MODE_COPY = 'copy';

    /**
     * Append mode value
     *
     * @var string
     */
    const APPEND_MODE_MOVE = 'move';

    /**
     * Append mode value
     *
     * @var null
     */
    const APPEND_MODE_NONE = null;

    /**
     * Move mode value
     *
     * @var string
     */
    const MOVE_MODE_APPEND = 'append';

    /**
     * Move mode value
     *
     * @var string
     */
    const MOVE_MODE_PREPEND = 'prepend';

    /**
     * Move mode value
     *
     * @var string
     */
    const MOVE_MODE_APPEND_CHILD = 'appendChild';

    /**
     * The item dictionary
     *
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_dictionary;

    /**
     * The indirect reference for this item (if available)
     *
     * @var SetaPDF_Core_Type_IndirectObjectInterface
     */
    protected $_indirectReference;

    /**
     * The current iterator item
     *
     * @var SetaPDF_Core_Document_OutlinesItem
     */
    protected $_current;

    /**
     * Current iterator key
     *
     * @var integer
     */
    protected $_key = 0;

    /**
     * Creates an outline item
     *
     * The configuration array could hold keyed values:
     * <code>
     * $config = array(
     *     SetaPDF_Core_Document_OutlinesItem::TITLE => string,
     *     SetaPDF_Core_Document_OutlinesItem::DEST => SetaPDF_Core_Document_Destination|SetaPDF_Core_Type_Array|string,
     *     SetaPDF_Core_Document_OutlinesItem::ACTION => SetaPDF_Core_Document_Action|SetaPDF_Core_Type_Dictionary,
     *     SetaPDF_Core_Document_OutlinesItem::COLOR => SetaPDF_Core_Document_Color_Rgb|array
     *     SetaPDF_Core_Document_OutlinesItem::BOLD => boolean,
     *     SetaPDF_Core_Document_OutlinesItem::ITALIC => boolean,
     * );
     * </code>
     *
     * @param SetaPDF_Core_Document $document
     * @param string|array $titleOrConfig
     * @param array $config
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    static public function create(SetaPDF_Core_Document $document, $titleOrConfig, $config = array())
    {
        $dictionary = new SetaPDF_Core_Type_Dictionary();
        $item = new self($document->createNewObject($dictionary));

        if (!is_array($titleOrConfig))
            $item->setTitle($titleOrConfig);
        else
            $config = $titleOrConfig;

        foreach ($config AS $key => $value) {
            switch ($key) {
                case self::DEST:
                case self::ACTION:
                case self::COLOR:
                case self::BOLD:
                case self::ITALIC:
                case self::TITLE:
                    $method = 'set' . ucfirst($key);
                    $item->$method($value);
            }
        }

        return $item;
    }

    /**
     * Copies an item
     *
     * This method internally removes all relations to its parents or neighboring items
     *
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Document_OutlinesItem $item
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    static public function copyItem(SetaPDF_Core_Document $document, self $item)
    {
        $dictionary = clone $item->_dictionary;
        $dictionary->offsetUnset('Parent');
        $dictionary->offsetUnset('Next');
        $dictionary->offsetUnset('Prev');
        $dictionary->offsetUnset('First');
        $dictionary->offsetUnset('Last');

        return new self($document->createNewObject($dictionary));
    }

    /**
     * Copies an item including all sub-items to another item
     *
     * @internal
     * @param SetaPDF_Core_Document $document
     * @param self $rootItem
     * @param SetaPDF_Core_Document_OutlinesItem|SetaPDF_Core_Document_Catalog_Outlines $targetItem
     * @param string $appendMethod
     * @param boolean $first
     * @return SetaPDF_Core_Document_OutlinesItem
     * @throws InvalidArgumentException
     */
    static private function _copy(
        SetaPDF_Core_Document $document,
        self $rootItem,
        $targetItem,
        $appendMethod,
        $first = true
    )
    {
        if ($first && $rootItem->contains($targetItem, $appendMethod === 'appendChild')) {
            throw new InvalidArgumentException(
                'A cyclic reference is detected while copying outline items.'
            );
        }

        foreach ($rootItem AS $item) {
            $newItem = self::copyItem($document, $item);
            $targetItem->$appendMethod($newItem);
            if ($item->hasChildren()) {
                self::_copy($document, $item->getChildren(), $newItem, 'appendChild', false);
            }
            if ($first)
                break;
        }

        return $newItem;
    }

    /**
     * Get a hash of an outline item
     *
     * Used for checking cyclic references
     *
     * @see SetaPDF_Core_Document_OutlinesItem::contains()
     * @param self $item
     * @return string
     */
    static private function _getHash(self $item)
    {
        return md5(serialize($item->_dictionary->toPhp()));
    }

    /**
     * The constructor
     *
     * @param SetaPDF_Core_Type_IndirectObjectInterface|SetaPDF_Core_Type_Dictionary $objectOrDictionary
     */
    public function __construct($objectOrDictionary)
    {
        if ($objectOrDictionary instanceof SetaPDF_Core_Type_IndirectObjectInterface)
            $this->_indirectReference = $objectOrDictionary;

        $this->_dictionary = $objectOrDictionary->ensure();
        $this->_current = $this;
    }

    /**
     * Omit cloning
     *
     * @throws BadMethodCallException
     */
    public function __clone()
    {
        throw new BadMethodCallException(
            'Outline item objects are not cloneable. Use SetaPDF_Core_Document_OutlinesItem::' .
                'copy() or the SetaPDF_Core_Document_OutlinesItem::(append|prepend|appendChild)Copy()-methods instead.'
        );
    }

    /**
     * Get the reference to this item
     *
     * @return SetaPDF_Core_Type_IndirectObjectInterface|SetaPDF_Core_Type_Dictionary
     */
    public function getReferenceTo()
    {
        if (null !== $this->_indirectReference)
            return $this->_indirectReference;

        return $this->_dictionary;
    }

    /**
     * Get the item title
     *
     * @param string $encoding
     * @return string
     */
    public function getTitle($encoding = 'UTF-8')
    {
        return SetaPDF_Core_Encoding::convertPdfString($this->_dictionary->getValue('Title')->ensure()->getValue(), $encoding);
    }

    /**
     * Set the item title
     *
     * @param string $title
     * @param string $encoding
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function setTitle($title, $encoding = 'UTF-8')
    {
        if (!$this->_dictionary->offsetExists('Title')) {
            $this->_dictionary->offsetSet('Title', new SetaPDF_Core_Type_String());
        }

        $titleValue = $this->_dictionary->getValue('Title')->ensure();
        $titleValue->setValue(SetaPDF_Core_Encoding::toPdfString($title, $encoding));

        return $this;
    }

    /**
     * Checks if the item should be displayed bold
     *
     * @return boolean
     */
    public function isBold()
    {
        if (!$this->_dictionary->offsetExists('F'))
            return false;

        return ($this->_dictionary->getValue('F')->ensure()->getValue() & 2) !== 0;
    }

    /**
     * Sets whether the item should be displayed bold or not.
     *
     * @param boolean $bold
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function setBold($bold = true)
    {
        if ($bold === $this->isBold())
            return $this;

        if (!$this->_dictionary->offsetExists('F')) {
            $this->_dictionary->offsetSet('F', new SetaPDF_Core_Type_Numeric(2));
            return $this;
        }

        $value = $this->_dictionary->getValue('F')->ensure();
        if ($bold) {
            $value->setValue($value->getValue() | 2);
        } else {
            $value->setValue($value->getValue() & ~2);
        }

        return $this;
    }

    /**
     * Checks if the item should be displayed italic
     *
     * @return boolean
     */
    public function isItalic()
    {
        if (!$this->_dictionary->offsetExists('F'))
            return false;

        return ($this->_dictionary->getValue('F')->ensure()->getValue() & 1) !== 0;
    }

    /**
     * Sets whether the item should be displayed italic or not.
     *
     * @param boolean $italic
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function setItalic($italic = true)
    {
        if ($italic === $this->isItalic())
            return $this;

        if (!$this->_dictionary->offsetExists('F')) {
            $this->_dictionary->offsetSet('F', new SetaPDF_Core_Type_Numeric(1));
            return $this;
        }

        $value = $this->_dictionary->getValue('F')->ensure();
        if ($italic) {
            $value->setValue($value->getValue() | 1);
        } else {
            $value->setValue($value->getValue() & ~1);
        }

        return $this;
    }

    /**
     * Get the color of the item
     *
     * @return false|SetaPDF_Core_DataStructure_Color_Rgb
     */
    public function getColor()
    {
        if (!$this->_dictionary->offsetExists('C'))
            return false;

        return SetaPDF_Core_DataStructure_Color::createByComponents($this->_dictionary->getValue('C')->ensure());
    }

    /**
     * Set the color of the item
     *
     * @param array|SetaPDF_Core_DataStructure_Color_Rgb|float $colorOrR
     * @param float $g
     * @param float $b
     */
    public function setColor($colorOrR, $g = null, $b = null)
    {
        if (!($colorOrR instanceof SetaPDF_Core_DataStructure_Color_Rgb))
            $colorOrR = new SetaPDF_Core_DataStructure_Color_Rgb($colorOrR, $g, $b);

        $values = $colorOrR->getValue();

        if (!$this->_dictionary->offsetExists('C')) {
            $this->_dictionary->offsetSet('C', $values);
            return;
        }

        $value = $this->_dictionary->getValue('C');
        $value->setValue($values);
    }

    /**
     * Get the destination of the item
     *
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_Document_Destination|false
     * @throws BadMethodCallException
     */
    public function getDestination(SetaPDF_Core_Document $document = null)
    {
        if (!$this->_dictionary->offsetExists('Dest'))
            return false;

        $dest = $this->_dictionary->getValue('Dest')->ensure();
        if ($dest instanceof SetaPDF_Core_Type_StringValue || $dest instanceof SetaPDF_Core_Type_Name) {
            if ($document === null) {
                throw new BadMethodCallException('To resolve a named destination the $document parameter has to be set.');
            }

            return SetaPDF_Core_Document_Destination::findByName($document, $dest->getValue());
        }

        return new SetaPDF_Core_Document_Destination($dest);
    }

    /**
     * Set the destination of the item
     *
     * @param SetaPDF_Core_Document_Destination|SetaPDF_Core_Type_Array|SetaPDF_Core_Type_String $destination
     * @throws InvalidArgumentException
     */
    public function setDestination($destination)
    {
        if ($destination instanceof SetaPDF_Core_Document_Destination)
            $destination = $destination->getDestinationArray();

        if (!($destination instanceof SetaPDF_Core_Type_Array) &&
            !($destination instanceof SetaPDF_Core_Type_StringValue) &&
            !($destination instanceof SetaPDF_Core_Type_Name)
        ) {
            throw new InvalidArgumentException('Only valid destination values allowed (SetaPDF_Core_Type_Array, SetaPDF_Core_Type_StringValue, SetaPDF_Core_Type_Name or SetaPDF_Core_Document_Destination)');
        }

        $this->_dictionary->offsetSet('Dest', $destination);
        $this->_dictionary->offsetUnset('A');
    }

    /**
     * Get the action of the item
     *
     * @return bool|SetaPDF_Core_Document_Action
     */
    public function getAction()
    {
        if (!$this->_dictionary->offsetExists('A'))
            return false;

        return SetaPDF_Core_Document_Action::byObjectOrDictionary($this->_dictionary->getValue('A'));
    }

    /**
     * Set the action of the item
     *
     * @throws InvalidArgumentException
     * @param SetaPDF_Core_Document_Action|SetaPDF_Core_Type_Dictionary $action
     */
    public function setAction($action)
    {
        if ($action instanceof SetaPDF_Core_Document_Action)
            $action = $action->getActionDictionary();

        if (!($action instanceof SetaPDF_Core_Type_Dictionary) || !$action->offsetExists('S')) {
            throw new InvalidArgumentException('Invalid $action parameter. SetaPDF_Core_Document_Action or SetaPDF_Core_Type_Dictionary with an S key needed.');
        }

        $this->_dictionary->offsetSet('A', $action);
        $this->_dictionary->offsetUnset('Dest');
    }

    /**
     * Checks whether the item is open (true) or not (false) or if the item does not holds any descendants (null)
     *
     * @return NULL|boolean
     */
    public function isOpen()
    {
        $value = $this->getCount();
        if ($value == 0)
            return null;

        return $value > 0;
    }

    /**
     * Open or close the item
     *
     * @param boolean $open
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function setOpen($open = true)
    {
        // only possible if the item has any descendants
        if (!$this->_dictionary->offsetExists('First'))
            return $this;

        if ($open == $this->isOpen())
            return $this;

        $initCount = $this->getCount();
        $count = 0;
        $firstItem = $this->getFirstItem();
        if ($firstItem) {
            $iterator = new RecursiveIteratorIterator(
                $firstItem,
                RecursiveIteratorIterator::SELF_FIRST
            );
            $iterator->setMaxDepth(0);

            foreach ($iterator AS $childItem) {
                $count++;
                $childCount = $childItem->getCount();
                if ($childCount > 0) {
                    $count += $childCount;
                }
            }
        }

        $value = $this->_dictionary->getValue('Count')->ensure();

        $count = $count * ($open ? 1 : -1);
        $value->setValue($count);

        $parentPdfDict = $this->_dictionary->getValue('Parent')->ensure();
        while ($parentPdfDict !== false) {
            $value = $parentPdfDict->getValue('Count')->ensure();
            $currentCount = $value->getValue();
            $value->setValue($currentCount + ($count * ($currentCount < 0 ? -1 : 1)));
            $parentPdfDict = $currentCount > 0 && $parentPdfDict->offsetExists('Parent')
                ? $parentPdfDict->getValue('Parent')->ensure()
                : false;
        }

        return $this;
    }

    /**
     * Close the item
     *
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function close()
    {
        return $this->setOpen(false);
    }

    /**
     * Open the item
     *
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function open()
    {
        return $this->setOpen(true);
    }

    /**
     * Remove the item from the outline
     *
     * @return SetaPDF_Core_Document_OutlinesItem The removed item
     */
    public function remove()
    {
        $currentNext = $this->_dictionary->offsetGet('Next');
        $currentPrevious = $this->_dictionary->offsetGet('Prev');
        $next = $currentNext ? $currentNext->ensure() : false;
        $previous = $currentPrevious ? $currentPrevious->ensure() : false;

        $parentPdfDict = $this->_dictionary->getValue('Parent')->ensure();

        // Change Next and Prev values by the surrounding items
        if ($next && $previous) {
            $next->offsetSet('Prev', $currentPrevious->getValue());
            $previous->offsetSet('Next', $currentNext->getValue());

            // if no next is available unset next in the previous and set the parents last entry to the previous
        } elseif (!$next && $previous) {
            $previous->offsetUnset('Next');
            $parentPdfDict->offsetSet('Last', $currentPrevious->getValue());

            // if no previous is available unset prev in the next and set the parents first entry to the next
        } elseif (!$previous && $next) {
            $next->offsetUnset('Prev');
            $parentPdfDict->offsetSet('First', $currentNext->getValue());

            // If this was the only item unset the parents first and last entries
        } else {
            $parentPdfDict->offsetUnset('First');
            $parentPdfDict->offsetUnset('Last');
        }

        // Unset all reference values
        $this->_dictionary->offsetUnset('Parent');
        $this->_dictionary->offsetUnset('Prev');
        $this->_dictionary->offsetUnset('Next');
        $this->_dictionary->offsetUnset('First');
        $this->_dictionary->offsetUnset('Last');

        // descendants + current (1)
        $removedCount = $this->_dictionary->offsetExists('Count')
            ? $this->_dictionary->getValue('Count')->ensure()->getValue() + 1
            : 1;

        while ($parentPdfDict !== false) {
            $value = $parentPdfDict->getValue('Count')->ensure();
            $currentCount = $value->getValue();
            $value->setValue($currentCount - ($removedCount * ($currentCount < 0 ? -1 : 1)));
            $parentPdfDict = $currentCount > 0 && $parentPdfDict->offsetExists('Parent')
                ? $parentPdfDict->getValue('Parent')->ensure()
                : false;
        }

        return $this;
    }

    /**
     * Prepend another item to this item
     *
     * The $mode parameter can be used to specify the way the item will be
     * prepended: moved or copied
     *
     * @param self $item
     * @param SetaPDF_Core_Document $document
     * @param string $mode
     * @return SetaPDF_Core_Document_OutlinesItem
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    public function prepend(self $item, SetaPDF_Core_Document $document = null, $mode = null)
    {
        if ($item->getParent() !== false) {
            switch ($mode) {
                case self::APPEND_MODE_COPY:
                    return self::_copy($document, $item, $this, 'prepend');

                case self::APPEND_MODE_MOVE:
                    $newItem = self::_copy($document, $item, $this, 'prepend');
                    $item->remove();
                    return $newItem;
            }

            throw new InvalidArgumentException(
                'Item is already attached to an outline. Define the handling of these items by the $mode parameter.'
            );
        }

        $currentPrevious = $this->_dictionary->offsetGet('Prev');

        if (!$this->_dictionary->offsetExists('Parent')) {
            throw new LogicException('This item needs at least to be attached to an outline or other item.');
        }
        $parentPdfDict = $this->_dictionary->getValue('Parent')->ensure();

        $item->_dictionary->offsetSet('Next', $this->getReferenceTo());

        // Change prev value of the next item to the new $item reference
        if ($currentPrevious) {
            $currentPrevious->ensure()->offsetSet('Next', $item->getReferenceTo());
            $item->_dictionary->offsetSet(null, clone $currentPrevious);

        } else {
            $parentPdfDict->offsetSet('First', $item->getReferenceTo());
        }

        $item->_dictionary->offsetSet('Parent', $this->_dictionary->getValue('Parent'));
        $this->_dictionary->offsetSet('Prev', $item->getReferenceTo());

        while ($parentPdfDict !== false) {
            $value = $parentPdfDict->getValue('Count')->ensure();
            $currentCount = $value->getValue();
            $value->setValue($currentCount + ($currentCount < 0 ? -1 : 1));
            $parentPdfDict = $currentCount > 0 && $parentPdfDict->offsetExists('Parent')
                ? $parentPdfDict->getValue('Parent')->ensure()
                : false;
        }

        return $this;
    }

    /**
     * Append another item to this item
     *
     * The $mode parameter can be used to specify the way the item will be
     * appended: moved or copied
     *
     * @param self $item
     * @param SetaPDF_Core_Document $document
     * @param string $mode
     * @return SetaPDF_Core_Document_OutlinesItem
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    public function append(self $item, SetaPDF_Core_Document $document = null, $mode = null)
    {
        if ($item->getParent() !== false) {
            switch ($mode) {
                case self::APPEND_MODE_COPY:
                    return self::_copy($document, $item, $this, 'append');

                case self::APPEND_MODE_MOVE:
                    $newItem = self::_copy($document, $item, $this, 'append');
                    $item->remove();
                    return $newItem;
            }

            throw new InvalidArgumentException(
                'Item is already attached to an outline. Define the hanlding of these items by the $mode parameter.'
            );
        }

        $currentNext = $this->_dictionary->offsetGet('Next');

        if (!$this->_dictionary->offsetExists('Parent')) {
            throw new LogicException('This item needs at least to be attached to an outline or other item.');
        }
        $parentPdfDict = $this->_dictionary->getValue('Parent')->ensure();

        $item->_dictionary->offsetSet('Prev', $this->getReferenceTo());

        // Change prev value of the next item to the new $item reference
        if ($currentNext) {
            $currentNext->ensure()->offsetSet('Prev', $item->getReferenceTo());
            $item->_dictionary->offsetSet(null, clone $currentNext);

        } else {
            $parentPdfDict->offsetSet('Last', $item->getReferenceTo());
        }

        $item->_dictionary->offsetSet('Parent', $this->_dictionary->getValue('Parent'));
        $this->_dictionary->offsetSet('Next', $item->getReferenceTo());

        while ($parentPdfDict !== false) {
            $value = $parentPdfDict->getValue('Count')->ensure();
            $currentCount = $value->getValue();
            $value->setValue($currentCount + ($currentCount < 0 ? -1 : 1));
            $parentPdfDict = $currentCount > 0 && $parentPdfDict->offsetExists('Parent')
                ? $parentPdfDict->getValue('Parent')->ensure()
                : false;
        }

        return $this;
    }

    /**
     * Append another item as a child of this item
     *
     * The $mode parameter can be used to specify the way the item will be
     * appended: moved or copied
     *
     * @param self $item
     * @param SetaPDF_Core_Document $document
     * @param string $mode
     * @return SetaPDF_Core_Document_OutlinesItem|void
     * @throws InvalidArgumentException
     */
    public function appendChild($item, SetaPDF_Core_Document $document = null, $mode = null)
    {
        if ($item->getParent() !== false) {
            switch ($mode) {
                case self::APPEND_MODE_COPY:
                    return $this->appendChildCopy($item, $document);

                case self::APPEND_MODE_MOVE:
                    $newItem = $this->appendChildCopy($item, $document);
                    $item->remove();
                    return $newItem;
            }

            throw new InvalidArgumentException(
                'Item is already attached to an outline. Define the hanlding of these items by the $mode parameter.'
            );
        }

        /* check if item already have descendent outline items:
         * if so, just get the last item and call append on it.
         */
        $lastItem = $this->getLastItem();
        if ($lastItem) {
            $lastItem->append($item);
            return $this;
        }

        $value = $item->getReferenceTo();
        $this->_dictionary->offsetSet('First', $value);
        $this->_dictionary->offsetSet('Last', $value);
        $this->_dictionary->offsetSet('Count', new SetaPDF_Core_Type_Numeric(1));
        $value->ensure()->offsetSet('Parent', $this->getReferenceTo());

        $parentPdfDict = $this->_dictionary->getValue('Parent')->ensure();
        while ($parentPdfDict !== false) {
            $value = $parentPdfDict->getValue('Count')->ensure();
            $currentCount = $value->getValue();
            $value->setValue($currentCount + ($currentCount < 0 ? -1 : 1));
            $parentPdfDict = $currentCount > 0 && $parentPdfDict->offsetExists('Parent')
                ? $parentPdfDict->getValue('Parent')->ensure()
                : false;
        }
    }

    /**
     * Move this item to another item or root outline
     *
     * The $mode parameter can be used to specify how the item will be moved.
     *
     * @param SetaPDF_Core_Document_Catalog_Outlines|SetaPDF_Core_Document_OutlinesItem $target
     * @param SetaPDF_Core_Document $document
     * @param string $mode
     * @throws InvalidArgumentException
     */
    public function move($target, SetaPDF_Core_Document $document, $mode = 'appendChild')
    {
        switch ($mode) {
            case self::MOVE_MODE_APPEND:
            case self::MOVE_MODE_PREPEND:
            case self::MOVE_MODE_APPEND_CHILD:
                return $target->$mode($this, $document, self::APPEND_MODE_MOVE);
        }

        throw new InvalidArgumentException(sprintf('Unknows move mode (%s)', $mode));
    }

    /**
     * Appends a copy of another item to this item
     *
     * @param SetaPDF_Core_Document_OutlinesItem $originalItem
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function appendCopy(self $originalItem, SetaPDF_Core_Document $document)
    {
        return self::_copy($document, $originalItem, $this, 'append');
    }

    /**
     * Prepends a copy of another item to this item
     *
     * @param SetaPDF_Core_Document_OutlinesItem $originalItem
     * @param SetaPDF_Core_Document $document
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function prependCopy(self $originalItem, SetaPDF_Core_Document $document)
    {
        return self::_copy($document, $originalItem, $this, 'prepend');
    }


    /**
     * Appends a copy of another item or outline as a child to this item
     *
     * @param SetaPDF_Core_Document_Catalog_Outlines|SetaPDF_Core_Document_OutlinesItem $originalItem
     * @param SetaPDF_Core_Document $document
     * @return void|SetaPDF_Core_Document_OutlinesItem
     */
    public function appendChildCopy($originalItem, SetaPDF_Core_Document $document)
    {
        if ($originalItem instanceof SetaPDF_Core_Document_Catalog_Outlines) {
            $items = array();
            $iterator = $originalItem->getIterator();

            if ($iterator instanceof RecursiveIteratorIterator)
                $iterator->setMaxDepth(0);

            foreach ($iterator AS $_item) {
                $this->appendChildCopy($_item, $document);
            }

            if ($iterator instanceof RecursiveIteratorIterator)
                $iterator->setMaxDepth(-1);
            return;
        }

        return self::_copy($document, $originalItem, $this, 'appendChild');
    }

    /**
     * Checks if an item is specified in any descendants of this item
     *
     * @param self $item
     * @param boolean $checkAgainstThis
     * @return boolean
     */
    public function contains(self $item, $checkAgainstThis = true)
    {
        $mainItemHash = self::_getHash($item);
        if ($checkAgainstThis && $mainItemHash === self::_getHash($this))
            return true;

        if (false === $this->hasFirstItem())
            return false;

        $children = $this->getFirstItem();

        $iterator = new RecursiveIteratorIterator(
            $children,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator AS $item) {
            $itemHash = self::_getHash($item);
            if ($mainItemHash === $itemHash) {
                return true;
            }
        }

        return false;
    }

  /* Access-methods for navigating in the linked list */

    /**
     * Get an item instance of the item referenced in the Prev key
     *
     * @return boolean|SetaPDF_Core_Document_OutlinesItem
     */
    public function getPrevious()
    {
        if (!$this->_dictionary->offsetExists('Prev'))
            return false;

        return new self($this->_dictionary->getValue('Prev'));
    }

    /**
     * Get an item instance of the item referenced in the Next key
     *
     * @return boolean|SetaPDF_Core_Document_OutlinesItem
     */
    public function getNext()
    {
        if (!$this->_dictionary->offsetExists('Next'))
            return false;

        return new self($this->_dictionary->getValue('Next'));
    }

    /**
     * Get an item instance of the item referenced in the Parent key
     *
     * @return boolean|SetaPDF_Core_Document_OutlinesItem
     */
    public function getParent()
    {
        if (!$this->_dictionary->offsetExists('Parent'))
            return false;

        return new self($this->_dictionary->getValue('Parent'));
    }

    /**
     * Get an item instance of the item referenced in the First key
     *
     * @return boolean|SetaPDF_Core_Document_OutlinesItem
     */
    public function getFirstItem()
    {
        if (!$this->_dictionary->offsetExists('First'))
            return false;

        return new self($this->_dictionary->getValue('First'));
    }

    /**
     * Checks if this item has a First value set
     *
     * @return boolean
     */
    public function hasFirstItem()
    {
        return $this->_dictionary->offsetExists('First');
    }

    /**
     * Get an item instance of the item referenced in the Last key
     *
     * @return boolean|SetaPDF_Core_Document_OutlinesItem
     */
    public function getLastItem()
    {
        if (!$this->_dictionary->offsetExists('Last'))
            return false;

        return new self($this->_dictionary->getValue('Last'));
    }

    /**
     * Get the value of the Count key
     *
     * @return integer
     */
    public function getCount()
    {
        if (!$this->_dictionary->offsetExists('Count'))
            return 0;

        return $this->_dictionary->getValue('Count')->ensure()->getValue();
    }

    /**
     * Get the dictionary
     *
     * @return SetaPDF_Core_Type_Dictionary
     */
    public function getDictionary()
    {
        $this->_dictionary;
    }

  /* RecursiveIterator implementations */

    /**
     * Checks if this item has descendants
     *
     * @see RecursiveIterator::hasChildren()
     */
    public function hasChildren()
    {
        return $this->_current->hasFirstItem();
    }

    /**
     * Get the first descendant item
     *
     * @see RecursiveIterator::getChildren()
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function getChildren()
    {
        if ($this->_current->hasFirstItem())
            return $this->_current->getFirstItem();

        return false;
    }

    /**
     * Get the current item
     *
     * @see RecursiveIterator::current()
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Get the next item
     *
     * @see RecursiveIterator::next()
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function next()
    {
        $this->_current = $this->_current->getNext();
        $this->_key++;
    }

    /**
     * Get the iterator key
     *
     * @see RecursiveIterator::key()
     * @return integer
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Checks wheter the pointer of the iterator is valid or not
     *
     * @see RecursiveIterator::valid()
     * @return boolean
     */
    public function valid()
    {
        return $this->_current !== false;
    }

    /**
     * Reset the iterator
     *
     * @see RecursiveIterator::rewind()
     */
    public function rewind()
    {
        $this->_current = $this;
        $this->_key = 0;
    }

  /* ArrayAccess Implementation */

    /**
     * Checks if an item exists at a specific position
     *
     * @see ArrayAccess::offsetExists()
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        try {
            $item = $this->offsetGet($offset);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Set an item at a specific position
     *
     * @see ArrayAccess::offsetSet()
     * @see self::append()
     * @see self::appendChild()
     * @see self::remove()
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset)
            return $this->appendChild($value);

        $current = $this->offsetGet($offset);
        $prev = $current->getPrevious();
        if ($prev) {
            $current->remove();
            $prev->append($value);
            return;
        }

        $next = $current->getNext();
        if ($next) {
            $current->remove();
            $next->prepend($value);
            return;
        }

        $current->remove();
        $this->appendChild($value);
    }

    /**
     * Get an item by a specific position
     *
     * @see ArrayAccess::offsetGet()
     * @param mixed $offset
     * @return SetaPDF_Core_Document_OutlinesItem
     * @throws InvalidArgumentException
     */
    public function offsetGet($offset)
    {
        switch ($offset) {
            case 'first':
                $item = $this->getFirstItem();
                break;
            case 'last':
                $item = $this->getLastItem();
                break;
            case !is_numeric($offset):
                $item = false;
                break;
            default:
                $item = $this->getFirstItem();
                for ($n = 0; $n < $offset && $item !== false; $n++) {
                    $item = $item->getNext();
                }
        }

        if (false === $item) {
            throw new InvalidArgumentException(sprintf('No item at offset "%s" found.', $offset));
        }

        return $item;
    }

    /**
     * Removes an item at a specific position
     *
     * @see ArrayAccess::offsetUnset()
     * @param mixed $offset
     * @return SetaPDF_Core_Document_OutlinesItem
     */
    public function offsetUnset($offset)
    {
        $item = $this->offsetGet($offset);
        return $item->remove();
    }
}