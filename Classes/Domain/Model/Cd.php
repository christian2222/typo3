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
 * Eine Cd
 */
class Cd extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * cdName
     *
     * @var string
     */
    protected $cdName = '';
    
    /**
     * erschJahr
     *
     * @var string
     */
    protected $erschJahr = '';
    
    /**
     * artists
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Kuenstler>
     */
    protected $artists = null;
    
    /**
     * titles
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Titel>
     * @cascade remove
     */
    protected $titles = null;
    
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
        $this->artists = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->titles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * Returns the cdName
     *
     * @return string $cdName
     */
    public function getCdName()
    {
        return $this->cdName;
    }
    
    /**
     * Sets the cdName
     *
     * @param string $cdName
     * @return void
     */
    public function setCdName($cdName)
    {
        $this->cdName = $cdName;
    }
    
    /**
     * Returns the erschJahr
     *
     * @return string $erschJahr
     */
    public function getErschJahr()
    {
        return $this->erschJahr;
    }
    
    /**
     * Sets the erschJahr
     *
     * @param string $erschJahr
     * @return void
     */
    public function setErschJahr($erschJahr)
    {
        $this->erschJahr = $erschJahr;
    }
    
    /**
     * Adds a Kuenstler
     *
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $artist
     * @return void
     */
    public function addArtist(\CDpackage\Cmcd\Domain\Model\Kuenstler $artist)
    {
        $this->artists->attach($artist);
    }
    
    /**
     * Removes a Kuenstler
     *
     * @param \CDpackage\Cmcd\Domain\Model\Kuenstler $artistToRemove The Kuenstler to be removed
     * @return void
     */
    public function removeArtist(\CDpackage\Cmcd\Domain\Model\Kuenstler $artistToRemove)
    {
        $this->artists->detach($artistToRemove);
    }
    
    /**
     * Returns the artists
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Kuenstler> $artists
     */
    public function getArtists()
    {
        return $this->artists;
    }
    
    /**
     * Sets the artists
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Kuenstler> $artists
     * @return void
     */
    public function setArtists(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $artists)
    {
        $this->artists = $artists;
    }
    
    /**
     * Adds a Titel
     *
     * @param \CDpackage\Cmcd\Domain\Model\Titel $title
     * @return void
     */
    public function addTitle(\CDpackage\Cmcd\Domain\Model\Titel $title)
    {
        $this->titles->attach($title);
    }
    
    /**
     * Removes a Titel
     *
     * @param \CDpackage\Cmcd\Domain\Model\Titel $titleToRemove The Titel to be removed
     * @return void
     */
    public function removeTitle(\CDpackage\Cmcd\Domain\Model\Titel $titleToRemove)
    {
        $this->titles->detach($titleToRemove);
    }
    
    /**
     * Returns the titles
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Titel> $titles
     */
    public function getTitles()
    {
        return $this->titles;
    }
    
    /**
     * Sets the titles
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CDpackage\Cmcd\Domain\Model\Titel> $titles
     * @return void
     */
    public function setTitles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $titles)
    {
        $this->titles = $titles;
    }

}