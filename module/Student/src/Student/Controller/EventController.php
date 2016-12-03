<?php

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EventController extends AbstractActionController {
    protected $var;

    public function indexAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $pageTitle = "Event :: Index";
        $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
        ));
        return $view;
        }
    }

    public function prevEventsAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $pageTitle = "Event :: Index";
        $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
        ));
        return $view;
        }
    }
     public function upcomingEventsAction(){
        if(\Student\Controller\IndexController::studentaccessAction() == true){
        $pageTitle = "Event :: Index";
        $view = new ViewModel(array(
                'pageTitle' => $pageTitle,
        ));
        return $view;
        }
     }
    /* Encode Variables passed in URL */
    public function encodeInputAction($inp) {
        return base64_encode(str_rot13($inp));
    }

    /* Decode Endocded Variables passed in URL */
    public function decodeInputAction($inp) {
        return str_rot13(base64_decode($inp));
    }

    
}
