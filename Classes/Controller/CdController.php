<?php
namespace CDpackage\Cmcd\Controller;

use CDpackage\Cmcd\PropertyTypeConverter\UploadedFileReferenceConverter;
use CDpackage\Cmcd\Domain\Model\Titel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Tests\Functional\Framework\Constraint\RequestSection\StructureDoesNotHaveRecordConstraint;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\DuplicationBehaviour;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Christian Marquardt <christian.marquardt11@gmx.de>, project
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * CdController
 */
class CdController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * cdRepository
     *
     * @var \CDpackage\Cmcd\Domain\Repository\CdRepository
     * @inject
     */
    protected $cdRepository = null;
    
    /**
     * kuenstlerRepository
     *
     * @var \CDpackage\Cmcd\Domain\Repository\KuenstlerRepository
     * @inject
     */
    protected $kuenstlerRepository = null;
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction(\CDpackage\Cmcd\Domain\Model\Libary $libary)
    {
        $cds = $libary->getCds();
        $this->view->assign('cds', $cds);
        $this->view->assign('lib',$libary);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function addCdAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd = NULL)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function addAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $libary->addCd($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('edit','Libary',NULL,array('libary' => $libary));
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function showAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function updateFormAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
        $this->view->assign('titles', $cd->getTitles());
        $kuenstler = $libary->getInterpreten();
        $this->view->assign('kuenstler', $kuenstler);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function updateAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $libary->addCd($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('edit','Libary',NULL,array('libary' => $libary));
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function deleteAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $libary->removeCd($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('edit','Libary',NULL,array('libary' => $libary));
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     */
    public function deleteConfirmAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function showTitlesAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel = NULL)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
        $this->view->assign('title', $titel);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function addTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
    	
    	$filename = $_FILES["datei"]["name"];
    	// $filename === "" ~> no file selected
    	$basename = basename($filename);
    	$tmpname = $_FILES["datei"]["tmp_name"];
    	$reference = $this->uploadFile($basename, $tmpname,$this->ensureDirectory('cmcdPlugin' . '/' . $libary->getBibName() . '/' . $cd->getCdName()));
    	$titel->setTName($basename);
    	$titel->setLaenge($tmpname);
    	$titel->setMp3($reference);
        $cd->addTitle($titel);
        $libary->addCd($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('showTitles','Cd',NULL,array('libary' => $libary,'cd' => $cd));
    }
    
    public function initializeAddTitleAction() {
    	$this->setTypeConverterConfigurationForImageUpload('titel');
    }
    
    /**
     * not in controller
     * 
     * @param string $uploadPath
     * @param string $filePath
     * 
     * @return FileReference
     */
    protected function uploadFile($newFilename,$tmpfileName,$folder) {
    	/**
    	 * if (!GeneralUtility::verifyFilenameAgainstDenyPattern($uploadInfo['name'])) {
    	 * throw new TypeConverterException('Uploading files with PHP file extensions is not allowed!', 1399312430); }
    	 */
    	
    	$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
    	$storage = $resourceFactory->getDefaultStorage();
    	//$folder = $storage->getRootLevelFolder();
    	// go to subfolder (should exist!)
    	//$hasSubfolder = $storage->hasFolder('songs'); // boolean
    	//$folder = $folder->getSubfolder('songs');
    	
    	// create a new subfolder and turn into
    	//$folder = $folder->createFolder('subfolder');	
    	// $storage->getFolder('1:songs/') does not work
  
    	$newFile = $storage->addFile(
    			$tmpfileName,
    			$folder,
    			$newFilename,\TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE,FALSE
    			);
    	
    	// TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference
    	$fileReference = $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Model\\FileReference');
    	$fileReference->setFile($newFile);
    	
    	
    	return $fileReference;
    }
    
  

    /**
     *
     */
    protected function setTypeConverterConfigurationForImageUpload($argumentName)
    {
    	$uploadConfiguration = [
    			UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => 'mp3',
    			UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/'
    		//	UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_CONFLICT_MODE => \TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE
    	];
    	/** @var PropertyMappingConfiguration $newExampleConfiguration */
    	$newExampleConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
    	$newExampleConfiguration->forProperty('mp3')
    	->setTypeConverterOptions(
    			'CDpackage\\Cmcd\\PropertyTypeconverter\\UploadedFileReferenceConverter',
    			$uploadConfiguration
    			);
    }
    
    protected function checkAllowedFilename($filename) {
    	$allowed = TRUE;
    	
    	$allowed = $allowed && GeneralUtility::verifyFilenameAgainstDenyPattern($filename);
    	
    	$allowed = $allowed &&  ( $this->endsWith($filename, '.mp3') || $this->endsWith($filename, '.MP3') );
    	
    	$allowed = $allowed && (!$this->hasForbiddenCharacters($filename));
    	
    	return $allowed;
    	
    }
    
    /**
     * 
     * @param Folder $directory
     * @return \TYPO3\CMS\Core\Resource\Folder
     * 
     * recursively creates/walks down a folder structure from rootlevel folder
     * folders are separated by '/'
     * usage
     * $folder = $this->ensureDirectory('test/test1/test2/test3');
     */
    protected function ensureDirectory($directory) {
    	$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
    	$storage = $resourceFactory->getDefaultStorage();
    	$folder = $storage->getRootLevelFolder();
    	$dirArray = explode('/',$directory);
    	foreach ($dirArray as $dir) {
    		if($folder->hasFolder($dir)) {
    			$folder = $folder->getSubfolder($dir);
    		} else {
    				$folder = $folder->createFolder($dir);
    		}
    	}
    	
    	return $folder;
    	
    }
    
    protected function hasForbiddenCharacters($string) {
    	$occurence = substr_count($string, '/');
    	
    	return ($occurence != 0);
    	
    }
    
    protected function endsWith($haystack, $needle)
    {
    	$length = strlen($needle);
    	if ($length == 0) {
    		return true;
    	}
    
    	return (substr($haystack, -$length) === $needle);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function deleteConfirmTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
        $this->view->assign('title', $titel);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function deleteTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
        $cd->removeTitle($titel);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\CdRepository')->update($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('showTitles', 'Cd', NULL, array('libary' => $libary,'cd' => $cd));
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function editTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
        $this->view->assign('lib',$libary);
        $this->view->assign('cd', $cd);
        $this->view->assign('title', $titel);
    }
    
    // funktioniert, aber unklar???
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function updateTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
        $cd->addTitle($titel);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\CdRepository')->update($cd);
        //$libary->addCd($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('showTitles', 'Cd', NULL, array('libary' => $libary,'cd' => $cd));
    }
    
    public function listKuenstlerAction(\CDpackage\Cmcd\Domain\Model\Libary $libary)
    {
        $kuenstler = $libary->getInterpreten();
        $this->view->assign('kuenstler', $kuenstler);
        $this->view->assign('lib',$libary);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $kuenstler
     */
    public function addKuenstlerAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Kuenstler $kuenstler)
    {
        $libary->addInterpreten($kuenstler);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('listKuenstler','Cd',NULL,array('libary'=>$libary));
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $kunst
     */
    public function deleteConfirmKuenstlerAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Kuenstler $kunst)
    {
        $this->view->assign('kunst', $kunst);
        $this->view->assign('lib',$libary);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $kuenstler
     */
    public function deleteKuenstlerAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Kuenstler $kuenstler)
    {
        $libary->removeInterpreten($kuenstler);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('listKuenstler','Cd',NULL,array('libary'=>$libary));
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $kunst
     */
    public function editKuenstlerAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Kuenstler $kunst)
    {
        $this->view->assign('kunst', $kunst);
        $this->view->assign('lib',$libary);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $kunst
     */
    public function updateKuenstlerAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Kuenstler $kunst)
    {
        $libary->addInterpreten($kunst);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        $this->redirect('listKuenstler','Cd',NULL,array('libary'=>$libary));
    }
    


}