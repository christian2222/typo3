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
 * Test case for class \CDpackage\Cmcd\Domain\Model\Liabry.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Christian Marquardt <christian.marquardt11@gmx.de>
 */
class LiabryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \CDpackage\Cmcd\Domain\Model\Liabry
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \CDpackage\Cmcd\Domain\Model\Liabry();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function dummyTestToNotLeaveThisFileEmpty()
	{
		$this->markTestIncomplete();
	}
}
