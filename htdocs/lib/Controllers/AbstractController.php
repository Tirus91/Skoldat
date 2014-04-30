<?php

abstract class Controllers_AbstractController {

    protected $registry;
    protected $view;
    protected $args;
    protected $pagination;
    protected $sk_syst;

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
        if ($this->registry['sk_user']->logged) {
            $this->view->set('content', '');
        } else {
            header("LOCATION: {$this->registry['homelink']}/User/login");
        }
    }

    public function render($template) {
        $this->view->set('menu_list', Models_menu::getMenu($this->registry));
        if ($this->registry['sk_user']->logged) {
            $this->view->set('login_text', 'Jste přihlášen(a) jako <b>' . $this->registry['sk_user']->user_info['first_name_user'] . ' ' . $this->registry['sk_user']->user_info['surname_user'] . '</b> (<a href="' . $this->
                    registry['homelink'] . '/User/logout">Odhlásit se</a>)');
        } else {
            $this->view->set('login_text', 'Nejste přihlášen(a) (<a href="' . $this->registry['homelink'] . '/User/login">Přihlásit se</a>)');
        }
        $this->view->set('vertical_menu', array(array('name' => 'Domů', 'link' => '/', 'title' => 'Domovská stránka'),array('name' => 'Administrace', 'link' => '/Admin', 'title' => 'Administrace aplikace'),array('name' => 'Pronájmy', 'link' => '/Rents', 'title' => 'Pronájmy'),array('name' => 'Moje nastavení', 'link' => '/User/myInfo', 'title' => 'Moje nastavení')));
        echo $this->view->fetch($template);
    }

}
