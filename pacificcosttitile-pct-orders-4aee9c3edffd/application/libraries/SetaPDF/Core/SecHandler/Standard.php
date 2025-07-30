<?php
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Standard.php 380 2013-02-11 22:58:42Z jan $
 */

/**
 * Security handler class handling standard encryption features
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_SecHandler_Standard implements SetaPDF_Core_SecHandler_Interface
{
    /**
     * The padding string
     * 
     * @var string
     */
    static protected $_padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
    
    /**
     * The doucment to which this security handler is attached
     * 
     * @var SetaPDF_Core_Document
     */
    protected $_document;
    
    /**
     * The encryption key
     * 
     * @var string
     */
    protected $_encryptionKey;
    
    /**
     * The encryption dictionary
     * 
     * @var SetaPDF_Core_Type_Dictionary
     */
    protected $_encryptionDictionary;
    
    /**
     * Defines if this security handler is authenticated
     * 
     * @var boolean
     */
    protected $_auth = false;
    
    /**
     * The auth mode
     * 
     * Says who is authenticated: user or owner
     * 
     * @var string
     */
    protected $_authMode = SetaPDF_Core_SecHandler::OWNER;
    
    /**
     * The algorithm an key length to be used for en/decrypting strings
     * 
     * @var array
     */
    protected $_stringAlgorithm = array(SetaPDF_Core_SecHandler::ARCFOUR, 5);
    
    /**
     * The algorithm an key length to be used for en/decrypting stream
     * 
     * @var array
     */
    protected $_streamAlgorithm = array(SetaPDF_Core_SecHandler::ARCFOUR, 5);
    
    /**
     * The keylength in bytes
     * 
     * This value is still needed if crypt filters are in use:
     *   - It is needed to compute the encryption key.
     *   - It is needed to compute the O value
     *  It is NOT documentated which key length should be used for this things
     *  if a crypt filter is in use.
     *  
     * @var integer
     */
    protected $_keyLength = 5;
    
    /**
     * Metadata are encrypted or not
     * 
     * @var boolean
     */
    protected $_encryptMetadata = true;
    
    /**
     * The constructor
     * 
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Type_Dictionary $encryptionDicitonary
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    public function __construct
    (
        SetaPDF_Core_Document $document,
        SetaPDF_Core_Type_Dictionary $encryptionDictionary
    )
    {
        // TODO: Implement function to check for mcrypt, hash and desired algorithms 
        
        $this->_document = $document;
        $this->_encryptionDictionary = $encryptionDictionary;
        
        // Mark string elements as not encrypted
        foreach ($this->_encryptionDictionary AS $value) {
            if ($value instanceof SetaPDF_Core_Type_StringValue) {
                $value->setBypassSecHandler(true);
            }
        }
        
        // define the standard key length
        if ($this->_encryptionDictionary->offsetExists('Length')) {
    	    $keyLength = $this->_encryptionDictionary->getValue('Length')->getValue();
    	} else {
    	    $keyLength = 40;
    	}
    	
    	$this->_keyLength = $keyLength / 8;
        
    	// Crypt Filters / V == 4
        if ($this->_encryptionDictionary->offsetExists('CF')) {
            $streamFilterName = $this->_encryptionDictionary->getValue('StmF')->getValue();
            $cryptFilters = $this->_encryptionDictionary->getValue('CF');
            
            // TODO: This can be "Identity" which is a predefined crypt filter
            $streamFilter = $cryptFilters->getValue($streamFilterName);
            
            $cryptFilterMethod = $streamFilter->getValue('CFM')->getValue();
            $keyLength = $streamFilter->offsetExists('Length')
                       ? $streamFilter->getValue('Length')->getValue()
                       : $this->_keyLength;
            
            switch ($cryptFilterMethod) {
                case 'V2':
                    $this->_streamAlgorithm = array(SetaPDF_Core_SecHandler::ARCFOUR, $keyLength);
                    break;
                case 'AESV2':
                    $this->_streamAlgorithm = array(SetaPDF_Core_SecHandler::AES_128, $keyLength);
                    break;
                case 'AESV3':
                    $this->_streamAlgorithm = array(SetaPDF_Core_SecHandler::AES_256, $keyLength);
                    break;
                default:
                    throw new SetaPDF_Core_SecHandler_Exception(
                        'Unsupported Crypt Filter Method: ' . $cryptFilterMethod,
                        SetaPDF_Core_SecHandler_Exception::UNSUPPORTED_CRYPT_FILTER_METHOD
                    );
            }

            $stringFilterName = $this->_encryptionDictionary->getValue('StrF')->getValue();
            $stringFilter = $cryptFilters->getValue($stringFilterName);
            
            $cryptFilterMethod = $stringFilter->getValue('CFM')->getValue();
            $keyLength = $stringFilter->offsetExists('Length')
                       ? $stringFilter->getValue('Length')->getValue()
                       : $this->_keyLength;
            
           switch ($cryptFilterMethod) {
                case 'V2':
                    $this->_stringAlgorithm = array(SetaPDF_Core_SecHandler::ARCFOUR, $keyLength);
                    break;
                case 'AESV2':
                    $this->_stringAlgorithm = array(SetaPDF_Core_SecHandler::AES_128, $keyLength);
                    break;
                case 'AESV3':
                    $this->_stringAlgorithm = array(SetaPDF_Core_SecHandler::AES_256, $keyLength);
                    break;
                default:
                    throw new SetaPDF_Core_SecHandler_Exception(
                        'Unsupported Crypt Filter Method: ' . $cryptFilterMethod,
                        SetaPDF_Core_SecHandler_Exception::UNSUPPORTED_CRYPT_FILTER_METHOD
                    );
            }
            
        // Standard
        } else {
            $this->_streamAlgorithm =
            $this->_stringAlgorithm = array(SetaPDF_Core_SecHandler::ARCFOUR, $this->_keyLength);
        }
        
        if ($this->_encryptionDictionary->offsetExists('EncryptMetadata')) {
            $this->_encryptMetadata = $this->_encryptionDictionary
                                        ->getValue('EncryptMetadata')
                                        ->getValue();
        }
    }
    
	/**
	 * Gets the encryption dictionary
	 * 
     * @return SetaPDF_Core_Type_Dictionary
     */
    public function getEncryptionDictionary()
    {
        return $this->_encryptionDictionary;
    }

    /**
     * Get the revision of the security handler
     *
     * @return mixed
     */
    public function getRevision()
    {
        return $this->_encryptionDictionary->getValue('R')->getValue();
    }

    /**
     * Encrypt a string
     * 
     * @param string $data
     * @param SetaPDF_Core_Type $param
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    public function encryptString($data, $param = null)
    {
        if ($this->isAuth()) 
            return $this->_crypt($data, $this->_stringAlgorithm, $param);
        
        throw new SetaPDF_Core_SecHandler_Exception(
        	'Security handler not authorized to encrypt strings or streams. Authenticate first!',
            SetaPDF_Core_SecHandler_Exception::NOT_AUTHENTICATED
        );
    }
    
    /**
     * Encrypt a stream
     * 
     * @param string $data
     * @param SetaPDF_Core_Type $param
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    public function encryptStream($data, $param = null)
    {
        if ($this->isAuth()) 
            return $this->_crypt($data, $this->_streamAlgorithm, $param);
        
        throw new SetaPDF_Core_SecHandler_Exception(
        	'Security handler not authorized to encrypt strings or streams. Authenticate first!',
            SetaPDF_Core_SecHandler_Exception::NOT_AUTHENTICATED
        );
    }
    
    /**
     * Decrypt a string
     * 
     * @param string $data
     * @param SetaPDF_Core_Type $param
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
	public function decryptString($data, $param = null)
    {
        if ($this->isAuth()) 
            return $this->_crypt($data, $this->_stringAlgorithm, $param, false);
        
        throw new SetaPDF_Core_SecHandler_Exception(
        	'Security handler not authorized to decrypt strings or streams. Authenticate first!',
            SetaPDF_Core_SecHandler_Exception::NOT_AUTHENTICATED
        );
    }
    
    /**
     * Decrypt a stream
     * 
     * @param string $data
     * @param SetaPDF_Core_Type $param
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    public function decryptStream($data, $param = null)
    {
        if ($this->isAuth()) 
            return $this->_crypt($data, $this->_streamAlgorithm, $param, false);
        
        throw new SetaPDF_Core_SecHandler_Exception(
        	'Security handler not authorized to decrypt strings or streams. Authenticate first!',
            SetaPDF_Core_SecHandler_Exception::NOT_AUTHENTICATED
        );
    }
    
    /**
     * Authenticate against the security handler
     * 
     * This method will try to auth first with the owner password.
     * If this will fail it will try to auth to the user password.
     * 
     * @param string $data The password to authenticate with
     * @return boolean Authentification was successfull or not
     */
    public function auth($data = null)
    {
        $data = (string)$data;
        if (false !== $this->authByOwnerPassword($data)) {
            return true;
            
        } elseif (false !== $this->authByUserPassword($data)) {
            return true;
        }
        
        $this->_auth = false;

        return false;
    }
    
    /**
     * Authenticate with the owner password
     * 
     * @param string $password
     * @return boolean
     */
    public function authByOwnerPassword($password)
    {
        if (false !== ($encryptionKey = $this->_authByOwnerPassword($password))) {
            $this->_auth = true;
            $this->_encryptionKey = $encryptionKey;
            $this->_authMode = SetaPDF_Core_SecHandler::OWNER;
            return true;
        }
        
        return false;
    }
    
    /**
     * Authenticate with the user password
     * 
     * @param string $password
     * @return boolean
     */
    public function authByUserPassword($password)
    {
        if (false !== ($encryptionKey = $this->_authByUserPassword($password))) {
            $this->_auth = true;
            $this->_encryptionKey = $encryptionKey;
            $this->_authMode = SetaPDF_Core_SecHandler::USER;
            return true;
        }
        
        return false;
    }
    
    /**
     * Internal method to authenticate with the user password
     * 
     * Returns the encryption key if the authentication was successful. 
     * Returns false if not. 
     * 
     * @param string $userPassword
     * @return string|boolean
     */
    protected function _authByUserPassword($userPassword = '')
    {
        $revision = $this->getRevision();
        if ($revision < 5) {
            // Algorithm 6: Authenticating the user password
            $encryptionKey = $this->_getEncryptionKeyByUserPassword($userPassword);
            
            $uValue = $this->_computeUValue($encryptionKey);
            $originalUValue = $this->_encryptionDictionary->offsetGet('U')->getValue()->getValue(true);
            
            if ($uValue == $originalUValue ||
                $revision >= 3 &&
                substr($uValue, 0, 16) == substr($originalUValue, 0, 16)
            ) {
                return $encryptionKey;
            }
            
        } elseif ($revision == 5) {
            // 1. The password string is generated from Unicode input by processing the input
            //    string with the SASLprep (IETF RFC 4013) profile of stringprep (IETF RFC 3454),
            //    and then converting to a UTF-8 representation.
            
            // 2. Truncate the UTF-8 representation to 127 bytes if it is longer than 127 bytes.
            if (strlen($userPassword) > 127)
                $userPassword = substr($userPassword, 0, 127);
            
            // The first 32 bytes are a hash value (explained below). The next 8 bytes are
            // called the Validation Salt. The final 8 bytes are called the Key Salt.
            $oValue = $this->_encryptionDictionary->offsetGet('O')->getValue()->getValue(true);
            $uValue = $this->_encryptionDictionary->offsetGet('U')->getValue()->getValue(true);
            
            // 4. Test the password against the user key by computing the SHA-256 hash of the 
            //    UTF-8 password concatenated with the 8 bytes of user Validation Salt. If the
            //    32 byte result matches the first 32 bytes of the U string, this is the user
            //    password.
            $validationSalt = substr($uValue, 32, 8);
            $hash = hash('sha256', $userPassword . $validationSalt, true);
            
            if ($hash == substr($uValue, 0, 32)) {
                // Compute an intermediate user key by computing the SHA-256 hash of the UTF-8 password
                // concatenated with the 8 bytes of user Key Salt. The 32-byte result is the key used
                // to decrypt the 32-byte UE string using AES-256 in CBC mode with no padding and an
                // initialization vector of zero. The 32-byte result is the file encryption key.
                $keySalt = substr($uValue, 40, 8);
                $tmpKey = hash('sha256', $userPassword . $keySalt, true);
                
                $ueValue = $this->_encryptionDictionary->offsetGet('UE')->getValue()->getValue(true);
                $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                $encryptionKey = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $tmpKey, $ueValue, MCRYPT_MODE_CBC, str_repeat("\0", $ivSize));
                
                // 5. Decrypt the 16-byte Perms string using AES-256 in ECB mode with an
                //    initialization vector of zero and the file encryption key as the key. Verify 
                //    that bytes 9-11 of the result are the characters ‘a’, ‘d’, ‘b’. Bytes 0-3 of the
                //    decrypted Perms entry, treated as a little-endian integer, are the user
                //    permissions. They should match the value in the P key.
                $perms = $this->_encryptionDictionary->offsetGet('Perms')->getValue()->getValue(true);

                $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
                $tmpPerms = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encryptionKey, $perms, MCRYPT_MODE_ECB, str_repeat("\0", $ivSize));
                
                if (
                    $tmpPerms[9]  == 'a' &&
                    $tmpPerms[10] == 'd' &&
                    $tmpPerms[11] == 'b'
                ) {
                    return $encryptionKey;
                } else {
                    return false;
                }
            }
        }
        
        return false;
    }

    /**
     * Internal method to authenticate with the owner password
     * 
     * Returns the encryption key if the authentication was successful. 
     * Returns false if not. 
     * 
     * @param string $ownerPassword
     * @return string|boolean
     * @throws SetaPDF_Exception_NotImplemented
     */
    protected function _authByOwnerPassword($ownerPassword = '')
    {
        $revision = $this->getRevision();
        if ($revision < 5) {
            // Algorithm 7: Authenticating the owner password
            // a) Compute an encryption key from the supplied password string, as described
            //    in steps (a) to (d) of "Algorithm 3: Computing the encryption dictionary’s
            //    O (owner password) value".
            $s = substr($ownerPassword . self::$_padding, 0, 32);
            $s = md5($s, true);
            if ($revision >= 3) {
                for ($i = 0; $i < 50; $i++)
            	    $s = md5($s, true);
            }
            
    	    $encryptionKey = substr($s, 0, $this->_keyLength);
    	    
    	    // b) (Security handlers of revision 2 only) Decrypt the value of the encryption
    	    //     dictionary’s O entry, using an RC4 encryption function with the encryption
    	    //     key computed in step (a).
    	    $originalOValue = $this->_encryptionDictionary->offsetGet('O')->getValue()->getValue(true);
    	    
    	    if (2 == $revision) {
    	        $userPassword = SetaPDF_Core_SecHandler::arcfour($encryptionKey, $originalOValue);
    
            // (Security handlers of revision 3 or greater) Do the following 20 times: Decrypt
            //  the value of the encryption dictionary’s O entry (first iteration) or the output
            // from the previous iteration (all subsequent iterations), using an RC4 encryption
            // function with a different encryption key at each iteration. The key shall be
            // generated by taking the original key (obtained in step (a)) and performing an XOR
            // (exclusive or)
    	    } elseif ($revision >= 3) {
    	        $userPassword = $originalOValue;
    	        
    	        $length = strlen($encryptionKey);
    	        for($i = 19; $i >= 0; $i--) {
    	        	$tmp = array();
    	        	for($j = 0; $j < $length; $j++) {
    					$tmp[$j] = ord($encryptionKey[$j]) ^ $i;
    					$tmp[$j] = chr($tmp[$j]);
    	        	}
    	        	$userPassword = SetaPDF_Core_SecHandler::arcfour(join('', $tmp), $userPassword);
    	        }
    	    }
    	    
    	    // c) The result of step (b) purports to be the user password. Authenticate this
    	    //    user password using "Algorithm 6: Authenticating the user password". If it
    	    //    is correct, the password supplied is the correct owner password.
    	    return $this->_authByUserPassword($userPassword);
    	    
        } elseif ($revision == 5) {
            
            // 1. The password string is generated from Unicode input by processing the input
            //    string with the SASLprep (IETF RFC 4013) profile of stringprep (IETF RFC 3454),
            //    and then converting to a UTF-8 representation.
            
            // 2. Truncate the UTF-8 representation to 127 bytes if it is longer than 127 bytes.
            if (strlen($ownerPassword) > 127)
                $ownerPassword = substr($ownerPassword, 0, 127);
            
            // The first 32 bytes are a hash value (explained below). The next 8 bytes are
            // called the Validation Salt. The final 8 bytes are called the Key Salt.
            $oValue = $this->_encryptionDictionary->offsetGet('O')->getValue()->getValue(true);
            $uValue = $this->_encryptionDictionary->offsetGet('U')->getValue()->getValue(true);
            
            // 3. Test the password against the owner key by computing the SHA-256 hash of the
            //    UTF-8 password concatenated with the 8 bytes of owner Validation Salt,
            //    concatenated with the 48-byte U string. If the 32-byte result matches the
            //    first 32 bytes of the O string, this is the owner password.
            $validationSalt = substr($oValue, 32, 8);
            $hash = hash('sha256', $ownerPassword . $validationSalt . substr($uValue, 0, 48), true);
            
            if ($hash == substr($oValue, 0, 32)) {
                //    Compute an intermediate owner key by computing the SHA-256 hash of the UTF-8
                //    password concatenated with the 8 bytes of owner Key Salt, concatenated with
                //    the 48-byte U string. The 32-byte result is the key used to decrypt the
                //    32-byte OE string using AES-256 in CBC mode with no padding and
                //    an initialization vector of zero. The 32-byte result is the file encryption key.
                $keySalt = substr($oValue, 40, 8);
                $tmpKey = hash('sha256', $ownerPassword . $keySalt . substr($uValue, 0, 48), true);
                
                $oeValue = $this->_encryptionDictionary->offsetGet('OE')->getValue()->getValue(true);
                $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                $encryptionKey = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $tmpKey, $oeValue, MCRYPT_MODE_CBC, str_repeat("\0", $ivSize));
                
                // 5. Decrypt the 16-byte Perms string using AES-256 in ECB mode with an
                //    initialization vector of zero and the file encryption key as the key. Verify 
                //    that bytes 9-11 of the result are the characters ‘a’, ‘d’, ‘b’. Bytes 0-3 of the
                //    decrypted Perms entry, treated as a little-endian integer, are the user
                //    permissions. They should match the value in the P key.
                $perms = $this->_encryptionDictionary->offsetGet('Perms')->getValue()->getValue(true);

                $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
                $tmpPerms = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encryptionKey, $perms, MCRYPT_MODE_ECB, str_repeat("\0", $ivSize));
                
                if (
                    $tmpPerms[9]  == 'a' &&
                    $tmpPerms[10] == 'd' &&
                    $tmpPerms[11] == 'b'
                ) {
                    return $encryptionKey;
                } else {
                    return false;
                }
            }
            
            return false;
            
        } else {
            throw new SetaPDF_Exception_NotImplemented(
                sprintf('Revision %s not implemented yet.', $revision)
            );
        }
    }
    
    /**
     * Get the auth method (user or owner)
     * 
     * @return string
     */
    public function getAuthMode()
    {
        return $this->_authMode;
    } 
    
    /**
     * Queries if a permission is granted
     * 
     * @param integer $permission
     * @return boolean
     */
    public function getPermission($permission)
    {
        if ($this->isAuth()) { 
            
            // Owner has all access
            if ($this->getAuthMode() === SetaPDF_Core_SecHandler::OWNER)
                return true;

            $p = $this->getPermissions();
            /* We have to switch some permissions depending on the revision.
             * See PDF 32000-1:2008, 7.6.3.2 Table 22
             */
            switch ($permission) {
                case SetaPDF_Core_SecHandler::PERM_DIGITAL_PRINT:
                case SetaPDF_Core_SecHandler::PERM_ASSEMBLE:
                case SetaPDF_Core_SecHandler::PERM_ACCESSIBILITY:
                case SetaPDF_Core_SecHandler::PERM_FILL_FORM: {
                    $revision = $this->getRevision();
                    if ($revision < 3) {
                        switch ($permission) {
                        	case SetaPDF_Core_SecHandler::PERM_DIGITAL_PRINT:
                        	    $permission = SetaPDF_Core_SecHandler::PERM_PRINT;
                        	    break;
                        	case SetaPDF_Core_SecHandler::PERM_ASSEMBLE:
                        	    $permission = SetaPDF_Core_SecHandler::PERM_MODIFY;
                        	    break;
                        	case SetaPDF_Core_SecHandler::PERM_ACCESSIBILITY:
                        	    $permission = SetaPDF_Core_SecHandler::PERM_COPY;
                        	    break;
                        	case SetaPDF_Core_SecHandler::PERM_FILL_FORM:
                        	    $permission = SetaPDF_Core_SecHandler::PERM_MODIFY | SetaPDF_Core_SecHandler::PERM_ANNOT;
                        	    break;
                        }
                    }
                }
            }
            
            return ($p & $permission) !== 0;
        }
        
        return false;
    }
    
    /**
     * Get permission flag
     * 
     * @return integer
     * @see SetaPDF_Core_SecHandler_Interface::getPermissions()
     */
    public function getPermissions()
    {
        $currentPerm = (int)(float)$this->_encryptionDictionary->getValue('P')->getValue();
        return $currentPerm & 
            (
                SetaPDF_Core_SecHandler::PERM_PRINT |
                SetaPDF_Core_SecHandler::PERM_MODIFY |
                SetaPDF_Core_SecHandler::PERM_COPY |
                SetaPDF_Core_SecHandler::PERM_ANNOT |
                SetaPDF_Core_SecHandler::PERM_FILL_FORM |
                SetaPDF_Core_SecHandler::PERM_ACCESSIBILITY |
                SetaPDF_Core_SecHandler::PERM_ASSEMBLE |
                SetaPDF_Core_SecHandler::PERM_DIGITAL_PRINT
            );
    }
    
    /**
     * Queries if the security handler is authenticated
     * 
     * If not it tries by calling auth() without a password
     * 
     * @return boolean
     */
    public function isAuth()
    {
        if (false === $this->_auth)
            $this->auth();
            
        return $this->_auth;
    }
    
    /**
     * Get the encryption key if known/authenticated
     * 
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    public function getEncryptionKey()
    {
        if ($this->isAuth())
            return $this->_encryptionKey;
        
            
        throw new SetaPDF_Core_SecHandler_Exception(
        	'Security handler not authenticated, so no encryption key is known. Authenticate first!',
            SetaPDF_Core_SecHandler_Exception::NOT_AUTHENTICATED
        );
    }
    
	/**
     * En- or decrypts data using Algorithm 1 of the PDF specification
     * 
     * @param string $data
     * @param array $algorithm
     * @param SetaPDF_Core_Type_IndirectObject $param
     * @param boolean $encrypt
     * @return string
     */
    protected function _crypt($data, $algorithm, $param = null, $encrypt = true)
    {
        // Algorithm 1: Encryption of data using the RC4 or AES algorithms
        
        // Use the 32-byte file encryption key for the AES-256 symmetric key algorithm, along
        // with the string or stream data to be encrypted.
        // Use the AES algorithm in Cipher Block Chaining (CBC) mode, which requires an
        // initialization vector. The block size parameter is set to 16 bytes, and the
        // initialization vector is a 16-byte random number that is stored as the first 16
        // bytes of the encrypted stream or string.
        // The output is the encrypted data to be stored in the PDF file.
        if (SetaPDF_Core_SecHandler::AES_256 === $algorithm[0]) {
            if (true === $encrypt)
                return SetaPDF_Core_SecHandler::Aes128Encrypt($this->_encryptionKey, $data);
                
            return SetaPDF_Core_SecHandler::Aes128Decrypt($this->_encryptionKey, $data);
        }
        
        // a) Obtain the object number and generation number from the object
        //    identifier of the string or stream to be encrypted (see 7.3.10,
        //    "Indirect Objects"). If the string is a direct object, use the
        //    identifier of the indirect object containing it.
        // b) For all strings and streams without crypt filter specifier; treating
        //    the object number and generation number as binary integers, extend
        //    the original n-byte encryption key to n + 5 bytes by appending the
        //    low-order 3 bytes of the object number and the low-order 2 bytes of
        //    the generation number in that order, low-order byte first.
        //    (n is 5 unless the value of V in the encryption dictionary is greater
        //    than 1, in which case n is the value of Length divided by 8.)
        // TODO: Check if this works for different documents! 
        $objectData = $this->_document->getIdForObject($param);
        $key = $this->_encryptionKey . pack('VXVXX', $objectData[0], $objectData[1]);
        
        // If using the AES algorithm, extend the encryption key an additional 4 bytes
        // by adding the value “sAlT”, which corresponds to the hexadecimal values 0x73,
        // 0x41, 0x6C, 0x54. (This addition is done for backward compatibility and is not
        // intended to provide additional security.)
        if (SetaPDF_Core_SecHandler::AES_128 === $algorithm[0]) {
            $key .= "\x73\x41\x6c\x54";
        }
        
        // c) Initialize the MD5 hash function and pass the result of step (b) as input
        //    to this function.
        $s = md5($key, true);
        
        // d) Use the first (n + 5) bytes, up to a maximum of 16, of the output from the
        //    MD5 hash as the key for the RC4 or AES symmetric key algorithms, along with
        //    the string or stream data to be encrypted.
        $s = substr(substr($s, 0, $algorithm[1] + 5), 0, 16);
        
        if (SetaPDF_Core_SecHandler::ARCFOUR & $algorithm[0]) {
            return SetaPDF_Core_SecHandler::arcfour($s, $data);
        } 
        // If using the AES algorithm, the Cipher Block Chaining (CBC) mode, which requires
        // an initialization vector, is used. The block size parameter is set to 16 bytes,
        // and the initialization vector is a 16-byte random number that is stored as the
        // first 16 bytes of the encrypted stream or string.
        elseif (SetaPDF_Core_SecHandler::AES_128 === $algorithm[0]) {
            if (true === $encrypt)
                return SetaPDF_Core_SecHandler::Aes128Encrypt($s, $data);            
            
            return SetaPDF_Core_SecHandler::Aes128Decrypt($s, $data);
        }
    }
    
    /**
     * Compute the encryption key based on a password
     *
     * @param string $password
     * @return string
     * @throws SetaPDF_Exception_NotImplemented
     */
    protected function _computeEncryptionKey($password = '')
    {
        $revision = $this->getRevision();
        
        if ($revision <= 4) {
            // TODO: The password string is generated from OS codepage characters by first 
            // converting the string to PDFDocEncoding. If the input is Unicode, first convert
            // to a codepage encoding, and then to PDFDocEncoding for backward compatibility.
            
            
            // Algorithm 2: Computing an encryption key
            // a) Pad or truncate the password string to exactly 32 bytes.
            // b) Initialize the MD5 hash function and pass the result of step (a) as input to this function.
            $s = substr($password . self::$_padding, 0, 32);
            
            // c) Pass the value of the encryption dictionary’s O entry to the MD5 hash function.
            //    ("Algorithm 3: Computing the encryption dictionary’s O (owner password) value" shows how the O value is computed.)
            $s .= $this->_encryptionDictionary->offsetGet('O')->getValue()->getValue(true);
            
            // d) Convert the integer value of the P entry to a 32-bit unsigned binary number and pass these
            //    bytes to the MD5 hash function, low-order byte first.
            $pValue = $this->_encryptionDictionary->offsetGet('P')->getValue()->getValue();
            $pValue = (int)(float)$pValue;
            $s .= pack("V", $pValue);
            
            // e) Pass the first element of the file’s file identifier array (the value of the ID
            //    entry in the document’s trailer dictionary; see Table 15) to the MD5 hash function.
            $s .= $this->_document->getFileIdentifier(true);
            
            // f) (Security handlers of revision 4 or greater) If document metadata is not 
            //    being encrypted, pass 4 bytes with the value 0xFFFFFFFF to the MD5 hash function.
            if ($revision == 4 && $this->_encryptMetadata == false) {
                $s .= "\xFF\xFF\xFF\xFF";
            }
            
            // g) Finish the hash.
            $s = md5($s, true);
            
            // h) (Security handlers of revision 3 or greater) Do the following 50 times:
            //    Take the output from the previous MD5 hash and pass the first n bytes of
            //    the output as input into a new MD5 hash, where n is the number of bytes
            //    of the encryption key as defined by the value of the encryption dictionary’s
            //    Length entry.
            if (3 <= $revision) {
        	    for ($i = 0; $i < 50; $i++)
                	$s = md5(substr($s, 0, $this->_keyLength), true);
            }
            
            // i) Set the encryption key to the first n bytes of the output from the final
            //    MD5 hash, where n shall always be 5 for security handlers of revision 2 but,
            //    for security handlers of revision 3 or greater, shall depend on the value of
            //    the encryption dictionary’s Length entry.
            
            return substr($s, 0, $this->_keyLength); // keylength is calculated automatically if it is missing (5)
            
        } elseif ($revision == 5) {
            
            return hash(
            	'sha256',
                uniqid('sAlT') . microtime() . mt_rand()
              . $this->_document->getFileIdentifier(true) . __FILE__,
                true
            );
            
        } else {
            throw new SetaPDF_Exception_NotImplemented(
                sprintf('Revision %s not implemented yet.', $revision)
            );
        }
    }
    
    /**
     * Compute the O value
     * 
     * @param string $userPassword
     * @param string $ownerPassword
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    protected function _computeOValue($userPassword, $ownerPassword = '')
    {
        $revision = $this->getRevision();
        // Algorithm 3: Computing the encryption dictionary’s O (owner password) value
        if ($revision < 5) {
                
            // a) Pad or truncate the owner password string as described in step (a) of
            //    "Algorithm 2: Computing an encryption key". If there is no owner password,
            //    use the user password instead.
            if ('' === $ownerPassword)
                $ownerPassword = $userPassword;
            
            $s = substr($ownerPassword . self::$_padding, 0, 32);
              
            // b) Initialize the MD5 hash function and pass the result of step (a) as input to
            //    this function.
            $s = md5($s, true);
            
            // c) (Security handlers of revision 3 or greater) Do the following 50 times:
            //    Take the output from the previous MD5 hash and pass it as input into a new MD5 hash.
            if (3 <= $revision) {
                for ($i = 0; $i < 50; $i++)
            	    $s = md5($s, true);
            }
            
            // d) Create an RC4 encryption key using the first n bytes of the output from the
            //    final MD5 hash, where n shall always be 5 for security handlers of revision 2
            //    but, for security handlers of revision 3 or greater, shall depend on the value
            //    of the encryption dictionary’s Length entry.
            $encryptionKey = substr($s, 0, $this->_keyLength);
            
            // e) Pad or truncate the user password string as described in step (a) of
            //    "Algorithm 2: Computing an encryption key".
            $s = substr($userPassword . self::$_padding, 0, 32);
            
            // f) Encrypt the result of step (e), using an RC4 encryption function with 
            //    the encryption key obtained in step (d).
            $s = SetaPDF_Core_SecHandler::arcfour($encryptionKey, $s);
            
            // g) (Security handlers of revision 3 or greater) Do the following 19 times:
            //    Take the output from the previous invocation of the RC4 function and pass
            //    it as input to a new invocation of the function; use an encryption key
            //    generated by taking each byte of the encryption key obtained in step (d)
            //    and performing an XOR (exclusive or) operation between that byte and the
            //    single-byte value of the iteration counter (from 1 to 19).
            if (3 <= $revision) {
                for($i = 1; $i <= 19; $i++) {
    	        	$tmp = array();
    	        	for($j = 0; $j < $this->_keyLength; $j++) {
    					$tmp[$j] = ord($encryptionKey[$j]) ^ $i;
    					$tmp[$j] = chr($tmp[$j]);
    	        	} 
    	        	$s = SetaPDF_Core_SecHandler::arcfour(join('', $tmp), $s);
    	        }
            }
            
            // h) Store the output from the final invocation of the RC4 function as the value
            //    of the O entry in the encryption dictionary.
            return $s;
            
        } elseif ($revision == 5) {
            // 1. Generate 16 random bytes of data using a strong random number generator. The
            //    first 8 bytes are the Owner Validation Salt. The second 8 bytes are the Owner
            //    Key Salt. Compute the 32-byte SHA-256 hash of the password concatenated with
            //    the Owner Validation Salt and then concatenated with the 48-byte U string as
            //    generated in Algorithm 3.8. The 48-byte string consisting of the 32-byte hash
            //    followed by the Owner Validation Salt followed by the Owner Key Salt is stored
            //    as the O key.
            $rand = md5(microtime() . mt_rand() . $this->_document->getFileIdentifier(true) . __FILE__, true);
            $validationSalt = substr($rand, 0, 8);
            $keySalt = substr($rand, 8, 16);
            $uValue = $this->_encryptionDictionary->offsetGet('U')->getValue()->getValue();

            $hash = hash('sha256', $ownerPassword . $validationSalt . $uValue, true);
            
            return $hash . $validationSalt . $keySalt;
            
        } else {
            
            throw new SetaPDF_Core_SecHandler_Exception(
                sprintf('Unsupported Revision: %s', $revision),
                SetaPDF_Core_SecHandler_Exception::UNSUPPORTED_REVISION
            );
        }
    }
    
    /**
     * Compute the U value
     * 
     * @param string $encryptionKey
     * @return string
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    protected function _computeUValue($encryptionKey)
    {
        $revision = $this->getRevision();
        // Algorithm 4: Computing the encryption dictionary’s U (user password)
        // value (Security handlers of revision 2)	        	
        if (2 == $revision) {
    	    return SetaPDF_Core_SecHandler::arcfour($encryptionKey, self::$_padding);
    	}
    	
        // Algorithm 5: Computing the encryption dictionary’s U (user password)
        // value (Security handlers of revision 3 or greater)
        elseif (
            3 == $revision || 4 == $revision
        ) {
            // a) Create an encryption key based on the user password string, as described
            //    in "Algorithm 2: Computing an encryption key".
            //    passed through $encryptionKey-parameter
            
            // b) Initialize the MD5 hash function and pass the 32-byte padding string shown
            //    in step (a) of "Algorithm 2: Computing an encryption key" as input to
            //    this function.
            $s = self::$_padding;
            
            // c) Pass the first element of the file’s file identifier array (the value of
            //    the ID entry in the document’s trailer dictionary; see Table 15) to the
            //    hash function and finish the hash.
            $s .= $this->_document->getFileIdentifier(true);
            $s = md5($s, true);
            
    		// d) Encrypt the 16-byte result of the hash, using an RC4 encryption function
    		//    with the encryption key from step (a).
    		$s = SetaPDF_Core_SecHandler::arcfour($encryptionKey, $s);
    		
    		// e) Do the following 19 times: Take the output from the previous invocation
    		//    of the RC4 function and pass it as input to a new invocation of the function;
    		//    use an encryption key generated by taking each byte of the original encryption
    		//    key obtained in step (a) and performing an XOR (exclusive or) operation 
    		//    between that byte and the single-byte value of the iteration counter (from 1 to 19).
            $length = strlen($encryptionKey);
    		for($i = 1; $i <= 19; $i++) {
	        	$tmp = array();
	        	for($j = 0; $j < $length; $j++) {
					$tmp[$j] = ord($encryptionKey[$j]) ^ $i;
					$tmp[$j] = chr($tmp[$j]);
	        	} 
	        	$s = SetaPDF_Core_SecHandler::arcfour(join('', $tmp), $s);
	        }
	        
	        // f) Append 16 bytes of arbitrary padding to the output from the final invocation
	        //    of the RC4 function and store the 32-byte result as the value of the U entry
	        //    in the encryption dictionary.
	        return $s . str_repeat("\0", 16);
	        
        } elseif (5 == $revision) {
            $userPassword = $encryptionKey;
            // 1. Generate 16 random bytes of data using a strong random number generator. The 
            //    first 8 bytes are the User Validation Salt. The second 8 bytes are the User Key
            //    Salt. Compute the 32-byte SHA-256 hash of the password concatenated with the 
            //    User Validation Salt. The 48-byte string consisting of the 32-byte hash followed
            //    by the User Validation Salt followed by the User Key Salt is stored as the U key.
            $rand = md5(microtime() . mt_rand() . $this->_document->getFileIdentifier(true) . __FILE__, true);
            $validationSalt = substr($rand, 0, 8);
            $keySalt = substr($rand, 8, 16);
            $hash = hash('sha256', $userPassword . $validationSalt, true);
           
            return $hash . $validationSalt . $keySalt;
            
        } else {
            throw new SetaPDF_Core_SecHandler_Exception(
                sprintf('Unsupported Revision: %s', $revision),
                SetaPDF_Core_SecHandler_Exception::UNSUPPORTED_REVISION
            );
        }
        
    }
    
    /**
     * Get the encryption key by the user password
     * 
     * @param string $password
     * @return string
     */
    protected function _getEncryptionKeyByUserPassword($password = '')
    {
        return $this->_computeEncryptionKey($password);
    }
    
    /**
     * Get the PDF version, which is needed for the currently used encryption algorithm
     *
     * @return string
     * @throws SetaPDF_Exception_NotImplemented
     */
    public function getPdfVersion()
    {
        $algoCode = $this->_encryptionDictionary->offsetGet('V')->getValue()->getValue();
        
        switch ($algoCode)
        {
            case 0: // undocumented
            case 1:
                return '1.3';
                
            case 2:
            case 3:
                return '1.4';
                
            case 4:
                if ($this->_encryptionDictionary->offsetExists('EFF'))
                    return '1.6';
                    
                return '1.5';
                
            case 5:
                return '1.7';
                
            default:
                throw new SetaPDF_Exception_NotImplemented(
                    sprintf('Algorithm with code %s not implemented.', $algoCode)
                );
        }
    }
    
    /**
     * Returns true if the metadata are/will be encrypted
     * 
     * @return boolean
     */
    public function getEncryptMetadata()
    {
        return $this->_encryptMetadata;
    }
}