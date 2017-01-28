<?php

namespace CDpackage\Cmcd\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Christian Marquardt <christian.marquardt11@gmx.de>, project
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \CDpackage\Cmcd\Domain\Model\Cd.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Christian Marquardt <christian.marquardt11@gmx.de>
 */
class CdTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \CDpackage\Cmcd\Domain\Model\Cd
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \CDpackage\Cmcd\Domain\Model\Cd();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getCdNameReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getCdName()
		);
	}

	/**
	 * @test
	 */
	public function setCdNameForStringSetsCdName()
	{
		$this->subject->setCdName('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'cdName',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getErschJahrReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getErschJahr()
		);
	}

	/**
	 * @test
	 */
	public function setErschJahrForStringSetsErschJahr()
	{
		$this->subject->setErschJahr('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'erschJahr',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getArtistsReturnsInitialValueForKuenstler()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getArtists()
		);
	}

	/**
	 * @test
	 */
	public function setArtistsForObjectStorageContainingKuenstlerSetsArtists()
	{
		$artist = new \CDpackage\Cmcd\Domain\Model\Kuenstler();
		$objectStorageHoldingExactlyOneArtists = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneArtists->attach($artist);
		$this->subject->setArtists($objectStorageHoldingExactlyOneArtists);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneArtists,
			'artists',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addArtistToObjectStorageHoldingArtists()
	{
		$artist = new \CDpackage\Cmcd\Domain\Model\Kuenstler();
		$artistsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$artistsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($artist));
		$this->inject($this->subject, 'artists', $artistsObjectStorageMock);

		$this->subject->addArtist($artist);
	}

	/**
	 * @test
	 */
	public function removeArtistFromObjectStorageHoldingArtists()
	{
		$artist = new \CDpackage\Cmcd\Domain\Model\Kuenstler();
		$artistsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$artistsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($artist));
		$this->inject($this->subject, 'artists', $artistsObjectStorageMock);

		$this->subject->removeArtist($artist);

	}

	/**
	 * @test
	 */
	public function getTitlesReturnsInitialValueForTitel()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getTitles()
		);
	}

	/**
	 * @test
	 */
	public function setTitlesForObjectStorageContainingTitelSetsTitles()
	{
		$title = new \CDpackage\Cmcd\Domain\Model\Titel();
		$objectStorageHoldingExactlyOneTitles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneTitles->attach($title);
		$this->subject->setTitles($objectStorageHoldingExactlyOneTitles);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneTitles,
			'titles',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addTitleToObjectStorageHoldingTitles()
	{
		$title = new \CDpackage\Cmcd\Domain\Model\Titel();
		$titlesObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$titlesObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($title));
		$this->inject($this->subject, 'titles', $titlesObjectStorageMock);

		$this->subject->addTitle($title);
	}

	/**
	 * @test
	 */
	public function removeTitleFromObjectStorageHoldingTitles()
	{
		$title = new \CDpackage\Cmcd\Domain\Model\Titel();
		$titlesObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$titlesObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($title));
		$this->inject($this->subject, 'titles', $titlesObjectStorageMock);

		$this->subject->removeTitle($title);

	}
}
