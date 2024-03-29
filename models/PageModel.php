<?php

class PageModel {
    public $page;
    protected $isPost = false;
    public $menu;
    public $errors = array();
    public $connectionErr;
    protected $sessionManager;

    public function __construct($copy) {
        if (empty($copy)) {
            require_once('SessionManager.php');
            $this->sessionManager = new SessionManager();
        } else {
            $this->page = $copy->page;
            $this->isPost = $copy->isPost;
            $this->menu = $copy->menu;
            $this->errors = $copy->errors;
            $this->connectionErr = $copy->connectionErr;
            $this->sessionManager = $copy->sessionManager;
        }
    }

    public function isAjaxRequest() {
        $this->isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        require_once('Util.php');
        if ($this->isPost) {
            return Util::getPostVar('action', '') == 'ajax';
        }
        return Util::getUrlVar('action', '') == 'ajax';
    }

    public function getAjaxRequestInfo() {
        if ($this->isPost) {
            return $_POST;
        }
        return $_GET;
    }

    public function getRequestedPage() {
        $this->isPost = $_SERVER['REQUEST_METHOD'] == 'POST';

        require_once('Util.php');

        if ($this->isPost) {
            $this->setPage(Util::getPostVar('page', 'home'));
        } else {
            $this->setPage(Util::getUrlVar('page', 'home'));
        }
    }

    private function setPage($page) {
        $this->page = $page;
    }

    public function createMenu() {
        $this->menu['home'] = 'HOME';
        $this->menu['about'] = 'ABOUT';
        $this->menu['contact'] = 'CONTACT';
        $this->menu['webshop'] = 'WEBSHOP';
        $this->menu['topfive'] = 'TOP 5';
        if ($this->isUserLoggedIn()) {
            $this->menu['cart'] = 'SHOPPING CART';
            $this->menu['logout'] = 'LOGOUT '.$this->sessionManager->getLoggedInUsername();
        } else {
            $this->menu['register'] = 'REGISTER';
            $this->menu['login'] = 'LOGIN';
        }
    }

    public function isUserLoggedIn() {
        return $this->sessionManager->isUserLoggedIn();
    }
}

?>
