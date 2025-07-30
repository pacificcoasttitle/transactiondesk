<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Dictionary.php 442 2013-05-15 09:01:14Z jan.slabon $
 */

/**
 * Class representing a dictionary
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_Dictionary extends SetaPDF_Core_Type_Abstract
    implements SplObserver, ArrayAccess, Countable, Iterator
{
    /**
     * The entries/values in the dictionary
     *
     * @var array An array of SetaPDF_Core_Type_Dictionary_Entry objects
     */
    protected $_entries = array();

    /**
     * Defines if this object make use of pdf string callbacks
     *
     * @var boolean
     */
    protected $_usePdfStringCallbacks = false;

    /**
     * An array of callbacks before this object is converted to a PDF string.
     *
     * @var array
     */
    protected $_pdfStringCallbacks = array();

    /**
     * Parses a associative array to a pdf dictionary string and writes it into a writer
     *
     * @see SetaPDF_Core_Type_Abstract
     * @param SetaPDF_Core_WriteInterface $writer
     * @param array $values
     * @return void
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $values)
    {
        $writer->write('<<');

        $i = 0;
        foreach ($values AS $key => $value) {
            if ($i++ > 10) {
                $writer->write("\n");
                $i = 0;
            }
            SetaPDF_Core_Type_Name::writePdfString($writer, $key);
            SetaPDF_Core_Type_Abstract::writePdfString($writer, $value);
        }

        $writer->write('>>');
    }

    /**
     * The constructor
     *
     * @param array $entries An array filled with SetaPDF_Core_Type_Dictionary_Entry OR an associative array
     * @throws InvalidArgumentException
     */
    public function __construct(array $entries = null)
    {
        unset($this->_observed);

        if (null !== $entries) {
            foreach ($entries AS $name => $value) {
                if ($value instanceof SetaPDF_Core_Type_Dictionary_Entry) {
                    $name = $value->getKeyValue();
                    $this->_entries[$name] = $value;
                } else if ($value instanceof SetaPDF_Core_Type_Abstract) {
                    $this->offsetSet($name, $value);
                } else {
                    throw new InvalidArgumentException('Parameter should be an array of values of type SetaPDF_Core_Type_Dictionary_Entry');
                }
            }
        }

        unset($this->_usePdfStringCallbacks, $this->_pdfStringCallbacks);
    }

    /**
     * Implementation of {@link http://www.php.net/language.oop5.magic.php#object.wakeup __wakeup()}
     */
    public function __wakeup()
    {
        if (false === $this->_usePdfStringCallbacks) {
            unset($this->_usePdfStringCallbacks, $this->_pdfStringCallbacks);
        }

        parent::__wakeup();
    }

    /**
     * Implementation of {@link http://www.php.net/language.oop5.cloning.php#object.clone __clone()}
     *
     * @see SetaPDF_Core_Type_Abstract::__clone()
     */
    public function __clone()
    {
        foreach ($this->_entries AS $key => $entry) {
            $this->_entries[$key] = clone $entry;
        }

        parent::__clone();
    }

    /**
     * Add an observer to the object
     *
     * Implementation of the Observer Pattern.
     * This overwritten method forwards the attach()-call
     * to all dictionary values.
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        $isObserved = isset($this->_observed);
        parent::attach($observer);

        if (false === $isObserved) {
            foreach ($this->_entries AS $entry) {
                $entry->attach($this);
            }
        }
    }

    /**
     * Triggered if a value of this object is changed.
     * Forward this to the "parent" object.
     *
     * @param SplSubject $SplSubject
     */
    public function update(SplSubject $SplSubject)
    {
        if (isset($this->_observed)) {
            $this->notify();
        }

        // TODO: Should be optimized
        $oldKey = array_search($SplSubject, $this->_entries, true);
        if ($oldKey) {
            $newKey = $SplSubject->getKeyValue();
            if ($newKey !== $oldKey) {
                unset($this->_entries[$oldKey]);
                $this->_entries[$newKey] = $SplSubject;
            }
        }
    }

    /**
     * Set the values of the dictionary
     *
     * @param array $entries Array of SetaPDF_Core_Type_Dictionary_Entry objects
     * @throws InvalidArgumentException
     */
    public function setValue($entries)
    {
        if (!is_array($entries)) {
            throw new InvalidArgumentException(
                'Parameter should be an array of SetaPDF_Core_Type_Dictionary_Entry objects.'
            );
        }

        // disable observing
        $observed = isset($this->_observed);
        if ($observed) {
            unset($this->_observed);
        }

        foreach ($entries AS $entry) {
            $this->offsetSet(null, $entry);
        }

        // reset observing
        if ($observed) {
            $this->_observed = true;

            // and notify...
            $this->notify();
        }
    }

    /**
     * Gets the value
     *
     * Returns all entries of this dictionary or a specific value of a named entry
     *
     * @param string|null $offset The name of the entry or null to receive all entries
     * @return array|SetaPDF_Core_Type_Abstract Array of SetaPDF_Core_Type_Dictionary_Entry objects
     */
    public function getValue($offset = null)
    {
        if (null === $offset) {
            return $this->_entries;
        }

        if (isset($this->_entries[$offset])) {
            return $this->_entries[$offset]->getValue();
        }

        return null;
    }

    /**
     * Returns the key names
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->_entries);
    }

    /**
     * Returns the type as a formatted PDF string
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        // Handle write callback in the owner document instance
        $self = $pdfDocument->getCurrentObjectDocument()->handleWriteCallback($this);

        $self->_handlePdfStringCallback();
        $s = '<<';

        $i = 0;
        foreach ($self->_entries AS $entry) {
            if ($i++ > 10) {
                $s .= "\n";
                $i = 0;
            }
            $s .= $entry->toPdfString($pdfDocument);
            #$s .= $pdfDocument->typeToPdfString($entry);
        }

        return $s . '>>';
    }

    /**
     * Release objects/memory
     *
     * @see SetaPDF_Core_Type_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        if (!isset($this->_observed)) {
            /*
            foreach (array_keys($this->_entries) AS $key) {
                $this->_entries[$key]->detach($this);
                $this->_entries[$key]->cleanUp();
                unset($this->_entries[$key]);
            }
            */
            while (($value = array_pop($this->_entries)) !== null) {
                $value->detach($this);
                $value->cleanUp();
            }
        }
    }

    /**
     * Converts the PDF data type to a PHP data type and returns it
     *
     * @return boolean
     */
    public function toPhp()
    {
        $result = array();

        $i = 0;
        foreach ($this->_entries AS $entry) {
            $php = $entry->toPhp();
            $result[$php['key']] = $php['value'];
        }

        return $result;
    }

    /**
     * Checks whether a offset exists
     *
     * @link http://www.php.net/ArrayAccess.offsetExists ArrayAccess::offsetExists
     * @param string $offset An offset to check for.
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_entries[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link http://www.php.net/ArrayAccess.offsetGet ArrayAccess::offsetGet
     * @param string $offset The offset to retrieve.
     * @return SetaPDF_Core_Type_Dictionary_Entry
     */
    public function offsetGet($offset)
    {
        if (isset($this->_entries[$offset])) {
            return $this->_entries[$offset];
        }

        return null;
    }

    /**
     * Offset to set
     *
     * If offset is null then the value need to be a SetaPDF_Core_Type_Dictionary_Entry.
     *
     * If value is scalar and offset is already set the setValue method of the offset will be used.
     *
     * Otherwise it should be an SetaPDF_Core_Type_Abstract.
     *
     * @link http://www.php.net/ArrayAccess.offsetSet ArrayAccess::offsetSet
     * @param null|string|SetaPDF_Core_Type_Name $offset The offset to assign the value to.
     * @param SetaPDF_Core_Type_Dictionary_Entry|SetaPDF_Core_Type_Abstract|mixed $value The value to set.
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (null !== $offset && is_scalar($offset) && isset($this->_entries[$offset])) {
            $this->_entries[$offset]->setValue($value);
            return;
        }

        if (null !== $offset && !($value instanceof SetaPDF_Core_Type_Dictionary_Entry)) {
            $value = new SetaPDF_Core_Type_Dictionary_Entry(
                ($offset instanceof SetaPDF_Core_Type_Name) ? $offset : new SetaPDF_Core_Type_Name($offset, true),
                $value
            );
        }

        /** This will need some microseconds if there are thousands of entries */
        if (!($value instanceof SetaPDF_Core_Type_Dictionary_Entry)) {
            throw new InvalidArgumentException('Parameter should be a value of type SetaPDF_Core_Type_Dictionary_Entry');
        }

        $name = $value->getKeyValue();
        if (isset($this->_entries[$name])) {
            $this->offsetUnset($name);
        }

        $this->_entries[$name] = $value;

        if (isset($this->_observed)) {
            $value->attach($this);
            $this->notify();
        }
    }

    /**
     * Checks whether a offset exists
     *
     * @link http://www.php.net/ArrayAccess.offsetUnset ArrayAccess::offsetUnset
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        if ($offset instanceof SetaPDF_Core_Type_Dictionary_Entry) {
            $key = $offset->getKey()->getValue();
        } else {
            $key = $offset;
        }

        if (isset($this->_entries[$key])) {
            if ($this->_entries[$key]->isObserved()) {
                $this->_entries[$key]->detach($this);
            }
            $this->_entries[$key]->cleanUp(); // If not called, we've a memory leak
            unset($this->_entries[$key]);

            if (isset($this->_observed)) {
                $this->notify();
            }
        }
    }

    /**
     * Returns the number of elements in the dictionary.
     *
     * @link http://www.php.net/Countable.count Countable::count
     * @return int
     */
    public function count()
    {
        return count($this->_entries);
    }

    /**
     * Returns the current element.
     *
     * @link http://www.php.net/Iterator.current Iterator::current
     * @return SetaPDF_Core_Type_Abstract
     */
    public function current()
    {
        $entry = current($this->_entries);
        return $entry ? $entry->getValue() : $entry;
    }

    /**
     * Moves forward to next element.
     *
     * @link http://www.php.net/Iterator.next Iterator::next
     * @return SetaPDF_Core_Type_Abstract
     */
    public function next()
    {
        $entry = next($this->_entries);
        return $entry ? $entry->getValue() : $entry;
    }

    /**
     * Returns the key of the current element.
     *
     * @link http://www.php.net/Iterator.key Iterator::key
     * @return integer
     */
    public function key()
    {
        return key($this->_entries);
    }

    /**
     * Checks if current position is valid.
     *
     * @see http://www.php.net/Iterator.valid Iterator::valid
     * @return boolean
     */
    public function valid()
    {
        return current($this->_entries) !== false;
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     * @link http://www.php.net/Iterator.rewind Iterator::rewind
     */
    public function rewind()
    {
        reset($this->_entries);
    }

    /**
     * Register a callback function which is called before the object is converted to a PDF string
     *
     * @param callback $callback
     * @param string $name
     */
    public function registerPdfStringCallback($callback, $name)
    {
        if (!isset($this->_pdfStringCallbacks)) {
            $this->_pdfStringCallbacks = array();
        }

        $this->_pdfStringCallbacks[$name] = $callback;
        $this->_usePdfStringCallbacks = true;
    }

    /**
     * Un-register a callback function
     *
     * @param string $name
     */
    public function unRegisterPdfStringCallback($name)
    {
        if (isset($this->_pdfStringCallbacks[$name])) {
            unset($this->_pdfStringCallbacks[$name]);
        }

        if (isset($this->_pdfStringCallbacks) && count($this->_pdfStringCallbacks) === 0) {
            unset($this->_usePdfStringCallbacks, $this->_pdfStringCallbacks);
        }

        parent::__wakeup();
    }

    /**
     * Execute the registered callbacks before the object is converted to a PDF string.
     */
    protected function _handlePdfStringCallback()
    {
        if (!isset($this->_usePdfStringCallbacks)) {
            return;
        }

        foreach ($this->_pdfStringCallbacks AS $name => $callback) {
            call_user_func_array($callback, array($this, $name));
        }
    }
}