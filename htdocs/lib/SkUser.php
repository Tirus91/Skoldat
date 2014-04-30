<?php

class SkUser {

    public $logged = false;
    private $registry;
    public $errno = null;
    public $error = null;
    protected $login_info = array();
    public $user_info = array();
    public $id_subj = array(
        'ADMIN_GROU' => 2,
        'ADMIN_USER' => 1,
        'EVER_GROU' => 3,
        'RENT_GROU' => 4);
    public $is_admin;
    public $perm_list = array(0 => array('sel' => '', 'value' => '0', 'name' => 'Není členem'), 1 => array('sel' => '', 'value' => '1', 'name' => 'Vidět'), 2 => array('sel' => '', 'value' => '2', 'name' => 'Vidět + editace'));

    function __construct($registry) {
        session_start();
        $this->registry = $registry;
        $this->isLoged();
        if (!isset($this->user_info['theme_user'])) {
            $this->user_info['theme_user'] = 'default';
        }
    }

    /**
     * Převede login na malé znaky a uloží si do proměnné
     * 
     * @param mixed $login
     */
    public function setLogin($login) {
        $this->login_info['login_user'] = strtolower($login);
        return true;
    }

    /**
     * Uloží hash hesla
     * 
     * @param mixed $password
     */
    public function setPassword($password) {
        if (isset($this->login_info['login_user'])) {
            $this->login_info['password_user'] = $this->getHashedPassword($this->login_info['login_user'], $password);
            return true;
        }
        return false;
    }

    /**
     * Nastavení hesla i loginu
     * 
     * @param mixed $login
     * @param mixed $password
     */
    public function setUserLoginInfo($login, $password) {
        $this->setLogin($login);
        $this->setPassword($password);
        if (isset($this->login_info['login_user']) && isset($this->login_info['password_user'])) {
            if ($this->getHashedPassword($login, $password) == $this->login_info['password_user']) {
                return true;
            }
        }
        return false;
    }

    public function setNewPassword($id_user, $password = '', $old_password = '') {
        $login_user = dibi::query('SELECT [login_user] FROM [:prefix:user] WHERE [id_user] = %i', $id_user)->fetchSingle();
        if ($login_user != false) {
            $old_password = $this->getHashedPassword($login_user, $old_password);
            $new_password = $this->getHashedPassword($login_user, $password);
            return (bool) dibi::query('UPDATE [:prefix:user] SET ', array('password_user' => $new_password), 'WHERE %and ', array('id_user' => $id_user, 'login_user' => $login_user, 'password_user' => $old_password));
        }
        return false;
    }

    public function changePassword($password, $id_user, $old_password = '') {
        $login_user = dibi::query('SELECT [login_user] FROM [:prefix:user] WHERE [id_user] = %i', $id_user)->fetchSingle();
        if ($login_user != false) {
            $new_pwd = $this->getHashedPassword($login_user, $password);
            return (bool) dibi::query('UPDATE [:prefix:user] SET ', array('password_user' => $new_pwd), 'WHERE [id_user] = %i', $id_user);
        }
        return false;
    }

    /**
     * Přihlásí uživatele
     * 
     */
    public function loginUser() {
        if (isset($this->login_info['login_user']) && isset($this->login_info['password_user'])) {
            $this->user_info = (array) dibi::fetch('SELECT * FROM [:prefix:user] WHERE %and', $this->login_info);
            if (isset($this->user_info['email_user'])) {
                $_SESSION[SESSION_NAME]['sk'] = $this->login_info['password_user'] . '*|*' . $this->login_info['login_user'] . '*|*' . $this->getControlHash();
                $this->is_admin = (bool) dibi::query('SELECT [id_user] FROM [:prefix:user] WHERE %and', $this->login_info)->fetchSingle();
                return $this->isLoged();
            }
        }
        return false;
    }

    /**
     * Odhlásí aktuálního uživatele
     */
    public function logoutUser() {
        if ($this->logged) {
            $this->logged = false;
            $this->user_info = array();
            $this->login_info = array();
            unset($_SESSION[SESSION_NAME]['sk']);
            session_destroy();
            return !$this->isLoged();
        }
        return false;
    }

