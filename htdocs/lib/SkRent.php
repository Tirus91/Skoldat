<?php

class SkRent {

    protected $registry;
    public $error;

    function __construct($registry) {
        $this->registry = $registry;
    }

    public function addRoom($room_info = array()) {
        $array = array(
            'location_room',
            'name_room',
            'description_room');
        foreach ($room_info as $key => $val) {
            if (!in_array($key, $array)) {
                unset($room_info[$key]);
            } else {
                if (empty($val)) {
                    $this->error = 'Jedna z položek je prázdná.';
                    return false;
                }
            }
            if ((bool) dibi::query('SELECT [id_room] FROM [:prefix:room] WHERE [name_room] = %s', $room_info['name_room'])->fetchSingle() != false) {
                $this->error = 'Místnost s tímto názvem již existuje';
                return false;
            }
            dibi::begin();
            try {
                dibi::query('INSERT INTO [:prefix:subj] ', array('type_subj' => SUBJ_ROOM));
                $room_info['id_room'] = dibi::getInsertId();
                dibi::query('INSERT INTO [:prefix:room] ', $room_info);
                dibi::commit();
                return true;
            } catch (DibiException $e) {
                dibi::rollback();
            }
        }
        return false;
    }

    public function delFromSubj($id_subj, $type_subj = SUBJ_ROOM) {
        if (!empty($id_subj)) {
            return (bool) dibi::query('DELETE FROM [:prefix:subj] WHERE %and', array('id_subj' => $id_subj, 'type_subj' => $type_subj));
        }
        return false;
    }

    public function editRoom($id_room, $room_info = array()) {
        $array = array(
            'location_room',
            'name_room',
            'description_room');
        foreach ($room_info as $key => $val) {
            if (!in_array($key, $array)) {
                unset($room_info[$key]);
            } else {
                if (empty($val)) {
                    $this->error = 'Jedna z položek je prázdná.';
                    return false;
                }
            }
            if ((bool) dibi::query('SELECT [id_room] FROM [:prefix:room] WHERE [name_room] = %s', $room_info['name_room'], ' AND [id_room] != %i', $id_room)->fetchSingle() != false) {
                $this->error = 'Místnost s tímto názvem již existuje';
                return false;
            }
            return (bool) dibi::query('UPDATE [:prefix:room] SET ', $room_info, ' WHERE [id_room] = %i', $id_room);
        }
        return false;
    }

    public function getRoomInfo($id_room) {
        return (array) dibi::fetch('SELECT * FROM [:prefix:room] WHERE [id_room] = %i', $id_room);
    }

    public function getRentInfo($id_rent) {
        return (array) dibi::fetch('SELECT * FROM [:prefix:rent] WHERE [id_rent] = %i', $id_rent);
    }

    public function editRent($id_rent, $rent_info = array()) {
        $array = array(
            'first_name_rent',
            'surname_rent',
            'phone_rent',
            'address_rent',
            'town_rent',
            'email_rent');
        foreach ($rent_info as $key => $val) {
            if (!in_array($key, $array)) {
                unset($rent_info[$key]);
            } else {
                if (empty($val)) {
                    $this->error = 'Jedna z položek je prázdná.';
                    return false;
                }
            }
            if ((bool) dibi::query('SELECT [id_rent] FROM [:prefix:rent] WHERE %and', $rent_info, ' AND [id_rent] != %i', $id_rent)->fetchSingle() != false) {
                $this->error = 'Smluvní strana s těmito údajemi již existuje';
                return false;
            }
            return (bool) dibi::query('UPDATE [:prefix:rent] SET ', $rent_info, ' WHERE [id_rent] = %i', $id_rent);
        }
        return false;
    }

