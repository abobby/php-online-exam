<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class StudentController extends AbstractActionController {
	
	public function indexAction() {
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $pageTitle = "Admin : Exam : Index";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
            ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
	}
        public function dashboardAction() {
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $pageTitle = "Admin : Exam : Dashboard";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
            ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
	}
        public function listStudentsAction() {
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findAll();
                
                
                $studrecs = Array();
                foreach($studentDetails as $stud){
                    $studclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $stud->getClassGrade()));
                    if(count($studclass) > 0){
                        $stcls = $studclass->getName();
                    } else {
                        $stcls = '';
                    }
                    $studrecs[] = Array(
                        'id' => $stud->userId->getId(),
                        'name' => $stud->getFirstName() ? $stud->getFirstName() : '',
                        'mname' => $stud->getMiddleName() ? $stud->getMiddleName() : '',
                        'lname' => $stud->getLastName() ? $stud->getLastName() : '',
                        'username' => $stud->getUserId()->getUsername(),
                        'phone' => $stud->getPhone() ? $stud->getPhone() : '',
                        'fathername' => $stud->getFathername() ? $stud->getFathername() : 'NA',
                        'class' => $stcls ? $stcls : 'NA',
                        'passyear' => $stud->getPassyear() ? $stud->getPassyear() : 'NA',  
                    );
                }
                
                $pageTitle = "Admin : Students : List All";
                $view = new ViewModel(array(
                    'allstud' => $studrecs,
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
	}
        public function editStudentProfileAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $studid = $_REQUEST['stdid'];
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$studid));
                $userdetails = $objectManager->getRepository('\Application\Entity\User')->findOneBy(array('id'=>$studentDetails->getUserId()));
                $totalclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findBy(array('showinmenu' => 1));
                //var_dump($totalclass);
                if(count($studentDetails) > 0){
                    $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
                } else {
                    $studentDetails = '';
                }
                $pageTitle = "Admin :: Edit Student Profile";
                $view = new ViewModel(array(
                    'totcls' => $totalclass,
                    'user' => $userdetails,
                    'studentdetails' => $studentDetails,
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            }

        }
        public function saveStudentProfileAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $data = $request->getPost();
                    $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$data['studid']));
                    $userdetails = $objectManager->getRepository('\Application\Entity\User')->findOneBy(array('id'=>$studentDetails->getUserId()));
                    if(count($studentDetails) > 0){
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

                        $userdetails->setPhonenum($data['inputphone']);
                        //$studentInfo->setCreated(time());
                        $objectManager->persist($studentDetails);
                        $objectManager->flush();
                        $message = "You have successfully updated the profile data";
                        $this->redirect()->toUrl('/admin/student/list-students');
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
        public function listStudentsPaymentAction() {
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findBy(array(
                    'paystatus'=> 0,
                ));
                
                $pageTitle = "Admin : List of Due Students";
                $view = new ViewModel(array(
                    'usrinfo' => $userdetails,
                    'allstud' => $studentDetails,
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
	}
        public function studentMarkPaidAction() {
            //$userdetails = $this->zfcUserAuthentication()->getIdentity();
            $stpay = $_REQUEST['stdid'];
            //echo $stpay;
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array(
                'id'=> $stpay,
            ));
            $userdetails = $objectManager->getRepository('\Application\Entity\User')->findOneBy(array(
                'id'=> $studentDetails->getUserId(),
            ));
            $studentDetails->setPaystatus('1');
            //$objectManager->persist($addsubj);
            $objectManager->flush();
            
            $smsapi = "5688def6ca7ab";
            $smssenderid = "BLFNGO";
            $smsroute = "transactional";
            $displayname = $userdetails->getdisplayName();
            $displayname = preg_replace('/\s+/', '', $displayname);
            $mobile = $userdetails->getPhonenum();
            
            //echo $displayname; exit;
            if(!empty($mobile)){
                $smsurl = "http://www.commnestsms.com/api/push?apikey=5688def6ca7ab&route=transactional&sender=BLFNGO&mobileno=$mobile&text=Dear $displayname, You have successfully paid eScholarship fee. Thank you for being part of Bright Life Foundation";
                $sendsmsurl = self::sendsms($smsurl);
                echo $sendsmsurl;
            }
            return;
	}
        public function listStudentsPaidAction() {
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findBy(array(
                    'paystatus'=> 1,
                ));
                
                $pageTitle = "Admin : List of Paid Students";
                $view = new ViewModel(array(
                    'usrinfo' => $userdetails,
                    'allstud' => $studentDetails,
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
	}
        public function studentMarkUnpaidAction() {
            $stpay = $_REQUEST['stdid'];
            //echo $stpay;
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array(
                'id'=> $stpay,
            ));
            $studentDetails->setPaystatus('0');
            $objectManager->flush();
            return;
	}
    public function showStudentPassAction(){
        if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $allStudpass = $objectManager->getRepository('\Application\Entity\TempUser')->findAll();

            $pageTitle = "Admin :: Show User Password";
            $view = new ViewModel(array(
                'allrecs' => $allStudpass,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
    }
    public function sendsms($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function showReferralsAction(){
        if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $allStudRef = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findAll();
            $allRegbyRef = $objectManager->createQuery("SELECT count(u.refcode) FROM \Application\Entity\User u WHERE u.refcode IS NOT NULL AND u.refcode LIKE 'BLF%'")
                ->getResult();
            
            $countRef = $objectManager->createQuery('SELECT count(r.userId), r.strefcode FROM \Student\Entity\StudentReferralInfo r WHERE NOT r.userId IS NULL GROUP BY r.userId')
                ->getResult();

            $refdetails = array();
            foreach ($countRef as $key => $value) {
                $studname = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('strefcode'=>$value['strefcode']));
                $refdetails[] = ['name'=> $studname->getFirstName() . " " . $studname->getMiddleName() . " " . $studname->getLastName(),
                    'phone' => $studname->getPhone(),
                    'totrefs' => $value[1]];
            }

            $pageTitle = "Admin :: Show Referrals";
            $view = new ViewModel(array(
                'allrefs' => $allStudRef,
                'regRefs' => $allRegbyRef[0][1],
                'refdetails' => $refdetails,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
    }

    public function showReferralSuccessAction(){
        if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $allStudRef = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findAll();
            $allRegbyRef = $objectManager->createQuery("SELECT count(u.refcode) FROM \Application\Entity\User u WHERE u.refcode IS NOT NULL AND u.refcode LIKE 'BLF%'")
                ->getResult();
            
            $countRef = $objectManager->createQuery('SELECT count(r.userId), r.strefcode FROM \Student\Entity\StudentReferralInfo r WHERE NOT r.userId IS NULL GROUP BY r.userId')
                ->getResult();

            $allRefStds = $objectManager->createQuery("SELECT u.id, u.username, u.refcode, COUNT(u.id) as recount FROM \Application\Entity\User u WHERE u.refcode IS NOT NULL AND u.refcode LIKE 'BLF%' GROUP BY u.refcode")
                ->getResult();
            //echo "<pre>";
            //var_dump($allRefStds);
            //exit;

            foreach ($allRefStds as $key => $value) {
                $studname = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('strefcode'=>$value['refcode']));
                if(count($studname) > 0){
                    $refdetails[] = ['name'=> $studname->getFirstName() . " " . $studname->getMiddleName() . " " . $studname->getLastName(),
                        'phone' => $studname->getPhone(),
                        'totrefs' => $value['recount'],
                        'uid' => $studname->getUserId()->getId(),
                    ];
                }
            }

            //var_dump($refdetails);
            //exit;
            $pageTitle = "Admin :: Show Registered Referrals";
            $view = new ViewModel(array(
                'allrefs' => $allStudRef,
                'regRefs' => $allRegbyRef[0][1],
                'refdetails' => $refdetails,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
    }
}
