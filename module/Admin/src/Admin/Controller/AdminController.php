<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    public function adminaccessAction(){
        $identity = $this->zfcUserAuthentication()->getIdentity()->getRoles();
        //var_dump($identity[0]->getRoleId());
        $role = $identity[0]->getroleId();
        if($role == 'admin') {
            return true;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
    }
    public function indexAction()
    {
        if(self::adminaccessAction() == true){
        $pageTitle = "Admin";
        $view = new ViewModel(array(
            'pageTitle' => $pageTitle,
        ));
        return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
    }

    public function dashboardAction(){
        if(self::adminaccessAction() == true){
        $objectmanager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $studentDetails = $objectmanager->getRepository('\Student\Entity\StudentDetails')->findAll();
        $studentDue = $objectmanager->getRepository('\Student\Entity\StudentDetails')->findBy(Array(
            'paystatus' => 0,
        ));
        $studentPaid = $objectmanager->getRepository('\Student\Entity\StudentDetails')->findBy(Array(
            'paystatus' => 1,
        ));
        $tddate = new \Datetime();
        /*$regtoday = $objectmanager->getRepository('\Application\Entity\User')->findBy(array(
            'createdAt' => $tddate,
        ));*/

        $regtoday = $objectmanager->createQuery('SELECT r FROM \Application\Entity\User r WHERE NOT r.createdAt IS NULL and r.createdAt >= :now ORDER BY r.createdAt ASC')
            ->setParameter('now', new \DateTime('today'))
            ->getResult();
        //echo count($regtoday)." today";
        $totstudent = count($studentDetails);
        $totpaid = count($studentPaid);
        $totdue = count($studentDue);
        $pageTitle = "Admin :: Dashboard";
        $view = new ViewModel(array(
            'pageTitle' => $pageTitle,
            'totstudent' => $totstudent,
            'totpaid' => $totpaid,
            'totdue' => $totdue,
            'regtoday' => count($regtoday),
        ));
        return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
    }

    public function createExamAction(){
        if(self::adminaccessAction() == true){
    	$pageTitle = "Admin :: Create Exam";
        $view = new ViewModel(array(
            'pageTitle' => $pageTitle,
        ));
        return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
    }
}
