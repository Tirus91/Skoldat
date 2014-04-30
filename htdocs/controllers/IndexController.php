<?php class IndexController extends Controllers_AbstractController
{
    public function index()
    {
       header('LOCATION: '.$this->registry['homelink'].'/Rents/showRecu');
    }
}
