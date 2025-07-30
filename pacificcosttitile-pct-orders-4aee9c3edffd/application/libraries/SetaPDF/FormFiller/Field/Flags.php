<?php 
/**
 * This file is part of the SetaPDF-FormFiller Component
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Flags.php 261 2012-06-01 06:46:42Z jan $
 */

/**
 * A class representing named form field flags
 * 
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_FormFiller_Field_Flags
{
  /* Field Flags for all field types */
                                    
    const READ_ONLY =               0x01;         // 1
    const REQUIRED =                0x02;         // 2
    const NO_EXPORT =               0x04;         // 3
    
  /* Field flags for Button Fields */
    
    const NO_TOGGLE_TO_OFF =        0x4000;       // 15
    const RADIO =                   0x8000;       // 16
    const PUSHBUTTON =              0x010000;     // 17
    const RADIOS_IN_UNISON =        0x02000000;   // 26
    
  /* Field flags for Text Fields */
    
    const MULTILINE =               0x1000;       // 13
    const PASSWORD =                0x2000;       // 14
    const FILE_SELECT =             0x100000;     // 21
    const DO_NOT_SPELL_CHECK =      0x400000;     // 23
    const DO_NOT_SCROLL =           0x800000;     // 24
    const COMB =                    0x01000000;   // 25
    const RICH_TEXT =               0x02000000;   // 26
    
  /* Field flags for Choice Fields */
    
    const COMBO =                   0x020000;     // 18
    const EDIT =                    0x040000;     // 19
    const SORT =                    0x080000;     // 20
    const MULTI_SELECT =            0x200000;     // 22
    const COMMIT_ON_SEL_CHANGE =    0x04000000;   // 27  

    /**
     * Prohibit object initiation by defining the constructor to be private
     */
    private function __construct()
    {
    }
}