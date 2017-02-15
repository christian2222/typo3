<?php
namespace CDpackage\Cmcd\Domain\Model;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * Ein Titel
 */
class Titel extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * tName
     *
     * @var string
     */
    protected $tName = '';
    
    /**
     * laenge
     *
     * @var string
     */
    protected $laenge = '';
    
    /**
     * mp3
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade remove
     */
    protected $mp3 = null;
    
  
    public function getFullMp3Name() {
    	if($this->mp3 === null) return null;
    	$fileRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
    	$fileResource = $fileRepository->findFileReferenceByUid($this->mp3->getUid());
  		return $fileResource->getPublicUrl(); 
    	//return $this->mp3->getOriginalRecource()->getName();
    }
    
    public function clearMp3() {
    	if($this->mp3 !== null) {
    		// solution form http://lists.typo3.org/pipermail/typo3-dev/2014-April/048303.html does not work
    		$fileRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
    		$fileReference = $fileRepository->findFileReferenceByUid($this->mp3->getUid());
    		//$fileReference->delete(); // not yet implemented
    		// does not work
    		$fileRepository->remove($fileReference);
    		$this->setMp3(null);
    		
    	}
    }
    /**
     * Returns the tName
     *
     * @return string $tName
     */
    public function getTName()
    {
        return $this->tName;
    }
    
    /**
     * Sets the tName
     *
     * @param string $tName
     * @return void
     */
    public function setTName($tName)
    {
        $this->tName = $tName;
    }
    
    /**
     * Returns the laenge
     *
     * @return string $laenge
     */
    public function getLaenge()
    {
        return $this->laenge;
    }
    
    /**
     * Sets the laenge
     *
     * @param string $laenge
     * @return void
     */
    public function setLaenge($laenge)
    {
        $this->laenge = $laenge;
    }
    
    /**
     * Returns the mp3
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $mp3
     */
    public function getMp3()
    {
        return $this->mp3;
    }
    
    /**
     * Sets the mp3
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $mp3
     * @return void
     */
    public function setMp3($mp3)
    {
        $this->mp3 = $mp3;
    }

}