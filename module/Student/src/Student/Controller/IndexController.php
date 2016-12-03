<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
    
    //converts object to array
    public function object_2_array($result) {
        $array = array();
        if ($result) {
            foreach ($result as $key => $value) {
                if (is_object($value)) {
                    $array[$key] = $this->object_2_array($value);
                } elseif (is_array($value)) {
                    $array[$key] = $this->object_2_array($value);
                } else {
                    $array[$key] = $value;
                }
            }
        }
        return $array;
    }
    
    public function studentaccessAction(){
        $identity = $this->zfcUserAuthentication()->getIdentity()->getRoles();
        $role = $identity[0]->getroleId();
        if($role == 'student') {
            if(self::paidStudentAction() == true){
               return true; 
            } else {
                $this->redirect()->toUrl('/student/student/dashboard');
            }
        } else {
            $this->redirect()->toUrl('/user/login');
        }
    }
    public function paidStudentAction(){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
        $controllerName =$this->params('controller');
        $actionName = $this->params('action');
        if(count($studentDetails) > 0){
            if($studentDetails->getPaystatus() == 1){
                return true;
            } else {
                if($actionName == 'dashboard' || $actionName == 'edit-profile' || $actionName == 'profile' || $actionName = 'referral-email-form' || $actionName = 'referral-sms-form' || $actionName = 'referral-dashboard'){
                    return true;
                } else {
                    return $this->redirect()->toUrl('/student/student/dashboard');
                }
            }
        } else {
            if($actionName == 'dashboard' || $actionName == 'edit-profile' || $actionName == 'profile'){
                return true;
            } else {
                return $this->redirect()->toUrl('/student/student/dashboard');
            }
        }
    }
    public function indexAction()
    {
        if(self::studentaccessAction() == true){
            $pageTitle = "Student";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
            ));
            return $view;
        }
    }
    public function dashboardAction(){
        if(self::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            
            if(count($studentDetails) > 0){
                if(empty($studentDetails->getClassGrade())){
                    return $this->redirect()->toUrl('/student/student/edit-profile');
                }
            } else {
                $newStudentDetails = new \Student\Entity\StudentDetails();
                $newStudentDetails->setUserId($userdetails);
                $objectManager->persist($newStudentDetails);
                $objectManager->flush();
                return $this->redirect()->toUrl('/student/student/edit-profile');
            }
            if(empty($studentDetails->getStrefcode())){
                $refcode = "BLF".substr($userdetails->getUsername(),0,3)."".$userdetails->getId();
                $refcode = strtoupper($refcode);
                $studentDetails->setStrefcode($refcode);
                $objectManager->persist($studentDetails);
                $objectManager->flush();
            }
            /* Demo Paper Details */
            $demoexmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> 1));
            $demoexmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array('examPaperId'=> $demoexmpprid));
            $studdemoscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array('examPaperId'=> $demoexmpprid));
            
            if(count($demoexmalreadystarted) > 0){
                if(count($studdemoscoreexist) > 0) {
                    //$demoattempt = 1; Disabled for a while
                    $demoattempt = 0;
                } elseif(new \DateTime() > $demoexmalreadystarted->getStoppedAt()){
                    $demoattempt = 0;
                } else {
                    $demoattempt = 0;
                }
            } else {
                $demoattempt = 0;
            }
            
            /* Scholarship Paper Details */
            $schexmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> 2));
            $schexmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                'userId'=>$userdetails,
                'examPaperId'=> $schexmpprid,
            ));
            $studschscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                'userId'=>$userdetails,
                'examPaperId'=> $schexmpprid,
            ));
            
            if(count($schexmalreadystarted) > 0){
                if(count($studschscoreexist) > 0) {
                    $schattempt = 1;
                    //$demoattempt = 0;
                } elseif(new \DateTime() > $schexmalreadystarted->getStoppedAt()){
                    $schattempt = 1;
                } else {
                    $schattempt = 0;
                }
            } else {
                $schattempt = 0;
            }
            
            $pageTitle = "My Dashboard";
            $view = new ViewModel(array(
                    'demoattempt' => 1,
                    'studdetails' => $studentDetails,
                    'schattempt' => $schattempt,
                    'demopprdetails' => $studdemoscoreexist,
                    'demoexamlink' => 'ZmdlZ1JLWnBicXJfMQ==',
                    'examlink' => 'ZmdlZ1JLWnBicXJfMg==',
                    'pageTitle' => $pageTitle,
            ));
            return $view;
        }
    }
    public function profileAction(){
        if(self::studentaccessAction() == true){
            if(self::paidStudentAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
                $studentClass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id'=>$studentDetails->classGrade));
                $pageTitle = "My Profile";
                $view = new ViewModel(array(
                    'user' => $userdetails,
                    'studentdetails' => $studentDetails,
                    'studentclass' => $studentClass,
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            }
        }
    }
    public function editProfileAction(){
        if(self::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            $totalclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findBy(array('showinmenu' => 1));
            //var_dump($totalclass);
            if(count($studentDetails) > 0){
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            } else {
                $studentDetails = '';
            }
            $pageTitle = "Edit My Profile";
            $view = new ViewModel(array(
                'totcls' => $totalclass,
                'user' => $userdetails,
                'studentdetails' => $studentDetails,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }

    }
    public function saveProfileAction(){
        if(self::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $request = $this->getRequest();
            if ($request->isPost()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
                if(count($studentDetails) > 0){
                    $data = $request->getPost();
                    $studentDetails->exchangeArray(array(
                        'gender' => $data['inputgen'],
                        'firstName' => $data['inputfname'],
                        'middleName' => $data['inputmname'],
                        'lastName' => $data['inputlname'],
                        'birthdate' => new \DateTime($data['inputdob']),
                        'phone' => $data['inputphone'],
                        'mothername' => $data['inputmoname'],
                        'fathername' => $data['inputfaname'],
                        'address' => $data['inputaddress'],
                        'city' => $data['inputcity'],
                        'state' => $data['inputstate'],
                        'pin' => $data['inputpin'],
                        'religion' => $data['inputreligion'],
                        'caste' => $data['inputcaste'],
                        'country' => $data['inputcountry'],
                        'aadhaarnum' => $data['inputaadhaarnum'],
                        'classGrade' => $data['inputsclass'],
                        'passyear' => $data['inputsyear'],
                        'schoolColg' => $data['inputschool'],
                        'schcolAddr' => $data['inputschaddr'],
                        'boardUniv' => $data['inputboard'],
                        'scmedium' => $data['scmedium'],
                        'updatedAt' => new \DateTime(),
                    ));
                    if(empty($studentDetails->getStrefcode())){
                        $refcode = "BLF".substr($userdetails->getUsername(),0,3)."".$userdetails->getId();
                        $refcode = strtoupper($refcode);
                        $studentDetails->setStrefcode($refcode);
                    }
                    //var_dump(new \DateTime()); exit;
                    $userdetails->setPhonenum($data['inputphone']);
                    $userdetails->setEmail($data['inputemail']);
                    //$studentInfo->setCreated(time());
                    $objectManager->persist($studentDetails);
                    $objectManager->flush();
                    $message = "You have successfully updated the profile data";
                    $this->redirect()->toUrl('/student/student/dashboard');
                } else {
                    $studentDetails = new \Student\Entity\StudentDetails();
                    $data = $request->getPost();
                    $comb1 = substr($userdetails->getUsername(),0,3);
                    $refcode = "BLF".substr($userdetails->getUsername(),0,3)."".$userdetails->getId();
                    $refcode = strtoupper($refcode);
                    $studentDetails->exchangeArray(array(
                        'userId' => $userdetails,
                        'gender' => $data['inputgen'],
                        'firstName' => $data['inputfname'],
                        'middleName' => $data['inputmname'],
                        'lastName' => $data['inputlname'],
                        'birthdate' => new \DateTime($data['inputdob']),
                        'phone' => $data['inputphone'],
                        'mothername' => $data['inputmoname'],
                        'fathername' => $data['inputfaname'],
                        'address' => $data['inputaddress'],
                        'city' => $data['inputcity'],
                        'state' => $data['inputstate'],
                        'pin' => $data['inputpin'],
                        'religion' => $data['inputreligion'],
                        'caste' => $data['inputcaste'],
                        'country' => $data['inputcountry'],
                        'aadhaarnum' => $data['inputaadhaarnum'],
                        'classGrade' => $data['inputsclass'],
                        'passyear' => $data['inputsyear'],
                        'schoolColg' => $data['inputschool'],
                        'schcolAddr' => $data['inputschaddr'],
                        'boardUniv' => $data['inputboard'],
                        'scmedium' => $data['scmedium'],
                        'strefcode' => $refcode,
                        'createdAt' => new \DateTime(),
                        'updatedAt' => new \DateTime(),
                    ));
                    $userdetails->setPhonenum($data['inputphone']);
                    $userdetails->setEmail($data['inputemail']);
                    $objectManager->persist($studentDetails);
                    $objectManager->flush();
                    $message = "You have successfully updated the profile data";
                    $this->redirect()->toUrl('/student/student/dashboard');
                }
                
            } else {
                $message = "There is some issue with the Update. Please contact site Admin";
            }
            $pageTitle = "Dashboard :: Save Profile";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
                'msg' => $message,
            ));
            return $view;
        }
    }
}
