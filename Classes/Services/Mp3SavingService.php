<?php
namespace CDpackage\Cmcd\Services;

// toDo: simple quotations for files array

use CDpackage\Cmcd\PropertyTypeConverter\UploadedFileReferenceConverter;
use CDpackage\Cmcd\Domain\Model\Titel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Tests\Functional\Framework\Constraint\RequestSection\StructureDoesNotHaveRecordConstraint;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\DuplicationBehaviour;

class Mp3SavingService extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	
	public $uploaded = false;
	
	public $help = 'help';
	
	public $debugX;

	public function hasUploaded() {
		return $this->uploaded;
	}
	
	public function getHelp() {
		return $this->help;
	}
	
	public function getDebugX() {
		return $this->debugX;
	}
	
	
	/**
	 * loads the file up in a directory depending on $bibName and $cdName
	 * 
	 * @param string $bibName
	 * @param string $cdName
	 * @return \CDpackage\Cmcd\Domain\Model\FileReference|NULL
	 */
	public function uploadNew($bibName, $cdName) {
		// not yet uploaded
		$this->uploaded = false;
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
				$reference = $this->uploadFile($basename, $tmpname,$this->ensureDirectory('cmcdPlugin' . '/' . $bibName . '/' . $cdName) );
				// set name and title depenent on the $_FILES array
				//$titel->setTName($basename);
				//$titel->setLaenge($tmpname);
				//signal uploading success
				$this->uploaded = true;
				// set the reference
				return $reference;
			} else 
				return NULL;
		}
	}
	
	public function reload($bibName, $cdName) {
		// not yet uploaded
		$this->uploaded = false;
		$this->help = 'outside';
		// 'datei' is the name of the input-field
		if(is_uploaded_file($_FILES['nochmal']['tmp_name'])) {
			// extract the $file- and $tmpname from the $-FILES array
			$this->help = 'inside upload';
			$filename = $_FILES["nochmal"]["name"];
			// $filename === "" ~> no file selected
			$basename = basename($filename);
			$tmpname = $_FILES["nochmal"]["tmp_name"];
			// check for allowed file extensions
			if ($this->checkAllowedFilename($basename)) {
				;
				// upload the file into directory 1:/cmcdPlugin/[libaryName]/[cdName]/$basename
				$reference = $this->uploadFile($basename, $tmpname,$this->ensureDirectory('cmcdPlugin' . '/' . $bibName . '/' . $cdName) );
				// set name and title depenent on the $_FILES array
				//$titel->setTName($basename);
				$this->help = $tmpname;
				//$titel->setLaenge($tmpname);
				//signal for uploading success
				$this->uploaded = true;
				// set the reference
				return $reference;
			} else
				return NULL;
		}
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
	public function ensureDirectory($directory) {
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
		/** @var $fileReference \CDpackage\Cmcd\Domain\Model\FileReference */
		$fileReference = $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Model\\FileReference');
		// set it to the new file
		$fileReference->setFile($newFile);
		//$fileReference->setOriginalResource($newFile);
		 
		 $this->debugX = $newFile->getNameWithoutExtension();
		 
		
		return $fileReference;
	}
	
	

}