    /**
     * Vytvoření nového uživatele
     * 
     * @param mixed $user
     */
    public function newUser($user = array()) {
        $all_row = array(
            'id_user',
            'login_user',
            'password_user',
            'first_name_user',
            'surname_user',
            'email_user',
            'role_user',
            'phone_user',
            'theme_user');
        if (isset($user['login_user'], $user['password_user'], $user['email_user'])) {
            if (!$this->checkLoginUser($user['login_user'])) {
                $this->errno = 11;
                return false;
            }
            if (!$this->checkEmailUser($user['email_user'])) {
                $this->errno = 12;
                return false;
            }
            $pass = $user['password_user'];
            $user['password_user'] = $this->getHashedPassword($user['login_user'], $user['password_user']);
            foreach ($user as $key => $val) {
                if (!in_array($key, $all_row)) {
                    unset($user[$key]);
                }
            }
            dibi::begin();
            try {
                dibi::query('INSERT INTO [:prefix:subj] ', array('type_subj' => SUBJ_USER));
                $user['id_user'] = dibi::getInsertId();
                dibi::query('INSERT INTO [:prefix:user] ', $user);
                dibi::commit();
                $this->setUserToGrou($user['id_user'], $this->id_subj['EVER_GROU']);

                if (is_file(APPROOT . 'templates' . DS . $this->user_info['theme_user'] . DS . 'Subparts' . DS . 'mail' . DS . 'newUser.tpl')) {
                    $user['password_user'] = $pass;
                    $mail_body = file_get_contents(APPROOT . 'templates' . DS . $this->user_info['theme_user'] . DS . 'Subparts' . DS . 'mail' . DS . 'newUser.tpl');
                    foreach ($user as $key => $value) {
                        $mail_body = str_replace('#' . $key, $value, $mail_body);
                    }
                    $mail_body = str_replace('#school_name', $this->registry['sk_syst']->school_name_syst, $mail_body);
                    $mail_body = str_replace('#skoldat_site_path', $this->registry['homelink'], $mail_body);
                    dibi::query('INSERT INTO [:prefix:mail] ', array('recipient_mail' => $user['email_user'], 'subject_mail' => $this->registry['sk_syst']->site_name_syst . ' - Váš účet byl úspěšně založen', 'body_mail' => $mail_body));
                }
                return true;
            } catch (DibiException $e) {
                $this->errno = 13;
                dibi::rollback();
            }
        }
        return false;
    }

    /**
     * Smazání daného subjektu
     * 
     * @param mixed $id_user
     * @return mixed
     */
    public function deleteSubj($id_subj, $type_subj = SUBJ_USER) {
        foreach ($this->id_subj as $protected_val) {
            if ($id_subj == $protected_val) {
                $this->errno = 5;
                return false;
            }
        }
        if ((bool) dibi::query('DELETE FROM [:prefix:subj] WHERE %and ', array('id_subj' => $id_subj, 'type_subj' => $type_subj))) {
            return true;
        }
        $this->errno = 6;
        return false;
    }

    /**
     * Změna uživatelových informací
     * 
     * @param mixed $id_user
     * @param mixed $user
     */
    public function editUser($id_user, $user = array(), $self = true) {
        $all_row = array(
            'first_name_user',
            'surname_user',
            'email_user',
            'role_user',
            'phone_user',
            'theme_user');
        if (isset($user['login_user'], $user['email_user'])) {
            if (!$this->checkEmailUser($user['email_user'])) {
                if (dibi::query('SELECT [email_user] FROM [:prefix:user] WHERE [id_user] = %i', $id_user)->fetchSingle() != $user['email_user']) {
                    $this->errno = 13;
                    return false;
                }
            }
        }
        foreach ($user as $key => $val) {
            if (!in_array($key, $all_row)) {
                unset($user[$key]);
            }
        }
        if ((bool) dibi::query('UPDATE [:prefix:user] SET ', $user, ' WHERE [id_user]=%i', $id_user)) {
            if ($self) {
                $this->user_info = $this->getUserInfo($id_user);
            }
            return true;
        }
        return false;
    }

