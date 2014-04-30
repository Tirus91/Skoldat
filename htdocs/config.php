<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
date_default_timezone_set('Europe/Prague'); //Nastavení časového pásma
define('DS', DIRECTORY_SEPARATOR); //Definování složkového oddělovače
define('APPROOT', realpath(dirname(__file__)) . DS); //Definování approot  
set_include_path(APPROOT . 'lib' . PATH_SEPARATOR . get_include_path());
setlocale(LC_ALL, 'czech'); // záleží na použitém systému
define('DEFAULTLANG', 'cs');
define('SK_LICENSE_TYPE', 'VIP');
define('SK_LICENSE_TO', '01.01.2027');
define('SK_LICENSE_TO_FOR', 'Licence pro #SCHOOL_NAME# vypršela dne ' . SK_LICENSE_TO);

//Připojení k databázi
define("SQL_DB_PREFIX", "sk_");
define("SQL_DRIVER", "mysql");
define("SQL_HOST", "localhost");
define("SQL_USERNAME", "skoldat_cz");
define("SQL_PASSWORD", "Tss");
define("SQL_DATABASE_NAME", "skoldat_cz");
define("SQL_CHARSET", "utf8");
define("SESSION_NAME", "skoldat");

define('SUBJ_USER', 1);
define('SUBJ_GROU', 2);
define('SUBJ_MELI', 3);
define('SUBJ_MEIT', 4);
define('SUBJ_ROOM', 5);
define('SUBJ_RENT', 6);
define('SUBJ_REFI', 7);
define('SUBJ_RECU', 8);


