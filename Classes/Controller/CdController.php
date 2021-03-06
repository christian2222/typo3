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
use CDpackage\Cmcd\Services\Mp3SavingService;
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
    	$mp3Saver = $this->objectManager->get('CDpackage\\Cmcd\\Services\\Mp3SavingService');
		
    	$fileReference = $mp3Saver->uploadNew($libary->getBibName(),$cd->getCdName());
    	
    	$titel->setMp3($fileReference);
    	//$titel->setLaenge($fileReference);
    	// refresh title of cd
        $cd->addTitle($titel);
        // refresh cd of libary
        $libary->addCd($cd);
        // update libary
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        unset($mp3Saver);
        // redirect to showTitles with given $cd and $libary
        $this->redirect('showTitles','Cd',NULL,array('libary' => $libary,'cd' => $cd));
    }
    
    public function initializeAddTitleAction() {
    	// only setting the configuration is enough, but why?
    	$this->setTypeConverterConfigurationForImageUpload('titel');
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
        $this->view->assign('titel', $titel);
    }
    
    public function initializeUpdateTitleAction() {
    	$this->setTypeConverterConfigurationForImageUpload('titel');
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titel
     */
    public function updateTitleAction(\CDpackage\Cmcd\Domain\Model\Libary $libary, \CDpackage\Cmcd\Domain\Model\Cd $cd,
        \CDpackage\Cmcd\Domain\Model\Titel $titel)
    {
    	$this->initializeUpdateTitleAction();
    	
    	/*
    	 *    	$mp3Saver = $this->objectManager->get('CDpackage\\Cmcd\\Services\\Mp3SavingService');
		
    	$fileReference = $mp3Saver->uploadNew($libary->getBibName(),$cd->getCdName());
    	
    	$titel->setMp3($fileReference);
    	// refresh title of cd
        $cd->addTitle($titel);
        // refresh cd of libary
        $libary->addCd($cd);
        // update libary
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        // redirect to showTitles with given $cd and $libary
        $this->redirect('showTitles','Cd',NULL,array('libary' => $libary,'cd' => $cd));
    	 */
		//$titel->setMp3(null);
		
    	$mp3Saver = $this->objectManager->get('CDpackage\\Cmcd\\Services\\Mp3SavingService');
    	 
    	$fileReference = $mp3Saver->reload($libary->getBibName(),$cd->getCdName());
    	//$titel->setLaenge($mp3Saver->getHelp());
    	
    	if($mp3Saver->hasUploaded()) {
    		$titel->clearMp3();
    		$this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\CdRepository')->update($cd);
    		//$this->fileReferenceRepository is NULL ?
    		//$reference = $this->fileReferenceRepository->findByUid($this->mp3->getUid());
    		//$this->fileReferenceRepository->remove($reference);
    		$titel->setMp3($fileReference);
    		//$titel->setTName($mp3Saver->getDebugX());
    	}
    	
        $cd->addTitle($titel);
        $libary->addCd($cd);
        //$this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\CdRepository')->update($cd);
        //$libary->addCd($cd);
        $this->objectManager->get('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository')->update($libary);
        unset($mp3Saver);
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