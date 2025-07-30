<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: StreamProxyInterface.php 298 2012-10-31 14:36:24Z maximilian $
 */

/**
 * Interface of a StreamProxy
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    http://www.setasign.de/ Commercial
 */
interface SetaPDF_Core_Canvas_StreamProxyInterface
    extends SetaPDF_Core_WriteInterface
{
    /**
     * Clears the complete canvas content
     */
    public function clear();

    /**
     * Get the whole byte stream of the canvas
     *
     * @return string
     */
    public function getStream();
}