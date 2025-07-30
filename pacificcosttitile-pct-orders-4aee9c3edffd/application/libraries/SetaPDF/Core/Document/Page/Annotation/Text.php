<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2012 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 * @version    $Id: Link.php 408 2013-02-26 13:55:24Z jan.slabon $
 */

/**
 * Class representing a Text annotation
 *
 * See PDF 32000-1:2008 - 12.5.6.14
 *
 * A text annotations icon will display a static predefined icon which will not resize if the
 * document is zoomed. It will be aligned to the upper left corner of the Rect.
 *
 * By setting the no rotate flag ({@see SetaPDF_Core_Document_Page_Annotation::setNoRotateFlag})
 * and the no-zoom flag ({@see SetaPDF_Core_Document_Page_Annotation::setNoZoomFlag}) the fixed
 * size can be disabled and will allow you to define the size of the annotation your own. Anyhow
 * the annotation is still not zoomable.
 *
 * The aspect ratio of default icons are:
 * Comment: 20 x 18
 * Key: 18 x 17
 * Note: 18 x 20
 * Help: 20 x 20
 * NewParagraph: 13 x 20
 * Paragraph: 11 x 20
 * Insert: 20 x 17
 *
 * @copyright  Copyright (c) 2013 Setasign - Jan Slabon (http://www.setasign.de)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Document
 * @license    http://www.setasign.de/ Commercial
 */
