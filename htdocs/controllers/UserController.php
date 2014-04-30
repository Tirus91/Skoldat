<?php

class UserController extends Controllers_AbstractController {

    public function __construct($registry) {
        $this->registry = $registry;
        $this->view = $this->registry['view'];
        $this->args = $this->registry['args'];
        $this->sk_syst = $this->registry['sk_syst'];
        $this->view->set('homelink', $this->registry['homelink']);
        $this->view->set('sitename', $this->registry['sitename']);
        $today = new DateTime();
        $license_to = new DateTime(SK_LICENSE_TO);
        if ($today > $license_to) {
            $this->view->set('content', SK_LICENSE_TO_FOR);
            $this->render('endOfLicense.tpl');
            die;
        }
    }

    function index() {
        if (!$this->registry['sk_user']->logged) {
            header("LOCATION: {$this->registry['homelink']}/User/login");
        }
    }

    function login() {
        $login = '';
        if (isset($_REQUEST['loginRequest'], $_REQUEST['login_user'])) {
            $pass = '';
            $login = $_REQUEST['login_user'];
            if (isset($_REQUEST['password_user'])) {
                $pass = $_REQUEST['password_user'];
            }
            $this->registry['sk_user']->setUserLoginInfo($_REQUEST['login_user'], $pass);
            $this->registry['sk_user']->loginUser();
        }
        if ($this->registry['sk_user']->logged) {
            header("LOCATION: {$this->registry['homelink']}");
        }
        $this->view->set('login_user', $login);
        $this->view->set('content', $this->view->fetch('Subparts/login_page.tpl'));
        $this->view->set('title', 'Přihlášení');
        $this->render('main.tpl');
    }

    function logout() {
        if (!$this->registry['sk_user']->logged) {
            header("LOCATION: {$this->registry['homelink']}");
        }
        $this->registry['sk_user']->logoutUser();
        header("LOCATION: {$this->registry['homelink']}");
    }

    function myInfo() {
        if (!$this->registry['sk_user']->logged) {
            header("LOCATION: {$this->registry['homelink']}/User/login");
        }
        $message = '';
        if (isset($this->args[0])) {
            if ($this->args[0] == 'edited') {
                $message = 'Účet byl úspěšně upraven';
            }
        }
        if (isset($_REQUEST['editRequest'])) {
            if ($this->registry['sk_user']->editUser($this->registry['sk_user']->user_info['id_user'], $_REQUEST)) {
                header("LOCATION: {$this->registry['homelink']}/User/myInfo/edited");
            }
        }
        $this->view->set('inf', $message);
        foreach ($this->registry['sk_user']->getUserInfo($this->registry['sk_user']->user_info['id_user']) as $key => $value) {
            $this->view->set($key, $value);
        }
        $this->view->set('content', $this->view->fetch('Subparts/myInfo.tpl'));
        $this->view->set('title', 'Mé informace');
        $this->render('main.tpl');
    }

    public function changePassword() {
        if (!$this->registry['sk_user']->logged) {
            header("LOCATION: {$this->registry['homelink']}/User/login");
        }
        $message = '';
        if (isset($_REQUEST['changePassword'])) {
            if ($_REQUEST['password_new'] == $_REQUEST['password_new_2']) {
                if ($this->registry['sk_user']->setNewPassword($_REQUEST['id_user'], $_REQUEST['password_new'], $_REQUEST['password_old'])) {
                    if ($this->registry['sk_user']->logoutUser()) {
                        header('LOCATION: ' . $this->registry['homelink']);
                    }
                }
            } else {
                $message = 'Zadaná hesla se neshodují';
            }
        }
        $this->view->set('inf', $message);
        foreach ($this->registry['sk_user']->getUserInfo($this->registry['sk_user']->user_info['id_user']) as $key => $value) {
            $this->view->set($key, $value);
        }
        $this->view->set('content', $this->view->fetch('Subparts/changePassword.tpl'));
        $this->view->set('title', 'Mé informace');
        $this->render('main.tpl');
    }

}
