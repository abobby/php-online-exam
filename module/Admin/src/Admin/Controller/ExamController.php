<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Exception;

class ExamController extends AbstractActionController {
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
            }
	}
        
        /*Class Management Start*/
        public function addClassAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $data = $request->getPost();
                    $addsubj = new \Admin\Entity\ExamClasses();
                    $addsubj->exchangeArray(array(
                        'name' => $data['inputclsname'],
                        'details' => $data['inputclsdeta'],
                        'createdAt' => new \DateTime(),
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($addsubj);
                    $objectManager->flush();
                    return $this->redirect()->toUrl('/admin/exam/list-class');
                }
                
                $pageTitle = "Admin : Exam : Add Class";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
            
        }
        public function editClassAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $request = $_REQUEST['clsid'];
                if (!empty($request)) {
                    $classDetails = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(Array(
                        'id' => $request,
                    ));
                }
                
                $getForm = $this->getRequest();
                if($getForm->isPost()){
                    $data = $getForm->getPost();
                    $classDetails->exchangeArray(array(
                        'name' => $data['inputclsname'],
                        'details' => $data['inputclsdeta'],
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($classDetails);
                    $objectManager->flush();
                    return $this->redirect()->toUrl('/admin/exam/list-class');
                }
                $pageTitle = "Admin : Exam : Edit Class";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'classDetails' => $classDetails,
                ));
                return $view;     
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        public function deleteClassAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                if(!empty($_REQUEST['theid'])){
                    $clsid = $_REQUEST['theid'];
                }
                
                if(!empty($clsid)){
                    /*try {
                        throw new Exception("Error Deleting the record. The might be releated Data linked with this record");*/
                    $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $remclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array(
                        'id' => $clsid,
                    ));
                    if(count($remclass) > 0){
                        $objectManager->remove($remclass);
                        $objectManager->flush();
                        $msg = "The record is successfuly Deleted";
                    } else {
                        $msg = "No Record Found";
                    }
                    /*} catch(Exception $e) {
                        echo "Error occured : $e\n";
                        exit;
                    }*/
                    
                } else {
                    $msg = "Something wrong please contact Administrator";
                }
                $pageTitle = "Admin :: Delete Class";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'message' => $msg,
                ));
                return $view;
            }
        }
        public function listClassAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                
                $allclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findBy(array(
                    'status' => 1,
                ));
                
                $classrepo = Array();
                foreach($allclass as $cls){
                    $totqustn = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(Array(
                        'exclass' => $cls->getId(),
                    ));
                    $classrepo[] = Array(
                        'clsid' => $cls->getId(),
                        'clsname' => $cls->getName(),
                        'clsdetail' => $cls->getDetails(),
                        'qstns' => count($totqustn),
                    );
                }
                //var_dump($classrepo); exit;
                $pageTitle = "Admin : Exam : List Classes";
                $view = new ViewModel(array(
                    'allcls' => $classrepo,
                    'pageTitle' => $pageTitle,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        /*Class Management End*/
        
        /*Subject Management Start*/
        public function addSubjectAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $data = $request->getPost();
                    $addsubj = new \Admin\Entity\ExamSubjects();
                    $addsubj->exchangeArray(array(
                        'name' => $data['inputsubname'],
                        'details' => $data['inputsubdeta'],
                        'createdAt' => new \DateTime(),
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($addsubj);
                    $objectManager->flush();
                    return $this->redirect()->toUrl('/admin/exam/list-subject');
                }
                
                $pageTitle = "Admin : Exam : Add Subject";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        public function editSubjectAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                
                $subjid = $_REQUEST['subjid'];
                if(!empty($subjid)){
                    $subjDetails = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findOneBy(Array(
                        'eid' => $subjid,
                    ));
                }
                
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $data = $request->getPost();
                    $subjDetails->exchangeArray(array(
                        'name' => $data['inputsubname'],
                        'details' => $data['inputsubdeta'],
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($subjDetails);
                    $objectManager->flush();
                    return $this->redirect()->toUrl('/admin/exam/list-subject');
                }
                
                $pageTitle = "Admin : Exam : Edit Subject";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'subjDetails' => $subjDetails,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }            
        }
        public function deleteSubjectAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                if(!empty($_REQUEST['subjid'])){
                    $sbid = $_REQUEST['subjid'];
                }
                if(!empty($sbid)){
                    $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $remsubj = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findOneBy(array(
                        'eid' => $sbid,
                    ));
                    if(count($remsubj) > 0){
                        $objectManager->remove($remsubj);
                        $objectManager->flush();
                        $msg = "The record is successfuly Deleted";
                    } else {
                        $msg = "No Record Found";
                    }
                    
                } else {
                    $msg = "Something wrong please contact Administrator";
                }
                $pageTitle = "Admin :: Delete Class";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'message' => $msg,
                ));
                return $view;
            }
        }
        public function listSubjectAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $allsubjects = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findAll();
                $pageTitle = "Admin : Exam : List Subject";
                $view = new ViewModel(array(
                    'allsubj' => $allsubjects,
                    'pageTitle' => $pageTitle,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        /*Subject Management End*/
        /*Exam Management Start*/
        public function createExamAction(){
            $pageTitle = "Admin : Exam : Create Exam";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
        public function editExamAction(){
            $pageTitle = "Admin : Exam : Edit Exam";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
        public function deleteExamAction(){
            
        }
        /*Exam Management End*/
        
        /*Paper Management Start*/
        public function createPaperAction(){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $exmsubjects = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findAll();
            $exmclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findAll();
            $exmqstns = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findAll();

            //var_dump($exmsubjects); exit;
            
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                var_dump($data);
                $exqstnsv->exchangeArray(array(
                    ''=>$data[''],
                ));
            }
            $pageTitle = "Admin : Exam : Create Paper";
            $view = new ViewModel(array(
                'exsub' => $exmsubjects,
                'excls' => $exmclass,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
        public function editPaperAction(){
            
        }
        public function deletePaperAction(){
            
        }
        /*Paper Management End*/
        
        /*Question Management Start*/
        public function createQuestionAction(){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $exmsubjects = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findAll();
            $exmclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findAll();
            $exmqstns = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findAll();

            //var_dump($exmsubjects); exit;
            
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                //var_dump($data);
                $selcls = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $data['inputexcls']));
                $selsub = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findOneBy(array('eid' => $data['inputexsubj']));
                //var_dump($selcls);
                //var_dump($selsub);
                $exqstnsv = new \Admin\Entity\ExamQuestions();
                $exqstnsv->exchangeArray(array(
                    'exmode' => $data['inputexmode'],
                    'exquest' => $data['inputexquest'],
                    'exchoice1' => $data['inputexopt1'],
                    'exchoice2' => $data['inputexopt2'],
                    'exchoice3' => $data['inputexopt3'],
                    'exchoice4' => $data['inputexopt4'],
                    'excrchoice' => $data['inputexans'],
                    'exclass' => $selcls,
                    'exsubject' => $selsub,
                    'createdBy' => $userdetails,
                    'createdAt' => new \DateTime(),
                    'updatedAt' => new \DateTime(),
                    'status' => 1
                ));
                //$exqstnsv->setCreatedAt(\DateTime('now'));
                //$exqstnsv->setUpdatedAt(\DateTime('now'));
                
                $objectManager->persist($exqstnsv);
                $objectManager->flush();
                
            }
            $pageTitle = "Admin : Exam : Create Paper";
            $view = new ViewModel(array(
                'exsub' => $exmsubjects,
                'excls' => $exmclass,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
        public function editQuestionAction(){
            $qstnno = $_REQUEST['qstid'];
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $exmsubjects = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findAll();
            $exmclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findAll();
            
            $exqstn = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array('id'=> $qstnno));
            $qstncls = $exqstn->getExclass()->getId();
            $qstnsub = $exqstn->getExsubject()->getEid();
            var_dump($qstnsub);

            $excls = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id'=> $qstncls));
            $exsub = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findOneBy(array('eid'=> $qstnsub));
            $svqstnmsg = '';
            
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                //echo "<pre>";
                //var_dump($data);
                //exit;
                $selcls = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $data['inputexcls']));
                $selsub = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findOneBy(array('eid' => $data['inputexsubj']));

                //var_dump($selcls);
                var_dump($selsub->geteid());
                //exit;
                $exqstnup = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array('id'=> $qstnno));
                $exqstnup->exchangeArray(array(
                    'exmode' => $data['inputexmode'],
                    'exquest' => $data['inputexquest'],
                    'exchoice1' => $data['inputexopt1'],
                    'exchoice2' => $data['inputexopt2'],
                    'exchoice3' => $data['inputexopt3'],
                    'exchoice4' => $data['inputexopt4'],
                    'excrchoice' => $data['inputexans'],
                    'exclass' => $selcls,
                    'exsubject' => $selsub,
                    'createdBy' => $userdetails,
                    'updatedAt' => new \DateTime(),
                    'status' => 1
                ));

                $objectManager->persist($exqstnup);
                $objectManager->flush();
                $svqstnmsg = "Question is updated now!";
            }
            
            $pageTitle = "Admin : Exam : Edit Paper";
            $view = new ViewModel(array(
                'qstnid' => $_REQUEST['qstid'],
                'exsub' => $exmsubjects,
                'excls' => $exmclass,
                'qstnsub' => $qstnsub,
                'qstncls' => $qstncls,
                'exqstn' => $exqstn,
                'svqstnmsg' => $svqstnmsg,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
        
        public function deleteQuestionAction(){
            
        }
        public function listQuestionAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $allquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findAll();
                $pageTitle = "Admin : Exam : List Questions";
                $view = new ViewModel(array(
                    'allqstn' => $allquestions,
                    'pageTitle' => $pageTitle,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }

        public function listClassQuestionsAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $getClass = $_REQUEST['clsid'];
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                if(!empty($getClass)){

                    $classExists = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array(
                        'id' => $getClass,
                    ));

                    if(count($classExists > 0)) {
                        $allquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                            'exclass' => $classExists,
                        ));
                    } else {
                        //invalid class
                    }
                } else {
                    //class not mentioned
                }
                
                //$allquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findAll();
                $pageTitle = "Admin : Exam : List Questions";
                $view = new ViewModel(array(
                    'allqstn' => $allquestions,
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        
        public function listsearchQuestionAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                
                $showclasses = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findAll();
                $showsubjects = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findAll();
                
                $request = $this->getRequest();
                $srchdquestions = '';
                
                if($request->isPost()){
                    $data = $request->getPost();
                    //var_dump($data);
                    
                    $srchclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array(
                        'id' => $data['allclass'],
                    ));
                    $srchsubject = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findOneBy(array(
                        'eid' => $data['allsubject'],
                    ));
                    
                    $srchdquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                        'exclass' => $srchclass,
                        'exsubject' => $srchsubject,
                        'exmode' => $data['allmode'],
                    ));
                    //var_dump($srchdquestions);
                }
                
                
                
                $pageTitle = "Admin : Exam : List Questions";
                $view = new ViewModel(array(
                    'allclass' => $showclasses,
                    'allsubj' => $showsubjects,
                    'gotquestions' => $srchdquestions,
                    'pageTitle' => $pageTitle,
                ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        public function showQuestionsDetailsAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $totalquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findAll();
                $totalclasses = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findAll();
                $totalsubjects = $objectManager->getRepository('\Admin\Entity\ExamSubjects')->findAll();
                
                $qstnrecs = Array();
                foreach($totalquestions as $qstn){
                    
                }
                
                $pageTitle = "Admin :: Show Question Details";
                $view = new ViewModel(Array(
                    'totquestions' => count($totalquestions),
                    'totalclasses' => count($totalclasses),
                    'totalsubjects' => count($totalsubjects),
                ));
            }
        }
        /*Question Management End*/
        
        public function studentResultsAction(){
           if(\Admin\Controller\AdminController::adminaccessAction() == true){
               $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
               $allexams = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findAll();
               $stdresults = '';
               /*Fetch Students Result for Event Selected */
               $request = $this->getRequest();
               if($request->isPost()){
                    $data = $request->getPost();
                    
                    $showforpaper = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array(
                        'id' => $data['examcode'],
                    ));
                    //var_dump($showforpaper);
                    $stdresults = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findBy(array(
                       'examPaperId' => $showforpaper,
                    ));
                    
                    //var_dump($stdresults); exit;
               }
               
               return new ViewModel(array(
                   'studentresult' => $stdresults,
                   'allexams' => $allexams,
               ));
           }
        }
    public function updateStudentScoresAction(){
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $examppr = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=>2));
        $stdexampaperstart = $objectManager->getRepository('Student\Entity\StudentExamStarted')->findBy(array(
            'examPaperId' => $examppr,
        ));
        //var_dump($stdexampaperstart);
        foreach($stdexampaperstart as $key=>$studentexp){
            //var_dump($studentexp->id);
            $stdgivenexam = $objectManager->getRepository('Student\Entity\StudentExamPaper')->findOneBy(array(
                'userId' => $studentexp->userId,
            ));

            if(count($stdgivenexam) > 0){
                $studentscoredb = $stdgivenexam->examQstnStatus;
                $studentscorearray = explode(',',$studentscoredb);
                $studentscorearraycount = array_count_values($studentscorearray);
                $studentscore = $studentscorearraycount['R'];
                echo $studentscore;
                echo "<br>";
                //var_dump($studentscore);
                if($stdgivenexam->totalScore == 0){
                    $stdgivenexam->exchangeArray(array(
                        'totalScore' => $studentscore,
                    ));
                    $objectManager->persist($stdgivenexam);
                    $objectManager->flush();
                } else {
                    //
                }

            } else {
                //$studentscore = $stdgivenexam->examQstnStatus;

            }
        }
    }
    
    public function showExamStatusAction(){
        if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            
            $totExamStarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findBy(array('examPaperId' => 2));
            $totExamSubmited = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findBy(array('examPaperId' => 2));
            //var_dump($totExamSubmited);
            $countExamStarted = count($totExamStarted);
            $countExamSubmited = count($totExamSubmited);
            
            /*$examStatus = Array();
            foreach($totExamSubmited as $key=>$sstats){
                $examStatus['stid'] = $sstats->userId->getId();
                $examStatus['stuname'] = $sstats->userId->getUsername();
                $examStatus['class'] = $sstats->userId->student->getClassGrade();
                
                
            }*/
            
            $pageTitle = "Admin : Exam Status";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
                'countExStart' => $countExamStarted,
                'countExComp' => $countExamSubmited,
                'exSubmitStat' => $totExamSubmited,
            ));
            return $view;
        }
    }
    
     public function reExamStudentAction(){
        if(\Admin\Controller\AdminController::adminaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            
            $request = $this->getRequest();
            if($request->isPost()){
                $data = $request->getPost();
                $forstd = $data['stdid'];
                $usrdetail = $objectManager->getRepository('\Application\Entity\User')->findOneBy(array('id' => $forstd));
                if(count($usrdetail) > 0) {
                    $stdetail = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array(
                        'userId'=> $usrdetail,
                        'paystatus' => 1
                    ));
                    
                    if(count($stdetail) > 0){
                        $ifexsubmited = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                            'userId' => $usrdetail,
                            'examPaperId' => 2,
                        ));
                        if(count($ifexsubmited) > 0){
                            //$msg = "This Student Already Submitted Paper. Can't delete/modifiy the record";
                            $rmvfromexstart = $objectManager->getRepository('Student\Entity\StudentExamStarted')->findOneBy(array(
                                'userId' => $usrdetail,
                                'examPaperId' => 2,
                            ));
                            if(count($rmvfromexstart) > 0) {
                                $msg = "Ok Student Started Exam but didnt submit paper";
                                $msg .= "Deleting Record now";
                                $objectManager->remove($rmvfromexstart);
                                $objectManager->remove($ifexsubmited);
                                $objectManager->flush();
                                $msg .= "Record Deleted Successfully. This user can retake exam now";
                            } else {
                                $msg = "This student did not Start the exam";
                            }
                        } else {
                            $rmvfromexstart = $objectManager->getRepository('Student\Entity\StudentExamStarted')->findOneBy(array(
                                'userId' => $usrdetail,
                                'examPaperId' => 2,
                            ));
                            if(count($rmvfromexstart) > 0) {
                                $msg = "Ok Student Started Exam but didnt submit paper";
                                $msg .= "Deleting Record now";
                                $objectManager->remove($rmvfromexstart);
                                $objectManager->flush();
                                $msg .= "Record Deleted Successfully. This user can retake exam now";
                            } else {
                                $msg = "This student did not Start the exam";
                            }
                        }
                    } else {
                        $msg = "This Student is not valid or authorised for giving exam";
                    }
                    //var_dump($stddetail->firstName);
                    
                } else {
                    $msg = "This User ID does not exist";
                }
                
                //echo $msg;
                
                $pageTitle = "Reset Student Exam";
                $view = new ViewModel(array(
                   'pageTitle' => $pageTitle,
                   'message' => $msg, 
                ));
                return $view;
            }
        }
    }
    public function updateStudentMissedExamAction(){
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $examppr = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=>2));
        $stdexampaperstart = $objectManager->getRepository('Student\Entity\StudentExamStarted')->findBy(array(
            'examPaperId' => $examppr,
        ));
        //var_dump($stdexampaperstart);
        $norecord = 0;
        foreach($stdexampaperstart as $key=>$studentexp){
            //var_dump($studentexp->id);
            $stdgivenexam = $objectManager->getRepository('Student\Entity\StudentExamPaper')->findOneBy(array(
                'userId' => $studentexp->userId,
            ));
            
            /*Students who records were not saved*/
            if(count($stdgivenexam) > 0){
                //
            } else {
                //$norecord++;
                $studentrecord = $objectManager->getRepository('Student\Entity\StudentDetails')->findOneBy(array(
                    'userId' => $studentexp->userId,
                ));
                
                /* Get paper and maximum questions */
                $minexamqustndb = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array(
                    'id' => 2,
                ));
                $minexampprsetdb = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array(
                    'id' => 2,
                ));
                $minexamqustn = $minexamqustndb->getTotalMarks();
                
                /* Get students Class */
                if(!empty($studentrecord->getClassGrade())){
                    $studentclass = $studentrecord->getClassGrade();    
                } else {
                    $studentclass = 12;
                }
                $studentActualClass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array(
                    'id' => $studentclass,
                ));
                
                /* If students class exists */
                if(count($studentActualClass) > 0){
                    //echo "<br>".$studentActualClass->getName()." : ";

                    /*Check questions per class*/
                    $classquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                        'exclass' => $studentActualClass,
                    ));
                    
                    if(count($classquestions) > $minexamqustn){
                        /* Check if state is added */
                        if(!empty($studentrecord->getState())){
                            $stdstate = strtolower($studentrecord->getState());
                            /* If state is within Hindi states */
                            if($stdstate == 'haryana' || $stdstate == 'rajasthan' || $stdstate == 'bihar' || $stdstate == 'uttar pradesh'){
                                //echo " : In Hindi State ";
                                $exmode = 2;
                                $modequestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                                    'exclass' => $studentActualClass,
                                    'exmode' => $exmode,
                                ));
                                /* If State is Hindi but Hindi Questions are more */
                                if(count($modequestions) > $minexamqustn){
                                    $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                                        'exclass' => $studentActualClass,
                                        'exmode' => $exmode,
                                    ));
                                    //echo " : Hindi suffecient ";
                                    //echo " : Total Questions : ". count($examquestions);
                                } else {
                                    //echo " : Hindi not suffecient";
                                    /* State is Hindi but Hindi Questions are less Default English */
                                    $exmode = 1;
                                    $examengquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                                        'exclass' => $studentActualClass,
                                        'exmode' => $exmode,
                                    ));
                                    //echo " : Total Questions : ". count($examquestions);
                                    
                                }
                            } else {
                                /* Non hindi states exam is English */
                                //echo "Non hindi state";
                                $exmode = 1;
                                $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                                    'exclass' => $studentActualClass,
                                    'exmode' => $exmode,
                                ));
                                //echo " : Total Questions : ". count($examquestions);
                            }
                        } else {
                           //echo " : Not in State : ";
                           /* If state is not added Mode is English */
                           $exmode = 1;
                           $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                                'exclass' => $studentActualClass,
                                'exmode' => $exmode,
                            ));
                            //echo " : Total Questions : ". count($examquestions);
                        }
                        //echo count($classquestions)."<br>";
                    } else {
                        //echo " Less Questions : ";
                        $studentclass = 12;
                        $studentActualClass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array(
                            'id' => $studentclass,
                        ));
                        $exmode = 1;
                        $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                            'exclass' => $studentActualClass,
                            'exmode' => $exmode,
                        ));
                        //echo " : Total Questions : ". count($examquestions);
                    }
                } else {
                    //echo " : No Class";
                    $studentclass = 12;
                    $studentActualClass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array(
                        'id' => $studentclass,
                    ));
                    $exmode = 1;
                    $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                        'exclass' => $studentActualClass,
                        'exmode' => $exmode,
                    ));
                }
                //echo " : Total Questions : ". count($examquestions);
                /* Count Total Questions in Students Class/Grade */
                $totexamquestions = count($examquestions);
                $stdclsquestionids = Array();
                foreach($examquestions as $val){
                    $stdclsquestionids[] = $val->getId();
                }
                //print_r($stdclsquestionids);
                $shufquestions = array_rand($stdclsquestionids, $minexamqustn);
                $studentquestions = Array();
                foreach($shufquestions as $shuquestn){
                    $qstnid = $stdclsquestionids[$shuquestn];
                    $finalexamquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array(
                        'id' => $qstnid
                    ));
                    $stdchoice = rand(1,4);
                    $studentquestions[] = Array(
                        'qsid' => $finalexamquestions->getId(),
                        'stdchoice' => $stdchoice,
                        'crchoice' => $finalexamquestions->getExcrchoice(),
                        //'question' => $finalexamquestions->getExquest(),
                        //'choice1' => $finalexamquestions->getExchoice1(),
                        //'choice2' => $finalexamquestions->getExchoice2(),
                        //'choice3' => $finalexamquestions->getExchoice3(),
                        //'choice4' => $finalexamquestions->getExchoice4(),
                    );
                }
                //var_dump($studentquestions);
                echo $studentrecord->getId()." ######################## <br>";
                $finalquestionsarray = array();
                $finalchoicearray = array();
                $finalstatusarray = array();
                $score = 0;
                foreach($studentquestions as $key => $val){
                    $finalquestionsarray[] = $val['qsid'];
                    $finalchoicearray[] = $val['stdchoice'];
                    if($val['stdchoice'] == $val['crchoice']){
                        $finalstatusarray[] = 'R';
                        $score++;
                    } else {
                        $finalstatusarray[] = 'W';
                    }
                }
                //echo $score." :::::::: <br>";
                $finalquestions = implode(',', $finalquestionsarray);
                //echo $finalquestions,"<br>";
                $finalchoice = implode(',', $finalchoicearray);
                //echo $finalchoice."<br>";
                $finalstatus = implode(',',$finalstatusarray);
                //echo $finalstatus."<br>";
                
                /*$stduserid = $objectManager->getRepository('\Application\Entity\User')->findOneBy(array(
                   'id' => $studentexp->userId,
                ));*/
                //var_dump($studentexp->userId->getId());
                /*$addrectostdexpaper = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                   'userId' => $stduserid,
                   'examPaperId' => $minexamqustndb,
                ));*/
                $addrectostdexpaper = new \Student\Entity\StudentExamPaper();
                $addrectostdexpaper->exchangeArray(array(
                    'userId' => $studentexp->userId,
                    'examPaperId' => $minexamqustndb,
                    'examSetId' => $minexampprsetdb,
                    'examQstnIds' => $finalquestions,
                    'examQstnOptions' => $finalchoice,
                    'examQstnStatus' => $finalstatus,
                    'totalScore' => $score,
                    'createdAt' => new \DateTime(),
                    'updatedAt' => new \DateTime(),
                    'status' => 1,
                ));
                $objectManager->persist($addrectostdexpaper);
                $objectManager->flush();
                
            } /* End of Students not in StudentExamPaper */
        }
        //echo $norecord;
    }
}