    public function addRent($rent_info = array()) {
        $array = array(
            'first_name_rent',
            'surname_rent',
            'phone_rent',
            'address_rent',
            'town_rent',
            'email_rent');
        foreach ($rent_info as $key => $val) {
            if (!in_array($key, $array)) {
                unset($rent_info[$key]);
            } else {
                if (empty($val)) {
                    $this->error = 'Jedna z položek je prázdná.';
                    return false;
                }
            }
        }
        if ($rent_info['email_rent'] != '') {
            if (filter_var($rent_info['email_rent'], FILTER_VALIDATE_EMAIL) == false) {
                $this->error = 'Email je ve špatném formátu';
                return false;
            }
        }
        if ((bool) dibi::query('SELECT [id_rent] FROM [:prefix:rent] WHERE %and', $rent_info)->fetchSingle() != false) {
            $this->error = 'Místnost s tímto názvem již existuje';
            return false;
        }
        $rent_info['id_user_rent'] = $this->registry['sk_user']->user_info['id_user'];
        dibi::begin();
        try {
            dibi::query('INSERT INTO [:prefix:subj] ', array('type_subj' => SUBJ_RENT));
            $rent_info['id_rent'] = dibi::getInsertId();
            dibi::query('INSERT INTO [:prefix:rent] ', $rent_info);
            dibi::commit();
            return true;
        } catch (DibiException $e) {
            dibi::rollback();
        }
        return false;
    }

    public function getRooms($where = array()) {
        $rooms = array();
        if (!empty($where)) {
            $rooms = dibi::test('SELECT * FROM [:prefix:room] WHERE %and', $where)->fetchAssoc('id_room');
        } else {
            $rooms = dibi::query('SELECT * FROM [:prefix:room]')->fetchAssoc('id_room');
        }
        foreach ($rooms as $row => $inf) {
            $rooms[$row] = (array) $inf;
            $rooms[$row]['fullname'] = $inf->name_room . ' (' . $inf->location_room . ')';
        }
        return $rooms;
    }

