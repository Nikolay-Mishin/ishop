<?php

namespace app\controllers;

class MainController extends AppController {

    public function indexAction(){
        echo __METHOD__; // константа имя метода (app\controllers\MainController::indexAction)
    }

}