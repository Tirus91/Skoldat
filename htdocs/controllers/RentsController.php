<?php

class RentsController extends Controllers_AbstractController {

    function index() {
        //$this->showRecu();
        header('LOCATION: '.$this->registry['homelink'].'/Rents/showRecu');
    }

    function addRoom() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - nová místnost');
            $this->render('main.tpl');
            return true;
        }
        $room = null;
        $rooms = array();
        if (isset($_REQUEST['name_room']) && isset($_REQUEST['addRoom'])) {
            foreach ($_REQUEST['name_room'] as $row => $val) {
                $room = array(
                    'name_room' => $_REQUEST['name_room'][$row],
                    'location_room' => $_REQUEST['location_room'][$row],
                    'description_room' => $_REQUEST['description_room'][$row]);
                if ($this->registry['sk_rent']->addRoom($room)) {
                    $room['status'] = 'Místnost založena';
                } else {
                    $room['status'] = 'Místnost se nepodařilo založit. ' . $this->registry['sk_rent']->error;
                }
                $rooms[] = $room;
            }
        } else {
            $rooms[] = array(
                'name_room' => '',
                'location_room' => '',
                'description_room' => '',
                'status' => '');
        }
       
        $this->view->set('rooms', $rooms);
        if (isset($this->args[0])) {
            if ($this->args[0] == 'popup') {
                echo $this->view->fetch('Subparts/addRoom.tpl');
                die;
            }
        }
        $this->view->set('content', $this->view->fetch('Subparts/addRoom.tpl'));
        $this->view->set('title', 'Nové místnosti');
        $this->render('main.tpl');
    }

    function editRoom() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - editace místnosti');
            $this->render('main.tpl');
            return true;
        }
        $room = null;
        if (isset($_REQUEST['name_room']) && isset($_REQUEST['editRoom'])) {
            $room = array(
                'name_room' => $_REQUEST['name_room'],
                'location_room' => $_REQUEST['location_room'],
                'description_room' => $_REQUEST['description_room']);
            if (isset($this->args[0])) {
                if ($this->registry['sk_rent']->editRoom($this->args[0], $room)) {
                    $room['status'] = 'Místnost byla editována';
                } else {
                    $room['status'] = 'Místnost se nepodařilo editovat. ' . $this->registry['sk_rent']->error;
                }
            }
        } else {
            if (isset($this->args[0])) {
                $room = $this->registry['sk_rent']->getRoomInfo($this->args[0]);
                $room['status'] = '';
            } else {
                $room = array(
                    'name_room' => '',
                    'location_room' => '',
                    'description_room' => '',
                    'status' => '');
            }
        }
       
        foreach ($room as $key => $value) {
            $this->view->set($key, $value);
        }
        if (isset($this->args[1])) {
            if ($this->args[1] == 'popup') {
                echo $this->view->fetch('Subparts/editRoom.tpl');
                die;
            }
        }
        $this->view->set('content', $this->view->fetch('Subparts/editRoom.tpl'));
        $this->view->set('title', 'Změna údajů místnosti ' . $room['name_room']);
        $this->render('main.tpl');
    }

    function showRooms($deleted = '') {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 1) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - přehled místností');
            $this->render('main.tpl');
            return true;
        }
        $curpos = '0';
        if ($deleted != '') {
            
        } else {
            if (isset($this->args[0])) {
                $curpos = $this->args[0];
            }
        }
        if (!isset($_SESSION['rooms']['location_room'])) {
            $_SESSION['rooms']['location_room'] = null;
        }
        if (!isset($_SESSION['rooms']['name_room'])) {
            $_SESSION['rooms']['name_room'] = null;
        }
        if (isset($_REQUEST['filter'])) {
            if (isset($_REQUEST['location_room'])) {
                $_SESSION['rooms']['location_room'] = $_REQUEST['location_room'];
            } else {
                $_SESSION['rooms']['location_room'] = '';
            }
            if (isset($_REQUEST['name_room'])) {
                $_SESSION['rooms']['name_room'] = $_REQUEST['name_room'];
            } else {
                $_SESSION['rooms']['name_room'] = '';
            }
        }
        if (isset($_REQUEST['reset_filter'])) {
            $_SESSION['rooms']['location_room'] = null;
            $_SESSION['rooms']['name_room'] = null;
        }
        $where = array();
        if (!empty($_SESSION['rooms']['location_room'])) {
            $where[] = array('location_room LIKE %s', '%' . $_SESSION['rooms']['location_room'] . '%');
        }
        if (!empty($_SESSION['rooms']['name_room'])) {
            $where[] = array('name_room LIKE %s', '%' . $_SESSION['rooms']['name_room'] . '%');
        }
        $page = 1;
        if (isset($this->args[0])) {
            $page = (int) $this->args[0];
            if ($page == 0) {
                $page = 1;
            }
        }
        require_once 'class/pagination.php';
        $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Rents/showRooms/');
        $room = $this->registry['sk_rent']->getRoomsInfo($pag->first_row, $this->sk_syst->row_show_syst, $where);
        $pag->totalcount = $room['total_count'];
        $this->view->set('pagination', $pag->get_pagination());
        $this->view->set('addItembutton',$this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2),TRUE);
        $this->view->set('location_room', $_SESSION['rooms']['location_room']);
        $this->view->set('name_room', $_SESSION['rooms']['name_room']);
        $this->view->set('message', $deleted);
        $this->view->set('rooms', $room['rooms']);
        $this->view->set('content', $this->view->fetch('Subparts/showRoom.tpl'));
        $this->view->set('title', 'Přehled místností');
        $this->render('main.tpl');
    }

    function deleteRoom() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - smazání místnosti');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        if (isset($this->args[0])) {
            if ($this->registry['sk_rent']->delFromSubj($this->args[0], SUBJ_ROOM)) {
                $message = 'smazáno';
            }
        }
        $this->showRooms($message);
    }

    function showRent($message = '') {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 1) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Přehled smluvních stran');
            $this->render('main.tpl');
            return true;
        }
        if (!isset($_SESSION['recu']['first_name_rent'])) {
            $_SESSION['recu']['first_name_rent'] = null;
        }
        if (!isset($_SESSION['rooms']['surname_rent'])) {
            $_SESSION['recu']['surname_rent'] = null;
        }
        if (!isset($_SESSION['rooms']['phone_rent'])) {
            $_SESSION['recu']['phone_rent'] = null;
        }
        if (isset($_REQUEST['filter'])) {
            if (isset($_REQUEST['first_name_rent'])) {
                $_SESSION['recu']['first_name_rent'] = $_REQUEST['first_name_rent'];
            } else {
                $_SESSION['recu']['first_name_rent'] = '';
            }
            if (isset($_REQUEST['surname_rent'])) {
                $_SESSION['recu']['surname_rent'] = $_REQUEST['surname_rent'];
            } else {
                $_SESSION['recu']['surname_rent'] = '';
            }
            if (isset($_REQUEST['phone_rent'])) {
                $_SESSION['recu']['phone_rent'] = $_REQUEST['phone_rent'];
            } else {
                $_SESSION['recu']['phone_rent'] = '';
            }
        }
        if (isset($_REQUEST['reset_filter'])) {
            $_SESSION['recu']['first_name_rent'] = null;
            $_SESSION['recu']['surname_rent'] = null;
            $_SESSION['recu']['phone_rent'] = null;
        }
        $where = array();
        if (!empty($_SESSION['recu']['first_name_rent'])) {
            $where[] = array('first_name_rent LIKE %s', '%' . $_SESSION['recu']['first_name_rent'] . '%');
        }
        if (!empty($_SESSION['recu']['surname_rent'])) {
            $where[] = array('surname_rent LIKE %s', '%' . $_SESSION['recu']['surname_rent'] . '%');
        }
        if (!empty($_SESSION['recu']['phone_rent'])) {
            $where[] = array('phone_rent LIKE %s', '%' . $_SESSION['recu']['phone_rent'] . '%');
        }
        $all_rent = array();
        $page = 1;
        if (isset($this->args[0])) {
            $page = (int) $this->args[0];
            if ($page == 0) {
                $page = 1;
            }
        }
        require_once 'class/pagination.php';
        $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Rents/showRent/');
        $all_rent = $this->registry['sk_rent']->getRentsInfo($pag->first_row, $this->sk_syst->row_show_syst, $where);
        $pag->totalcount = $all_rent['total_count'];
        $this->view->set('addItembutton',$this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2),TRUE);
        $this->view->set('pagination', $pag->get_pagination());
        $this->view->set('first_name_rent', $_SESSION['recu']['first_name_rent']);
        $this->view->set('surname_rent', $_SESSION['recu']['surname_rent']);
        $this->view->set('phone_rent', $_SESSION['recu']['phone_rent']);
        $this->view->set('rents', $all_rent['rents']);
        $this->view->set('message', $message);
        $this->view->set('content', $this->view->fetch('Subparts/showRent.tpl'));
        $this->view->set('title', 'Přehled smluvních stran');
        $this->render('main.tpl');
    }

    function addRent() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - nová smluvní strana');
            $this->render('main.tpl');
            return true;
        }
        $rent = null;
        if (isset($_REQUEST['addRent'])) {
            $rent = array(
                'first_name_rent' => $_REQUEST['first_name_rent'],
                'surname_rent' => $_REQUEST['surname_rent'],
                'phone_rent' => $_REQUEST['phone_rent'],
                'address_rent' => $_REQUEST['address_rent'],
                'town_rent' => $_REQUEST['town_rent'],
                'email_rent' => $_REQUEST['email_rent']);
            if ($this->registry['sk_rent']->addRent($rent)) {
                $rent['status'] = 'Smluvní strana byla přidána';
            } else {
                $rent['status'] = 'Smluvní stranu se nepodařilo přidat. ' . $this->registry['sk_rent']->error;
            }
        } else {
            $rent = array(
                'first_name_rent' => '',
                'surname_rent' => '',
                'phone_rent' => '',
                'address_rent' => '',
                'town_rent' => '',
                'email_rent' => '',
                'status' => '');
        }
        foreach ($rent as $key => $val) {
            $this->view->set($key, $val);
        }
        $this->view->set('content', $this->view->fetch('Subparts/addRent.tpl'));
        $this->view->set('title', 'Nová smluvní strana');
        $this->render('main.tpl');
    }

    function deleteRent() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Smazání smluvní strany');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        if (isset($this->args[0])) {
            if ($this->registry['sk_rent']->delFromSubj($this->args[0], SUBJ_RENT)) {
                $message = 'smazáno';
            }
        }
        $this->showRent($message);
    }

    function deleteRecu() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Smazání pronájmu');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        if (isset($this->args[0])) {
            if ($this->registry['sk_rent']->delFromSubj($this->args[0], SUBJ_RECU)) {
                $message = 'smazáno';
            } else {
                $message = 'ne';
            }
        }
        $this->showRecu($message);
    }

    function editRent() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Úprava smluvní strany');
            $this->render('main.tpl');
            return true;
        }
        $rent = null;
        if (isset($_REQUEST['addRent'])) {
            $rent = array(
                'first_name_rent' => $_REQUEST['first_name_rent'],
                'surname_rent' => $_REQUEST['surname_rent'],
                'phone_rent' => $_REQUEST['phone_rent'],
                'address_rent' => $_REQUEST['address_rent'],
                'town_rent' => $_REQUEST['town_rent'],
                'email_rent' => $_REQUEST['email_rent']);
            if (isset($this->args[0])) {
                if ($this->registry['sk_rent']->editRent($this->args[0], $rent)) {
                    $rent['status'] = 'Smluvní strana byla upravena';
                } else {
                    $rent['status'] = 'Smluvní stranu se nepodařilo editovat. ' . $this->registry['sk_rent']->error;
                }
            } else {
                $rent['status'] = '';
            }
        } else {
            $rent = array(
                'first_name_rent' => '',
                'surname_rent' => '',
                'phone_rent' => '',
                'address_rent' => '',
                'town_rent' => '',
                'email_rent' => '',
                'status' => '');
        }
        if (isset($this->args[0])) {
            $rent = $this->registry['sk_rent']->getRentInfo($this->args[0]);
        }
        foreach ($rent as $key => $val) {
            $this->view->set($key, $val);
        }
        $this->view->set('content', $this->view->fetch('Subparts/addRent.tpl'));
        $this->view->set('title', 'Nová smluvní strana');
        $this->render('main.tpl');
    }

    function addRecu() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Nový pronájem');
            $this->render('main.tpl');
            return true;
        }
        $array = array(
            'time_from_recu',
            'time_to_recu',
            'date_from_recu',
            'date_to_recu');
        $date_recu = array(
            'all' => array(
                'key' => 'all',
                'value' => 'V celém daném rozmezí',
                'sel' => ''),
            'one' => array(
                'key' => 'one',
                'value' => 'Jednorázově',
                'sel' => ''),
            'Mon' => array(
                'key' => 'Mon',
                'value' => 'Pondělí',
                'sel' => ''),
            'Tue' => array(
                'key' => 'Tue',
                'value' => 'Úterý',
                'sel' => ''),
            'Wed' => array(
                'key' => 'Wed',
                'value' => 'Středa',
                'sel' => ''),
            'Thu' => array(
                'key' => 'Thu',
                'value' => 'Čtvrtek',
                'sel' => ''),
            'Fri' => array(
                'key' => 'Fri',
                'value' => 'Pátek',
                'sel' => ''),
            'Sat' => array(
                'key' => 'Sat',
                'value' => 'Sobota',
                'sel' => ''),
            'Sun' => array(
                'key' => 'Sun',
                'value' => 'Neděle',
                'sel' => ''));
        $recu = array();
        $recus = array();
        $rents_model = new Models_Rents($this->registry);
        if (isset($_REQUEST['addRecu'])) {
            if (isset($_REQUEST['date_from_recu'])) {
                foreach ($_REQUEST['date_from_recu'] as $row => $val) {
                    $recu = array(
                        'time_from_recu' => $_REQUEST['time_from_recu'][$row],
                        'time_to_recu' => $_REQUEST['time_to_recu'][$row],
                        'date_from_recu' => $_REQUEST['date_from_recu'][$row],
                        'date_to_recu' => $_REQUEST['date_to_recu'][$row],
                        'id_refi_recu' => $_REQUEST['id_refi_recu'][$row],
                        'day_recu' => $_REQUEST['date_recu'][$row]
                    );
                    if ($this->registry['sk_rent']->addRecu($recu, $row)) {
                        $recu['status'] = 'Pronájem byl zapsán';
                    } else {
                        $recu['status'] = 'Pronájem se nepodařilo zapsat. ' . $this->registry['sk_rent']->error;
                    }
                    $recu['date_recu'] = $date_recu;
                    $recu['id_refi_recu'] = $rents_model->getRefiAndRent($_REQUEST['id_refi_recu'][$row]);
                    $recu['date_recu'][$_REQUEST['date_recu'][$row]]['sel'] = 'selected="selected"';
                    $recu['row_count'] = count($recus) + 1;
                    $recus[] = $recu;
                }
            }
        }
        if (empty($recu)) {
            $recus[0] = array(
                'time_from_recu' => '',
                'time_to_recu' => '',
                'date_from_recu' => '',
                'date_to_recu' => '',
                'row_count' => '1',
                'id_refi_recu' => $rents_model->getRefiAndRent(),
                'status' => (isset($_REQUEST['addRecu']) ? 'Neočekávaná chyba, prosím zkuste akci opakovat později.' : ''),
                'date_recu' => $date_recu);
        }
        $this->view->set('recu', $recus);
        $this->view->set('content', $this->view->fetch('Subparts/addRecu.tpl'));
        $this->view->set('title', 'Nová smluvní strana');
        $this->render('main.tpl');
    }

    function massDeleteRecu() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Hromadné mazání');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        if (isset($_REQUEST['massdeletebutton']) && isset($_REQUEST['mass_delete'])) {
            $succes = 0;
            $tc = count($_REQUEST['mass_delete']);
            foreach ($_REQUEST['mass_delete'] as $id_recu_for_delete) {
                if ($this->registry['sk_rent']->delFromSubj($id_recu_for_delete, SUBJ_RECU)) {
                    $succes++;
                }
            }
            $message = 'Bylo smazáno ' . (int) $succes . ' z ' . (int) $tc;
        }
        $this->showRecu($message);
    }

    function showRecu($message = '') {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 1) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - přehled pronájmů');
            $this->render('main.tpl');
            return true;
        }
        
        $from = null;
        $to = null;
        if (!isset($_SESSION['recu']['day_from_recu'])) {
            $date_c = new DateTime();
            $_SESSION['recu']['day_from_recu'] = $date_c->format('d.m.Y');
        }
        if (!isset($_SESSION['recu']['day_to_recu'])) {
            $date_c = new DateTime();
            $_SESSION['recu']['day_to_recu'] = $date_c->format('d.m.Y');
        }
        if (!isset($_SESSION['recu']['id_room_recu'])) {
            $_SESSION['recu']['id_room_recu'] = null;
        }
        if (isset($_REQUEST['filter'])) {
            if (isset($_REQUEST['showRecuFrom'])) {
                if (strtotime($_REQUEST['showRecuFrom'])) {
                    $fro = new DateTime($_REQUEST['showRecuFrom']);
                    $from = $fro->format('Y-m-d');
                    $_SESSION['recu']['day_from_recu'] = $fro->format('d.m.Y');
                }
            }
            if (isset($_REQUEST['showRecuTo'])) {
                if (strtotime($_REQUEST['showRecuTo'])) {
                    $t = new DateTime($_REQUEST['showRecuTo']);
                    $to = $t->format('Y-m-d');
                    $_SESSION['recu']['day_to_recu'] = $t->format('d.m.Y');
                }
            }
            if (isset($_REQUEST['id_room_recu'])) {
                if ($_REQUEST['id_room_recu'] == 'all') {
                    $_SESSION['recu']['id_room_recu'] = null;
                }
            }
        }
        $rooms = $this->registry['sk_rent']->getRooms();
        $id_room = null;
        foreach ($rooms as $row => $value) {
            if (isset($_REQUEST['id_room_recu']) && isset($_REQUEST['filter'])) {
                if ($value['id_room'] == $_REQUEST['id_room_recu']) {
                    $rooms[$row]['sel'] = 'selected="selected"';
                    $_SESSION['recu']['id_room_recu'] = $_REQUEST['id_room_recu'];
                    continue;
                }
            } else {
                if ($value['id_room'] == $_SESSION['recu']['id_room_recu']) {
                    $rooms[$row]['sel'] = 'selected="selected"';
                    continue;
                }
            }
            $rooms[$row]['sel'] = '';
        }
        if (empty($rooms)) {
            $rooms = array(array('fullname' => 'Prosím vytvořte místnost'));
        } else {
            
        }
        if (isset($_REQUEST['reset_filter'])) {
            $date_c = new DateTime();
            $_SESSION['recu']['day_from_recu'] = $date_c->format('d.m.Y');
            $_SESSION['recu']['day_to_recu'] = $date_c->format('d.m.Y');
            $_SESSION['recu']['id_room_recu'] = null;
        }
        $page = 1;
        if (isset($this->args[0])) {
            $page = (int) $this->args[0];
            if ($page == 0) {
                $page = 1;
            }
        }
        require_once 'class/pagination.php';
        $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Rents/showRecu/');
        $recus = $this->registry['sk_rent']->showRecuWithInfo($pag->first_row, $this->sk_syst->row_show_syst, $_SESSION['recu']['day_from_recu'], $_SESSION['recu']['day_to_recu'], $_SESSION['recu']['id_room_recu']);
        $pag->totalcount = $recus['total_count'];
        $this->view->set('rooms', $rooms);
        $this->view->set('message', $message);
        $this->view->set('recu', $recus['rows']);
        $this->view->set('pagination', $pag->get_pagination());
        
        if($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) != true){
            $this->view->set('massdeletechb','disabled="disabled"');
        }else{
            $this->view->set('massdeletechb','');
        }
        
        
        $this->view->set('addItembutton',$this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2),TRUE);
        $this->view->set('massdeletebutton',$this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2),TRUE);
        $this->view->set('title', 'Přehled pronájmů');
        $this->view->set('from_date', $_SESSION['recu']['day_from_recu']);
        $this->view->set('to_date', $_SESSION['recu']['day_to_recu']);
        $export = true;
        if (isset($_REQUEST['export_csv'])) {
            $cur_date = new DateTime();
            if (!is_dir('./download/csv/' . $cur_date->format('Y'))) {
                mkdir('./download/csv/' . $cur_date->format('Y'));
            }
            $file_name = 'pronajmy_' . $cur_date->format('Y-m-d') . '_obdobi_od_' . $_SESSION['recu']['day_from_recu'] . '_do_' . $_SESSION['recu']['day_to_recu'];
            $files = glob('./download/csv/' . $cur_date->format('Y') . '/*.csv');
            $file_name = str_pad((count($files) + 1), 4, 0, STR_PAD_LEFT) . '_' . str_replace('.', '-', $file_name) . '.csv';

            $csv = '';
            foreach ($this->registry['sk_rent']->showRecuWithInfoCSV($_SESSION['recu']['day_from_recu'], $_SESSION['recu']['day_to_recu'], $_SESSION['recu']['id_room_recu']) as $recu) {

                $csv .= '"' . $recu["first_name_rent"] . ' ' . $recu["surname_rent"] . '";"' . $recu["name_room"] . ' (' . $recu["description_room"] . ')";"' . $recu["time_from_recu"] . '";"' . $recu["time_to_recu"] . '";"' . $recu["day_recu"] . '";"' . $recu["status_recu"] . '"' . "\r\n";
            }
            if (strlen($csv) <= 1) {
                $export = false;
            } else {
                $csv = '"Název smluvní strany";"Pronajatá místnost";"Pronájem od";"Pronájem do";"Den pronájmu";"Stav pronájmu"' . "\r\n" . $csv;
                $csv = iconv('utf-8', 'windows-1250', $csv);
                file_put_contents('./download/csv/' . $cur_date->format('Y') . '/' . $file_name, $csv);
                if (file_exists('./download/csv/' . $cur_date->format('Y') . '/' . $file_name)) {
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private", false);
                    header("Content-Type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=\"$file_name\";");
                    header("Content-Transfer-Encoding: binary");
                    unlink('./download/csv/' . $cur_date->format('Y') . '/' . $file_name);
                    echo $csv;
                    die;
                } else {
                    $export = false;
                }
            }
        }
        $this->view->set('content', ($export == false ? '<script>alert("Export se nepodařilo provést");</script>' : '') . $this->view->fetch('Subparts/showRecu.tpl'));
        $this->render('main.tpl');
    }

    public function setStatusRecu() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 1) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Smazání pronájmu');
            $this->render('main.tpl');
            return true;
        }
        $message = '';
        $status = 2;
        if (isset($this->args[0])) {
            if (isset($this->args[1])) {
                if ($this->args[1] == 'accept') {
                    $status = 1;
                } elseif ($this->args[1] == 'decline') {
                    $status = 0;
                }
            }
            if ($this->registry['sk_rent']->setStatus($this->args[0], $status)) {
                $message = 'Pronájem byl úspěšně označen jako ' . ($status == 1 ? 'konaný' : ($status == 0 ? 'nekonaný' : 'chybný'));
            } else {
                $message = 'Pronájem se nepodařilo označit';
            }
        }
        $this->showRecu($message);
    }

    public function addRefi() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - Nová smlouva');
            $this->render('main.tpl');
            return true;
        }
        $refi = array();
        if (isset($_REQUEST['addRefi'])) {
            foreach ($_REQUEST['description_refi'] as $row => $val) {
                $refi[$row] = array('description_refi' => $_REQUEST['description_refi'][$row],
                    'id_rent_refi' => $this->registry['sk_rent']->getRents(),
                    'id_room_refi' => $this->registry['sk_rent']->getRooms());
                if (isset($refi[$row]['id_rent_refi'][$_REQUEST['id_rent_refi'][$row]])) {
                    $refi[$row]['id_rent_refi'][$_REQUEST['id_rent_refi'][$row]]['sel'] = 'selected="selected"';
                }
                if (isset($refi[$row]['id_room_refi'][$_REQUEST['id_room_refi'][$row]])) {
                    $refi[$row]['id_room_refi'][$_REQUEST['id_room_refi'][$row]]['sel'] = 'selected="selected"';
                }
                if ($this->registry['sk_rent']->addRefi(array('description_refi' => $_REQUEST['description_refi'][$row],
                            'id_rent_refi' => $_REQUEST['id_rent_refi'][$row],
                            'id_room_refi' => $_REQUEST['id_room_refi'][$row]))) {
                    $refi[$row]['status'] = 'Smlouva byla úspěšně vytvořena';
                } else {
                    $refi[$row]['status'] = 'Smlouvu se nepodařilo vytvořit. ' . $this->registry['sk_rent']->error;
                }
            }
        } else {
            $refi[] = array('description_refi' => '',
                'id_rent_refi' => $this->registry['sk_rent']->getRents(),
                'id_room_refi' => $this->registry['sk_rent']->getRooms(),
                'status' => '');
        }
        $this->view->set('refi', $refi);
        $this->view->set('content', $this->view->fetch('Subparts/addRefi.tpl'));
        $this->view->set('title', 'Pronájmy - Nová smlouva');

        $this->render('main.tpl');
    }

    public function showRefi($message = '') {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - přehled smluv');
            $this->render('main.tpl');
            return true;
        }

        if (isset($_REQUEST['filter'])) {
            $_SESSION['showRefi_rent_id'] = $_REQUEST['first_name_rent'];
        } else {
            if (!isset($_SESSION['showRefi_rent_id'])) {
                $_SESSION['showRefi_rent_id'] = '';
            }
        }
        if (isset($_REQUEST['reset_filter'])) {
            $_SESSION['showRefi_rent_id'] = '';
        }

        if (isset($_SESSION['showRefi_rent_id']) && $_SESSION['showRefi_rent_id'] != '') {
            $page = 1;
            if (isset($this->args[0])) {
                $page = (int) $this->args[0];
                if ($page == 0) {
                    $page = 1;
                }
            }
            require_once 'class/pagination.php';
            $pag = new pagination($page, $this->sk_syst->row_show_syst, $this->registry['homelink'] . '/Rents/showRefi/');
            $refi = $this->registry['sk_rent']->getRefiIndRentWithPag($pag->first_row, $this->sk_syst->row_show_syst, $_SESSION['showRefi_rent_id']);
            $pag->totalcount = $refi['total_count'];
            $this->view->set('refi', $refi['rows']);
            $this->view->set('pagination', $pag->get_pagination());
        } else {
            $this->view->set('refi', array());
            $this->view->set('pagination', '');
        }

        $this->view->set('message', $message);
        $rents = $this->registry['sk_rent']->getRents();
        if (isset($rents[$_SESSION['showRefi_rent_id']])) {
            $rents[$_SESSION['showRefi_rent_id']]['sel'] = 'selected="selected"';
        }
        $this->view->set('first_name_rent', $rents);

        $this->view->set('content', $this->view->fetch('Subparts/showRefi.tpl'));
        $this->view->set('title', 'Pronájmy - přehled smluv');

        $this->render('main.tpl');
    }

    public function lockRefi() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - uzamknutí smlouvy');
            $this->render('main.tpl');
            return true;
        }
        if (isset($this->args[0])) {
            if ($this->registry['sk_rent']->lockRefi($this->args[0])) {
                $message = 'Smlouva byla úspěšně uzamknuta';
            } else {
                $message = 'Smlouvu se nepodařilo uzamknout. ' . $this->registry['sk_rent']->error;
            }
            $this->showRefi($message);
            return true;
        }
        $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
        $this->view->set('title', 'Pronájmy - uzamknutí smlouvy');
        $this->render('main.tpl');
    }

    public function unlockRefi() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - odemknutí smlouvy');
            $this->render('main.tpl');
            return true;
        }
        if (isset($this->args[0])) {
            if ($this->registry['sk_rent']->unlockRefi($this->args[0])) {
                $message = 'Smlouva byla úspěšně odemknuta';
            } else {
                $message = 'Smlouvu se nepodařilo odemknout. ' . $this->registry['sk_rent']->error;
            }
            $this->showRefi($message);
            return true;
        }
        $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
        $this->view->set('title', 'Pronájmy - odemknutí smlouvy');
        $this->render('main.tpl');
    }

    public function delRefi() {
        if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
            $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
            $this->view->set('title', 'Pronájmy - smazání smlouvy');
            $this->render('main.tpl');
            return true;
        }
        if (isset($this->args[0])) {
            if ($this->registry['sk_rent']->delFromSubj($this->args[0], SUBJ_REFI)) {
                $message = 'Smlouva byla úspěšně smazána';
            } else {
                $message = 'Smlouvu se nepodařilo smazat. ' . $this->registry['sk_rent']->error;
            }
            $this->showRefi($message);
            return true;
        }
        $this->view->set('content', 'Neoprávněný požadavek - máte nedostačující oprávnění');
        $this->view->set('title', 'Pronájmy - smazání smlouvy');
        $this->render('main.tpl');
    }

}
