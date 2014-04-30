<?php
/**
 * Router.php
 * this file is contains Router class definition
 *
 * @version 0.68
 * @copyright Copyright (c) 2009 Igor Hlina
 * @license read LICENCE.txt
 *
 */


/**
 * Router class
 * This class analyzes URL, acording to that runs adquate Controller.
 * Also detects clients preffered language
 *
 */
class Router
{

    /**
     * Registry object
     *
     * @var object
     */
    private $registry;


    /**
     * View object
     *
     * @var object
     */
    private $view;


    /**
     * Path to controllers source files
     *
     * @var string
     */
    private $ctrlPath;


    /**
     * Asign needed elements to internal variables
     *
     * @param object $registry
     */
    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->view     = $this->registry['view'];
    }


    /**
     * Set path to controllers
     *
     * @param string $path
     * @return void
     */
    public function setPath($path)
    {
        $path  = rtrim($path, '/\\');
        $path .= DS;

        if (!is_dir($path)) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }

        $this->ctrlPath = $path;
    }


    /**
     * Parse URL. Find out which controller and action to run
     * Store additional elements in URL to $args.
     * Gained informations return by reference
     *
     * @param string $controller
     * @param string $action
     * @param string $lang
     * @param string $args
     */
    private function parseUrl(&$file, &$controller, &$action, &$lang, &$args)
    {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        if (empty($route)) {
            $route = 'index';
        }

        // split route by slashes
        $route = trim($route, '/\\');
        $parts = explode('/', $route);

        // test if very first URL part is 2chars langcode
        $langtest = array_shift($parts);  // move first part out of array

        if (preg_match('/^[a-z]{2}$/i', $langtest)) {
            $lang = $langtest;
        } else {
            // not a langcode, put URL part back to array
            array_unshift($parts, $langtest);
        }

        // Find right controller
        $cmdPath = $this->ctrlPath;
        foreach ($parts as $part) {
            $part     = str_replace('-', '_', $part);
            $fullPath = $cmdPath . $part;

            // Check if part is name of a directory
            if (is_dir($fullPath)) {
                $cmdPath .= $part . DS;
                array_shift($parts);
                continue;

            } else {
                // try to find Controller source file
                $fullPath = $cmdPath . ucfirst($part);  // capitalize first char in filename
                if (is_file($fullPath . 'Controller.php')) {
                    // gained controller name
                    $controller = ucfirst($part) . 'Controller';    // compose controller name
                    array_shift($parts);
                    break;

                } else {
                    // no Controller of specified name find,
                    // continue with error
                    $controller = 'ErrorController';
                    break;
                }
            }
        }

        // if URL was empty, set default controller
        if (empty($controller)) { $controller = 'IndexController'; };

        // compose filename
        $file = $cmdPath . $controller. '.php';

        // gain action
        if ($controller == 'ErrorController') {
            $action = 'index';

        } else {
            $action = array_shift($parts);
            $action = str_replace('-', '_', $action);

            if (empty($action)) {
                // URL was not containing actionname
                $action = 'index';
            }
        }

        $args = $parts;  // return all other elements of URL in $args
    }


    /**
     * This method runs the application
     * parses URL, to get controller & action to run
     * if URL is invalid, passes control to ErrorController
     *
     * @return  void
     */
    public function delegate()
    {
        $this->parseUrl($file, $controller, $action, $lang, $args);
        $this->registry['args'] = $args;

        // File available?
        if (!file_exists($file)) {
            $controller = "ErrorController";
            $file = $this->ctrlPath . $controller. '.php';
            $action = 'index';
        }

        if (is_readable($file) == false) {
            throw new Exception("File '$file' cannot be read from filesystem");
        }

        // Include controller definition
        include($file);

        // Initiate the controller
        $controllerObj = new $controller($this->registry);

        // Action available?
        if (!method_exists($controllerObj, $action)) {
            // gained action name not exist
            unset($controllerObj);           // free uneeded Controller
            $this->registry->remove('args'); // free unneded URL arguments

            // pass control to ErrorController
            $controller = 'ErrorController';
            $action     = 'index';
            $file       = $this->ctrlPath . "$controller.php";
            include($file);

            // Initiate the class
            $controllerObj = new $controller($this->registry);
        }

        // Run Controller::action()
        $controllerObj->$action();
    }


    /**
     * Get working directory of the application in web_root
     *
     * @return  string
     */
    public function getBaseUrl()
    {
        //$dirName = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return("http://$_SERVER[SERVER_NAME]" );
    }


    /**
     * Resolves langcode, which will be used across application
     * Also gains Ctrl and action names for autodetection on indexpage
     *
     * @return string
     */
    public function getClientLang()
    {
        $this->parseUrl($file, $controller, $action, $lang, $args);

        // check if is there language identifier in URL
        if (empty($lang)) {
            // recieved URL without lang identifier
            // detect language from UA
            // do this only on index page

            // check if request to indexpage
            if ($controller == 'IndexController' && $action == 'index') {
                // we are on indexpage, resolve lang from UA
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { // test if UA contains langcode
                    // USER_AGENT language identifier present
                    // get language from it
                    $lang = $this->getUAlang();

                    // check if gained langcode is supported by application
                    if (!in_array($lang, $this->registry['supportedLangs'])) {
                        // this lang identifier is not supported by application
                        return DEFAULTLANG;

                    } else {
                        // rediredt only if resolved is not DEFAULTLANG
                        if ($lang != DEFAULTLANG) {
                            header('Location: ' . $this->getBaseUrl() . "/$lang", true, 303);
                            die;

                        } else {
                            return DEFAULTLANG;
                        }
                    }

                } else {
                    // langcode not present in UA (probably validator or robot)
                    // set default lang for enviroment
                    return DEFAULTLANG;
                }

            } else {
                // not on indexpage, break detection
                return DEFAULTLANG;
            }

        } else {
            // lang identifier was present in URL
            // check if identifier is supported by application
            if (!in_array($lang, $this->registry['supportedLangs'])) {
                // this lang URL identifier is not supported by application
                // redirect to indexpage
                header('Location: ' . $this->getBaseUrl(), true, 303);
                die;

            } else {
                return $lang;
            }
        }
    }


    /**
     * Gain langcode from user's browser.
     * Also aling slight diferences in this langcode.
     * If langcode is not recognized, return default value.
     *
     * @return string
     */
    private function getUaLang()
    {
        $ua_langs     = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $primary_lang = array_shift($ua_langs);

        switch (strtolower($primary_lang)) {
            case 'en':
            case 'en-us':
            case 'en-au':
            case 'en-ca':
            case 'en-ie':
            case 'en-gb':
                return('en');

            /*
            case 'de':
            case 'de-de':
            case 'de-at':
            case 'de-li':
            case 'de-lu':
            case 'de-ch':
                return('de');

            case 'pl':
            case 'pl-pl':
                return('pl');

            case 'cs':
            case 'cs-cz':
                return('cz');
            //*/

            case "sk":
            case "sk-sk":
                return('sk');

            default:
                return DEFAULTLANG;
        }
    }

}
