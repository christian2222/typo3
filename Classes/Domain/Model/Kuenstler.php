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
 * Ein Künstler
 */
class Kuenstler extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * kuName
     *
     * @var string
     */
    protected $kuName = '';
    
    /**
     * gebJahr
     *
     * @var string
     */
    protected $gebJahr = '';
    
    /**
     * Returns the kuName
     *
     * @return string $kuName
     */
    public function getKuName()
    {
        return $this->kuName;
    }
    
    /**
     * Sets the kuName
     *
     * @param string $kuName
     * @return void
     */
    public function setKuName($kuName)
    {
        $this->kuName = $kuName;
    }
    
    /**
     * Returns the gebJahr
     *
     * @return string $gebJahr
     */
    public function getGebJahr()
    {
        return $this->gebJahr;
    }
    
    /**
     * Sets the gebJahr
     *
     * @param string $gebJahr
     * @return void
     */
    public function setGebJahr($gebJahr)
    {
        $this->gebJahr = $gebJahr;
    }

}