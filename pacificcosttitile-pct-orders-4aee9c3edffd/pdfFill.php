<?php

include('application/library/SetaPDF/Autoload.php'); // library for filling in PDF text fields
$reader = new SetaPDF_Core_Reader_File('test.pdf');
$writer = new SetaPDF_Core_Writer_File('new'.time().'.pdf');
// $document = SetaPDF_Core_Document::loadByFilename('test.pdf');
$document = SetaPDF_Core_Document::load($reader, $writer);

$formFiller = new SetaPDF_FormFiller($document);
$fields = $formFiller->getFields();


$allFields = $fields->getAll();
// echo '<pre>';
// var_dump($allFields);
$fields = $formFiller->getFields();
$fieldNames = $fields->getNames();
foreach ($fieldNames AS $key=>$fieldName) {
    echo $fieldName . "\n";
	$fields[$fieldName]->setValue('test');

}

// $fields->flatten();

$document->save()->finish();