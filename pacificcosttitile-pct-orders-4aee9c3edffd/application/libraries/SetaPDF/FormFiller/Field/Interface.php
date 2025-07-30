<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Interface.php 407 2013-02-26 10:47:34Z maximilian.kresse $
 */

/**
 * Field interface 
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_FormFiller_Field_Interface
{
    /**
     * Returns the field specific value
     * 
     * @return mixed
     */
    public function getValue();
    
    /**
     * Sets the fields value
     * 
     * @param $value
     */
    public function setValue($value);
    
    /**
     * Recreate or creates the appearance of the form field if needed
     * 
     * @return void
     */
    public function recreateAppearance();
    
    /**
     * Returns the default value of the field.
     * 
     * This value is used if the form is reset
     *
     * @param string $encoding
     * @return mixed
     */
    public function getDefaultValue($encoding = 'UTF-8');
    
    /**
     * Checks if a field is marked as read-only
     * 
     * @return boolean
     */
    public function isReadOnly();
    
    /**
     * Sets a field to read-only or not
     * 
     * @param boolean $readOnly
     * @return void
     */
    public function setReadOnly($readOnly = true);

    /**
     * Checks if a field is marked as required
     * 
     * @return boolean
     */
    public function isRequired();
    
    /**
     * Sets a field to be required or not
     * 
     * @param boolean $required
     * @return void
     */
    public function setRequired($required = true);
    
    /**
     * Get the info, if the field is marked as "no export"
     * 
     * This flag is not get- or setable with Acrobat!
     * 
     * @return boolean
     */
    public function getNoExport();
    
    /**
     * Sets the "no export" flag
     * 
     * This flag is not get- or setable with Acrobat!
     * 
     * If you remove this flag, the element could be still not exported
     * due to a defination in a FormSubmit actions Fields array.
     * 
     * @param boolean $noExport
     * @return void
     */
    public function setNoExport($noExport = true);
    
    /**
     * Returns the qualified name of the field
     *  
     * @return string
     */
    public function getQualifiedName();
    
    /**
     * Returns the original qualified name of the field (without suffix #n)
     * 
     * @return string
     */
    public function getOriginalQualifiedName();
    
    /**
     * Flatten the form fields appearance to the pages content
     *
     * @return void
     */
    public function flatten();
    
    /**
     * Delete the form field
     * 
     * @return void
     */
    public function delete();
    
    /**
     * Release cycled references and release memory
     * 
     * @return void
     */
    public function cleanUp();
}