class SetaPDF_Core_Document_Page_Annotation_Text
    extends SetaPDF_Core_Document_Page_Annotation_Markup
{

    /**#@+
     * Icon names defined in PDF 32000-1:2008 - 12.5.6.4 Text Annotations
     *
     * @var integer
     */
    const ICON_COMMENT = 'Comment'; // Default Size: 20 x 18
    const ICON_KEY = 'Key'; // Default Size: 18 x 17
    const ICON_NOTE = 'Note'; // Default Size: 18 x 20
    const ICON_HELP = 'Help'; // Default Size: 20 x 20
    const ICON_NEW_PARAGRAPH = 'NewParagraph'; // Default Size: 13 x 20
    const ICON_PARAGRAPH = 'Paragraph'; // Default Size: 11 x 20
    const ICON_INSERT = 'Insert'; // Default Size: 20 x 17
    /**#@-*/

    /**#@+
     * State model names
     *
     * @var string
     */
    const STATE_MODEL_MARKED = 'Marked';
    const STATE_MODEL_REVIEW = 'Review';
    const STATE_MODEL_MIGRATION_STATUS = 'MigrationStatus';
    /**#@-*/

    /**
     * Creates an text annotation dictionary
     *
     * @param SetaPDF_Core_DataStructure_Rectangle|array $rect
     * @return SetaPDF_Core_Type_Dictionary
     * @throws InvalidArgumentException
     */
    static public function createAnnotationDictionary($rect)
    {
        if (!($rect instanceof SetaPDF_Core_DataStructure_Rectangle)) {
            $rect = SetaPDF_Core_DataStructure_Rectangle::byArray($rect);
        }

        $dictionary = parent::_createAnnotationDictionary($rect, SetaPDF_Core_Document_Page_Annotation_Link::TYPE_TEXT);

        return $dictionary;
    }

    /**
     * The constructor
     *
     * @param array|SetaPDF_Core_Type_Abstract|SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_IndirectObjectInterface $objectOrDictionary The annotation dictionary or a rect value
     * @throws InvalidArgumentException
     */
    public function __construct($objectOrDictionary)
    {
        $dictionary = $objectOrDictionary instanceof SetaPDF_Core_Type_Abstract
            ? $objectOrDictionary->ensure(true)
            : $objectOrDictionary;

        if (!($dictionary instanceof SetaPDF_Core_Type_Dictionary)) {
            $args = func_get_args();
            $dictionary = $objectOrDictionary = call_user_func_array(
                array('SetaPDF_Core_Document_Page_Annotation_Text', 'createAnnotationDictionary'),
                $args
            );
            unset($args);
        }

        if (!SetaPDF_Core_Type_Dictionary_Helper::keyHasValue($dictionary, 'Subtype', 'Text')) {
            throw new InvalidArgumentException('The Subtype entry in a Text annotation shall be "Text".');
        }

        parent::__construct($objectOrDictionary);
    }

    /**
     * Checks if the annotation shall initially be displayed open
     *
     * @return bool
     */
    public function isOpen()
    {
        if (!$this->_annotationDictionary->offsetExists('Open'))
            return false;

        return $this->_annotationDictionary->getValue('Open')->getValue();
    }

    /**
     * Sets wheter the annotation shall initially be displayed open or not
     *
     * @param bool $open
     */
    public function setOpen($open)
    {
        if (false == $open) {
            $this->_annotationDictionary->offsetUnset('Open');
            return;
        }

        if (!$this->_annotationDictionary->offsetExists('Open')) {
            $this->_annotationDictionary->offsetSet('Open', new SetaPDF_Core_Type_Boolean($open));
            return;
        }

        $this->_annotationDictionary->getValue('Open')->setValue($open);
    }

    /**
     * Get the icon name of the annotation
     *
     * @return string
     */
    public function getIconName()
    {
        if (!$this->_annotationDictionary->offsetExists('Name')) {
            return 'Note';
        }

        return $this->_annotationDictionary->getValue('Name')->getValue();
    }

    /**
     * Set the name of the icon that shall be used in displaying the annotation.
     *
     * @param $iconName
     */
    public function setIconName($iconName)
    {
        if (null == $iconName) {
            $this->_annotationDictionary->offsetUnset('Name');
            return;
        }

        if (!$this->_annotationDictionary->offsetExists('Name')) {
            $this->_annotationDictionary->offsetSet('Name', new SetaPDF_Core_Type_Name($iconName));
            return;
        }

        $this->_annotationDictionary->getValue('Name')->setValue($iconName);
    }

    /**
     * Get the state model
     *
     * @return mixed|null
     */
    public function getStateModel()
    {
        if (!$this->_annotationDictionary->offsetExists('StateModel'))
            return null;

        return $this->_annotationDictionary->getValue('StateModel')->getValue();
    }

    /**
     * Set the annotation model
     *
     * @param string $stateModel
     */
    public function setStateModel($stateModel)
    {
        if (null == $stateModel) {
            $this->_annotationDictionary->offsetUnset('StateModel');
            return;
        }

        if (!$this->_annotationDictionary->offsetExists('StateModel')) {
            $this->_annotationDictionary->offsetSet('StateModel', new SetaPDF_Core_Type_String($stateModel));
            return;
        }

        $this->_annotationDictionary->getValue('StateModel')->setValue($stateModel);
    }

    /**
     * Get the annotation state
     *
     * @return mixed|null
     */
    public function getState()
    {
        if (!$this->_annotationDictionary->offsetExists('State'))
            return null;

        return $this->_annotationDictionary->getValue('State')->getValue();
    }

    /**
     * Set the annotation state
     *
     * This annotation should be a reply to another one and following annotation flags has to be set:
     * <code>
     * $annotation->setAnnotationFlages(
     *     SetaPDF_Core_Document_Page_Annotation_Flags::HIDDEN |
     *     SetaPDF_Core_Document_Page_Annotation_Flags::NO_ROTATE |
     *     SetaPDF_Core_Document_Page_Annotation_Flags::NO_ZOOM |
     *     SetaPDF_Core_Document_Page_Annotation_Flags::PRINTS
     * );
     * </code>
     * Otherwise Acrobat/Reader will not display the state in the comments pannel.
     *
     * @param string $state
     */
    public function setState($state)
    {
        if (null == $state) {
            $this->_annotationDictionary->offsetUnset('State');
            return;
        }

        if (!$this->_annotationDictionary->offsetExists('State')) {
            $this->_annotationDictionary->offsetSet('State', new SetaPDF_Core_Type_String($state));
            return;
        }

        $this->_annotationDictionary->getValue('State')->setValue($state);
    }
}