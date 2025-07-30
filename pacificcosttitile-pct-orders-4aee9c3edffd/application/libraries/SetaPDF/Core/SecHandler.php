<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: SecHandler.php 396 2013-02-18 15:30:18Z maximilian.kresse $
 */

/**
 * Main class for PDF security handlers
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_SecHandler
{
    const STANDARD = 'standard';
    const PUB_KEY = 'publicKey';

    /**#@+
     * Encryption constant
     *
     * @var string
     */
    const ARCFOUR = 4;
    const ARCFOUR_40 = 12; // 8 | 4
    const ARCFOUR_128 = 20; // 16 | 4
    const AES = 32;
    const AES_128 = 96; // 64 | 32
    const AES_256 = 160; // 128 | 32
    /**#@-*/

    /**#@+
     * Permission constant
     *
     * @var string
     */
    const PERM_PRINT = 4; // 3
    const PERM_MODIFY = 8; // 4
    const PERM_COPY = 16; // 5
    const PERM_ANNOT = 32; // 6
    const PERM_FILL_FORM = 256; // 9
    const PERM_ACCESSIBILITY = 512; // 10
    const PERM_ASSEMBLE = 1024; // 11
    const PERM_DIGITAL_PRINT = 2048; // 12
    /**#@-*/

    /**
     * User auth mode
     *
     * @var string
     */
    const USER = 'user';

    /**
     * Owner auth mode
     *
     * @var string
     */
    const OWNER = 'owner';

    static public $userMcrypt = true;

    /**
     * Checks a permission against a security handler of a document
     *
     * @param SetaPDF_Core_Document $document
     * @param integer $permission
     * @param string $message
     * @return bool
     * @throws SetaPDF_Core_SecHandler_Exception
     */
    static public function checkPermission(SetaPDF_Core_Document $document, $permission, $message = null)
    {
        if (
            $document->hasSecurityHandler() &&
            false === $document->getSecHandler()->getPermission($permission)
        ) {
            if (null === $message) {
                switch ($permission) {
                    case self::PERM_ACCESSIBILITY:
                        $message = 'You are not allowed to extract text and graphics in support of '
                                 . 'accessibility to users with disabilities or for other purposes.';
                        break;
                    case self::PERM_ANNOT:
                        $message = 'You are not allowed to add or modify text annotations and fill in interactive form fields.';
                        break;
                    case self::PERM_ASSEMBLE:
                        $message = 'You are not allowed to assemble the document.';
                        break;
                    case self::PERM_COPY:
                        $message = 'You are not allowed to copy or otherwise extract text and graphics from the document.';
                        break;
                    case self::PERM_DIGITAL_PRINT:
                        $message = 'You are not allowed to print the document to a representation, from '
                                 . 'which a faithful digital copy of the PDF content could be generated.';
                        break;
                    case self::PERM_FILL_FORM:
                        $message = 'You are not allowed to fill in existing interactive form fields.';
                        break;
                    case self::PERM_PRINT:
                        $message = 'You are not allowed to print the document.';
                        break;
                    case self::PERM_MODIFY:
                        $message = 'You are not allowed to modify contents of this document.';
                        break;
                }
            }

            throw new SetaPDF_Core_SecHandler_Exception(
                $message,
                SetaPDF_Core_SecHandler_Exception::NOT_ALLOWED
            );
        }

        return true;
    }

    /**
     * Returns a standard predefined security handler
     *
     * The type parameter will define things like algorithm and key length.
     * Additionally the type could be an encryption dictionary,
     * which will setup the desired security handler.
     *
     * @param SetaPDF_Core_Document $document
     * @param SetaPDF_Core_Type_Dictionary $encryptionDictionary
     * @return SetaPDF_Core_SecHandler_Interface
     * @throws SetaPDF_Core_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    static public function factory(SetaPDF_Core_Document $document, SetaPDF_Core_Type_Dictionary $encryptionDictionary)
    {
        $filter = $encryptionDictionary->offsetGet('Filter');
        if (null === $filter) {
            throw new SetaPDF_Core_Exception("Missing filter key in encryption dictionary.");
        }

        $filterName = $filter->ensure()->getValue();

        switch ($filterName) {
            case 'Standard':
                // cloning is needed, because the encryption dicitonary will be written
                // as one of the first objects at all
                $handler = new SetaPDF_Core_SecHandler_Standard($document, clone $encryptionDictionary);
                return $handler;

                break;
            case 'Adobe.PubSec':
                // TODO: Implement
                throw new SetaPDF_Exception_NotImplemented(
                    sprintf('Encryption filter (%s) not supported yet.', $filterName)
                );

                break;
            default:
                throw new SetaPDF_Exception_NotImplemented(
                    sprintf('Encryption filter (%s) not supported yet.', $filterName)
                );
        }
    }

    /**
     * En- or decrypts data using the RC4/Arcfour algorithm.
     *
     * @param string $key
     * @param string $data
     * @return string
     */
    static public function arcfour($key, $data)
    {
        if (self::$userMcrypt && function_exists('mcrypt_decrypt')) {
            return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM, '');
        }

        static $_lastRc4Key = null, $_lastRc4KeyValue = null;

        if ($_lastRc4Key !== $key) {
            $k = str_repeat($key, 256 / strlen($key) + 1);
            $rc4 = range(0, 255);
            $j = 0;
            for ($i = 0; $i < 256; $i++) {
                $rc4[$i] = $rc4[$j = ($j + ($t = $rc4[$i]) + ord($k{$i})) % 256];
                $rc4[$j] = $t;
            }
            $_lastRc4Key = $key;
            $_lastRc4KeyValue = $rc4;

        } else {
            $rc4 = $_lastRc4KeyValue;
        }

        $len = strlen($data);
        $newData = '';
        $a = 0;
        $b = 0;
        for ($i = 0; $i < $len; $i++) {
            $b = ($b + ($t = $rc4[$a = ($a + 1) % 256])) % 256;
            $rc4[$a] = $rc4[$b];
            $rc4[$b] = $t;
            $newData .= chr(ord($data{$i}) ^ $rc4[($rc4[$a] + $rc4[$b]) % 256]);
        }

        return $newData;
    }

    /**
     * Encrypts data using AES 128 bit algorithm.
     *
     * @param string $key
     * @param string $data
     * @return string
     */
    static public function aes128Encrypt($key, $data)
    {
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        // pad the original string
        $pad = 16 - (strlen($data) % 16);
        $data = $data . str_repeat(chr($pad), $pad);

        $data = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

        return $iv . $data;
    }

    /**
     * Decrypts data using AES 128 bit algorithm.
     *
     * @param string $key
     * @param string $data
     * @return string
     */
    static public function aes128Decrypt($key, $data)
    {
        $iv = substr($data, 0, 16);
        $data = substr($data, 16);

        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
        return substr($data, 0, -ord($data[strlen($data) - 1]));
    }
}