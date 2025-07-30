<?php 
/**
 * This file is part of the SetaPDF-FormFiller Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Fields.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Class allowing transparent access to form fields of a PDF document
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_FormFiller_Fields implements Countable, Iterator, ArrayAccess
{
    /**
     * The form filler instance
     * 
     * @var SetaPDF_FormFiller
     */
	protected $_formFiller;
    
    /**
     * The field instances
     * 
     * @var array
     */
    protected $_fields = array();
    
    /**
     * An array of names of related fields
     * 
     * @var array
     */
    protected $_relatedFields = array();
    
    /**
     * The pdf object of each form field / collection (at PDF level)
     * 
     * @var array
     */
    protected $_fieldObjects = array();
    
    /**
     * Flag defines that the fields are read from the document
     * 
     * @var boolean
     */
    protected $_fieldsRead = false;
    
    /**
     * Flag defines that the forwarding of setValue calls is active
     * 
     * @var boolean
     */
    protected $_forwardSetValue = false;
    
    /**
     * The constructor
     * 
     * @param SetaPDF_FormFiller $formFiller
     * @return SetaPDF_FormFiller_Fields
     */
    public function __construct(SetaPDF_FormFiller $formFiller)
    {
        $this->_formFiller = $formFiller;
    }
    
    /**
     * Releases memory and resources
     * 
     * @return void
     */
    public function cleanUp()
    {
        foreach (array_keys(array_filter($this->_fields)) AS $fieldName) {
            $this->_fields[$fieldName]->cleanUp();
        }
        
        $this->_fields = array();
        $this->_fieldObjects = array();
        $this->_formFiller = null;
    }
    
    /**
     * Get the form filler instance
     * 
     * @return SetaPDF_FormFiller
     */
    public function getFormFiller()
    {
    	return $this->_formFiller;
    }
    
    /**
     * Get all available field names
     * 
     * @return array
     */
    public function getNames()
    {
        if (false === $this->_fieldsRead)
            $this->_readFormFields();
        
        return array_keys($this->_fieldObjects);
    }
    
    /**
     * Gets a single field by field name
     * 
     * @param string $name
     * @return SetaPDF_FormFiller_Field_Interface
     * @throws SetaPDF_FormFiller_Exception
     */
    public function get($name)
    {
        if (false === $this->_fieldsRead)
            $this->_readFormFields();
            
        // Check if the field is already initiated 
        if (!isset($this->_fields[$name])) {
            
            if (!isset($this->_fieldObjects[$name])) {
                throw new SetaPDF_FormFiller_Exception(
                    sprintf('Form field not found (%s)', $name)
                );
            }
    
            $fieldData = $this->_fieldObjects[$name];
            
            switch ($fieldData['type'])
            {
                case 'Btn':
                    $this->_fields[$name] = new SetaPDF_FormFiller_Field_Button(
                        $this, $name, $fieldData['fieldObject'], $fieldData['originalName']
                    );
                    break;
                    
                case 'BtnGroup':
                    $this->_fields[$name] = new SetaPDF_FormFiller_Field_ButtonGroup($this, $name, $fieldData['firstFieldObject']);
                    foreach ($fieldData['buttonFieldObjects'] AS $key => $buttonFieldObject) {
                        $this->_fields[$name]->addButton(
                            new SetaPDF_FormFiller_Field_Button($this, $name . '#' . $key, $buttonFieldObject, $name)
                        );
                        
                        /* TODO: Will this work, too? Does this makes any sense?
                        $tmpName = $name . '#' . $key;
                        $this->_fields[$tmpName] = new SetaPDF_FormFiller_Field_Button($this, $tmpName, $buttonFieldObject, $name);
                        $this->_fields[$name]->addButton($this->_fields[$tmpName]);
                        */
                    }
                    
                    break;
                    
                case 'Tx':
                    $this->_fields[$name] = new SetaPDF_FormFiller_Field_Text(
                        $this, $name, $fieldData['fieldObject'], $fieldData['originalName']
                    );
                    
                    break;
                    
                case 'Ch':
                    $className = 'SetaPDF_FormFiller_Field_List';
                    if ($fieldData['fieldFlags'] & SetaPDF_FormFiller_Field_Flags::COMBO) {
                        $className = 'SetaPDF_FormFiller_Field_Combo';
                    } 

                    $this->_fields[$name] = new $className(
                        $this, $name, $fieldData['fieldObject'], $fieldData['originalName']
                    );
                    
                    break;
                /*
                case 'Sig':
                    // TODO: Implement
                    return false;
                    break;
                */
            }
        }
        
        return $this->_fields[$name];
    }
    
    /**
     * Get all available field objects
     * 
     * @return array
     */
    public function getAll()
    {
        if (false === $this->_fieldsRead)
            $this->_readFormFields();
        
        foreach ($this->_fieldObjects AS $name => $data) {
            if (null === $this->_fields[$name])
                $this->get($name);
        }
        
        return $this->_fields;
    }
    
    /**
     * NOT IMPLEMENTED: Get form fields by a page number
     * 
     * @param integer $pageNo
     * @throws SetaPDF_Exception_NotImplemented
     * @todo IMPLEMENT
     */
    public function getByPage($pageNo)
    {
        // TODO: Implement
        throw new SetaPDF_Exception_NotImplemented('Not implemented yet');
    }
    
    /**
     * This method is called when a field is deleted
     * 
     * @param SetaPDF_FormFiller_Field_Interface $field
     * @return void
     */
    public function onFieldDeleted(SetaPDF_FormFiller_Field_Interface $field)
    {
        $name = $field->getQualifiedName();
        
        $fieldData = $this->_fieldObjects[$name];
        
        if (isset($this->_fields[$name])) {
            $this->_fields[$name]->cleanUp();
            unset($this->_fields[$name]);
        }
            
        unset($this->_fieldObjects[$name]);
        
        // Delete entry in relatedFields property
        if (isset($this->_relatedFields[$fieldData['originalName']])) {
            $key = array_search($name, $this->_relatedFields[$fieldData['originalName']]);
            unset($this->_relatedFields[$fieldData['originalName']][$key]);
        }
    }
    
    /**
     * Delete a field
     * 
     * @param string|null|SetaPDF_FormFiller_Field_Interface $field The name or an instance or null to delete all fields
     * @return void
     */
    public function delete($field = null)
    {
        if (null === $field) {
            foreach ($this->getNames() AS $name)
                $this->delete($name);

            return;
        }
        
        if (!($field instanceof SetaPDF_FormFiller_Field_Interface))
            $field = $this->get($field);
            
        $field->delete();
    }
    
    /**
     * Flatten a field to the pages content stream
     * 
     * @param string|null|SetaPDF_FormFiller_Field_Interface $field The name or an instance or null to delete all fields
     * @return void
     */
    public function flatten($field = null)
    {
        if (null === $field) {
            foreach ($this->getNames() AS $name)
                $this->get($name)->flatten();
                
            return;
        }
        
        if (!($field instanceof SetaPDF_FormFiller_Field_Interface))
            $field = $this->get($field);
            
        $field->flatten();
    }
    
    /**
     * Reads the form field objects and prepares them for later usage
     *
     * @return void
     */
    protected function _readFormFields()
    {
        $acroForm = $this->_formFiller->getAcroForm();
        if ($acroForm) {
        	$fields = $acroForm->getTerminalFieldsObjects();
        
        	foreach ($fields AS $field) {
        		$fieldsDict = $this->_formFiller->getDocument()->ensureObject($field)->ensure(true);
        		 
        		$name = SetaPDF_Core_Document_Catalog_AcroForm::resolveFieldName($fieldsDict);
        		
        		$fieldType = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($fieldsDict, 'FT');
        		if (null === $fieldType)
        		    continue;
        		
        		$type = $fieldType->getValue();
        		$fieldFlags = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute(
    				$fieldsDict, 'Ff', new SetaPDF_Core_Type_Numeric(0)
        		)->getValue();
        		 
        		// Jump over special fields
        		if (
    				'Btn' === $type && ($fieldFlags & SetaPDF_FormFiller_Field_Flags::PUSHBUTTON)
    				|| 'Tx' === $type && ($fieldFlags & SetaPDF_FormFiller_Field_Flags::FILE_SELECT)
        		) {
        			continue;
        			 
        		// Radiobutton
        		} elseif ('Btn' === $type && $fieldFlags & SetaPDF_FormFiller_Field_Flags::RADIO) {
        			// Check if field type is available
        			if (!class_exists('SetaPDF_FormFiller_Field_ButtonGroup') || isset($this->simulateLiteVersion)) {
        				continue;
        			}
        			 
        			if (!isset($this->_fieldObjects[$name])) {
        				$this->_fieldObjects[$name] = array(
    						'type' => 'BtnGroup',
    						'buttonFieldObjects' => array(),
    						'firstFieldObject' => $field,
    						'originalName' => $name
        				);
        			}
        			 
        			$this->_fieldObjects[$name]['buttonFieldObjects'][] = $field;
        			 
        		// All other fields
        		} else {
        			 
        			// Check if field type is available
        			if (
    					'Tx'  === $type && !class_exists('SetaPDF_FormFiller_Field_Text')
    					|| 'Ch'  === $type && (!class_exists('SetaPDF_FormFiller_Field_List') || isset($this->simulateLiteVersion))
    					|| 'Btn' === $type && (!class_exists('SetaPDF_FormFiller_Field_Button') || isset($this->simulateLiteVersion))
    					|| 'Sig' === $type && (!class_exists('SetaPDF_FormFiller_Field_Signature') || isset($this->simulateLiteVersion))
        			) {
        				continue;
        			}
        			 
        			$originalName = $name;

        			// Only same field types could be related
        			if (isset($this->_fieldObjects[$name]) && $this->_fieldObjects[$name]['type'] !== $type) {
        			    continue;
        			}
        			
        			$i = 1;
        			while (isset($this->_fieldObjects[$name])) {
        				$name = $originalName . '#' . ($i++);
        			}
        			 
        			$this->_fieldObjects[$name] = array(
    					'type' => $type,
    					'fieldObject' => $field,
    					'fieldFlags' => $fieldFlags,
    					'originalName' => $originalName
        			);
        			 
        			if ($name !== $originalName) {
        				if (!isset($this->_relatedFields[$originalName]))
        					$this->_relatedFields[$originalName] = array($originalName);
        				 
        				$this->_relatedFields[$originalName][] = $name;
        			}
        		}
        		 
        		// prefill in the array for the final objects
        		$this->_fields[$name] = null;
        	}
        }
        
        $this->_fieldsRead = true;
    }
    
    /**
     * Get all names of related form fields
     * 
     * @param string|SetaPDF_FormFiller_Field_Interface $field
     * @param boolean $leftOriginFieldName
     * @return array
     */
    public function getRelatedFieldNames($field, $leftOriginFieldName = true)
    {
        if (!($field instanceof SetaPDF_FormFiller_Field_Interface))
            $field = $this->get($field);
            
        // The name including the suffix ("Text#1")
        $name = $field->getQualifiedName();
        // The origina name ("Text")
        $originalName = $field->getOriginalQualifiedName();
        $relatedFieldNames = array();
        
        if (isset($this->_relatedFields[$originalName])) {
            foreach ($this->_relatedFields[$originalName] AS $relatedFieldName) {
                // Is it the field name, from the initial field, skip over
                if (true === $leftOriginFieldName && $relatedFieldName == $name)
                    continue;
                
                $relatedFieldNames[] = $relatedFieldName;
            }
        }
        
        // If no related field is known but the original fieldname should not be left.
        if (false === $leftOriginFieldName && 0 === count($relatedFieldNames)) {
            $relatedFieldNames[] = $name;
        }
        
        return $relatedFieldNames;
    }
    
    /**
     * Get all same named/related form fields
     * 
     * @param SetaPDF_FormFiller_Field_Interface $field The initial field
     * @param boolean $leftOriginField Left the origin passed field in the resulting array or not
     * @return array An array of SetaPDF_FormFiller_Field_Interface
     */
    public function getRelatedFields(SetaPDF_FormFiller_Field_Interface $field, $leftOriginField = true)
    {
        // The name including the suffix ("Text#1")
        $name = $field->getQualifiedName();
        // The origina name ("Text")
        $originalName = $field->getOriginalQualifiedName();
        $relatedFields = array();
        
        // Walk through all related field entries and forward the setValue-call if needed
        if (isset($this->_relatedFields[$originalName])) {
            foreach ($this->_relatedFields[$originalName] AS $relatedFieldName) {
                // Is it the field name, from the initial field, skip over it
                if (true === $leftOriginField && $relatedFieldName === $name)
                    continue;
                
                $relatedFields[$relatedFieldName] = $this->get($relatedFieldName);
            }
        }
        
        // If no related field is known but the original field should not be left.
        if (false === $leftOriginField && 0 === count($relatedFields)) {
            $relatedFields[$originalName] = $field;
        }
        
        return $relatedFields;
    }
    
    /**
     * This method forwards a setValue call to related/same named form fields
     * 
     * @param mixed $value The value
     * @param SetaPDF_FormFiller_Field_Interface $field The initial form field, which was changed
     * @return void
     */
    public function forwardSetValueToRelated($value, SetaPDF_FormFiller_Field_Interface $field)
    {
        // Are we already forwarding?
        if (true === $this->_forwardSetValue)
            return;
            
        $name = $field->getQualifiedName();

        // set the "forwarding"-flag to true
        $this->_forwardSetValue = true;
        
        $relatedFields = $this->getRelatedFields($field);
        
        // Walk through all related field entries and forward the setValue-call if needed
        foreach ($relatedFields AS $relatedField) {
            // Buttonfields / Checkboxes should be handled in a special way:
            // Same named with different values will xor each other, like a
            // radio button group
            if ($field instanceof SetaPDF_FormFiller_Field_Button) {
                if ($field->getExportValue() === $relatedField->getExportValue()) {
                    $relatedField->setValue($value);
                } else {
                    $relatedField->setValue(false);
                }
                
            } else {
                $relatedField->setValue($value);
            }
        }
        
        // set the "forwarding"-flag to false
        $this->_forwardSetValue = false;
    }

    /**
     * @return bool
     */
    public function isForwardSetValueActive()
    {
        return $this->_forwardSetValue;
    }
    
  /* Implementation of SPL interfaces */
    
	/**
     * Implementation of Countable 
     */
    public function count()
    {
        if (false === $this->_fieldsRead)
            $this->_readFormFields();
        
        return count($this->_fieldObjects);
    }
    
	/**
     * Implementation of the Iterator interface
     */
    public function current()
    {
        if (false === $this->_fieldsRead)
            $this->_readFormFields();
        
        $currentName = key($this->_fields);
        
        if (!isset($this->_fields[$currentName]))
            $this->get($currentName);
            
        return $this->_fields[$currentName];
    }

	/**
     * Implementation of the Iterator interface
     */
    public function next()
    {
        if (false === $this->_fieldsRead)
        	$this->_readFormFields();
        
        next($this->_fields);
    }

	/**
     * Implementation of the Iterator interface
     */
    public function key()
    {
        if (false === $this->_fieldsRead)
        	$this->_readFormFields();
        
        return key($this->_fields);
    }

	/**
     * Implementation of the Iterator interface
     */
    public function valid()
    {
        if (false === $this->_fieldsRead)
            $this->_readFormFields();
        
        return key($this->_fields) !== null;
    }

	/**
     * Implementation of the Iterator interface
     */
    public function rewind()
    {
        if (false === $this->_fieldsRead)
        	$this->_readFormFields();
        
        reset($this->_fields);
    }
    
	/**
     * Implementation of the ArrayAccess interface
     * 
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        if (false === $this->_fieldsRead)
        	$this->_readFormFields();
        
        return array_key_exists($offset, $this->_fields);
    }

	/**
     * Implementation of the ArrayAccess interface
     * 
     * @param string $offset
     * @return boolean
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

	/**
     * Implementation of the ArrayAccess interface
     * 
     * @param string $offset
     * @param mixed $value
     * @throws BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException("Set operations are not allowed for this object.");
    }

	/**
     * Implementation of the ArrayAccess interface
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }
}