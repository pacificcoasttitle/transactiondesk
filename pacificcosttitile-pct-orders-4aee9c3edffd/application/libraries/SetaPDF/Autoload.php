<?php
/**
 * This file is part of the SetaPDF package
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @package    SetaPDF
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Autoload.php 298 2012-10-31 14:36:24Z maximilian $
 */

if (!function_exists('setapdf_autoload')) {

    /**
     * Global autoload function for SetaPDF class files
     *
     * @param string $class The classname
     * @return void
     */
    function setapdf_autoload($class)
    {
        static $path = null;

        if (strpos($class, 'SetaPDF_') === 0) {
            if (null === $path) {
                $path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
            }

            $filename = str_replace('_', '/', $class) . '.php';
            $fullpath = $path . DIRECTORY_SEPARATOR . $filename;

            if (file_exists($fullpath)) {
                require_once $fullpath;
            }
        }
    }

    spl_autoload_register('setapdf_autoload');
}