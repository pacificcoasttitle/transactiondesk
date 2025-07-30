<?php 
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Aes256.php 255 2012-05-16 13:04:22Z jan $
 */

/**
 * Generator class for AES 256 bit security handler
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_SecHandler_Standard_Aes256 extends SetaPDF_Core_SecHandler_Standard
{
    /**
     * Factory method for AES 256 bit security handler
     * 
     * @param SetaPDF_Core_Document $document
     * @param string $ownerPassword
     * @param string $userPassword
     * @param integer $permissions
     * @param boolean $encryptMetadata
     * @throws SetaPDF_Core_SecHandler_Exception
     * @return SetaPDF_Core_SecHandler_Standard
     */
    static public function factory(
        SetaPDF_Core_Document $document,
        $ownerPassword,
        $userPassword = '',
        $permissions = 0,
        $encryptMetadata = true
    )
    {
        $encryptionDict = new SetaPDF_Core_Type_Dictionary();
        $encryptionDict->offsetSet('Filter', new SetaPDF_Core_Type_Name('Standard', true));
        
        $encryptionDict->offsetSet('R', new SetaPDF_Core_Type_Numeric(5));
        $encryptionDict->offsetSet('V', new SetaPDF_Core_Type_Numeric(5));
        $encryptionDict->offsetSet('O', new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('U', new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('Length', new SetaPDF_Core_Type_Numeric(256));
        
        $cf = new SetaPDF_Core_Type_Dictionary();
        $stdCf = new SetaPDF_Core_Type_Dictionary();
        $stdCf->offsetSet('CFM', new SetaPDF_Core_Type_Name('AESV3', true));
        $stdCf->offsetSet('AuthEvent', new SetaPDF_Core_Type_Name('DocOpen', true));
        $stdCf->offsetSet('Length', new SetaPDF_Core_Type_Numeric(32));
        $cf->offsetSet('StdCF', $stdCf);
        $encryptionDict->offsetSet('CF', $cf);
        $encryptionDict->offsetSet('StrF', new SetaPDF_Core_Type_Name('StdCF', true));
        $encryptionDict->offsetSet('StmF', new SetaPDF_Core_Type_Name('StdCF', true));
        
        $encryptionDict->offsetSet('UE', new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('OE', new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('Perms', new SetaPDF_Core_Type_String());
        
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
        
        $encryptionDict->offsetSet('EncryptMetadata', new SetaPDF_Core_Type_Boolean($encryptMetadata));
        
        $instance = new self($document, $encryptionDict);
        
        $instance->_encryptionKey = $instance->_computeEncryptionKey(null);
        
        $uValue = $instance->_computeUValue($userPassword);
        $encryptionDict->offsetGet('U')->getValue()->setValue($uValue);
	    
        // 2. Compute the 32-byte SHA-256 hash of the password concatenated with the User
        //    Key Salt. Using this hash as the key, encrypt the file encryption key using
        //    AES-256 in CBC mode with no padding and an initialization vector of zero.
        //    The resulting 32-byte string is stored as the UE key.
	    $keySalt = substr($uValue, 40);
	    $key = hash('sha256', $userPassword . $keySalt, true);
	    
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $ueValue = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $instance->_encryptionKey, MCRYPT_MODE_CBC, str_repeat("\0", $iv_size));

        $encryptionDict->offsetGet('UE')->getValue()->setValue($ueValue);
	    
        $oValue = $instance->_computeOValue($userPassword, $ownerPassword);
        $encryptionDict->offsetGet('O')->getValue()->setValue($oValue);
	    // 2. Compute the 32-byte SHA-256 hash of the password concatenated with the Owner
	    //    Key Salt and then concatenated with the 48-byte U string as generated in
	    //    Algorithm 3.8. Using this hash as the key, encrypt the file encryption key
	    //    using AES-256 in CBC mode with no padding and an initialization vector of
	    //    zero. The resulting 32-byte string is stored as the OE key.
	    $keySalt = substr($oValue, 40);
	    $key = hash('sha256', $ownerPassword . $keySalt . $uValue, true);
	    
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $oeValue = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $instance->_encryptionKey, MCRYPT_MODE_CBC, str_repeat("\0", $iv_size));

        $encryptionDict->offsetGet('OE')->getValue()->setValue($oeValue);
	    
        // 1. Extend the permissions (contents of the P integer) to 64 bits by setting
        //    the upper 32 bits to all 1’s. (This allows for future extension without
        //    changing the format.)
        $perms = substr(pack('V', $permissions), 0, 4)
               . "\xFF\xFF\xFF\xFF"
               . ($encryptMetadata ? 'T' : 'F') // 8
               . 'adb'   // 9-11
               . 'SeTa'; // 12-15: 4 random bytes
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $permsValue = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $instance->_encryptionKey, $perms, MCRYPT_MODE_ECB, str_repeat("\0", $iv_size));
	    $encryptionDict->offsetGet('Perms')->getValue()->setValue($permsValue);
	    
	    $instance->_auth = true;
	    $instance->_authMode = SetaPDF_Core_SecHandler::OWNER;
	    
	    return $instance;
    }
}