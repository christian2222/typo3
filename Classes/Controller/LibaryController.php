<?php
namespace CDpackage\Cmcd\Controller;

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
 * LibaryController
 */
class LibaryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * libaryRepository
     *
     * @var \CDpackage\Cmcd\Domain\Repository\LibaryRepository
     * @inject
     */
    protected $libaryRepository = null;
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $libaries = $this->libaryRepository->findAll();
        $this->view->assign('libaries', $libaries);
    }
    
    /**
     * @param \CDpackage\Cmcd\Domain\Model\Libary $libary
     */
    public function newAction(\CDpackage\Cmcd\Domain\Model\Libary $libary = NULL)
    {
        $this->view->assign('libary', $libary);
    }

    public function createAction(\CDpackage\Cmcd\Domain\Model\Libary $libary) {
        $this->libaryRepository->add($libary);
        $this->redirect('list');
    }

    public function deleteConfirmAction(\CDpackage\Cmcd\Domain\Model\Libary $libary) {
        $this->view->assign('lib',$libary);
    }

    public function deleteAction(\CDpackage\Cmcd\Domain\Model\Libary $libary) {
        $this->libaryRepository->remove($libary);
        $this->redirect('list');
    }

    public function showAction(\CDpackage\Cmcd\Domain\Model\Libary $libary) {
        $this->view->assign('lib',$libary);
    }

    public function editAction(\CDpackage\Cmcd\Domain\Model\Libary $libary) {
        $this->view->assign('lib',$libary);
    }

    public function overviewAction(\CDpackage\Cmcd\Domain\Model\Libary $libary)
    {
        $cds = $libary->getCds();
        $this->view->assign('lib',$libary);
        $this->view->assign('cds', $cds);
    }

}