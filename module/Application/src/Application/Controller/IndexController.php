<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\Mail;
use ZfcUser\Controller;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Options\PasswordOptionsInterface;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;
use ZfcBase\EventManager\EventProvider;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $userroleid = $userdetails->getRoles();
            $role = $userroleid[0]->getroleId();
            if($role == 'admin') {
                $this->redirect()->toUrl('/admin/admin/dashboard');
            } elseif($role == 'student') {
                $this->redirect()->toUrl('/student/student/dashboard');
            } elseif($role == 'manager') {
                //$this->redirect()->toUrl('/user/login');
            } else {
                $this->redirect()->toUrl('/user/login');
                //$this->layout('layout/home-layout');
                //$this->layout()->setVariable('page_layout', 'home');
            }
        } else {
            $this->redirect()->toUrl('/user/login');
            //$this->layout('layout/home-layout');
            //$this->layout()->setVariable('page_layout', 'home');
        } 
    }
    public function dashboardAction(){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $userroleid = $userdetails->getRoles();
            $role = $userroleid[0]->getroleId();
            if($role == 'admin') {
                $this->redirect()->toUrl('/admin/admin/dashboard');
            } elseif($role == 'student') {
                $this->redirect()->toUrl('/student/student/dashboard');
            } elseif($role == 'manager') {
                //$this->redirect()->toUrl('/user/login');
            } else {
                $this->redirect()->toUrl('/user/login');
            }
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            $this->redirect()->toUrl('/user/login');
        }
    }
    public function prizeAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $pageTitle = "Prize";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
        }
    }
    public function winnersAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $pageTitle = "Winners";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
        }
    }
    public function aboutUsAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $pageTitle = "About us";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
        }
    }
    public function supportAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $request = $this->getRequest();
            $successmsg = '';
            if ($request->isPost()) {
                $data = $request->getPost();
                //var_dump($data);
                $message = "Message : " . $data['inputmessage']. "<br>";
                $message .= "From : ". $data['inputname']."<br>";
                $message .= "Email : ". $data['inputemail']."<br>";
                $message .= "Phone : ". $data['inputphone']."<br>";
                $mail = new Mail\Message();
                $mail->setBody($message);
                $mail->setFrom('donotreply@online-exam.in', $userdetails->getDisplayname());
                $mail->addTo('blfngoindia@gmail.com', 'Online-Exam');
                $mail->setSubject($data['inputmessage']);

                $transport = new Mail\Transport\Sendmail();
                $transport->send($mail);
                $successmsg = "Your for Support Request is sent to concerned department. Our team will get back to you at the earliest possible.";
            }
            $pageTitle = "Support";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
                    'successmsg' => $successmsg,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
        }
    }
    public function contactAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                var_dump($data);
            }
            $pageTitle = "Contact us";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
        }
    }
    public function termsAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                var_dump($data);
            }
            $pageTitle = "Terms of Service";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
        }
    }
    public function faqsAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $userdetails = $this->zfcUserAuthentication()->getIdentity();
        if($userdetails){
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                var_dump($data);
            }
            $pageTitle = "Frequently Asked Questions (FAQs)";
            $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                    'userinfo' => $userdetails,
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl('/user/login');
        }
    }
    }
    public function testAction(){
        $userData = array(
            'email' => 'myemail@mydomain.com',
            'display_name' => 'My Display Name',
            'password' => 'mypassword',
            'passwordVerify' => 'mypassword',
        );
        //$objectmanager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = $this->getServiceLocator()->get('zfcuser_user_service')->register($userData);
    }

    public function resetPasswordAction(){

        $message = '';
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $objectmanager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $hasuser = $objectmanager->getRepository('\Application\Entity\User')->findOneBy(array(
                'username' => $data['username']
            ));
            $hastmpuser = $objectmanager->getRepository('\Application\Entity\TempUser')->findOneBy(array(
                'username' => $data['username']
            ));

            if(count($hasuser) > 0){
                if($data['userpass'] == $data['userpassconf']){
                    $bcrypt = new Bcrypt();
                    $bcrypt->setCost(14);
                    $pass = $bcrypt->create($data['userpass']);
                    $hasuser->setPassword($pass);
                    if(count($hastmpuser) > 0){
                        $hastmpuser->setUserpass($data['userpass']);
                        $hastmpuser->exchangeArray(array(
                            'userpass' => $data['userpass'],
                            'updatedAt' => new \DateTime(),
                        ));
                    } else {
                        $hastmpuser = new \Application\Entity\TempUser();
                        $hastmpuser->exchangeArray(array(
                            'username' => $data['username'],
                            'userpass' => $data['userpass'],
                            'phonenum' => $hasuser->getPhonenum(),
                            'createdAt' => new \DateTime(),
                            'updatedAt' => new \DateTime(),
                        ));
                    }
                    
                    $objectmanager->persist($hasuser);
                    $objectmanager->persist($hastmpuser);
                    $objectmanager->flush();
                    $displayname = $data['username'];
                    $displayname = preg_replace('/\s+/', '', $displayname);
                    $password = $data['userpass'];
                    $mobile = $hasuser->getPhonenum();
                    $smsurl = "http://www.commnestsms.com/api/push?apikey=5688def6ca7ab&route=otp&sender=BLFNGO&mobileno=$mobile&text=Dear $displayname, Your new password is $password. For help: 8929807807 or info@blfngoindia.org";
                    $sendsmsurl = self::sendsms($smsurl);
                    $message = "Password updated Successfully";
                } else {
                    $message = "Sorry both password did not match";
                }
            } else {
                $message = "Sorry user not found";
            }
        }

        return new ViewModel(array(
            'sucmsg' => $message,
        ));
        
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
