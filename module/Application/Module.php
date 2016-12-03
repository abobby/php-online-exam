<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    protected $whitelist = array(
        'zfcuser/login' => array('login'),
        'zfcuser/register' => array('register'),
        'zfcuser/change-password' => array('change-password'),
        'application/index/test' => array('application/index/test'),
        'application/index/reset-password' => array('application/index/reset-password'),
        'your-landing-route' => array('your-landing-action'),
    );

    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();
        $shm = $em->getSharedManager();

        $list = $this->whitelist;
        $auth = $sm->get('zfcuser_auth_service');
        $config = $sm->get('config');
        $phpSettings = $config['php_settings'];
        if ($phpSettings) {
            foreach ($phpSettings as $key => $value) {
                ini_set($key, $value);
            }
        }
        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($list, $auth) {
            $match = $e->getRouteMatch();
            // No route match, this is a 404
            if (!$match instanceof RouteMatch) {
                return;
            }
            // Route and action is whitelisted
            $routeName = $match->getMatchedRouteName();
            $action = $match->getParam("action");

            if(array_key_exists($routeName,$list) && in_array($action,$list[$routeName])) {
                return;
            }
            // User is authenticated
            if ($auth->hasIdentity()) {
                return;
            }

            // Redirect to the user login page, as an example
            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'zfcuser/login'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);

        $shm->attach('ZfcUser\Form\Register', 'init', function($e) {
            // $form is a ZfcUser\Form\Register
            $form = $e->getTarget();
            $form->add(array(
                'name' => 'phonenum',
                'options' => array(
                    'label' => 'Phone Number',
                ),
                'attributes' => array(
                    'type' => 'text'
                ),
            ));
            $form->add(array(
                'name' => 'refcode',
                'options' => array(
                    'label' => 'Referral Code',
                ),
                'attributes' => array(
                    'type' => 'text'
                ),
            ));
        });


        $ems = \Zend\EventManager\StaticEventManager::getInstance();
        $ems->attach('ZfcUser\Service\User', 'register', function($ee) use($e) {
            $user = $ee->getParam('user');  // User account object
            $form = $ee->getParam('form');  // Form object

            $config = $e->getApplication()->getServiceManager()->get('config');
        	$sms_settings = $config['sms_settings'];
        	//echo $sms_settings['enable']; exit;
        	if($sms_settings['enable'] == 1) {
        		//$smsapi = "5688def6ca7ab";
	            //$smssenderid = "SCHSHP";
	            $smsroute = "transactional";
	            $username = $user->getUsername();
	            $displayname = $user->getdisplayName();
	            $displayname = preg_replace('/\s+/', '', $displayname);
	            $password = $form->getElements()['password']->getValue();
	            $refcode = $form->getElements()['refcode']->getValue();
	            $mobile = $user->getPhonenum();
	            
	            $smsurl = "http://www.commnestsms.com/api/push?apikey=5688def6ca7ab&route=otp&sender=BLFNGO&mobileno=$mobile&text=Dear $displayname, Thank you for registering for eScholarship. Login link: www.blfngoindia.org username: $username Password: $password";
	            $sendsmsurl = self::sendsms($smsurl);
	            
	            $smsurl2 = "http://www.commnestsms.com/api/push?apikey=5688def6ca7ab&route=transactional&sender=BLFNGO&mobileno=$mobile&text=Hello $displayname, Please pay Rs.100 for completing your registration. To make online payment visit at www.blfngoindia.org For help: 8929807807";
	            $sendsmsurl2 = self::sendsms($smsurl2);
	            echo $sendsmsurl;
	            echo $sendsmsurl2;
        	}
            

            $em = $e->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');
            $newtempuser = new \Application\Entity\TempUser();
            $newtempuser->exchangeArray(array(
                'userId' => $user->getId(),
                'username' => $user->getUsername(),
                'userpass' => $form->getElements()['password']->getValue(),
                'phonenum' => $user->getPhonenum(),
            ));
            $em->persist($newtempuser);
            $em->flush();
        });

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();
        $zfcServiceEvents->attach('register', function($ee) use($e) {
             $form = $e->getParam('form');
             $user = $ee->getParam('user');
             $em = $e->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');
             $defaultUserRole = $em->getRepository('Application\Entity\Role')->find(2);
             $user->addRole($defaultUserRole);
             $user->setCreatedAt(new \DateTime());
             $user->setUpdatedAt(new \DateTime());
        });
    }

    public function checkLogin($e) {
            $sm = $e->getApplication()->getServiceManager();
            $controller = $e->getTarget();
            $auth = $sm->get('zfcuser_auth_service');
            if (!$auth->hasIdentity() && $e->getRouteMatch()->getMatchedRouteName() !== 'zfcuser/login') {
                $application = $e->getTarget();

                $e->stopPropagation();
                $response = $e->getResponse();
                $response->setStatusCode(302);
                $response->getHeaders()->addHeaderLine('Location', $e->getRouter()->assemble(array(), array('name' => 'zfcuser/login')));
                //returning response will cause zf2 to stop further dispatch loop
                return $response;
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                }
            )
        );
    }

    /*public function genpaswd(){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }*/
    
    function genpaswd()
    {
        $pass = '';
        $length = 8;
        $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $pass;
    }
}
