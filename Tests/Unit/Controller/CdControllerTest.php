<?php
namespace CDpackage\Cmcd\Tests\Unit\Controller;
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
 * Test case for class CDpackage\Cmcd\Controller\CdController.
 *
 * @author Christian Marquardt <christian.marquardt11@gmx.de>
 */
class CdControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \CDpackage\Cmcd\Controller\CdController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('CDpackage\\Cmcd\\Controller\\CdController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
     * @backupGlobals disabled
	 * @test
	 */
	public function listActionFetchesAllCdsFromRepositoryAndAssignsThemToView()
	{

		$allCds = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$cdRepository = $this->getMock('CDpackage\\Cmcd\\Domain\\Repository\\CdRepository', array('findAll'), array(), '', FALSE);
		$cdRepository->expects($this->once())->method('findAll')->will($this->returnValue($allCds));
		$this->inject($this->subject, 'cdRepository', $cdRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('cds', $allCds);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}
}
