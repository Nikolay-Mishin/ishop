<?php
// контроллер валют - смена активной валюты и пересчет корзины в новую валюту

namespace app\controllers;

use app\models\Comment; // модель комментариев

class CommentController extends AppController {

    // метод добавления комментария
    public function addAction(){
        debug($_POST);
        debug(Comment::getByAlias($_POST['alias']));
        redirect(); // перезапрашиваем текущую страницу
    }

}
