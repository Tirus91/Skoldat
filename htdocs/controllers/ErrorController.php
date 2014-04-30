<?php /**
 * ErrorController.php
 * this file is contains ErrorController class definition
 *
 * @copyright Copyright (c) 2009 Igor Hlina
 * @license read LICENCE.txt
 *
 */
/**
 * Class for handlig #404 errors
 *
 */
class ErrorController extends Controllers_AbstractController
{
    /**
     * index Action()
     * display formated error message
     *
     */
    public function index()
    {
        $localizator = new Localize();
        $lang = $this->registry['lang'];
        $title = $localizator->getLocalizedMessage('Error404', $lang);
        $req_page = urldecode("http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]");
        $this->view->set('lang', $lang);
        $this->view->set('title', $title);
        $this->view->set('sitename', $this->registry['sitename']);
        $this->view->set('req_page', $req_page);
        $this->view->set('content', $this->view->fetch('ErrorMessages/404_' . $this->registry['lang'] . '.tpl'));
        // render error page
        header("HTTP/1.0 404 Not Found");
        $this->render('error.tpl');
        die;
    }
}
