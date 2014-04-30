<?php

class Models_Menu {

    public function getMenu($registry) {
        $menu = '';
        $grou = $registry['sk_user']->getUserGrou();
        if ($grou == false) {
            return false;
        }
        foreach ($grou as $id_grou => $val) {
            $where[] = array('id_grou_meli = %i', $id_grou);
        }
        foreach (dibi::query('SELECT * FROM [:prefix:meli] WHERE %or', $where)->fetchAssoc('rank_meli') as $id_meli => $meli) {
            $meit = array();
            foreach (dibi::query('SELECT * FROM [:prefix:meit] WHERE %and', array(
                array('id_meli_meit = %i', $meli['id_meli']),
                array('perm_level_user_meit <= %i', $registry['sk_user']->user_info['groups'][$meli['id_grou_meli']]['permission_usgr'])
                    ), ' ORDER BY [rank_meit] DESC')->fetchAssoc('name_meit') as $a => $v) {
                $v = (array) $v;
                $v['selected'] = 'not_selected_agenda_list';
                $v['name_small_meit'] = mb_substr($v['name_meit'], 0, 23, 'UTF-8');
                
                if (isset($_REQUEST['route'])) {
                    if (('/' . $_REQUEST['route']) == $v['link_meit']) {
                        $v['selected'] = 'selected_agenda_list';
                    }
                }

                $meit[] = $v;
            }
            $registry['view']->set('meit', $meit);
            $registry['view']->set('name_meli', $meli['name_meli']);
            $registry['view']->set('color_meli', $meli['color_meli']);
            if (sizeof($meit) == 0) {
                continue;
            }
            $menu .= $registry['view']->fetch('Subparts/menu.tpl');
        }
        return $menu;
    }

}
