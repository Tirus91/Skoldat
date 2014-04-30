<?php

function skp_daily_service_delete_subj($time) {
    global $registry;
    $before = dibi::query('SELECT count(id_subj) FROM [:prefix:subj]')->fetchSingle();
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_USER, ' AND [id_subj] NOT IN (SELECT [id_user] FROM [:prefix:user])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_GROU, ' AND [id_subj] NOT IN (SELECT [id_grou] FROM [:prefix:grou])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_MELI, ' AND [id_subj] NOT IN (SELECT [id_meli] FROM [:prefix:meli])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_MEIT, ' AND [id_subj] NOT IN (SELECT [id_meit] FROM [:prefix:meit])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_ROOM, ' AND [id_subj] NOT IN (SELECT [id_room] FROM [:prefix:room])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_RENT, ' AND [id_subj] NOT IN (SELECT [id_rent] FROM [:prefix:rent])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_REFI, ' AND [id_subj] NOT IN (SELECT [id_refi] FROM [:prefix:refi])');
    dibi::query('DELETE FROM [:prefix:subj] WHERE [type_subj] = ', SUBJ_RECU, ' AND [id_subj] NOT IN (SELECT [id_recu] FROM [:prefix:recu])');
    $after = dibi::query('SELECT count(id_subj) FROM [:prefix:subj]')->fetchSingle();
    echo "$before    ->    $after";
    return true;
}