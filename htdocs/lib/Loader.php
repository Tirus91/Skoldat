<?php
/**
 * Loader.php
 * this file is contains __autoload() magic function definition
 *
 * @copyright Copyright (c) 2009 Igor Hlina
 * @license read LICENCE.txt
 *
 */

/**
 * Magic function for loading classes.
 * Tryes to find source file of the class in the 'lib' folder.
 * If successfull, include file.
 *
 * @param string $class
 */
function __autoload($class)
{
    // replace all underscores by directory separators
    $file = str_replace('_', DS, $class) . '.php';

    if (!file_exists(APPROOT . 'lib' . DS . $file)) {
        die("File '$file' not found within 'lib' directory!");
    }

    include_once($file);

    if (!class_exists($class, false) && !interface_exists($class, false)) {
        die("Class '$class' not defined in file '$file'!");
    }
}
