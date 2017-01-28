<?php
namespace CDpackage\Cmcd\Tests\Unit\Controller;
/**
 * Created by PhpStorm.
 * User: xy
 * Date: 11.01.17
 * Time: 18:02
 */
class MyTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    public function setUp() {

    }

    /**
     * @test
     */
    public function testSimpleEquality() {
        $a = 't';
        $this->asserEquals('t','t');
    }

    public function tearDown()
    {
    }
}