    /**
     * Přidá novou skupinu
     * 
     * @param mixed $grou
     */
    public function newGrou($grou = array()) {
        $all_row = array(
            'id_grou',
            'name_grou',
            'abbrev_grou',
            'ident_grou');
        if (isset($grou['name_grou'], $grou['abbrev_grou'], $grou['ident_grou'])) {
            if ($this->checkGrou($grou)) {
                $this->errno = 19;
                return false;
            }
            foreach ($grou as $key => $val) {
                if (!in_array($key, $all_row)) {
                    unset($grou[$key]);
                }
            }
            dibi::begin();
            try {
                dibi::query('INSERT INTO [:prefix:subj] ', array('type_subj' => SUBJ_GROU));
                $grou['id_grou'] = dibi::getInsertId();
                dibi::query('INSERT INTO [:prefix:grou] ', $grou);
                dibi::commit();
                return true;
            } catch (DibiException $e) {
                dibi::rollback();
            }
        }
        return false;
    }

    /**
     * Provede změnu ve skupině
     * 
     * @param mixed $id_grou
     * @param mixed $grou
     */
    public function editGrou($id_grou, $grou = array()) {
        $all_row = array(
            'id_grou',
            'name_grou',
            'abbrev_grou',
            'ident_grou');
        if (isset($grou['name_grou'], $grou['abbrev_grou'], $grou['ident_grou'])) {
            $group = $grou;
            $group['id_grou'] = $id_grou;
            if ($this->checkGrou($group, 'edit')) {
                if (dibi::query('SELECT [id_grou] FROM [:prefix:grou] WHERE [id_grou] != %i', $id_grou, ' AND ( %or', $grou, ')')->fetchSingle()) {
                    $this->errno = 19;
                    return false;
                }
            }
            foreach ($grou as $key => $val) {
                if (!in_array($key, $all_row)) {
                    unset($grou[$key]);
                }
            }
            return (bool) dibi::query('UPDATE [:prefix:grou] SET ', $grou, 'WHERE [id_grou] =%i', $id_grou);
        }
        return false;
    }

    /**
     * Přiřadí uživatele do dané skupiny
     * 
     * @param mixed $id_user
     * @param mixed $id_grou
     * @return mixed
     */
    public function setUserToGrou($id_user, $id_grou) {
        if ($this->checkTypeSubj($id_grou, SUBJ_GROU) && $this->checkTypeSubj($id_user, SUBJ_USER)) {
            if (!$this->checkUsgr($id_user, $id_grou)) {
                $values = array('id_user_usgr' => $id_user, 'id_grou_usgr' => $id_grou, 'permission_usgr' => 1);
                return (bool) dibi::query('INSERT INTO [:prefix:usgr] ', $values);
            } else {
                return true;
            }
        }
        return false;
    }

