<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EventController extends AbstractActionController {
	
	public function indexAction() {
		$pageTitle = "Admin : Event : Index";
		$view = new ViewModel(array(
			'pageTitle' => $pageTitle,
		));
		return $view;
	}
	public function dashboardAction() {
		$pageTitle = "Admin : Event : Dashboard";
		$view = new ViewModel(array(
			'pageTitle' => $pageTitle,
		));
		return $view;
	}
        public function createEventAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $pageTitle = "Admin : Event : Create Event";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
        public function editEventAction(){
            if(\Admin\Controller\AdminController::adminaccessAction() == true){
                $pageTitle = "Admin : Event : Edit Event";
                $view = new ViewModel(array(
                    'pageTitle' => $pageTitle,
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl('/user/login');
            }
        }
}
