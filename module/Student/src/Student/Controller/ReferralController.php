<?php

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class ReferralController extends AbstractActionController {

    public function indexAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        
        }
    }

    public function referralDashboardAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
        $referralInfo = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                'userId'=> $userdetails,
        ));
        /*$referralDetails = $objectManager->getRepository('\Student\Entity\StudentReferral')->findOneBy(array(
                'referredId'=> $userdetails,
        ));*/
        $torefemail = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
            'userId' => $userdetails,
            'refmode'=> 'email',
        ));
        $torefsms = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
            'userId' => $userdetails,
            'refmode'=> 'sms',
        ));
        $torefsuccess = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
            'userId' => $userdetails,
            'refstatus'=> 1,
        ));
        $pageTitle = "My Referrals";
        $view = new ViewModel(array(
                'userinfo' => $userdetails,
                'studinfo' => $studentDetails,
                'refinfo' => $referralInfo,
                'totrefemail' => $torefemail,
                'totrefsms' => $torefsms,
                'totrefsuccess' => $torefsuccess,
                'pageTitle' => $pageTitle,
        ));
        return $view;
        }
    }

    public function referralEmailFormAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            $referralInfo = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                    'userId'=> $userdetails,
            ));
            /*$torefemail = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                    'refmode'=> 'email',
            ));
            $torefsms = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                    'refmode'=> 'sms',
            ));
            $torefsuccess = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                    'refstatus'=> 1,
            ));*/
            $successmessage = '';
            $request = $this->getRequest();
            if($request->isPost()){
                $data = $request->getPost();
                //var_dump($data);
                $checkrefmail = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findOneBy(array(
                    'refemail'=> $data['rfremail'],
                ));
                if(count($checkrefmail) <= 0 ){
                    $refemailadd = new \Student\Entity\StudentReferralInfo();
                    $refemailadd->exchangeArray(array(
                        'userId' => $userdetails,
                        'strefcode' => $studentDetails->getStrefcode(),
                        'refname' => $data['rfrname'],
                        'refmode' => 'email',
                        'refemail' => $data['rfremail'],
                        'refstatus' => 0,
                        'createdAt' => new \DateTime(),
                        'updatedAt' => new \DateTime(),
                        'status' => 1,
                    ));
                    $objectManager->persist($refemailadd);
                    $objectManager->flush();

                    $successmessage = "Referral Successfully sent to ".$data['rfrname'];
                } else {
                    $successmessage = "Sorry this Email is already referred ".$data['rfremail'];
                }
            } else {
                $successmessage = '';
            }
            $pageTitle = "Referral :: Email Form";
            $view = new ViewModel(array(
                'userinfo' => $userdetails,
                'studinfo' => $studentDetails,
                'refinfo' => $referralInfo,
                //'totrefemail' => $torefemail,
                //'totrefsms' => $torefsms,
                //'totrefsuccess' => $torefsuccess,
                'sucmsg' => $successmessage,
                'pageTitle' => $pageTitle,
            ));
            return $view;
        }
    }

    public function referralSmsFormAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
            $userdetails = $this->zfcUserAuthentication()->getIdentity();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $studentDetails = $objectManager->getRepository('\Student\Entity\StudentDetails')->findOneBy(array('userId'=>$userdetails));
            $referralInfo = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                    'userId'=> $userdetails,
            ));
            $torefemail = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                'userId' => $userdetails,
                'refmode'=> 'email',
            ));
            $torefsms = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                'userId' => $userdetails,
                'refmode'=> 'sms',
            ));
            $torefsuccess = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findBy(array(
                'userId' => $userdetails,
                'refstatus'=> 1,
            ));
            $successmessage = '';
            $request = $this->getRequest();
            if($request->isPost()){
                $data = $request->getPost();
                $checkrefmail = $objectManager->getRepository('\Student\Entity\StudentReferralInfo')->findOneBy(array(
                    'refphone'=> $data['rfrphone'],
                ));
                if(count($checkrefmail) <= 0 ){
                    $refemailadd = new \Student\Entity\StudentReferralInfo();
                    $refemailadd->exchangeArray(array(
                        'userId' => $userdetails,
                        'strefcode' => $studentDetails->getStrefcode(),
                        'refname' => $data['rfrname'],
                        'refmode' => 'sms',
                        'refphone' => $data['rfrphone'],
                        'refstatus' => 0,
                        'createdAt' => new \DateTime(),
                        'updatedAt' => new \DateTime(),
                        'status' => 1,
                    ));
                    $objectManager->persist($refemailadd);
                    $objectManager->flush();

                    $displayname = $studentDetails->getFirstname();
                    $displayname = preg_replace('/\s+/', '', $displayname);
                    $refername =  preg_replace('/\s+/', '', $data['rfrname']);
                    $strefercode = $studentDetails->getStrefcode();
                    $mobile = $data['rfrphone'];
                    
                    if(!empty($mobile)){
                        $smsurl = "http://www.commnestsms.com/api/push?apikey=5688def6ca7ab&route=transactional&sender=BLFNGO&mobileno=$mobile&text=Dear $refername, $displayname referred you for eScholarship program. Visit www.blfngoindia.org to register with Referral Code $strefercode";
                        //echo $smsurl;
                        $sendsmsurl = self::sendsms($smsurl);
                        //echo $sendsmsurl;
                    }
                    $successmessage = "Referral Successfully sent to ".$data['rfrname'];
                } else {
                    $successmessage = "Sorry this Phone Number is already referred ".$data['rfrphone'];
                }
            } else {
                $successmessage = '';
            }
            $pageTitle = "Referral :: Email Form";
            $view = new ViewModel(array(
                'userinfo' => $userdetails,
                'studinfo' => $studentDetails,
                'refinfo' => $referralInfo,
                'sucmsg' => $successmessage,
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
 
}