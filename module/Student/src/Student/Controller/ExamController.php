<?php

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ExamController extends AbstractActionController {
    protected $var;

    public function indexAction(){

            $pageTitle = "Exam :: Index";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
            ));
            return $view;
    }

    public function dashboardAction(){

            $pageTitle = "Exam :: Dashboard";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
            ));
            return $view;
    }
    /* Encode Variables passed in URL */
    public function encodeInputAction($inp) {
        return base64_encode(str_rot13($inp));
    }

    /* Decode Endocded Variables passed in URL */
    public function decodeInputAction($inp) {
        return str_rot13(base64_decode($inp));
    }

    public function startExamAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            
            $examstatus = 1;
            if($examstatus == 1) {
                return $this->redirect()->toUrl('/student/student/dashboard');
            } else {
            if(!empty($_REQUEST['stexid'])){
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
                $studmed = $studentDetails->getScmedium();
                if($studmed == 'english'){
                    $studmode = 1;
                } elseif ($studmed == 'hindi'){
                    $studmode = 2;
                } else {
                    $studmode = 0;
                }
                //$studmode = $_REQUEST['exammode'];
                if($studmode == 1 || $studmode == 2){
                $studexamid = $_REQUEST['stexid'];
                $stidpex = $_REQUEST['stexid'];
                $studexamid = self::decodeInputAction($studexamid);
                $studexamid = explode('strtEXMcode_', $studexamid);

                $examid = $studexamid[1];
                
                /* Check Paper ID exists */
                $demoexmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> $examid));
                $demoexmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> $demoexmpprid->paperSets));
                $totalQuestions = $demoexmpprid->totalMarks;
                $forClass = $studentDetails->getClassGrade();
                $stdactualclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $forClass));

                if(count($demoexmpprid) > 0 ){
                    /* From Demo Exam */
                    $exmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                        'userId' => $userdetails,
                        'examPaperId' => $demoexmpprid,
                    ));
                    $studdemoscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                        'userId'=> $userdetails,
                        'examPaperId' => $demoexmpprid,
                    ));
                    if(count($studdemoscoreexist) > 0){
                        return $this->redirect()->toUrl('/student/student/dashboard');
                    }
                    
                    if(count($exmalreadystarted) > 0){
                        if(new \DateTime() > $exmalreadystarted->getStoppedAt()){
                            $paperattempt = 1;
                            //$demoattempt = 0;
                            $extimeleft = 0;
                        } else {
                            $paperattempt = 0;
                            $timetostart = new \DateTime();
                            $timetostop = $exmalreadystarted->getStoppedAt();
                            $diff=$timetostop->diff($timetostart);
                            $extimeleft = $diff->i;
                        }
                    } else {
                        $paperattempt = 0;
                        $stdpprstart = new \Student\Entity\StudentExamStarted();
                        $sdate = new \DateTime();
                        $extimeleft = $demoexmpprid->getPapertime();
                        $stopdate = $sdate->modify("+$extimeleft minutes");
                        $stdpprstart->setStoppedAt($stopdate);

                        $stdpprstart->exchangeArray(Array(
                            'userId' => $userdetails,
                            'examPaperId' => $demoexmpprid,
                            'examSetId' => $demoexmpprsetid,
                            'startedAt' => new \DateTime(),
                            'stoppedAt' => $stopdate,
                            'createdAt' => new \DateTime(),
                            'updatedAt' => new \DateTime(),
                            'status' => 1
                        ));
                        $objectManager->persist($stdpprstart);
                        $objectManager->flush();
                    }                    
                    /*End from Demo Exam */
                    
                    /*Load Exam Questions for Logged In Students Class/Grade*/
                    $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                        'exclass' => $stdactualclass,
                        //'exsubject' => ''
                        'exmode' => $studmode,
                    ));

                    /*If Questions are not there for sudents Class Load All Classess Questions */
                    if(count($examquestions) < $totalQuestions){
                        //$studmode = 2;
                        $forClass = 12;
                        $forClass2 = 17;
                        $stdactualclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $forClass));
                        $stdactualclass2 = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $forClass2));
                        $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                            'exclass' => array($stdactualclass, $stdactualclass2),
                            //'exsubject' => ''
                            'exmode' => $studmode,
                        ));
                    }
                    //var_dump($examquestions);
                    /* Count Total Questions in Students Class/Grade */
                    $totexamquestions = count($examquestions);
                    
                    //echo $totexamquestions;exit;
                    $stdclsquestionids = Array();
                    foreach($examquestions as $val){
                        $stdclsquestionids[] = $val->getId();
                    }

                    //echo $totexamquestions;
                    if(empty($_SESSION['schexampaperquestion'])){
                        $shufquestions = array_rand($stdclsquestionids, $totalQuestions);
                        $studentquestions = Array();
                        foreach($shufquestions as $shuquestn){
                            $qstnid = $stdclsquestionids[$shuquestn];
                            $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array(
                                'id' => $qstnid
                            ));
                            $studentquestions[] = Array(
                                'qsid' => $examquestions->getId(),
                                'question' => $examquestions->getExquest(),
                                'choice1' => $examquestions->getExchoice1(),
                                'choice2' => $examquestions->getExchoice2(),
                                'choice3' => $examquestions->getExchoice3(),
                                'choice4' => $examquestions->getExchoice4(),
                            );
                        }
                      
                        $_SESSION['schexampaperquestion'] = $studentquestions;
                    }

                    $msg = '';
                    } else {
                        $msg = "Sorry this Exam has either expired or not available for you. Please contact at blfngoindia@gmail.com.";
                        $_SESSION['schexampaperquestion'] = Array();
                    }
                    } else { 
                        return $this->redirect()->toUrl('/student/student/dashboard');
                    }
                } else {
                    $msg = "Sorry this Exam has either expired or not available for you. Please contact at blfngoindia@gmail.com.";
                    $_SESSION['schexampaperquestion'] = Array();
                }
                $request = $this->getRequest();
                if($request->isPost()){
                    //echo "Form submitted";
                }
            $pageTitle = "Exam Center";
            $view = new ViewModel(array(
                'msg' => $msg,
                'pprattempt' => $paperattempt,
                'idpex' => $stidpex,
                'totalqstns' => $totalQuestions,
                'myexamquestions' => $_SESSION['schexampaperquestion'], 
                'pageTitle' => $pageTitle,
                'timetostop' => $extimeleft,
            ));
            return $view;
            }
        }
    }
    
    public function demoExamAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            if(!empty($_REQUEST['stexid'])){
                $studexamid = $_REQUEST['stexid'];
                $stidpex = $_REQUEST['stexid'];
                $studexamid = self::decodeInputAction($studexamid);
                $studexamid = explode('strtEXMcode_', $studexamid);
                $examid = $studexamid[1];
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
                
                /* Check Paper ID exists */
                $demoexmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> $examid));
                $demoexmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> $demoexmpprid->paperSets));
                $totalQuestions = $demoexmpprid->totalMarks;
                $forClass = 12; //$classGrade
                $stdactualclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $forClass));

                if(count($demoexmpprid) > 0 ){
                    /* From Demo Exam */
                    $exmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                        'userId' => $userdetails,
                        'examPaperId' => $demoexmpprid,
                    ));
                    
                    /*$studdemoscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                        'userId'=> $userdetails,
                        'examPaperId' => $demoexmpprid,
                    ));

                    if(count($studdemoscoreexist) > 0){
                        return $this->redirect()->toUrl('/student/student/dashboard');
                    }*/
                    
                    if(count($exmalreadystarted) > 0){
                        $paperattempt = 0;
                        $stdpprstart = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                            'userId' => $userdetails,
                            'examPaperId' => $demoexmpprid,
                        ));
                        $sdate = new \DateTime();
                        $extimeleft = $demoexmpprid->getPapertime();
                        $stopdate = $sdate->modify("+$extimeleft minutes");
                        //var_dump($stopdate);exit;
                        $stdpprstart->setStoppedAt($stopdate);

                        $stdpprstart->exchangeArray(Array(
                            'userId' => $userdetails,
                            'examPaperId' => $demoexmpprid,
                            'examSetId' => $demoexmpprsetid,
                            'startedAt' => new \DateTime(),
                            'stoppedAt' => $stopdate,
                            'updatedAt' => new \DateTime(),
                            'status' => 1
                        ));
                        $objectManager->persist($stdpprstart);
                        $objectManager->flush();
                    } else {
                        $paperattempt = 0;
                        $stdpprstart = new \Student\Entity\StudentExamStarted();
                        $sdate = new \DateTime();
                        $extimeleft = $demoexmpprid->getPapertime();
                        $stopdate = $sdate->modify("+$extimeleft minutes");
                        $stdpprstart->setStoppedAt($stopdate);

                        $stdpprstart->exchangeArray(Array(
                            'userId' => $userdetails,
                            'examPaperId' => $demoexmpprid,
                            'examSetId' => $demoexmpprsetid,
                            'startedAt' => new \DateTime(),
                            'stoppedAt' => $stopdate,
                            'createdAt' => new \DateTime(),
                            'updatedAt' => new \DateTime(),
                            'status' => 1
                        ));
                        $objectManager->persist($stdpprstart);
                        $objectManager->flush();
                    }                    
                    /*End from Demo Exam */
                    
                    /*Load Exam Questions for Logged In Students Class/Grade*/
                    $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                        'exclass' => $stdactualclass,
                        'exmode' => 1
                    ));
                    /*If Questions are not there for sudents Class Load All Classess Questions*/
                    if(count($examquestions) <= 0){
                        $forClass = 1;
                        $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                            'exclass' => $stdactualclass,
                            'exmode' => 1
                        ));
                        
                    }

                    /* Count Total Questions in Students Class/Grade */
                    $totexamquestions = count($examquestions);
                    foreach($examquestions as $val){
                        $stdclsquestionids[] = $val->getId();
                    }

                    if(empty($_SESSION['myexampaperquestion'])){
                        $shufquestions = array_rand($stdclsquestionids, $totalQuestions);
                        $studentquestions = Array();
                        foreach($shufquestions as $shuquestn){
                            $qstnid = $stdclsquestionids[$shuquestn];
                            $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array(
                                'id' => $qstnid
                            ));
                            
                            $studentquestions[] = Array(
                                'qsid' => $examquestions->getId(),
                                'question' => $examquestions->getExquest(),
                                'choice1' => $examquestions->getExchoice1(),
                                'choice2' => $examquestions->getExchoice2(),
                                'choice3' => $examquestions->getExchoice3(),
                                'choice4' => $examquestions->getExchoice4(),
                            );

                        }
                        $_SESSION['myexampaperquestion'] = $studentquestions;
                    }

                    $msg = '';
                    } else {
                        $msg = "Sorry this Exam has either expired or not available for you. Please contact at blfngoindia@gmail.com.";
                        $_SESSION['myexampaperquestion'] = Array();
                    }
                } else {
                    $msg = "Sorry this Exam has either expired or not available for you. Please contact at blfngoindia@gmail.com.";
                    $_SESSION['myexampaperquestion'] = Array();
                }
            $pageTitle = "Demo Exam Center";
            $view = new ViewModel(array(
                'msg' => $msg,
                'pprattempt' => $paperattempt,
                'idpex' => $stidpex,
                'totalqstns' => $totalQuestions,
                'myexamquestions' => $_SESSION['myexampaperquestion'], 
                'pageTitle' => $pageTitle,
                'timetostop' => $extimeleft,
            ));
            return $view;
        }
    }
    
    public function submitDemoPaperAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $request = $this->getRequest();
            if($request->isPost()){
                $data = $request->getPost();
                /*Get Exam Paper Id */
                $exampaperidval = $data['idpex'];
                $studexamid = self::decodeInputAction($exampaperidval);
                $exampaperidarray = explode('strtEXMcode_', $studexamid);
                $exampaperid = $exampaperidarray[1];
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));

                $exmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> $exampaperid));
                if(count($exmpprid) > 0) {
                    $exmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> $exampaperid));
                    $exmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                        'userId' => $userdetails,
                        'examPaperId'=> $exmpprid
                    ));
                    if(count($exmalreadystarted) > 0){
                        if(new \DateTime() > $exmalreadystarted->getStoppedAt()){
                            $paperattempt = 1;
                        } else {
                            $paperattempt = 0;
                        }
                    } else {
                        $paperattempt = 0;
                    }

                    if($paperattempt == 1){
                        $msg = "This Exam has either been submitted or has Expired.";
                    } elseif($paperattempt == 0) {
                        $result = Array();
                        foreach($data as $key => $value){
                            $postn = explode('_', $key);
                            if($postn[0] == 'qid'){
                                $result[$postn[1]]['qstid'] = (int) $value;
                                $result[$postn[1]]['qstans'] = 'NA';
                            } elseif($postn[0] == 'qans'){
                                $result[$postn[1]]['qstans'] = (int) $value;
                            }
                        }
                        $exmattempt = 0;
                        foreach($result as $key => $val){
                            if($val['qstans'] == 'NA'){
                                $result[$key]['qstcrans'] = 'NA';
                                $exmattempt++;
                            } else {
                                $getqstnanswer = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array('id'=> $val['qstid']));
                                if($val['qstans'] == $getqstnanswer->getExcrchoice()){
                                    $answer = 'R';
                                } else {
                                    $answer = 'W';
                                }
                                $result[$key]['qstcrans'] = $answer;
                                $result[$key]['organs'] = $getqstnanswer->getExcrchoice();
                            }
                        }
                        
                        $stexmqstnids = $stexmqstnstatus = $stexmqstnoptions = Array();
                        $stexmscore = 0;
                        foreach($result as $key => $val){
                            $stexmqstnids[] = $val['qstid'];
                            $stexmqstnstatus[] = $val['qstcrans'];
                            $stexmqstnoptions[] = $val['qstans'];
                            if($val['qstcrans'] == 'R'){
                                $stexmscore++;
                            } else {
                               $stexmscore = $stexmscore; 
                            }
                        }
                        $stexmqstnids = implode(',', $stexmqstnids);
                        $stexmqstnstatus = implode(',', $stexmqstnstatus);
                        $stexmqstnoptions = implode(',', $stexmqstnoptions);
                        
                        $stdalreadydiddemo = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                            'userId' => $userdetails,
                            'examPaperId' => $exmpprid,
                        ));
                        if(count($stdalreadydiddemo) > 0){
                            /* Update Existing Exam Record */
                            $stdalreadydiddemo->exchangeArray(Array(
                                'userId' => $userdetails,
                                'examPaperId' => $exmpprid,
                                'examSetId' => $exmpprsetid,
                                'examQstnIds' => $stexmqstnids,
                                'examQstnStatus' => $stexmqstnstatus,
                                'examQstnOptions' => $stexmqstnoptions,
                                'totalScore' => $stexmscore,
                                'updatedAt' => new \DateTime(),
                                'status' => 1
                            ));
                            $objectManager->persist($stdalreadydiddemo);
                            $objectManager->flush();
                            $msg = "";
                        } else {
                            /* Add New Exam Record */
                            $studdemoscoreadd = new \Student\Entity\StudentExamPaper();
                            $studdemoscoreadd->exchangeArray(Array(
                                'userId' => $userdetails,
                                'examPaperId' => $exmpprid,
                                'examSetId' => $exmpprsetid,
                                'examQstnIds' => $stexmqstnids,
                                'examQstnStatus' => $stexmqstnstatus,
                                'examQstnOptions' => $stexmqstnoptions,
                                'totalScore' => $stexmscore,
                                'createdAt' => new \DateTime(),
                                'updatedAt' => new \DateTime(),
                                'status' => 1
                            ));
                            $objectManager->persist($studdemoscoreadd);
                            $objectManager->flush();
                            $msg = "";
                        }
                    } else {
                        return $this->redirect()->toUrl('/student/student/dashboard');
                    }
                } else {
                    return $this->redirect()->toUrl('/student/student/dashboard');
                }
            }
            
            $view = new ViewModel(Array(
                'appmsg' => $msg,
                'exmscore' => $stexmscore,
                'exmattempt' => $exmattempt,
                'totexmqstn' => count($result),
                'dispscore' => 1,
                'paperattempt' => $paperattempt,
            ));
            return $view;
        }
    }
    
    public function demoExamOldAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            $exmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> 1));
            $exmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> 1));

            $exmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array('userId'=>$userdetails));
            $studdemoscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array('userId'=> $userdetails));
            /*if(count($studdemoscoreexist) > 0){
                return $this->redirect()->toUrl('/student/student/dashboard');
            }*/ /*Disabled for a while */

            if(count($exmalreadystarted) > 0){
                //var_dump($exmalreadystarted->getStoppedAt());
                if(new \DateTime() > $exmalreadystarted->getStoppedAt()){
                    //$demoattempt = 1; Disabled for a while
                    $demoattempt = 0;
                    $extimeleft = 10;
                } else {
                    $demoattempt = 0;
                    $timetostart = new \DateTime();
                    $timetostop = $exmalreadystarted->getStoppedAt();
                    $diff=$timetostop->diff($timetostart);
                    $extimeleft = $diff->i;
                    //exit;
                    /* Added for a while*/
                    $sdate = new \DateTime();
                    $stopdate = $sdate->modify("+60 minutes");
                    //$stdpprstart->setStoppedAt($stopdate);

                    $exmalreadystarted->exchangeArray(Array(
                        'startedAt' => new \DateTime(),
                        'stoppedAt' => $stopdate,
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($exmalreadystarted);
                    $objectManager->flush();
                }
            } else {
                $demoattempt = 0;
                $stdpprstart = new \Student\Entity\StudentExamStarted();
                $sdate = new \DateTime();
                $stopdate = $sdate->modify("+60 minutes");
                $stdpprstart->setStoppedAt($stopdate);

                $stdpprstart->exchangeArray(Array(
                    'userId' => $userdetails,
                    'examPaperId' => $exmpprid,
                    'examSetId' => $exmpprsetid,
                    'startedAt' => new \DateTime(),
                    'stoppedAt' => $stopdate,
                    'createdAt' => new \DateTime(),
                    'updatedAt' => new \DateTime(),
                    'status' => 1
                ));
                $objectManager->persist($stdpprstart);
                $objectManager->flush();
                $extimeleft = 10;
            }


            if($demoattempt != 1){
            $pageTitle = "Exam :: Demo Exam";
            $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
                'timetostop' => $extimeleft,
            ));
            return $view;
            } else {
                return $this->redirect()->toUrl('/student/student/dashboard');
            }
        }
    }
    public function submitDemoPaperOldAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            $demoexmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> 1));
            $demoexmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> 1));
            $demoexmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array('examPaperId'=> $demoexmpprid));
            if(count($demoexmalreadystarted) > 0){
                if(new \DateTime() > $demoexmalreadystarted->getStoppedAt()){
                    $demoattempt = 1;
                } else {
                    $demoattempt = 0;
                }
            } else {
                $demoattempt = 0;
            }
            if($demoattempt != 1){
            $request = $this->getRequest();
            if ($request->isPost()) {

                $data = $request->getPost();
                //var_dump($data);

                $result = Array();
                foreach($data as $key => $value){
                    $postn = explode('_', $key);
                    $result[$key] = Array(
                        'choice' => $value,
                        'attempt' => 'Y',
                        'qtno' => $postn[1],
                    );
                    if($key == 'qans_1' && $value == 'b' || $key == 'qans_2' && $value == 'd' || $key == 'qans_3' && $value == 'd' || $key == 'qans_4' && $value == 'c' || $key == 'qans_5' && $value == 'a'){
                        $result[$key]['resl'] = 'Correct';
                    } else {
                        $result[$key]['resl'] = 'Incorrect';
                    }
                }

                $stexmqstnids = $stexmqstnstatus = $stexmqstnoptions = Array();
                $stexmscore = 0;
                foreach($result as $key => $val){
                    $stexmqstnids[] = $val['qtno'];
                    $stexmqstnstatus[] = $val['resl'];
                    $stexmqstnoptions[] = $val['choice'];
                    if($val['resl'] == 'Correct'){
                        $stexmscore++;
                    }
                }


                $stexmqstnids = implode(',',$stexmqstnids);
                $stexmqstnstatus = implode(',',$stexmqstnstatus);
                $stexmqstnoptions = implode(',',$stexmqstnoptions);
                //$stexmscore = '';

                //echo $stexmqstnids . "<br>" . $stexmqstnstatus . "<br>" . $stexmqstnoptions . "<br>" . $stexmscore;
                //exit;

                $studdemoscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array('userId'=> $userdetails));
                if(count($studdemoscoreexist) > 0){
                    //return $this->redirect()->toUrl('/student/student/dashboard'); Disabled for a while
                    $studdemoscoreexist->exchangeArray(Array(
                        'examQstnIds' => $stexmqstnids,
                        'examQstnStatus' => $stexmqstnstatus,
                        'examQstnOptions' => $stexmqstnoptions,
                        'totalScore' => $stexmscore,
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($studdemoscoreexist);
                    $objectManager->flush();
                } else {
                    $studdemoscoreadd = new \Student\Entity\StudentExamPaper();
                    $studdemoscoreadd->exchangeArray(Array(
                        'userId' => $userdetails,
                        'examPaperId' => $demoexmpprid,
                        'examSetId' => $demoexmpprsetid,
                        'examQstnIds' => $stexmqstnids,
                        'examQstnStatus' => $stexmqstnstatus,
                        'examQstnOptions' => $stexmqstnoptions,
                        'totalScore' => $stexmscore,
                        'createdAt' => new \DateTime(),
                        'updatedAt' => new \DateTime(),
                        'status' => 1
                    ));
                    $objectManager->persist($studdemoscoreadd);
                    $objectManager->flush();
                }

                $excode = "Demo Exam";
                $pageTitle = "Thanks for submiting your $excode";
                $view = new ViewModel(array(
                        'resl' => $result,
                        'totqstn' => 5,
                        'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/student/student/dashboard');
            }
        } else {
                return $this->redirect()->toUrl('/student/student/dashboard');
            }
        }
    }
    public function testExamAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            if(!empty($_REQUEST['stexid'])){
                $studexamid = $_REQUEST['stexid'];
                $stidpex = $_REQUEST['stexid'];
                //echo self::encodeInputAction($studexamid);
                $studexamid = self::decodeInputAction($studexamid);
                $studexamid = explode('strtEXMcode_', $studexamid);

                $examid = $studexamid[1];
                //echo $examid;
                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
                
                /* Check Paper ID exists */
                $demoexmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> $examid));
                $demoexmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> $demoexmpprid->paperSets));
                $totalQuestions = $demoexmpprid->totalMarks;
                //echo "Total questions $totalQuestions";
                $forClass = $studentDetails->getClassGrade(); //$classGrade
                $stdactualclass = $objectManager->getRepository('\Admin\Entity\ExamClasses')->findOneBy(array('id' => $forClass));

                if(count($demoexmpprid) > 0 ){
                    /* From Demo Exam */
                    $exmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                        'userId' => $userdetails,
                        'examPaperId' => $demoexmpprid,
                    ));
                    $studdemoscoreexist = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                        'userId'=> $userdetails,
                        'examPaperId' => $demoexmpprid,
                    ));
                    if(count($studdemoscoreexist) > 0){
                        return $this->redirect()->toUrl('/student/student/dashboard');
                    }
                    
                    if(count($exmalreadystarted) > 0){
                        //var_dump($exmalreadystarted->getStoppedAt());
                        if(new \DateTime() > $exmalreadystarted->getStoppedAt()){
                            $paperattempt = 1;
                            //$demoattempt = 0;
                            $extimeleft = 0;
                        } else {
                            $paperattempt = 0;
                            $timetostart = new \DateTime();
                            $timetostop = $exmalreadystarted->getStoppedAt();
                            $diff=$timetostop->diff($timetostart);
                            $extimeleft = $diff->i;
                        }
                    } else {
                        $paperattempt = 0;
                        $stdpprstart = new \Student\Entity\StudentExamStarted();
                        $sdate = new \DateTime();
                        $extimeleft = $demoexmpprid->getPapertime();
                        $stopdate = $sdate->modify("+$extimeleft minutes");
                        //var_dump($stopdate);exit;
                        $stdpprstart->setStoppedAt($stopdate);

                        $stdpprstart->exchangeArray(Array(
                            'userId' => $userdetails,
                            'examPaperId' => $demoexmpprid,
                            'examSetId' => $demoexmpprsetid,
                            'startedAt' => new \DateTime(),
                            'stoppedAt' => $stopdate,
                            'createdAt' => new \DateTime(),
                            'updatedAt' => new \DateTime(),
                            'status' => 1
                        ));
                        $objectManager->persist($stdpprstart);
                        $objectManager->flush();
                        
                    }                    
                    /*End from Demo Exam */
                    
                    
                    /*Load Exam Questions for Logged In Students Class/Grade*/
                    $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                        'exclass' => $stdactualclass,
                        //'exsubject' => ''
                    ));
                    //echo count($examquestions);
                    /*If Questions are not there for sudents Class Load All Classess Questions*/
                    if(count($examquestions) <= 0){
                        //echo "Im here";
                        $forClass = 1;
                        $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findBy(array(
                            'exclass' => $stdactualclass,
                            //'exsubject' => ''
                        ));
                        
                    }
                    //var_dump($examquestions);
                    /* Count Total Questions in Students Class/Grade */
                    $totexamquestions = count($examquestions);
                    //echo $totexamquestions;
                    if(empty($_SESSION['myexampaperquestion'])){
                        $shufquestions = self::questionRangeAction(1,$totexamquestions,$totalQuestions);
                        //var_dump($shufquestions);
                        $studentquestions = Array();
                        foreach($shufquestions as $key=>$shuquestn){
                            $qstnid = $shuquestn;
                            //echo $qstnid."<br>";
                            //echo $qstnid."<br>";
                            $examquestions = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array(
                                //'exclass' => $stdactualclass,
                                'id' => $qstnid
                            ));
                            //var_dump($examquestions->getId());
                            $studentquestions[] = Array(
                                'qsid' => $examquestions->getId(),
                                'question' => $examquestions->getExquest(),
                                'choice1' => $examquestions->getExchoice1(),
                                'choice2' => $examquestions->getExchoice2(),
                                'choice3' => $examquestions->getExchoice3(),
                                'choice4' => $examquestions->getExchoice4(),
                            );

                        }
                        $_SESSION['myexampaperquestion'] = $studentquestions;
                    }

                    $msg = '';
                    } else {
                        $msg = "Sorry this Exam has either expired or not available for you. Please contact at blfngoindia@gmail.com.";
                        $_SESSION['myexampaperquestion'] = Array();
                    }
                } else {
                    $msg = "Sorry this Exam has either expired or not available for you. Please contact at blfngoindia@gmail.com.";
                    $_SESSION['myexampaperquestion'] = Array();
                }
                $request = $this->getRequest();
                if($request->isPost()){
                    echo "Form submitted";
                }
            $pageTitle = "Exam Center";
            $view = new ViewModel(array(
                'msg' => $msg,
                'pprattempt' => $paperattempt,
                'idpex' => $stidpex,
                'totalqstns' => $totalQuestions,
                'myexamquestions' => $_SESSION['myexampaperquestion'], 
                'pageTitle' => $pageTitle,
                'timetostop' => $extimeleft,
            ));
            return $view;
        }
    }
    public function submitExamPaperAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $_SESSION['schexampaperquestion'] = array();
            $request = $this->getRequest();
            
            if($request->isPost()){
                $data = $request->getPost();
                /*Get Exam Paper Id */
                $exampaperidval = $data['idpex'];
                //echo self::encodeInputAction($exampaperidval);
                $studexamid = self::decodeInputAction($exampaperidval);
                $exampaperidarray = explode('strtEXMcode_', $studexamid);
                $exampaperid = $exampaperidarray[1];

                $userdetails = $this->zfcUserAuthentication()->getIdentity();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));

                $exmpprid = $objectManager->getRepository('\Admin\Entity\ExamPaper')->findOneBy(array('id'=> $exampaperid));
                if(count($exmpprid) > 0) {
                    $exmpprsetid = $objectManager->getRepository('\Admin\Entity\ExamPaperSets')->findOneBy(array('id'=> $exampaperid));
                    $exmalreadystarted = $objectManager->getRepository('\Student\Entity\StudentExamStarted')->findOneBy(array(
                        'examPaperId'=> $exmpprid,
                        'userId' => $userdetails,
                    ));
                    $exmgiven = $objectManager->getRepository('\Student\Entity\StudentExamPaper')->findOneBy(array(
                        'examPaperId'=> $exmpprid,
                        'userId' => $userdetails,
                    ));
                    if(count($exmalreadystarted) > 0){
                        if(new \DateTime() > $exmalreadystarted->getStoppedAt()){
                            $paperattempt = 1;
                        } else {
                            $paperattempt = 0;
                        }
                    } else {
                        $paperattempt = 0;
                    }

                    if(count($exmgiven) > 0){
                        $paperattempt = 1;
                    } else {
                        $paperattempt = 0;
                    }
                    //echo $paperattempt;

                    if($paperattempt == 1){
                        $msg = "This Exam has either been submitted or has Expired.";
                    } elseif($paperattempt == 0) {
                        $result = Array();
                        foreach($data as $key => $value){
                            $postn = explode('_', $key);
                            if($postn[0] == 'qid'){
                                $result[$postn[1]]['qstid'] = (int) $value;
                                $result[$postn[1]]['qstans'] = 'NA';
                            } elseif($postn[0] == 'qans'){
                                $result[$postn[1]]['qstans'] = (int) $value;
                            }
                        }
                        foreach($result as $key => $val){
                            //echo $val['qstans'];
                            if($val['qstans'] == 'NA'){
                                $result[$key]['qstcrans'] = 'NA';
                            } else {
                                $getqstnanswer = $objectManager->getRepository('\Admin\Entity\ExamQuestions')->findOneBy(array('id'=> $val['qstid']));
                                if($val['qstans'] == $getqstnanswer->getExcrchoice()){
                                    $answer = 'R';
                                } else {
                                    $answer = 'W';
                                }
                                $result[$key]['qstcrans'] = $answer;
                                $result[$key]['organs'] = $getqstnanswer->getExcrchoice();
                            }
                        }
                        //var_dump($result);
                        
                        $stexmqstnids = $stexmqstnstatus = $stexmqstnoptions = Array();
                        $stexmscore = 0;
                        foreach($result as $key => $val){
                            $stexmqstnids[] = $val['qstid'];
                            $stexmqstnstatus[] = $val['qstcrans'];
                            $stexmqstnoptions[] = $val['qstans'];
                            if($val['qstcrans'] == 'R'){
                                $stexmscore++;
                            }
                        }
                        $stexmqstnids = implode(',', $stexmqstnids);
                        $stexmqstnstatus = implode(',', $stexmqstnstatus);
                        $stexmqstnoptions = implode(',', $stexmqstnoptions);
                        //echo "Score :$stexmscore / " . count($result);
                        $studdemoscoreadd = new \Student\Entity\StudentExamPaper();
                        $studdemoscoreadd->exchangeArray(Array(
                            'userId' => $userdetails,
                            'examPaperId' => $exmpprid,
                            'examSetId' => $exmpprsetid,
                            'examQstnIds' => $stexmqstnids,
                            'examQstnStatus' => $stexmqstnstatus,
                            'examQstnOptions' => $stexmqstnoptions,
                            'totalScore' => $stexmscore,
                            'createdAt' => new \DateTime(),
                            'updatedAt' => new \DateTime(),
                            'status' => 1
                        ));
                        $objectManager->persist($studdemoscoreadd);
                        $objectManager->flush();
                        $msg = "";
                    } else {
                        return $this->redirect()->toUrl('/student/student/dashboard');
                    }
                } else {
                    return $this->redirect()->toUrl('/student/student/dashboard');
                }
                
            }
            
            $view = new ViewModel(Array(
                'appmsg' => $msg,
                //'exmscore' => $stexmscore,
                //'totexmqstn' => count($result),
                'dispscore' => 0,
            ));
            return $view;
        }
    }
    public function questionRangeAction($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
    public function saveStudImgsAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $data = $_REQUEST['imgBase64'];
            
            if(!empty($data)){
                $stname = $userdetails->getDisplayname()."_".$userdetails->getId();
                $usrfldr = "blst".$stname;
                
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/stdphotos/" . $stname ."-" .time().'.png', $data);
                echo "done";
            }
        }
    }
}
