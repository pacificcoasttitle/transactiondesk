<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Link.php 408 2013-02-26 13:55:24Z jan.slabon $
 */

/**
 * Class representing a markup annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.2
 *
 * Markup annotations are:
 * - Text
 * - Free text annotations (no Popup)
 * - Line
 * - Square
 * - Circle
 * - Polygon
 * - PolyLine
 * - Highlight
 * - Underline
 * - Squiggly
 * - StrikeOut
 * - Stamp
 * - Caret
 * - Ink
 * - FileAttachment
 * - Sound (no Popup)
 * - Redact
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Markup
    extends SetaPDF_Core_Document_Page_Annotation
{
    /**
     * Get the associated popup object if available
     *
     * @return null|SetaPDF_Core_Document_Page_Annotation_Popup
     */
    public function getPopup()
    {
        if (!$this->_annotationDictionary->offsetExists('Popup')) {
            return null;
        }

        return new SetaPDF_Core_Document_Page_Annotation_Popup(
            $this->_annotationDictionary->getValue('Popup')
        );
    }

    /**
     * Set the pop-up annotation object
     *
     * @todo This method should be deactivated in "Free text annotations" and "Sound annotations"
     *
     * @param SetaPDF_Core_Document_Page_Annotation_Popup $annotation
     * @throws InvalidArgumentException
     */
    public function setPopup(SetaPDF_Core_Document_Page_Annotation_Popup $annotation)
    {
        $object = $annotation->getIndirectObject();
        if (!$object instanceof SetaPDF_Core_Type_IndirectObjectInterface) {
            throw new InvalidArgumentException(
                'Adding a popup annotation to a text annotation requires that ' .
                    'the popup annotation is attached to an indirect object.'
            );
        }

        $annotation->setParent($this);

        $this->_annotationDictionary->offsetSet('Popup', $object);
    }

    /**
     * Get the creation date
     *
     * <quote>
     * The date and time when the annotation was created.
     * </quote>
     *
     * @see setCreationDate
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @param $asString
     * @return mixed|null|SetaPDF_Core_DataStructure_DateG
     */
    public function getCreationDate($asString)
    {
        if (!$this->_annotationDictionary->offsetExists('CreationDate'))
            return null;

        $date = $this->_annotationDictionary->getValue('CreationDate')->ensure()->getValue();
        if (true === $asString) {
            return $date;
        }

        return new SetaPDF_Core_DataStructure_Date($date);
    }

    /**
     * Create a popup annotation object for this annotation
     *
     * If the x-offset value is less than zero the popup will be created at the left side of
     * the main annotation. Otherwise on the right side.
     * If the y-offset value is less than zero the popup will be create down below the main
     * annotation. Otherwise above.
     *
     * @param int $offsetX
     * @param int $offsetY
     * @param int $width
     * @param int $height
     *
     * @return SetaPDF_Core_Document_Page_Annotation_Popup
     */
    public function createPopup($offsetX = 30, $offsetY = 20, $width = 150, $height = 100)
    {
        $rect = $this->getRect();
        if ($offsetX >= 0) {
            $llx = $rect->getUrx() + $offsetX;
            $urx = $llx + $width;
        } else {
            $llx = $rect->getLlx() - $width - $offsetX;
            $urx = $llx - $width;
        }

        if ($offsetY >= 0) {
            $lly = $rect->getLly() + $offsetY;
            $ury = $lly + $height;
        } else {
            $lly = $rect->getLly() - $width - $offsetX;
            $ury = $lly - $height;
        }

        return new SetaPDF_Core_Document_Page_Annotation_Popup(array($llx, $lly, $urx, $ury));
    }

    /**
     * Set the creation date
     *
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @see getCreationDate
     * @param $date
     */
    public function setCreationDate($date = true)
    {
        if ($date === null) {
            $this->_annotationDictionary->offsetUnset('CreationDate');
            return;
        }

        if (!($date instanceof SetaPDF_Core_DataStructure_Date))
            $date = new SetaPDF_Core_DataStructure_Date($date !== true ? new SetaPDF_Core_Type_String($date) : null);

        $this->_annotationDictionary->offsetSet('CreationDate', $date->getValue());
    }

    /**
     * Get the text label
     *
     * <quote>
     * The text label that shall be displayed in the title bar of the annotation’s pop-up window when open and active.
     * This entry shall identify the user who added the annotation.
     * </quote>
     *
     * @see setTextLabel
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @param string $encoding
     * @return null|string
     */
    public function getTextLabel($encoding = 'UTF-8')
    {
        if (!$this->_annotationDictionary->offsetExists('T'))
            return null;

        return SetaPDF_Core_Encoding::convertPdfString($this->_annotationDictionary->getValue('T')->getValue(), $encoding);
    }

    /**
     * Set the text label
     *
     * @see getTextLabel
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @param string|null $textLabel
     * @param string $encoding
     */
    public function setTextLabel($textLabel, $encoding = 'UTF-8')
    {
        if (null == $textLabel) {
            $this->_annotationDictionary->offsetUnset('T');
            return;
        }

        $textLabel = SetaPDF_Core_Encoding::toPdfString($textLabel, $encoding);

        if (!$this->_annotationDictionary->offsetExists('T')) {
            $this->_annotationDictionary->offsetSet('T', new SetaPDF_Core_Type_String($textLabel));
            return;
        }

        $this->_annotationDictionary->getValue('T')->setValue($textLabel);
    }

    /**
     * Get the subject
     *
     * <quote>
     * Text representing a short description of the subject being addressed by the annotation.
     * </quote>
     *
     * @see setSubject
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @param string $encoding
     * @return null|string
     */
    public function getSubject($encoding = 'UTF-8')
    {
        if (!$this->_annotationDictionary->offsetExists('Subj'))
            return null;

        return SetaPDF_Core_Encoding::convertPdfString($this->_annotationDictionary->getValue('Subj')->getValue(), $encoding);
    }

    /**
     * Get the subject
     *
     * @see getSubject
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @param string|null $subject
     * @param string $encoding
     */
    public function setSubject($subject, $encoding = 'UTF-8')
    {
        if (null == $subject) {
            $this->_annotationDictionary->offsetUnset('Subj');
            return;
        }

        $subject = SetaPDF_Core_Encoding::toPdfString($subject, $encoding);

        if (!$this->_annotationDictionary->offsetExists('Subj')) {
            $this->_annotationDictionary->offsetSet('Subj', new SetaPDF_Core_Type_String($subject));
            return;
        }

        $this->_annotationDictionary->getValue('Subj')->setValue($subject);
    }

    /**
     * Set the in reply to annotation object
     *
     * @see getInReplyTo
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @param SetaPDF_Core_Document_Page_Annotation_Markup $annotation
     * @throws InvalidArgumentException
     */
    public function setInReplyTo(SetaPDF_Core_Document_Page_Annotation_Markup $annotation)
    {
        $object = $annotation->getIndirectObject();
        if (!$object instanceof SetaPDF_Core_Type_IndirectObjectInterface) {
            throw new InvalidArgumentException(
                'Adding a reply-to annotation to a markup annotation requires that ' .
                'the markup annotation is attached to an indirect object.'
            );
        }

        $this->_annotationDictionary->offsetSet('IRT', $object);
    }

    /**
     * Get the in reply to annotation (if available)
     *
     * @see setInReplyTo
     * @see PDF 32000-1:2008 - 12.5.6.2 - Table 170
     * @return null|SetaPDF_Core_Document_Action
     */
    public function getInReplyTo()
    {
        if (!$this->_annotationDictionary->offsetExists('IRT'))
            return null;

        return SetaPDF_Core_Document_Page_Annotation::byObjectOrDictionary(
            $this->_annotationDictionary->getValue('IRT')
        );
    }

    /**
     * Checks if this annotation is a reply to another annotation
     *
     * @return bool
     */
    public function isReplyTo()
    {
        return $this->_annotationDictionary->getValue('IRT') !== null;
    }

    /**
     * Adds a reply to this annotation
     *
     * @param SetaPDF_Core_Document_Page_Annotation_Markup $annotation
     */
    public function addReply(SetaPDF_Core_Document_Page_Annotation_Markup $annotation)
    {
        $annotation->setInReplyTo($this);
    }
}