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
 * Test case for class \CDpackage\Cmcd\Domain\Model\Libary.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Christian Marquardt <christian.marquardt11@gmx.de>
 */
class LibaryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \CDpackage\Cmcd\Domain\Model\Libary
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \CDpackage\Cmcd\Domain\Model\Libary();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getBibNameReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getBibName()
		);
	}

	/**
	 * @test
	 */
	public function setBibNameForStringSetsBibName()
	{
		$this->subject->setBibName('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'bibName',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCdsReturnsInitialValueForCd()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getCds()
		);
	}

	/**
	 * @test
	 */
	public function setCdsForObjectStorageContainingCdSetsCds()
	{
		$cd = new \CDpackage\Cmcd\Domain\Model\Cd();
		$objectStorageHoldingExactlyOneCds = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCds->attach($cd);
		$this->subject->setCds($objectStorageHoldingExactlyOneCds);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneCds,
			'cds',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addCdToObjectStorageHoldingCds()
	{
		$cd = new \CDpackage\Cmcd\Domain\Model\Cd();
		$cdsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$cdsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($cd));
		$this->inject($this->subject, 'cds', $cdsObjectStorageMock);

		$this->subject->addCd($cd);
	}

	/**
	 * @test
	 */
	public function removeCdFromObjectStorageHoldingCds()
	{
		$cd = new \CDpackage\Cmcd\Domain\Model\Cd();
		$cdsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$cdsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($cd));
		$this->inject($this->subject, 'cds', $cdsObjectStorageMock);

		$this->subject->removeCd($cd);

	}

	/**
	 * @test
	 */
	public function getInterpretenReturnsInitialValueForKuenstler()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getInterpreten()
		);
	}

	/**
	 * @test
	 */
	public function setInterpretenForObjectStorageContainingKuenstlerSetsInterpreten()
	{
		$interpreten = new \CDpackage\Cmcd\Domain\Model\Kuenstler();
		$objectStorageHoldingExactlyOneInterpreten = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneInterpreten->attach($interpreten);
		$this->subject->setInterpreten($objectStorageHoldingExactlyOneInterpreten);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneInterpreten,
			'interpreten',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addInterpretenToObjectStorageHoldingInterpreten()
	{
		$interpreten = new \CDpackage\Cmcd\Domain\Model\Kuenstler();
		$interpretenObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$interpretenObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($interpreten));
		$this->inject($this->subject, 'interpreten', $interpretenObjectStorageMock);

		$this->subject->addInterpreten($interpreten);
	}

	/**
	 * @test
	 */
	public function removeInterpretenFromObjectStorageHoldingInterpreten()
	{
		$interpreten = new \CDpackage\Cmcd\Domain\Model\Kuenstler();
		$interpretenObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$interpretenObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($interpreten));
		$this->inject($this->subject, 'interpreten', $interpretenObjectStorageMock);

		$this->subject->removeInterpreten($interpreten);

	}
}
