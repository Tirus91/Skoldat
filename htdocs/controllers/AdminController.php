<?php

class AdminController extends Controllers_AbstractController {

    public function __construct($registry) {
        $this->registry = $registry;
        $this->view = $this->registry['view'];
        $this->args = $this->registry['args'];
        $this->sk_syst = $this->registry['sk_syst'];
        if (!$this->registry['sk_user']->logged) {
            header("LOCATION: {$this->registry['homelink']}/User/login");
        }
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
        header('LOCATION: ' . $this->registry['homelink'] . '/Admin/showUser');
        //$this->showUser();
    }

    public function showUser($message = '') {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 1) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        if (!isset($_SESSION['admin_show_user']['first_name_user'])) {
            $_SESSION['admin_show_user']['first_name_user'] = null;
        }
        if (!isset($_SESSION['admin_show_user']['surname_user'])) {
            $_SESSION['admin_show_user']['surname_user'] = null;
        }
        if (isset($_REQUEST['filter'])) {
            if (isset($_REQUEST['first_name_user'])) {
                $_SESSION['admin_show_user']['first_name_user'] = $_REQUEST['first_name_user'];
            } else {
                $_SESSION['admin_show_user']['first_name_user'] = '';
            }
            if (isset($_REQUEST['surname_user'])) {
                $_SESSION['admin_show_user']['surname_user'] = $_REQUEST['surname_user'];
            } else {
                $_SESSION['admin_show_user']['surname_user'] = '';
            }
        }
        $where = array();
        if (isset($_REQUEST['reset_filter'])) {
            $_SESSION['admin_show_user']['first_name_user'] = null;
            $_SESSION['admin_show_user']['surname_user'] = null;
        }
        if (!empty($_SESSION['admin_show_user']['surname_user'])) {
            $where[] = array('surname_user LIKE %s', '%' . $_SESSION['admin_show_user']['surname_user'] . '%');
        }
        if (!empty($_SESSION['admin_show_user']['first_name_user'])) {
            $where[] = array('first_name_user LIKE %s', '%' . $_SESSION['admin_show_user']['first_name_user'] . '%');
        }
        $page = 1;
        if (isset($this->args[0])) {
            $page = (int) $this->args[0];
            if ($page == 0) {
                $page = 1;
            }
        }
        require_once 'class/pagination.php';
        $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Admin/showUser/');
        $users = $this->registry['sk_user']->getAllUser($pag->first_row, $this->sk_syst->row_show_syst, $where);
        $pag->totalcount = $users['total_count'];
        $this->view->set('pagination', $pag->get_pagination());
        $this->view->set('addItembutton', $this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2), TRUE);
        $this->view->set('message', $message);
        $this->view->set('first_name_user', $_SESSION['admin_show_user']['first_name_user']);
        $this->view->set('surname_user', $_SESSION['admin_show_user']['surname_user']);
        $this->view->set('user', $users['value']);
        $this->view->set('content', $this->view->fetch('Subparts/showUser.tpl'));
        $this->view->set('title', 'Administrace - přehled uživatelů');
        $this->render('main.tpl');
    }

    public function editUser() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        if (isset($_REQUEST['editRequest'])) {
            if (isset($this->args[0])) {
                if ($this->registry['sk_user']->editUser($this->args[0], $_REQUEST, false)) {
                    $message = 'Účet byl úspěšně upraven';
                }
            }
        }
        if (isset($this->args[0])) {
            $user_info = $this->registry['sk_user']->getUserInfo($this->args[0]);
            foreach ($user_info as $key => $value) {
                $this->view->set($key, $value);
            }
            $this->view->set('content', $this->view->fetch('Subparts/editUser.tpl'));
        } else {
            $this->view->set('content', 'Uživatel nebyl nalezen');
        }
        $this->view->set('inf', $message);
        $this->view->set('title', 'Administrace - editace uživatele');
        $this->render('main.tpl');
    }

    function delUser() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        if (isset($this->args[0])) {
            if ($this->registry['sk_user']->deleteSubj($this->args[0], SUBJ_USER)) {
                $message = 'Uživatel byl smazán';
            }
        }
        $this->showUser($message);
    }

    public function addUser() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        $input = array(
            "login_user" => '',
            "password_user" => '',
            "first_name_user" => '',
            "surname_user" => '',
            "email_user" => '',
            "phone_user" => '',
            "role_user" => '',
            'theme_user' => $this->registry['sk_syst']->available_theme);

        foreach ($input as $column_name => $value) {
            if (isset($_REQUEST[$column_name])) {
                $input[$column_name] = $_REQUEST[$column_name];
            } elseif (!isset($_REQUEST[$column_name]) && $column_name == 'theme_user') {
                $input[$column_name] = 'default';
            }
            if ($column_name != 'password_user') {
                if ($column_name == 'theme_user') {
                    $themes = $this->registry['sk_syst']->available_theme;
                    $themes[$input[$column_name]]['sel'] = 'selected="selected"';
                    $this->view->set($column_name, $themes);
                } else {
                    $this->view->set($column_name, $input[$column_name]);
                }
            } else {
                $this->view->set($column_name, '');
            }
        }
        if (isset($_REQUEST['password_user_2'])) {
            if ($_REQUEST['password_user_2'] == $input['password_user']) {
                if ($this->registry['sk_user']->newUser($input)) {
                    header('LOCATION: ' . $this->registry['homelink'] . '/Admin/showUser');
                } else {
                    switch ($this->registry['sk_user']->errno) {
                        case 11:
                            $message = 'Uživatel s tímto přihlašovacím jménem již existuje';
                            break;
                        case 12:
                            $message = 'Uživatel s tímto emailem již existuje nebo je ve špatném tvaru';
                            break;
                        case 13:
                            $message = 'Uživatele se z technických důvodů nepodařilo vytvořit';
                            break;
                    }
                }
            } else {
                $message = 'Zadaná hesla se neshodují';
            }
        }
        $this->view->set('password_user_2', '');
        $this->view->set('inf', $message);
        $this->view->set('content', $this->view->fetch('Subparts/addUser.tpl'));
        $this->view->set('title', 'Administrace - nový uživatel');
        $this->render('main.tpl');
    }

    public function showLicense() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 1) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $this->view->set('school_name', $this->sk_syst->school_name_syst);
        $this->view->set('school_address', $this->sk_syst->school_address_syst);
        $this->view->set('school_user', $this->sk_syst->school_contact_user_syst);
        $this->view->set('school_type', SK_LICENSE_TYPE);

        $this->view->set('license_to', SK_LICENSE_TO);
        $this->view->set('content', $this->view->fetch('Subparts/showlicense.tpl'));
        $this->view->set('title', 'Administrace - nový uživatel');
        $this->render('main.tpl');
    }

    function mainSettings() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        if (isset($_REQUEST['saveSetting'])) {
            $this->sk_syst->update_default_settings($_REQUEST);
        }
        $this->view->set('school_address_syst', $this->sk_syst->school_address_syst);
        $this->view->set('smtp_server_syst', $this->sk_syst->smtp_server_syst);
        $this->view->set('smtp_auth_email_syst', $this->sk_syst->smtp_auth_email_syst);
        $this->view->set('smtp_auth_pwd_syst', $this->sk_syst->smtp_auth_pwd_syst);
        $this->view->set('smtp_port_syst', $this->sk_syst->smtp_port_syst);
        $this->view->set('mail_sender_name_syst', $this->sk_syst->mail_sender_name_syst);
        $this->view->set('mail_wordwrap_syst', $this->sk_syst->mail_wordwrap_syst);
        $this->view->set('school_address_syst', $this->sk_syst->school_address_syst);
        $this->view->set('school_name_syst', $this->sk_syst->school_name_syst);
        $this->view->set('site_name_syst', $this->sk_syst->site_name_syst);
        $this->view->set('school_contact_user_syst', $this->sk_syst->school_contact_user_syst);
        if ($this->sk_syst->smtp_auth_syst == '1') {
            $this->view->set('smtp_auth_syst_1', 'selected="selected"');
            $this->view->set('smtp_auth_syst_0', '');
        } else {
            $this->view->set('smtp_auth_syst_1', '');
            $this->view->set('smtp_auth_syst_0', 'selected="selected"');
        }
        if ($this->sk_syst->type_send_mail_syst == '1') {
            $this->view->set('type_send_mail_syst_1', 'selected="selected"');
            $this->view->set('type_send_mail_syst_2', '');
        } else {
            $this->view->set('type_send_mail_syst_1', '');
            $this->view->set('type_send_mail_syst_2', 'selected="selected"');
        }
        if ($this->sk_syst->mail_format_syst == 'html') {
            $this->view->set('mail_format_syst_html', 'selected="selected"');
            $this->view->set('mail_format_syst_text', '');
        } else {
            $this->view->set('mail_format_syst_html', '');
            $this->view->set('mail_format_syst_text', 'selected="selected"');
        }
        if ($this->sk_syst->smtp_secure_syst == 'tls') {
            $this->view->set('smtp_secure_syst_tls', 'selected="selected"');
            $this->view->set('smtp_secure_syst_none', '');
            $this->view->set('smtp_secure_syst_ssl', '');
        } elseif ($this->sk_syst->smtp_secure_syst == 'ssl') {
            $this->view->set('smtp_secure_syst_tls', '');
            $this->view->set('smtp_secure_syst_ssl', 'selected="selected"');
            $this->view->set('smtp_secure_syst_none', '');
        } else {
            $this->view->set('smtp_secure_syst_tls', '');
            $this->view->set('smtp_secure_syst_ssl', '');
            $this->view->set('smtp_secure_syst_none', 'selected="selected"');
        }
        for ($i = 10; $i <= 35; $i+=5) {
            if ($this->sk_syst->row_show_syst == $i) {
                $this->view->set('row_show_syst_' . $i, 'selected="selected"');
            } else {
                $this->view->set('row_show_syst_' . $i, '');
            }
        }


        $this->view->set('content', $this->view->fetch('Subparts/settings.tpl'));
        $this->view->set('title', 'Administrace - nový uživatel');
        $this->render('main.tpl');
    }

    public function newGrou() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $group['abbrev_grou'] = null;
        $group['ident_grou'] = null;
        $group['name_grou'] = null;
        $status = '';
        if (isset($_REQUEST['addGrou'])) {
            $grou['abbrev_grou'] = $_REQUEST['abbrev_grou'];
            $grou['ident_grou'] = $_REQUEST['ident_grou'];
            $grou['name_grou'] = $_REQUEST['name_grou'];
            if ($grou['name_grou'] != '' && $grou['abbrev_grou'] != '') {
                if ($this->registry['sk_user']->newGrou($grou)) {
                    $status = 'Skupina byla vytvořena';
                } else {
                    $status = 'Skupina nebyla vytvořena';
                    $group = $grou;
                }
            } else {
                $group = $grou;
            }
        }
        foreach ($group as $key => $val) {
            $this->view->set($key, $val);
        }
        $this->view->set('status', $status);
        $this->view->set('content', $this->view->fetch('Subparts/groups/addGrou.tpl'));
        $this->view->set('title', 'Administrace - nová skupina');
        $this->render('main.tpl');
    }

    public function showGrou($message = null) {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $where = array();
        $page = 1;
        if (isset($this->args[0])) {
            $page = (int) $this->args[0];
            if ($page == 0) {
                $page = 1;
            }
        }
        require_once 'class/pagination.php';
        $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Admin/showGrou/');
        $users = $this->registry['sk_user']->getGrouList($pag->first_row, $this->sk_syst->row_show_syst, $where);
        $pag->totalcount = $users['total_count'];

        $this->view->set('message', $message);
        $this->view->set('pagination', $pag->get_pagination());
        $this->view->set('groups', $users['group']);
        $this->view->set('content', $this->view->fetch('Subparts/groups/showGrou.tpl'));
        $this->view->set('title', 'Administrace - přehled skupin');
        $this->render('main.tpl');
    }

    public function delGrou() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }
        $message = null;
        if (isset($this->args[0])) {
            if ($this->registry['sk_user']->deleteSubj($this->args[0], SUBJ_GROU)) {
                $message = 'Skupina byla smazána';
            }
        }
        $this->showGrou($message);
    }

    public function editGrou() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }

        if (isset($this->args[0])) {
            $grou = $this->registry['sk_user']->getGrouInfo($this->args[0]);
            if (isset($_REQUEST['editGrou'])) {
                foreach ($grou as $key => $val) {
                    if ($val != $_REQUEST[$key]) {
                        $grou[$key] = $_REQUEST[$key];
                    }
                    $this->view->set($key, $grou[$key]);
                }

                if ($this->registry['sk_user']->editGrou($this->args[0], $grou) == true) {
                    $this->showGrou('Skupina ' . $grou['name_grou'] . ' byla změněna');
                } else {
                    $this->view->set('status', 'Skupinu se nepodařilo upravit');
                    $this->view->set('content', $this->view->fetch('Subparts/groups/editGrou.tpl'));
                    $this->view->set('title', 'Administrace - editace skupiny');
                    $this->render('main.tpl');
                }
            } else {
                foreach ($grou as $key => $val) {
                    $this->view->set($key, $val);
                }
                $this->view->set('status', '');
                $this->view->set('content', $this->view->fetch('Subparts/groups/editGrou.tpl'));
                $this->view->set('title', 'Administrace - editace skupiny');
                $this->render('main.tpl');
            }
        } else {
            $this->showGrou('Skupina nebyla nalezena');
        }
    }

    public function setUsgr() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['ADMIN_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Administrace - nová skupina');
            $this->render('main.tpl');
            return true;
        }$message = null;

        if (isset($this->args[0])) {

            if (!isset($_SESSION['admin_show_user']['first_name_user'])) {
                $_SESSION['admin_show_user']['first_name_user'] = null;
            }
            if (!isset($_SESSION['admin_show_user']['surname_user'])) {
                $_SESSION['admin_show_user']['surname_user'] = null;
            }
            if (isset($_REQUEST['filter'])) {
                if (isset($_REQUEST['first_name_user'])) {
                    $_SESSION['admin_show_user']['first_name_user'] = $_REQUEST['first_name_user'];
                } else {
                    $_SESSION['admin_show_user']['first_name_user'] = '';
                }
                if (isset($_REQUEST['surname_user'])) {
                    $_SESSION['admin_show_user']['surname_user'] = $_REQUEST['surname_user'];
                } else {
                    $_SESSION['admin_show_user']['surname_user'] = '';
                }
            }
            $where = array();
            if (isset($_REQUEST['reset_filter'])) {
                $_SESSION['admin_show_user']['first_name_user'] = null;
                $_SESSION['admin_show_user']['surname_user'] = null;
            }
            if (!empty($_SESSION['admin_show_user']['surname_user'])) {
                $where[] = array('surname_user LIKE %s', '%' . $_SESSION['admin_show_user']['surname_user'] . '%');
            }
            if (!empty($_SESSION['admin_show_user']['first_name_user'])) {
                $where[] = array('first_name_user LIKE %s', '%' . $_SESSION['admin_show_user']['first_name_user'] . '%');
            }
            $page = 1;
            if (isset($this->args[0])) {
                $page = (int) $this->args[0];
                if ($page == 0) {
                    $page = 1;
                }
            }

            if (isset($_REQUEST['setUsgr'])) {
                if (($user_list = $this->selectUsgr() ) != '') {
                    $this->registry['sk_user']->setUserLevel($user_list);
                }
            }



            require_once 'class/pagination.php';
            $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Admin/showUser/');
            $users = $this->registry['sk_user']->getAllUser($pag->first_row, $this->sk_syst->row_show_syst, $where, $this->args[0]);
            $pag->totalcount = $users['total_count'];
            $this->view->set('pagination', $pag->get_pagination());
            $this->view->set('message', $message);
            $this->view->set('first_name_user', $_SESSION['admin_show_user']['first_name_user']);
            $this->view->set('surname_user', $_SESSION['admin_show_user']['surname_user']);
            $this->view->set('user', $users['value']);
            $this->view->set('content', $this->view->fetch('Subparts/groups/setUsgr.tpl'));
            $this->view->set('title', 'Administrace - uživatelé pro skupinu ');
            $this->render('main.tpl');
        } else {
            $this->showGrou('Skupina nebyla nalezena');
        }
    }

    private function selectUsgr() {
        $user = array();
        foreach ($_REQUEST as $key => $value) {
            if (substr($key, 0, 5) == 'usgr_' && substr($key, 5) != '') {
                $user[] = array('id_user_usgr' => substr($key, 5), 'id_grou_usgr' => $this->args[0], 'permission_usgr' => $value);
            }
        }
        return $user;
    }

}
