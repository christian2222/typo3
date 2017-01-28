<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'CDpackage.' . $_EXTKEY,
	'Cdmanager',
	'CD - Verwaltung'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'CD Verwaltung');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmcd_domain_model_libary', 'EXT:cmcd/Resources/Private/Language/locallang_csh_tx_cmcd_domain_model_libary.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmcd_domain_model_libary');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmcd_domain_model_cd', 'EXT:cmcd/Resources/Private/Language/locallang_csh_tx_cmcd_domain_model_cd.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmcd_domain_model_cd');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmcd_domain_model_kuenstler', 'EXT:cmcd/Resources/Private/Language/locallang_csh_tx_cmcd_domain_model_kuenstler.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmcd_domain_model_kuenstler');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cmcd_domain_model_titel', 'EXT:cmcd/Resources/Private/Language/locallang_csh_tx_cmcd_domain_model_titel.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cmcd_domain_model_titel');
