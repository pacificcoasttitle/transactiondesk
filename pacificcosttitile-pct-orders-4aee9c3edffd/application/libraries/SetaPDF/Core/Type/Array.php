<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Array.php 423 2013-04-12 10:39:33Z jan.slabon $
 */

/**
 * Class representing an array
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Type_Array extends SetaPDF_Core_Type_Abstract
    implements SplObserver, Countable, ArrayAccess, RecursiveIterator
{
    /**
     * The values
     *
     * An array of {@link SetaPDF_Core_Type_Abstract} objects
     *
     * @var $_values array
     */
    protected $_values = array();

    /**
     * The array count
     *
     * @var integer
     */
    protected $_count = 0;

    /**
     * Parses a php array to a pdf array string and writes it into a writer.
     *
     * @see SetaPDF_Core_Type_Abstract
     * @param SetaPDF_Core_WriteInterface $writer
     * @param array $values
     * @return void
     */
    static public function writePdfString(SetaPDF_Core_WriteInterface $writer, $values)
    {
        $writer->write('[');

        $i = 0;
        foreach ($values AS $value) {
            if ($i++ > 40) {
                $writer->write("\n");
                $i = 0;
            }
            SetaPDF_Core_Type_Abstract::writePdfString($writer, $value);
        }
        $writer->write(']');
    }

    /**
     * The constructor
     *
     * @param array $values An array filled with values of type SetaPDF_Core_Type_Abstract
     * @throws InvalidArgumentException
     */
    public function __construct(array $values = null)
    {
        unset($this->_observed);
        if (null !== $values) {
            foreach ($values AS $value) {
                if (!($value instanceof SetaPDF_Core_Type_Abstract)) {
                    throw new InvalidArgumentException('Parameter should be a values of type SetaPDF_Core_Type_Abstract');
                }

                if ($value instanceof SetaPDF_Core_Type_IndirectObject) {
                    $value = new SetaPDF_Core_Type_IndirectReference($value);
                }

                $this->_values[] = $value;
                $this->_count++;
            }
        }
    }

    /**
     * Implementation of {@link http://www.php.net/language.oop5.cloning.php#object.clone __clone()}
     *
     * @see SetaPDF_Core_Type_Abstract::__clone()
     */
    public function __clone()
    {

        foreach ($this->_values AS $key => $value) {
            $this->_values[$key] = clone $value;
        }

        parent::__clone();
    }

    /**
     * Add an observer to the object.
     *
     * This method forwards the attach()-call
     * to all values of this array.
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        $isObserved = isset($this->_observed);
        parent::attach($observer);

        if (false === $isObserved) {
            foreach ($this->_values AS $value) {
                $value->attach($this);
            }
        }
    }

    /**
     * Triggered if a value of this object is changed.
     *
     * Forward this to other observers.
     *
     * @param SplSubject $SplSubject
     */
    public function update(SplSubject $SplSubject)
    {
        if (isset($this->_observed))
            $this->notify();
    }

    /**
     * Sets the values.
     *
     * @param array|SetaPDF_Core_Type_Array $values An array of SetaPDF_Core_Type_Abstract objects
     * @throws InvalidArgumentException
     */
    public function setValue($values)
    {
        if ($values instanceof SetaPDF_Core_Type_Array)
            $values = $values->getValue();

        if (!is_array($values)) {
            throw new InvalidArgumentException('Parameter should be an array of SetaPDF_Core_Type_Abstract objects.');
        }

        // disable observing
        $observed = isset($this->_observed);
        if ($observed)
            unset($this->_observed);

        // remove all entries
        for ($key = $this->_count - 1; $key >= 0; $key--) {
            $this->offsetUnset($key);
        }

        foreach ($values AS $value) {
            $this->offsetSet(null, $value);
        }

        // reset observing
        if ($observed) {
            $this->_observed = true;

            // and notify...
            $this->notify();
        }
    }

    /**
     * Gets the value.
     *
     * @return array
     */
    public function getValue()
    {
        return $this->_values;
    }

    /**
     * Returns the type as a formatted PDF string.
     *
     * @param SetaPDF_Core_Document $pdfDocument
     * @return string
     */
    public function toPdfString(SetaPDF_Core_Document $pdfDocument)
    {
        $s = '[';

        $i = 0;
        foreach ($this->_values AS $value) {
            if ($i++ > 40) {
                $s .= "\n";
                $i = 0;
            }
            $s .= $value->toPdfString($pdfDocument);
        }

        return $s . ']';
    }

    /**
     * Release objects/memory
     *
     * @see SetaPDF_Core_Type_Abstract::cleanUp()
     */
    public function cleanUp()
    {
        if (!isset($this->_observed)) {
            $this->clear();
        }
    }

    /**
     * Converts the PDF data type to a PHP data type and returns it.
     *
     * @see SetaPDF_Core_Type_Abstract::toPhp()
     * @return array
     */
    public function toPhp()
    {
        $result = array();

        foreach ($this->_values AS $value) {
            $result[] = $value->toPhp();
        }

        return $result;
    }

    /**
     * Returns the number of elements in the array.
     *
     * @link http://www.php.net/Countable.count Countable::count
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Checks whether a offset exists
     *
     * @link http://www.php.net/ArrayAccess.offsetExists ArrayAccess::offsetExists
     * @param int $offset An offset to check for.
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_values[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link http://www.php.net/ArrayAccess.offsetGet ArrayAccess::offsetGet
     * @param int $offset The offset to retrieve.
     * @return SetaPDF_Core_Type_Abstract|null
     */
    public function offsetGet($offset)
    {
        return isset($this->_values[$offset])
            ? $this->_values[$offset]
            : null;
    }

    /**
     * Offset to set
     *
     * @link http://www.php.net/ArrayAccess.offsetSet ArrayAccess::offsetSet
     * @param null|int $offset The offset to assign the value to.
     * @param SetaPDF_Core_Type_Abstract $value The value to set.
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof SetaPDF_Core_Type_Abstract)) {
            throw new InvalidArgumentException(
                'Parameter should be a values of type SetaPDF_Core_Type_Abstract'
            );
        }

        if ($value instanceof SetaPDF_Core_Type_IndirectObject) {
            $value = new SetaPDF_Core_Type_IndirectReference($value);
        }


        if (is_null($offset)) {
            $this->_values[$this->_count++] = $value;
        } else {
            $offset = (int)$offset;
            if (!isset($this->_values[$offset]) && $offset != $this->_count) {
                throw new InvalidArgumentException(
                    sprintf('Offset (%s) does not exists or will create a gap (array count: %s).', $offset, $this->_count)
                );
            }

            $this->_values[$offset]->detach($this);
            $this->_values[$offset]->cleanUp();
            $this->_values[$offset] = $value;
            if ($offset === $this->_count)
                $this->_count++;
        }

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
        if (isset($this->_values[$offset])) {

            $this->_values[$offset]->detach($this);
            $this->_values[$offset]->cleanUp();
            unset($this->_values[$offset]);

            // TODO: BOTTLENECK !!!
            $this->_values = array_values($this->_values);
            $this->_count--;
            if (isset($this->_observed)) {
                $this->notify();
            }
        }
    }

    /**
     * Prepends one element to the beginning of the array.
     *
     * @param SetaPDF_Core_Type_Abstract $value
     */
    public function unshift(SetaPDF_Core_Type_Abstract $value)
    {
        if ($value instanceof SetaPDF_Core_Type_IndirectObject) {
            $value = new SetaPDF_Core_Type_IndirectReference($value);
        }

        array_unshift($this->_values, $value);
        $this->_count++;

        if (isset($this->_observed)) {
            $value->attach($this);
            $this->notify();
        }
    }

    /**
     * Inserts an element before another one.
     *
     * Index mustn't be higher than the count of elements in array.
     *
     * Index 0 is allowed in an empty array.
     *
     * @param SetaPDF_Core_Type_Abstract $value
     * @param null|integer $beforeIndex
     * @throws InvalidArgumentException
     */
    public function insertBefore(SetaPDF_Core_Type_Abstract $value, $beforeIndex = 0)
    {
        $beforeIndex = (int)$beforeIndex;

        if ($beforeIndex < 0 || ($beforeIndex > ($this->_count === 0 ? 0 : $this->_count - 1))) {
            throw new InvalidArgumentException(sprintf('$beforeIndex (%s) is out of range.', $beforeIndex));
        }

        if ($value instanceof SetaPDF_Core_Type_IndirectObject) {
            $value = new SetaPDF_Core_Type_IndirectReference($value);
        }

        $prev = array_slice($this->_values, 0, $beforeIndex);
        $end = array_slice($this->_values, $beforeIndex);

        $this->_values = array_merge($prev, array($value), $end);

        $this->_count++;

        if (isset($this->_observed)) {
            $value->attach($this);
            $this->notify();
        }
    }

    /**
     * Pushes a value onto the end of the array.
     *
     * @param SetaPDF_Core_Type_Abstract $value
     */
    public function push(SetaPDF_Core_Type_Abstract $value)
    {
        $this->offsetSet(null, $value);
    }

    /**
     * Merges this PDF array with other PDF arrays.
     *
     * @param SetaPDF_Core_Type_Array Any number of arrays
     * @throws InvalidArgumentException
     */
    public function merge()
    {
        foreach (func_get_args() AS $array) {
            if (!($array instanceof SetaPDF_Core_Type_Array)) {
                throw new InvalidArgumentException(
                    'Only arguments of type SetaPDF_Core_Type_Array allowed.'
                );
            }

            $values = $array->getValue();
            foreach ($values AS $value) {
                $this->offsetSet(null, $value);
            }
        }
    }

    /**
     * Merges this PDF array with other PDF arrays while only taking not exisitng values
     *
     * @param SetaPDF_Core_Type_Array Any number of arrays
     * @throws InvalidArgumentException
     */
    public function mergeUnique()
    {
        foreach (func_get_args() AS $array) {
            if (!($array instanceof SetaPDF_Core_Type_Array)) {
                throw new InvalidArgumentException(
                    'Only arguments of type SetaPDF_Core_Type_Array allowed.'
                );
            }

            $values = $array->getValue();
            foreach ($values AS $value) {
                if ($this->indexOf($value) === -1)
                    $this->offsetSet(null, $value);
            }
        }
    }

    /**
     * Clears the array.
     */
    public function clear()
    {
        for ($key = $this->_count - 1; $key >= 0; $key--) {
            $this->_values[$key]->detach($this);
            $this->_values[$key]->cleanUp();
            unset($this->_values[$key]);
        }

        $this->_count = 0;
    }

    /**
     * Returns the index of the element.
     *
     * If the element isn't in this array -1 will returned.
     *
     * @param SetaPDF_Core_Type_Abstract $element
     * @return int
     */
    public function indexOf(SetaPDF_Core_Type_Abstract $element)
    {
        $reqClassName = get_class($element);

        foreach ($this->_values AS $index => $value) {
            $className = get_class($value);

            if ($className !== $reqClassName &&
                $className !== 'SetaPDF_Core_Type_IndirectReference'
            ) {
                continue;
            }

            switch ($reqClassName) {
                case 'SetaPDF_Core_Type_IndirectObject':
                case 'SetaPDF_Core_Type_IndirectReference':
                    if ($element->getObjectIdent() === $value->getObjectIdent())
                        return $index;
                    break;

                case 'SetaPDF_Core_Type_Numeric':
                    if (abs($element->getValue() - $value->getValue()) <= SetaPDF_Core::FLOAT_COMPARSION_PRECISION) {
                        return $index;
                    }
                    break;

                default:
                    if ($value->toPhp() === $element->toPhp())
                        return $index;
            }
        }

        return -1;
    }

    /**
     * Returns the current element.
     *
     * @link http://www.php.net/Iterator.current Iterator::current
     * @return SetaPDF_Core_Type_Abstract
     */
    public function current()
    {
        return current($this->_values);
    }

    /**
     * Moves forward to next element.
     *
     * @link http://www.php.net/Iterator.next Iterator::next
     * @return SetaPDF_Core_Type_Abstract
     */
    public function next()
    {
        return next($this->_values);
    }

    /**
     * Returns the key of the current element.
     *
     * @link http://www.php.net/Iterator.key Iterator::key
     * @return integer
     */
    public function key()
    {
        return key($this->_values);
    }

    /**
     * Checks if current position is valid.
     *
     * @see http://www.php.net/Iterator.valid Iterator::valid
     * @return boolean
     */
    public function valid()
    {
        // TODO: Change to a property which is updated in next()
        return current($this->_values) !== false;
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     * @link http://www.php.net/Iterator.rewind Iterator::rewind
     */
    public function rewind()
    {
        reset($this->_values);
    }

    /**
     * Returns an iterator for the current entry.
     *
     * @link http://www.php.net/RecursiveIterator.getChildren RecursiveIterator::getChildren
     * @return array|void
     */
    public function getChildren()
    {
        $current = current($this->_values);

        if($current instanceof SetaPDF_Core_Type_Abstract)
            $current = current($this->_values)->ensure();

        if ($current instanceof SetaPDF_Core_Type_Array)
            return $current;
    }

    /**
     * Check whether the current entry is an SetaPDF_Core_Type_Array.
     *
     * @link http://www.php.net/RecursiveIterator.hasChildren RecursiveIterator::hasChildren
     * @return boolean
     */
    public function hasChildren()
    {
        return (current($this->_values)->ensure() instanceof SetaPDF_Core_Type_Array);
    }
}