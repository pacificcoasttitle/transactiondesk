<?php 
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: IndirectObjectInterface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Intarface indirect objects and object references
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Type_IndirectObjectInterface
{
    /**
     * Returns the initial object id
     *
     * @return integer
     */
    public function getObjectId();
    
    /**
     * Returns the initial generation number
     *
     * @return integer
     */
    public function getGen();
    
    /**
     * Get the Object Identifier
     *
     * This identifier has nothing to do with the object numbers
     * of a PDF document. They will be used to map an object to
     * docuement related object numbers.
     *
     * @return string
     */
    public function getObjectIdent();
    
    /**
     * Returns the owner document
     *
     * @return SetaPDF_Core_Document
     */
    public function getOwnerPdfDocument();
}