    public function setUserLevel($user_list) {
        foreach ($user_list as $user) {
            if ($user['permission_usgr'] > 0) {
                if ($this->setUserToGrou($user['id_user_usgr'], $user['id_grou_usgr'])) {
                    $return[] = (bool) dibi::query('UPDATE [:prefix:usgr] SET [permission_usgr] = %i', $user['permission_usgr'], ' WHERE %and', array('id_user_usgr' => $user['id_user_usgr'], 'id_grou_usgr' => $user['id_grou_usgr']));
                }
            } else {
                $return[] = (bool) dibi::query('DELETE FROM [:prefix:usgr] WHERE %and', array('id_user_usgr' => $user['id_user_usgr'], 'id_grou_usgr' => $user['id_grou_usgr']));
            }
        }
        foreach ($return as $val) {
            if ($val == false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Odebere uživatele ze skupiny
     * 
     * @param mixed $id_user
     * @param mixed $id_grou
     * @return mixed
     */
    public function remUserFromGrou($id_user, $id_grou) {
        if ($this->checkTypeSubj($id_grou, SUBJ_GROU) && $this->checkTypeSubj($id_user, SUBJ_USER)) {
            if ($this->checkUsgr($id_user, $id_grou)) {
                return (bool) dibi::query('DELETE FROM [:prefix:usgr] WHERE [id_user_usgr] = %i ', $id_user, ' AND [id_grou_usgr] =%i', $id_grou);
            }
        }
    }

    /**
     * Získá informace o uživateli
     * 
     * @param mixed $id_user
     */
    public function getUserInfo($id_user) {
        $user_info = (array) dibi::fetch('SELECT * FROM [:prefix:user] WHERE [id_user] = %i', $id_user);
        $pom = $user_info['theme_user'];
        $user_info['theme_user'] = $this->registry['sk_syst']->available_theme;
        $user_info['theme_user'][$pom]['sel'] = 'selected="selected"';
        if (!empty($user_info)) {
            return $user_info;
        }
        return false;
    }

    /**
     * Navrátí seznam skupin ve kterých je uživatel
     * 
     * @param mixed $id_user
     */
    public function getUserGrou($id_user = '') {
        if (!$this->logged) {
            return false;
        }
        if (empty($id_user)) {
            $id_user = $this->user_info['id_user'];
        }
        $all = dibi::query('SELECT * FROM [:prefix:subj] LEFT JOIN [:prefix:grou] ON [id_grou]=[id_subj] LEFT JOIN [:prefix:usgr] ON [id_grou_usgr]=[id_grou] WHERE [id_user_usgr] = %i', $id_user)->fetchAssoc('id_grou');
        foreach ($all as $row => $val) {
            $all[$row] = (array) $val;
        }
        return $all;
    }

    /**
     * Kontrola typu objektu
     * 
     * @param mixed $id_subj
     * @param mixed $type_subj
     * @return mixed
     */
    private function checkTypeSubj($id_subj, $type_subj = 1) {
        return (bool) dibi::query('SELECT [id_subj] FROM [:prefix:subj] WHERE [id_subj] = %i', $id_subj, ' AND [type_subj]= %i', $type_subj); //->fetchSingle();
    }

    /**
     * Zkontroluje zda uživatel je opravdu v dané skupině
     * 
     * @param mixed $id_user
     * @param mixed $id_grou
     * @return mixed
     */
    private function checkUsgr($id_user, $id_grou) {
        return (bool) dibi::query('SELECT [id_user_usgr] FROM [:prefix:usgr] WHERE [id_user_usgr] = %i ', $id_user, ' AND [id_grou_usgr] =%i', $id_grou)->fetchSingle();
    }

    /**
     * Navrátí hash hesla
     * 
     * @param mixed $login
     * @param mixed $password
     */
    private function getHashedPassword($login, $password) {
        return sha1($login . md5($password));
    }

    /**
     * Vytvoí kontrolní hash
     */
    private function getControlHash() {
        return sha1($this->login_info['login_user'] . $this->login_info['password_user']);
    }

    /**
     * Provede kontrolu zda je uživatel přihlášen
     * 
     */
    private function isLoged() {
        if ($this->logged == false) {
            if (isset($_SESSION[SESSION_NAME]['sk'])) {
                $sess = explode('*|*', $_SESSION[SESSION_NAME]['sk']);
                if (count($sess) == 3) {
                    $this->login_info['password_user'] = $sess[0];
                    $this->login_info['login_user'] = $sess[1];
                    if ($this->getControlHash() == $sess[2]) {
                        $this->user_info = (array) dibi::fetch('SELECT * FROM [:prefix:user] WHERE %and', $this->login_info);
                        $this->is_admin = (bool) dibi::query('SELECT [id_user] FROM [:prefix:user] WHERE %and', $this->login_info)->fetchSingle();
                        $this->logged = true;
                        $this->getUsgr();
                    }
                }
            }
        }
        return $this->logged;
    }

    public function isInGroup($grou = array()) {
        if (!empty($grou)) {
            if (isset($grou['id_grou'])) {
                if (isset($this->user_info['groups'][$grou['id_grou']])) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getPermLevel($id_user, $id_grou) {
        return dibi::query('SELECT [permission_usgr] FROM [:prefix:usgr] WHERE %and', array('id_user_usgr' => $id_user, 'id_grou_usgr' => $id_grou))->fetchSingle();
    }

    /**
     * Zkontroluje login, zda není ve špatném tvaru a není již používán
     * 
     * @param mixed $login
     */
    private function checkLoginUser($login = '') {
        if (!empty($login)) {
            if (dibi::query('SELECT [login_user] FROM [:prefix:user] WHERE [login_user]=%s', $login)->fetchSingle() == false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Provede kontrolu emailu (tvar i zda není již používán)
     * 
     * @param mixed $email
     */
    private function checkEmailUser($email = '') {
        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (dibi::query('SELECT [email_user] FROM [:prefix:user] WHERE [email_user]=%s', $email)->fetchSingle() == false) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getGrouList($offset, $limit, $where = array()) {
        if (count($where) > 0) {
            $cursor = dibi::query('SELECT * FROM [:prefix:grou] WHERE %and', $where);
        } else {
            $cursor = dibi::query('SELECT * FROM [:prefix:grou] ');
        }
        $groups['total_count'] = count($cursor);
        if ($offset >= $groups['total_count']) {
            $offset = 0;
        }
        $groups['group'] = array();
        foreach ($cursor->getIterator($offset, $limit) as $row => $inf) {
            $groups['group'][$row] = (array) $inf;
            $groups['group'][$row]['count_user'] = dibi::query('SELECT count(id_user_usgr) FROM [:prefix:usgr] WHERE [id_grou_usgr] = %i', $groups['group'][$row]['id_grou'])->fetchSingle();
            if (in_array($groups['group'][$row]['id_grou'], $this->id_subj)) {
                $groups['group'][$row]['del_grou'] = '';
                $groups['group'][$row]['edi_grou'] = '';
            } else {
                $groups['group'][$row]['del_grou'] = '<a class="remove" href="' . $this->registry['homelink'] . '/Admin/delGrou/' . $groups['group'][$row]['id_grou'] . '" data-confirm="Opravdu si přejete smazat skupinu ' . $groups['group'][$row]['name_grou'] . '?">&nbsp;&nbsp;&nbsp;</a>';
                $groups['group'][$row]['edi_grou'] = '<a class="edit" href="' . $this->registry['homelink'] . '/Admin/editGrou/' . $groups['group'][$row]['id_grou'] . '">&nbsp;&nbsp;</a>';
            }
        }

        return $groups;
    }

    public function getAllGroup($id_user, $offset = 0, $limit = null, $where) {
        if (!empty($id_user)) {
            //$where[]=array('id_user_usgr = %i',$id_user);
            $all = dibi::query('SELECT * FROM [:prefix:grou] LEFT JOIN [:prefix:usgr] ON [id_grou] = [id_grou_usgr] WHERE %and', $where);
            $return['total_count'] = count($all);
            $i = 0;
            if (!empty($limit)) {
                foreach ($all->getIterator($offset, $limit) as $val) {
                    $return['value'][$i] = (array) $val;
                    if ($this->isInGroup($return['value'][$i])) {
                        $return['value'][$i]['in_group'] = true;
                    } else {
                        $return['value'][$i]['in_group'] = false;
                    }
                    switch ($return['value'][$i]['permission_usgr']) {
                        case 1:
                            $return['value'][$i]['permission_usgr'] = 'none';
                            break;
                        case 2:
                            $return['value'][$i]['permission_usgr'] = 'view';
                            break;
                        case 3:
                            $return['value'][$i]['permission_usgr'] = 'all';
                            break;
                        default:
                            $return['value'][$i]['permission_usgr'] = 'none';
                            break;
                    }
                    $i++;
                }
            } else {
                foreach ($all as $val) {
                    $return['value'][$i] = (array) $val;
                    if ($this->isInGroup($return['value'][$i])) {
                        $return['value'][$i]['in_group'] = true;
                    } else {
                        $return['value'][$i]['in_group'] = false;
                    }
                    switch ($return['value'][$i]['permission_usgr']) {
                        case 1:
                            $return['value'][$i]['permission_usgr'] = 'none';
                            break;
                        case 2:
                            $return['value'][$i]['permission_usgr'] = 'view';
                            break;
                        case 3:
                            $return['value'][$i]['permission_usgr'] = 'all';
                            break;
                        default:
                            $return['value'][$i]['permission_usgr'] = 'none';
                            break;
                    }
                    $i++;
                }
            }
            return $return;
        }
        return false;
    }

    private function getUsgr($id_user = null) {
        if ($this->logged) {
            $where = array('id_user_usgr' => $this->user_info['id_user']);
            if (!empty($id_user)) {
                $where = array('id_user_usgr' => $id_user);
            }
            $this->user_info['groups'] = (array) dibi::query('SELECT * FROM [:prefix:usgr] LEFT JOIN [:prefix:grou] ON [id_grou] = [id_grou_usgr] WHERE %and', $where)->fetchAssoc('id_grou_usgr');
            foreach ($this->user_info['groups'] as $row => $val) {
                $this->user_info['groups'][$row] = (array) $val;
            }
        }
        return false;
    }

    public function getGrouInfo($id_grou) {
        return (array) dibi::fetch('SELECT * FROM [:prefix:grou] WHERE [id_grou] = %i ', $id_grou);
    }

    /**
     * Zkontroluje existenci skupiny s jedním ze zadaných údajů
     * 
     * @param mixed $grou
     * @return mixed
     */
    private function checkGrou($grou, $type = null) {
        foreach ($grou as $key => $val) {
            if ($val == '') {
                unset($grou[$key]);
            }
        }
        if ($type == 'edit') {
            $id_grou = $grou['id_grou'];
            unset($grou['id_grou']);
            return (bool) dibi::query('SELECT [id_grou] FROM [:prefix:grou] WHERE (%or', $grou, ') AND [id_grou] != %i', $id_grou)->fetchSingle();
        }
        return (bool) dibi::query('SELECT [id_grou] FROM [:prefix:grou] WHERE %or', $grou)->fetchSingle();
    }

    public function checkPrivileg($id_grou, $id_user, $perm = 0) {
        return (bool) dibi::query('SELECT [id_grou_usgr] FROM [:prefix:usgr] WHERE %and', array('id_grou_usgr' => $id_grou, 'id_user_usgr' => $id_user, array('permission_usgr>=%i', $perm)))->fetchSingle();
    }

    public function getAllUser($offset = 0, $limit = _DEFAULT_COUNT_SHOWED_ART, $where = array(), $id_grou_usgr = null) {
        if (empty($where)) {
            $c = dibi::query('SELECT * FROM [:prefix:user]');
        } else {
            $c = dibi::query('SELECT * FROM [:prefix:user] WHERE %and', $where);
        }
        $return_val['total_count'] = count($c);
        if ($offset >= $return_val['total_count']) {
            $offset = 0;
        }
        $row = 0;
        $return_val['value'] = array();
        foreach ($c->getIterator($offset, $limit) as $value) {
            $return_val['value'][$row] = (array) $value;
            if ($this->checkPrivileg($this->id_subj['ADMIN_GROU'], $this->user_info['id_user'], 2) == false) {
                $return_val['value'][$row]['delete_user'] = '';
                $return_val['value'][$row]['edit_user'] = '';
                $return_val['value'][$row]['perm_list'] = '';
            } else {
                if ($return_val['value'][$row]['id_user'] == $this->id_subj['ADMIN_USER']) {
                    $return_val['value'][$row]['delete_user'] = '';
                    $return_val['value'][$row]['edit_user'] = '';
                } elseif ($return_val['value'][$row]['id_user'] != $this->id_subj['ADMIN_USER'] && $this->user_info['id_user'] == $return_val['value'][$row]['id_user']) {
                    $return_val['value'][$row]['delete_user'] = '';
                    $return_val['value'][$row]['edit_user'] = '<a class="edit" href="' . $this->registry['homelink'] . '/Admin/editUser/' . $return_val['value'][$row]['id_user'] . '">&nbsp;&nbsp;</a>';
                } else {
                    $return_val['value'][$row]['delete_user'] = '<a class="remove" href="' . $this->registry['homelink'] . '/Admin/delUser/' . $return_val['value'][$row]['id_user'] .
                            '" data-confirm="Opravdu si přejete smazat uživatele ' . $return_val['value'][$row]['first_name_user'] . ' ' . $return_val['value'][$row]['surname_user'] . '?">&nbsp;&nbsp;&nbsp;</a>';
                    $return_val['value'][$row]['edit_user'] = '<a class="edit" href="' . $this->registry['homelink'] . '/Admin/editUser/' . $return_val['value'][$row]['id_user'] . '">&nbsp;&nbsp;</a>';
                }
                if ($id_grou_usgr != null) {
                    $perm_list = $this->perm_list;
                    foreach ($perm_list as $key => $val) {
                        if ($val['value'] == $this->getPermLevel($return_val['value'][$row]['id_user'], $id_grou_usgr)) {
                            $perm_list[$key]['sel'] = 'selected="selected"';
                        }
                    }

                    if ($return_val['value'][$row]['id_user'] == $this->id_subj['ADMIN_USER'] && in_array($id_grou_usgr, $this->id_subj)) {
                        $return_val['value'][$row]['disabled'] = 'disabled="disabled"';
                    } else {
                        $return_val['value'][$row]['disabled'] = '';
                    }
                    $return_val['value'][$row]['perm_list'] = $perm_list;
                } else {
                    $return_val['value'][$row]['perm_list'] = null;
                    $return_val['value'][$row]['disabled'] = null;
                }
            }
            $row++;
        }
        return $return_val;
    }

}

?>
