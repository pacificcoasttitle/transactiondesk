<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Flags.php 437 2013-05-15 08:50:28Z jan.slabon $
 */

/**
 * A class representing named annotation flags
 *
 * See PDF 32000-1:2008 - 12.5.3, Table 165
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Flags
{
    /**#@+
     * Annotation flags defined in PDF 32000-1:2008 - 12.5.3 / Table 165
     *
     * @var integer
     */
    const INVISIBLE       = 0x01; // bit 1
    const HIDDEN          = 0x02; // 2
    const PRINTS          = 0x04; // 3
    const NO_ZOOM         = 0x08; // 4
    const NO_ROTATE       = 0x10; // 5
    const NO_VIEW         = 0x20; // 6
    const READ_ONLY       = 0x40; // 7
    const LOCKED          = 0x80; // 8
    const TOGGLE_NO_VIEW  = 0x100; // 9
    const LOCKED_CONTENTS = 0x200; // bit 10
    /**#@-*/

    /**
     * Prohibit object initiation by defining the constructor to be private
     */
    private function __construct()
    {
    }
}