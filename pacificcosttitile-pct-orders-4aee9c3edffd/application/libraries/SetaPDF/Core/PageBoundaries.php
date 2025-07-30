<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: PageBoundaries.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Page Boundaries
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_PageBoundaries
{
    /**
     * MediaBox
     *
     * @var string
     */
    const MEDIA_BOX = 'MediaBox';

    /**
     * CropBox
     *
     * @var string
     */
    const CROP_BOX = 'CropBox';

    /**
     * BleedBox
     *
     * @var string
     */
    const BLEED_BOX = 'BleedBox';

    /**
     * TrimBox
     *
     * @var string
     */
    const TRIM_BOX = 'TrimBox';

    /**
     * ArtBox
     *
     * @var string
     */
    const ART_BOX = 'ArtBox';

    /**
     * All page boundaries
     *
     * @var array
     */
    static public $all = array(
        self::MEDIA_BOX, self::CROP_BOX, self::BLEED_BOX,
        self::TRIM_BOX, self::ART_BOX
    );

    /**
     * Checks if a name is a valid page boundary name
     *
     * @param string $name
     * @return boolean
     */
    static public function isValidName($name)
    {
        return in_array($name, self::$all);
    }

    /**
     * Prohibit object initiation by defining the constructor to be private
     */
    private function __construct()
    {
    }
}