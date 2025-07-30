<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Flate.php 332 2012-11-12 16:42:55Z maximilian $
 */

/**
 * Class for handling zlib/deflate compression
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Filter_Flate extends SetaPDF_Core_Filter_Predictor
{
    /**
     * Returns whether the zlib extension is loaded
     *
     * @return boolean
     */
    protected function _extensionLoaded() {
        return extension_loaded('zlib');
    }

    /**
     * Decodes a flate compressed string
     *
     * @param string $data
     * @return string
     * @throws SetaPDF_Core_Filter_Exception
     */
    public function decode($data)
    {
        // TODO: better errorhandling ($php_errormsg)
        if ($this->_extensionLoaded()) {
            $oData = $data;
            $data = @((strlen($data) > 0) ? gzuncompress($data) : '');
            if (false === $data) {
                // Try this fallback
                $data = @(gzinflate(substr($oData, 2)));

                if (false === $data) {
                    throw new SetaPDF_Core_Filter_Exception(
                        'Error while decompressing stream.',
                        SetaPDF_Core_Filter_Exception::DECOMPRESS_ERROR
                    );
                }
            }
        } else {
            throw new SetaPDF_Core_Filter_Exception(
                'To handle FlateDecode filter, enable zlib support in PHP.',
                SetaPDF_Core_Filter_Exception::NO_ZLIB
            );
        }

        return parent::decode($data);
    }

    /**
     * Encodes a string with flate compression
     *
     * @param string $data
     * @return string
     * @throws SetaPDF_Core_Filter_Exception
     */
    public function encode($data)
    {
        $data = parent::encode($data);

        if ($this->_extensionLoaded()) {
            $data = gzcompress($data);
        } else {
            throw new SetaPDF_Core_Filter_Exception(
                'To handle FlateDecode filter, enable zlib support in PHP.',
                SetaPDF_Core_Filter_Exception::NO_ZLIB
            );
        }

        return $data;
    }
}