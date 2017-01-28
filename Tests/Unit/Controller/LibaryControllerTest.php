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
 * Test case for class CDpackage\Cmcd\Controller\LibaryController.
 *
 * @author Christian Marquardt <christian.marquardt11@gmx.de>
 */
class LibaryControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \CDpackage\Cmcd\Controller\LibaryController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('CDpackage\\Cmcd\\Controller\\LibaryController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllLibariesFromRepositoryAndAssignsThemToView()
	{

		$allLibaries = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$libaryRepository = $this->getMock('CDpackage\\Cmcd\\Domain\\Repository\\LibaryRepository', array('findAll'), array(), '', FALSE);
		$libaryRepository->expects($this->once())->method('findAll')->will($this->returnValue($allLibaries));
		$this->inject($this->subject, 'libaryRepository', $libaryRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('libaries', $allLibaries);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}
}
