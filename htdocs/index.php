<?php

include_once 'config.php';
require_once('Loader.php');
$registry = new Registry();
dibi::connect(array('driver' => SQL_DRIVER,
    'host' => SQL_HOST,
    'username' => SQL_USERNAME,
    'password' => SQL_PASSWORD,
    'database' => SQL_DATABASE_NAME,
    'charset' => SQL_CHARSET));
dibi::addSubst('prefix', SQL_DB_PREFIX);
$registry['sk_syst'] = new SkSyst;
//dibi::query('select version()')->fetchSingle();                               
$registry['sk_user'] = new SkUser($registry);


if(is_dir(APPROOT . 'templates' . DS.$registry['sk_user']->user_info['theme_user'].DS)){
$registry['view'] =  new bTemplate(APPROOT . 'templates' . DS.$registry['sk_user']->user_info['theme_user'].DS);
}else{
    $registry['view'] =  new bTemplate(APPROOT . 'templates' . DS.'default'.DS);
}

$registry['supportedLangs'] = array('cs');
$router = new Router($registry);
$router->setPath(APPROOT . 'controllers');

$mail = new SkMail($registry);
//$mail->send_mail(array(array('mail'=>'tomas.kulhanek@skoldat.cz','name'=>'Tomáš Kulhánek')), 'Test zprávy', 'pokusný');
//var_dump($mail->error);
$registry['homelink'] = $router->getBaseUrl();
$registry['lang'] = $router->getClientLang();
$registry['sitename'] = $registry['sk_syst']->site_name_syst;


$registry['sk_rent'] = new SkRent($registry);

$router->delegate();


if (isset($_REQUEST))
    unset($_REQUEST);