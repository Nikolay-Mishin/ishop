<?php
// контроллер валют - смена активной валюты и пересчет корзины в новую валюту

namespace app\controllers;

use app\models\Comment; // модель комментариев

class CommentController extends AppController {

    // метод добавления комментария
    public function addAction(){
        new Comment($_POST);
        redirect(); // перезапрашиваем текущую страницу
    }

}
