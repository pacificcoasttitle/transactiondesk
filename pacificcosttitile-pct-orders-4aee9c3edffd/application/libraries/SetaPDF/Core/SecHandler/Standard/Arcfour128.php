<?php 
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Arcfour128.php 255 2012-05-16 13:04:22Z jan $
 */

/**
 * Generator class for RC4 128 bit security handler
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_SecHandler_Standard_Arcfour128 extends SetaPDF_Core_SecHandler_Standard
{
    /**
     * Factory method for RC4 128 bit security handler
     * 
     * @param SetaPDF_Core_Document $document
     * @param string $ownerPassword
     * @param string $userPassword
     * @param integer $permissions
     * @throws SetaPDF_Core_SecHandler_Exception
     * @return SetaPDF_Core_SecHandler_Standard
     */
    static public function factory(
        SetaPDF_Core_Document $document,
        $ownerPassword,
        $userPassword = '',
        $permissions = 0
    )
    {
        $encryptionDict = new SetaPDF_Core_Type_Dictionary();
        $encryptionDict->offsetSet('Filter', new SetaPDF_Core_Type_Name('Standard', true));
        
        $encryptionDict->offsetSet('R', new SetaPDF_Core_Type_Numeric(3));
        $encryptionDict->offsetSet('V', new SetaPDF_Core_Type_Numeric(2));
        $encryptionDict->offsetSet('O', new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('U', new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('Length', new SetaPDF_Core_Type_Numeric(128));
        
        if ((3900 & $permissions) != $permissions) {
            throw new SetaPDF_Core_SecHandler_Exception(
                sprintf('Permission flags (%s) are not allowed fot this security handler (revision 2).', $permissions)
            );
        }
        
        // 61632 = bit 7, bit 8, bit 13 to 16
        // 0xFFFF0000 = bit 17 - 32
        $permissions = 61632 | 0xFFFF0000 | $permissions;
        $permissions = SetaPDF_Core_Type_Numeric::ensure32BitInteger($permissions);
        $encryptionDict->offsetSet('P', new SetaPDF_Core_Type_Numeric($permissions));
        
        $instance = new self($document, $encryptionDict);
        
        $oValue = $instance->_computeOValue($userPassword, $ownerPassword);
        $encryptionDict->offsetGet('O')->getValue()->setValue($oValue);
        
	    $encryptionKey = $instance->_computeEncryptionKey($userPassword);
	    
	    $uValue = $instance->_computeUValue($encryptionKey);
	    $encryptionDict->offsetGet('U')->getValue()->setValue($uValue);
	    
	    $instance->_encryptionKey = $encryptionKey;
	    $instance->_auth = true;
	    $instance->_authMode = SetaPDF_Core_SecHandler::OWNER;
	    
	    return $instance;
    }
}