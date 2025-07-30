<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Resource.php 304 2012-11-01 17:53:36Z jan $
 */

/**
 * Interface for PDF resources
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Resource
{
    /**#@+
     * Type constant
     *
     * @var string
     */
    const TYPE_FONT = 'Font';
    const TYPE_X_OBJECT = 'XObject';
    const TYPE_EXT_G_STATE = 'ExtGState';
    const TYPE_COLOR_SPACE = 'ColorSpace';
    const TYPE_PATTERN = 'Pattern';
    const TYPE_SHADING = 'Shading';
    const TYPE_PROPERTIES = 'Properties';
    const TYPE_PROC_SET = 'ProcSet';
    /**#@-*/

    /**
     * Get the indirect object of this resource
     *
     * @return SetaPDF_Core_Type_IndirectObject
     */
    public function getIndirectObject();
    
    /**
     * Get the resource type of an implementation
     * 
     * @return string
     */
    public function getResourceType();    
}