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

/**
 * Bibliothek von Cds
 */
class Libary extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * bibName
     *
     * @var string
     */
    protected $bibName = '';
    
    /**
     * cds
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Cd>
     * @cascade remove
     */
    protected $cds = null;
    
    /**
     * interpreten
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Kuenstler>
     * @cascade remove
     */
    protected $interpreten = null;
    
    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }
    
    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->cds = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->interpreten = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * Returns the bibName
     *
     * @return string $bibName
     */
    public function getBibName()
    {
        return $this->bibName;
    }
    
    /**
     * Sets the bibName
     *
     * @param string $bibName
     * @return void
     */
    public function setBibName($bibName)
    {
        $this->bibName = $bibName;
    }
    
    /**
     * Adds a Cd
     *
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cd
     * @return void
     */
    public function addCd(\CDpackage\Cmcd\Domain\Model\Cd $cd)
    {
        $this->cds->attach($cd);
    }
    
    /**
     * Removes a Cd
     *
     * @param \CDpackage\Cmcd\Domain\Model\Cd $cdToRemove The Cd to be removed
     * @return void
     */
    public function removeCd(\CDpackage\Cmcd\Domain\Model\Cd $cdToRemove)
    {
        $this->cds->detach($cdToRemove);
    }
    
    /**
     * Returns the cds
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Cd> $cds
     */
    public function getCds()
    {
        return $this->cds;
    }
    
    /**
     * Sets the cds
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Cd> $cds
     * @return void
     */
    public function setCds(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $cds)
    {
        $this->cds = $cds;
    }
    
    /**
     * Adds a Kuenstler
     *
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $interpreten
     * @return void
     */
    public function addInterpreten(\CDpackage\Cmcd\Domain\Model\Kuenstler $interpreten)
    {
        $this->interpreten->attach($interpreten);
    }
    
    /**
     * Removes a Kuenstler
     *
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $interpretenToRemove The Kuenstler to be removed
     * @return void
     */
    public function removeInterpreten(\CDpackage\Cmcd\Domain\Model\Kuenstler $interpretenToRemove)
    {
        $this->interpreten->detach($interpretenToRemove);
    }
    
    /**
     * Returns the interpreten
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Kuenstler> $interpreten
     */
    public function getInterpreten()
    {
        return $this->interpreten;
    }
    
    /**
     * Sets the interpreten
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Kuenstler> $interpreten
     * @return void
     */
    public function setInterpreten(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $interpreten)
    {
        $this->interpreten = $interpreten;
    }


}