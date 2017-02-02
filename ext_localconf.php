<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'CDpackage.' . $_EXTKEY,
	'Cdmanager',
	array(
		'Libary' => 'list,new,create,show,deleteConfirm,delete,edit,overview',
        'Cd' => 'addCd,add,list,show,updateForm,update,deleteConfirm,delete,
                    showTitles,addTitle,deleteConfirmTitle,deleteTitle,editTitle,updateTitle,
                    addKuenstler,listKuenstler,deleteConfirmKuenstler,deleteKuenstler,editKuenstler,updateKuenstler'
		
	),
	// non-cacheable actions
	array(
		'Libary' => 'list,new,create,show,deleteConfirm,delete,edit,overview',
        'Cd' => 'addCd,add,list,show,updateForm,update,deleteConfirm,delete,
                    showTitles,addTitle,deleteConfirmTitle,deleteTitle,editTitle,updateTitle,
                    addKuenstler,listKuenstler,deleteConfirmKunestler,deleteKuenstler,editKuenstler,updateKuenstler'

	)
		
		
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('CDpackage\\Cmcd\\PropertyTypeConverter\\UploadedFileReferenceConverter');