    public function getRoomsInfo($offset, $limit, $where = array()) {
        $rooms = array();
        if (!empty($where)) {
            $sql = dibi::query('SELECT * FROM [:prefix:room] WHERE %and', $where);
        } else {
            $sql = dibi::query('SELECT * FROM [:prefix:room]');
        }
        $rooms['total_count'] = count($sql);
        if ($offset >= $rooms['total_count']) {
            $offset = 0;
        }
        $rooms['rooms'] = array();
        foreach ($sql->getIterator($offset, $limit) as $row => $inf) {
            $rooms['rooms'][$row] = (array) $inf;
            $rooms['rooms'][$row]['fullname'] = $inf->name_room . ' (' . $inf->location_room . ')';
            if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
                $rooms['rooms'][$row]['delete'] = '';
                $rooms['rooms'][$row]['edit'] = '';
            } else {
                $rooms['rooms'][$row]['delete'] = '<a class="remove" href="' . $this->registry['homelink'] . '/Rents/deleteRoom/' . $rooms['rooms'][$row]['id_room'] .
                        '" data-confirm="Opravdu si přejete smazat místnost ' . $rooms['rooms'][$row]['name_room'] . ' a všechny pronájmy spojené s touto místností?">&nbsp;&nbsp;&nbsp;</a>';
                $rooms['rooms'][$row]['edit'] = '<a class="edit" href="' . $this->registry['homelink'] . '/Rents/editRoom/' . $rooms['rooms'][$row]['id_room'] . '">&nbsp;&nbsp;</a>';
            }
        }
        return $rooms;
    }

    public function getRentsInfo($offset, $limit, $where = array()) {
        $rent = array();
        if (!empty($where)) {
            $sql = dibi::query('SELECT * FROM [:prefix:rent] WHERE %and', $where);
        } else {
            $sql = dibi::query('SELECT * FROM [:prefix:rent]');
        }
        $rent['total_count'] = count($sql);
        if ($offset >= $rent['total_count']) {
            $offset = 0;
        }
        $rent['rents'] = array();
        foreach ($sql->getIterator($offset, $limit) as $row => $inf) {
            $rent['rents'][$row] = (array) $inf;
            if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
                $rent['rents'][$row]['delete'] = '';
                $rent['rents'][$row]['edit'] = '';
            } else {
                $rent['rents'][$row]['delete'] = '<a class="remove" href="' . $this->registry['homelink'] . '/Rents/deleteRent/' . $rent['rents'][$row]['id_rent'] .
                        '" data-confirm="Opravdu si přejete smazat smluvní stranu ' . $rent['rents'][$row]['first_name_rent'] . ' ' . $rent['rents'][$row]['surname_rent'] . ' a její pronájmy?">';
                $rent['rents'][$row]['edit'] = '<a class="edit" href="' . $this->registry['homelink'] . '/Rents/editRent/' . $rent['rents'][$row]['id_rent'] . '">&nbsp;&nbsp;</a>';
            }
        }
        return $rent;
    }

    public function addRefi($refi) {
        $pom = array('description_refi', 'id_rent_refi', 'id_room_refi');
        foreach ($pom as $value) {
            if ($refi[$value] != '') {
                $pom_refi[$value] = $refi[$value];
            }
        }
        if (count($pom_refi) < 3) {
            $this->error = 'Nezadal(a) jste všechna pole';
            return false;
        }
        $refi = $pom_refi;
        $exists = dibi::query('SELECT [id_refi] FROM [:prefix:refi] WHERE %and', array(
                    'description_refi' => $refi['description_refi'],
                    'id_rent_refi' => $refi['id_rent_refi']))->fetchAll();
        if (sizeof($exists)) {
            $this->error = 'Již existuje smlouva s tímto popisem pro danou smluvní stranu.';
            return false;
        }
        $refi['dt_add_refi'] = new DateTime;
        $refi['id_user_refi'] = $this->registry['sk_user']->user_info['id_user'];

        dibi::begin();
        try {
            dibi::query('INSERT INTO [:prefix:subj] ', array('type_subj' => SUBJ_REFI));
            $refi['id_refi'] = dibi::getInsertId();
            dibi::query('INSERT INTO [:prefix:refi] ', $refi);
            dibi::commit();
            return true;
        } catch (DibiException $e) {
            $this->error = 'Technické problémy, opakujte akci později';
            dibi::rollback();
        }
        return false;
    }

    public function getRents() {
        $rents = array();
        $rents = dibi::query('SELECT * FROM [:prefix:rent]')->fetchAssoc('id_rent');
        foreach ($rents as $row => $inf) {
            $rents[$row] = (array) $inf;
        }
        return $rents;
    }

    public function getRefi() {
        $rents = array();
        $rents = dibi::query('SELECT * FROM [:prefix:refi]')->fetchAssoc('id_refi');
        foreach ($rents as $row => $inf) {
            $rents[$row] = (array) $inf;
        }
        return $rents;
    }

    public function getRefiIndRent($id_rent, $id_refi = null) {
        $rents = array();
        $rents = dibi::query('SELECT * FROM [:prefix:refi] WHERE [id_rent_refi] = %i', $id_rent, ' AND [closed_refi] = %i', 0)->fetchAssoc('id_refi');
        foreach ($rents as $row => $inf) {
            $rents[$row] = (array) $inf;
            if ($id_refi != null) {
                if ($id_refi == $rents[$row]['id_refi']) {
                    $rents[$row]['sel'] = 'selected="selected"';
                }
            }
        }
        return $rents;
    }

    public function getRefiIndRentWithPag($offset, $limit, $rent_id = null) {
        $refi = array();
        $sql = dibi::query('SELECT * FROM [:prefix:refi] WHERE [id_rent_refi] = %i', $rent_id);

        $tc = count($sql);
        $refi['total_count'] = $tc;
        if ($offset >= $tc) {
            $offset = 0;
        }
        $row = 0;
        $refi_info = array();
        foreach ($sql->getIterator($offset, $limit) as $record) {
            $refi['rows'][$row] = (array) $record;
            $refi['rows'][$row] = array_merge($refi['rows'][$row], $this->getRoomInfo($refi['rows'][$row]['id_room_refi']));
            $refi['rows'][$row] = array_merge($refi['rows'][$row], $this->getRentInfo($refi['rows'][$row]['id_rent_refi']));
            $refi['rows'][$row]['delete'] = '';
            $refi['rows'][$row]['lock'] = '';
            if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) != false) {
                $refi['rows'][$row]['delete'] = '<a class="remove" href="' . $this->registry['homelink'] . '/Rents/delRefi/' . $refi['rows'][$row]['id_refi'] . '" data-confirm="Opravdu si přejete smazat smlouvu ' . $refi['rows'][$row]['description_refi'] . '?">&nbsp;&nbsp;&nbsp;</a>';

                if ($refi['rows'][$row]['closed_refi'] == 1) {
                    $refi['rows'][$row]['lock'] = '<a class="accept" href="' . $this->registry['homelink'] . '/Rents/unlockRefi/' . $refi['rows'][$row]['id_refi'] . '" data-confirm="Opravdu si přejete odemknout smlouvu ' . $refi['rows'][$row]['description_refi'] . '?">&nbsp;&nbsp;&nbsp;</a>';
                } else {
                    $refi['rows'][$row]['lock'] = '<a class="lock" href="' . $this->registry['homelink'] . '/Rents/lockRefi/' . $refi['rows'][$row]['id_refi'] . '" data-confirm="Opravdu si přejete uzamknout smlouvu ' . $refi['rows'][$row]['description_refi'] . '?">&nbsp;&nbsp;&nbsp;</a>';
                }
            }
            $row++;
        }
        if($tc <1){
            $refi['rows']=array();
        }
        return $refi;
    }
    
    public function unlockRefi($id_refi){
        return (bool)dibi::query('UPDATE [:prefix:refi] SET [closed_refi] =%i',0,' WHERE [id_refi] = %i',$id_refi);
    }
    public function lockRefi($id_refi){
        return (bool)dibi::query('UPDATE [:prefix:refi] SET [closed_refi] =%i',1,' WHERE [id_refi] = %i',$id_refi);
    }

    public function addRecu($recu, $c = 0) {
        foreach ($recu as $key => $val) {
            if (empty($val)) {
                if ($key == 'date_to_recu' && $recu['day_recu'] != 'one') {
                    $this->error = 'Jedna ze zadaných položek je prázdná';
                    return false;
                }
            }
        }
        $recu['id_room_recu'] = dibi::query('SELECT [id_room_refi] FROM [:prefix:refi] WHERE [id_refi] =%i', $recu['id_refi_recu'])->fetchSingle();
        $recu['id_rent_recu'] = dibi::query('SELECT [id_rent_refi] FROM [:prefix:refi] WHERE [id_refi] =%i', $recu['id_refi_recu'])->fetchSingle();
        /*
          if ($recu['date_to_recu'] < $recu['date_from_recu'] && ($recu['day_recu'] != 'one' or $recu['day_recu'] != 'all')) {
          $pom = $recu['date_to_recu'];
          $recu['date_to_recu'] = $recu['date_from_recu'];
          $recu['date_from_recu'] = $pom;
          } */
        if ($recu['day_recu'] == 'all') {
            $recu = $this->prepareLongRecu($recu);
        } elseif ($recu['day_recu'] == 'one') {
            $recu['day_recu'] = $recu['date_from_recu'];
            $recu = array($recu);
        } else {
            $date = new DateTime($recu['date_from_recu']);
            if ($c != 0) {
                $date->modify('+' . ($c * 7) . ' day');
            }
            $to = new DateTime($recu['date_to_recu']);
            while ($date->format('D') != $recu['day_recu']) {
                $date->modify('+1 day');
            }
            $a = array();
            $i = 0;
            while ($date <= $to) {
                $a[$i] = $recu;
                $a[$i]['day_recu'] = $date->format('d.m.Y');
                $i++;
                $date->modify('+7 day');
            }
            $recu = $a;
        }
        foreach ($recu as $rec) {
            if ($this->writeRecu($rec) == false) {
                foreach ($recu as $recdel) {
                    $this->delRecu($recdel);
                }

                return false;
            }
        }
        return true;
    }

    private function writeRecu($recu) {
        $array = array(
            'id_rent_recu',
            'id_refi_recu',
            'id_room_recu',
            'id_user_recu',
            'time_from_recu',
            'time_to_recu',
            'day_recu');
        foreach ($recu as $col => $val) {
            if (!in_array($col, $array)) {
                unset($recu[$col]);
            }
        }
        if (strlen($recu['time_to_recu']) == 5) {
            $recu['time_to_recu'] .= ':00';
        }
        if (strlen($recu['time_from_recu']) == 5) {
            $recu['time_from_recu'] .= ':00';
        }
        if (strtotime($recu['day_recu']) == false) {
            $this->error = 'datum';
            return false;
        }
        $recu['id_room_recu'] = dibi::query('SELECT [id_room_refi] FROM [:prefix:refi] WHERE [id_refi] =%i', $recu['id_refi_recu'])->fetchSingle();
        $recu['id_rent_recu'] = dibi::query('SELECT [id_rent_refi] FROM [:prefix:refi] WHERE [id_refi] =%i', $recu['id_refi_recu'])->fetchSingle();
        $day = new DateTime($recu['day_recu']);
        $recu['day_recu'] = $day->format('Y-m-d');
        $recu['id_user_recu'] = $this->registry['sk_user']->user_info['id_user'];
        $cross = array();
        $exists = dibi::query('SELECT [id_recu] FROM [:prefix:recu] WHERE %and', array(
                    'day_recu' => $recu['day_recu'],
                    'id_room_recu' => $recu['id_room_recu'],
                    array('time_to_recu >%t', $recu['time_from_recu']),
                    array('time_from_recu<%t', $recu['time_to_recu'])))->fetchAll();
        if (sizeof($exists)) {
            $this->error = 'Pronájem se kříží s jiným.';
            return false;
        }
        dibi::begin();
        try {
            dibi::query('INSERT INTO [:prefix:subj] ', array('type_subj' => SUBJ_RECU));
            $recu['id_recu'] = dibi::getInsertId();
            dibi::query('INSERT INTO [:prefix:recu] ', $recu);
            dibi::commit();
            return true;
        } catch (DibiException $e) {
            $this->error = 'insert';
            dibi::rollback();
        }
        return false;
    }

    public function delRecu($recu) {
        $array = array(
            'id_rent_recu',
            'id_room_recu',
            'id_user_recu',
            'time_from_recu',
            'time_to_recu',
            'day_recu');
        foreach ($recu as $col => $val) {
            if (!in_array($col, $array)) {
                unset($recu[$col]);
            }
        }
        if (strtotime($recu['day_recu']) == false) {
            return false;
        }
        $day = new DateTime($recu['day_recu']);
        $recu['day_recu'] = $day->format('Y-m-d');
        return (bool) dibi::query('DELETE FROM [:prefix:recu] WHERE %and', $recu);
    }

    private function prepareLongRecu($recu) {
        $day = array();
        $from = new DateTime($recu['date_from_recu']);
        $day[] = array(
            'id_rent_recu' => $recu['id_rent_recu'],
            'id_room_recu' => $recu['id_room_recu'],
            'id_refi_recu' => $recu['id_refi_recu'],
            'id_user_recu' => $this->registry['sk_user']->user_info['id_user'],
            'time_from_recu' => $recu['time_from_recu'],
            'time_to_recu' => '23:59:59',
            'day_recu' => $recu['date_from_recu']);
        $from->modify('+1 day');
        $to = new DateTime($recu['date_to_recu']);
        while ($from < $to) {
            $day[] = array(
                'id_rent_recu' => $recu['id_rent_recu'],
                'id_room_recu' => $recu['id_room_recu'],
                'id_refi_recu' => $recu['id_refi_recu'],
                'id_user_recu' => $this->registry['sk_user']->user_info['id_user'],
                'time_from_recu' => '00:00:01',
                'time_to_recu' => '23:59:59',
                'day_recu' => $from->format('d.m.Y'));
            $from->modify('+1 day');
        }
        $day[] = array(
            'id_rent_recu' => $recu['id_rent_recu'],
            'id_room_recu' => $recu['id_room_recu'],
            'id_refi_recu' => $recu['id_refi_recu'],
            'id_user_recu' => $this->registry['sk_user']->user_info['id_user'],
            'time_from_recu' => '00:00:01',
            'time_to_recu' => $recu['time_to_recu'],
            'day_recu' => $recu['date_to_recu']);
        unset($from);
        unset($to);
        return $day;
    }

    public function showRecuWithInfo($offset, $limit, $date_from = null, $date_to = null, $id_room = null) {
        $curdate = new DateTime();
        $a = array();
        if (empty($date_to) && !empty($date_from)) {
            $a[] = array('day_recu >= %t ', $date_from);
        } elseif (empty($date_to) && empty($date_from)) {
            $a[] = array('day_recu=%sql', 'CURRENT_DATE()');
        } else {
            $a[] = array('day_recu >= %t', $date_from);
            $a[] = array('day_recu <= %t', $date_to);
        }
        if (!empty($id_room)) {
            $a[] = array('id_room_recu =', $id_room);
        }
        if (empty($a)) {
            $a = array('1' => '1');
        }
        $sql = dibi::query('SELECT * FROM [:prefix:recu] 
                            LEFT JOIN [:prefix:rent] ON [id_rent] = [id_rent_recu]
                            LEFT JOIN [:prefix:room] ON [id_room] = [id_room_recu] WHERE %and', $a, ' ORDER BY [day_recu] ASC,[time_from_recu] ASC ');
        $tc = count($sql);
        if ($offset >= $tc) {
            $offset = 0;
        }
        $row = 0;
        $recu_info = array();
        // echo "offset = {$offset}    limit = {$limit}   tc = {$tc}";
        foreach ($sql->getIterator($offset, $limit) as $record) {
            $recu_info[$row] = (array) $record;
            $from = new DateTime($recu_info[$row]['day_recu'] . ' ' . $recu_info[$row]['time_from_recu']);
            $to = new DateTime($recu_info[$row]['day_recu'] . ' ' . $recu_info[$row]['time_to_recu']);
            $recu_info[$row]['day_recu'] = $to->format('d.m.Y');
            $recu_info[$row]['time_from_recu'] = $from->format('H:i');
            $recu_info[$row]['time_to_recu'] = $to->format('H:i');
            if ($curdate < $from) {
                $recu_info[$row]['sel'] = 'future';
            } elseif ($curdate > $from) {
                $recu_info[$row]['sel'] = 'past';
            }
            if ($to > $curdate && $curdate > $from) {
                $recu_info[$row]['sel'] = 'running';
            }
            $recu_info[$row]['fulltext'] = $recu_info[$row]['first_name_rent'] . ' ' . $recu_info[$row]['surname_rent'] . ' dne ' . $recu_info[$row]['day_recu'] . ' od ' . $recu_info[$row]['time_from_recu'] . ' do ' . $recu_info[$row]['time_to_recu'];
            if ($recu_info[$row]['status_recu'] == 2) {
                $recu_info[$row]['status_text_recu'] = "Pronájem se " . ($recu_info[$row]['status_recu'] == 0 ? 'ne' : '') . "uskutečnil";
                if ($recu_info[$row]['sel'] == 'running') {
                    $recu_info[$row]['status_recu'] = '';
                    $recu_info[$row]['status_recu'] .= '<a href="' . $this->registry['homelink'] . '/Rents/setStatusRecu/' . $recu_info[$row]['id_recu'] . '/accept" title="Potvrdit pronájem ' . $recu_info[$row]['fulltext'] . '" data-confirm="Opravdu se pronájem ' . $recu_info[$row]['fulltext'] . ' uskutečnil?" class="accept">&nbsp;&nbsp;&nbsp;</a>';
                    $recu_info[$row]['status_recu'] .= '&nbsp;';
                    $recu_info[$row]['status_recu'] .= '<a href="' . $this->registry['homelink'] . '/Rents/setStatusRecu/' . $recu_info[$row]['id_recu'] . '/decline" title="Zamítnout pronájem ' . $recu_info[$row]['fulltext'] . '" data-confirm="Opravdu se pronájem ' . $recu_info[$row]['fulltext'] . ' neuskutečnil?" class="decline">&nbsp;&nbsp;&nbsp;</a>';
                    $recu_info[$row]['delete'] = '&nbsp;&nbsp;&nbsp;&nbsp;';
                } elseif ($recu_info[$row]['sel'] == 'past') {
                    $recu_info[$row]['status_recu'] = '<span class="lock" title="Pronájem propadl.">&nbsp;&nbsp;&nbsp;</span>';
                    $recu_info[$row]['delete'] = '&nbsp;&nbsp;&nbsp;&nbsp;';
                } else {
                    if ($this->registry['sk_user']->checkPrivileg($this->registry['sk_user']->id_subj['RENT_GROU'], $this->registry['sk_user']->user_info['id_user'], 2) == false) {
                        $recu_info[$row]['delete'] = '&nbsp;&nbsp;&nbsp;&nbsp;';
                    } else {
                        $recu_info[$row]['delete'] = '<a class="remove" href="' . $this->registry['homelink'] . '/Rents/deleteRecu/' . $recu_info[$row]['id_recu'] . '" data-confirm="Opravdu si přejete smazat pronájem ' . $recu_info[$row]['fulltext'] . '?">&nbsp;&nbsp;&nbsp;</a>&nbsp;';
                    }
                    $recu_info[$row]['status_recu'] = '<span class="lock" title="Pronájem je v plánu.">&nbsp;&nbsp;&nbsp;</span>';
                }
            } else {
                $recu_info[$row]['status_text_recu'] = "Pronájem se " . ($recu_info[$row]['status_recu'] == 0 ? 'ne' : '') . "uskutečnil";
                $recu_info[$row]['status_recu'] = '<span class="lock" title="Pronájem se ' . ($recu_info[$row]['status_recu'] == 0 ? 'ne' : '') . 'uskutečnil">&nbsp;&nbsp;&nbsp;</span>';
                $recu_info[$row]['delete'] = '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            unset($date);
            $row++;
        }
        return array('total_count' => $tc, 'rows' => $recu_info);
    }

    public function showRecuWithInfoCSV($date_from = null, $date_to = null, $id_room = null) {
        $curdate = new DateTime();
        $a = array();
        if (empty($date_to) && !empty($date_from)) {
            $a[] = array('day_recu >= %t ', $date_from);
        } elseif (empty($date_to) && empty($date_from)) {
            $a[] = array('day_recu=%sql', 'CURRENT_DATE()');
        } else {
            $a[] = array('day_recu >= %t', $date_from);
            $a[] = array('day_recu <= %t', $date_to);
        }
        if (!empty($id_room)) {
            $a[] = array('id_room_recu =', $id_room);
        }
        if (empty($a)) {
            $a = array('1' => '1');
        }
        $sql = dibi::query('SELECT * FROM [:prefix:recu] 
                            LEFT JOIN [:prefix:rent] ON [id_rent] = [id_rent_recu]
                            LEFT JOIN [:prefix:room] ON [id_room] = [id_room_recu] WHERE %and', $a, ' ORDER BY [day_recu] ASC,[time_from_recu] ASC ');

        $row = 0;
        $recu_info = array();

        foreach ($sql as $record) {
            $recu_info[$row] = (array) $record;
            $from = new DateTime($recu_info[$row]['day_recu'] . ' ' . $recu_info[$row]['time_from_recu']);
            $to = new DateTime($recu_info[$row]['day_recu'] . ' ' . $recu_info[$row]['time_to_recu']);
            $recu_info[$row]['day_recu'] = $to->format('d.m.Y');
            $recu_info[$row]['time_from_recu'] = $from->format('H:i');
            $recu_info[$row]['time_to_recu'] = $to->format('H:i');
            if ($curdate < $from) {
                $recu_info[$row]['sel'] = 'future';
            } elseif ($curdate > $from) {
                $recu_info[$row]['sel'] = 'past';
            }
            if ($to > $curdate && $curdate > $from) {
                $recu_info[$row]['sel'] = 'running';
            }
            $recu_info[$row]['fulltext'] = $recu_info[$row]['first_name_rent'] . ' ' . $recu_info[$row]['surname_rent'] . ' dne ' . $recu_info[$row]['day_recu'] . ' od ' . $recu_info[$row]['time_from_recu'] . ' do ' . $recu_info[$row]['time_to_recu'];
            $recu_info[$row]['status_recu'] = ($recu_info[$row]['status_recu'] == 0 ? 'neuskutečněno' : ($recu_info[$row]['status_recu'] == 1 ? 'uskutečněno' : 'v plánu'));

            unset($date);

            $row++;
        }
        return $recu_info;
    }

    public function setStatus($id_recu = null, $status_code = 2) {
        if ($id_recu != null) {
            return (bool) dibi::query('UPDATE [:prefix:recu] SET [status_recu] = %i', $status_code, ' WHERE %and', array('id_recu' => $id_recu, 'status_recu' => 2));
        }
    }

}
