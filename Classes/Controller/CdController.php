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
	 * adds a title to the given $cd of the given $libary
	 * 
	 * @param \CDpackage\Cmcd\Domain\Model\Libary $libary
	 * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
	 * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
	 */ 
    public function addTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
    	// 'datei' is the name of the input-field
    	if(is_uploaded_file($_FILES['datei']['tmp_name'])) {
	    	// extract the $file- and $tmpname from the $-FILES array
    		$filename = $_FILES["datei"]["name"];
    		// $filename === "" ~> no file selected
	    	$basename = basename($filename);
    		$tmpname = $_FILES["datei"]["tmp_name"];
    		// check for allowed file extensions
    		if ($this->checkAllowedFilename($basename)) {
    			;
    			// upload the file into directory 1:/cmcdPlugin/[libaryName]/[cdName]/$basename
    			$reference = $this->uploadFile($basename, $tmpname,$this->ensureDirectory('cmcdPlugin' . '/' . $libary->getBibName() . '/' . $cd->getCdName()));
    			// set name and title depenent on the $_FILES array
    			//$titel->setTName($basename);
    			//$titel->setLaenge($tmpname);
    			// set the reference
    			$titel->setMp3($reference);
    		}
    	}
    	// refresh title of cd
        $cd->addTitle($titel);
        // refresh cd of libary
        $libary->addCd($cd);
        // update libary
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        // redirect to showTitles with given $cd and $libary
        $this->redirect('showTitles','Cd',NULL,array('libary' => $libary,'cd' => $cd));
    }
    
    public function initializeAddTitleAction() {
    	// only setting the configuration is enough, but why?
    	$this->setTypeConverterConfigurationForImageUpload('titel');
    }
    
	/**
	 * uploads a file with the $tmpname into the $folder with the $newFilename. It returns a filereference on the new file
	 * 
	 * @param string $newFilename
	 * @param string $tmpfileName
	 * @param \TYPO3\CMS\COre\Resource\Folder $folder
	 * @return \CDpackage\Cmcd\Domain\Model\FileReference $fileReference
	 */ 
    protected function uploadFile($newFilename,$tmpfileName,$folder) {
    	/**
    	 * if (!GeneralUtility::verifyFilenameAgainstDenyPattern($uploadInfo['name'])) {
    	 * throw new TypeConverterException('Uploading files with PHP file extensions is not allowed!', 1399312430); }
    	 */
    	
    	// get the resource factory
    	$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
    	// get the storage
    	$storage = $resourceFactory->getDefaultStorage();
  
    	// create a new file inside the $folder of $storage. The new file is given by $tmpfileName. It's new name is $newFilename
    	// it's replaced if it already ecists and the origin is not deleted
    	$newFile = $storage->addFile(
    			$tmpfileName,
    			$folder,
    			$newFilename,\TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE,FALSE
    			);
    	
    	// TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference
    	// get the file reference
    	$fileReference = $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Model\\FileReference');
    	// set it to the new file
    	$fileReference->setFile($newFile);
    	//$fileReference->setOriginalResource($newFile);
    	
    	
    	return $fileReference;
    }
    
  
	
    /**
     * sets some unknown converter configuration that is not used?
     * 
     * @param unknown $argumentName
     */
    protected function setTypeConverterConfigurationForImageUpload($argumentName)
    {
    	$uploadConfiguration = [
    			UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => 'mp3',
    			// CONFIGURATION_UPLOAD-FOLDER does not change it's content!
    			UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/user_upload',
    			UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_CONFLICT_MODE => \TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE
    	];
    	/** @var PropertyMappingConfiguration $newExampleConfiguration */
    	$newExampleConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
    	// convert the mp3 Property into a fileReference using the previous defined configuration
    	$newExampleConfiguration->forProperty('mp3')
    	->setTypeConverterOptions(
    			'CDpackage\\Cmcd\\PropertyTypeconverter\\UploadedFileReferenceConverter',
    			$uploadConfiguration
    			);
    	// why is the $newExampleConfiguration thrown away and does not convert anything?
    	// should we return the result of the conversion and not just throw away the configuration?
    	// how do we start the conversion? Where is the configuration used?
    }
    
    /**
     * checks for an allowed $filename
     * 
     * @param string $filename
     * @return boolean
     */
    protected function checkAllowedFilename($filename) {
    	// allow the file (be optimistic)
    	$allowed = TRUE;
    	// deny php files	
    	$allowed = $allowed && GeneralUtility::verifyFilenameAgainstDenyPattern($filename);
    	// choose mp3 files
    	$allowed = $allowed &&  ( $this->endsWith($filename, '.mp3') || $this->endsWith($filename, '.MP3') );
    	// check for forbidden characters like '/' in the filename
    	$allowed = $allowed && (!$this->hasForbiddenCharacters($filename));
    	
    	// result of all passed tests
    	return $allowed;
    	
    }
    
    /**
     * recursively creates/walks down a folder structure from rootlevel folder
     * folders are separated by '/'
     * usage
     * $folder = $this->ensureDirectory('test/test1/test2/test3');
     *      * 
     * @param Folder $directory
     * @return \TYPO3\CMS\Core\Resource\Folder
     * 
     */
    protected function ensureDirectory($directory) {
    	// get the resource factory
    	$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
    	// get the default storage
    	$storage = $resourceFactory->getDefaultStorage();
    	// get the root directory of fileadmin
    	$folder = $storage->getRootLevelFolder();
    	// extract the names of the path/tofile into an array [path, to, file]
    	$dirArray = explode('/',$directory);
    	// walk the "directories" down (inside the array)
    	foreach ($dirArray as $dir) {
    		// if the folder exists go into it
    		// otherwise create it and go into it
    		if($folder->hasFolder($dir)) {
    			$folder = $folder->getSubfolder($dir);
    		} else {
    				$folder = $folder->createFolder($dir);
    		}
    	}
    	
    	// return the current folder we are in (ie. fileadmin/path/to/file)
    	return $folder;
    	
    }
    
    /**
     * checks the $string for forbidden characters like '/' for filenames
     * 
     * @param string $string
     * @return boolean
     */
    protected function hasForbiddenCharacters($string) {
    	// count the occurences of '/'
    	$occurence = substr_count($string, '/');
    	
    	return ($occurence != 0);
    	
    }
    
    /**
     * checks wether $haystack ends with $needle
     * 
     * 
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    protected function endsWith($haystack, $needle)
    {
    	$length = strlen($needle);
    	if ($length == 0) {
    		// $needle is the empty word
    		return true;
    	}
    	// find the (possible) starting position of the $needle at the end of $haystack
   		$startPos =  strlen($haystack)-$length;
   		// cut from starting oosition and compare the result with $needle
    	return (substr($haystack, $startPos) === $needle);
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