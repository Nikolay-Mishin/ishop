<?php
// контроллер валют - смена активной валюты и пересчет корзины в новую валюту

namespace app\controllers;

use app\models\Comment; // модель комментариев

class CommentController extends AppController {

    // метод добавления комментария
    public function addAction(){
        // если данные пришли ajax, загружаем вид фильтра и передаем соответствующие данные
        if($this->isAjax()){
            // $this->loadView('comment_tpl', compact('_POST'));
            debug($_POST);
            die;
        }
        debug([$_POST], 1);
        new Comment($_POST);
        redirect(); // перезапрашиваем текущую страницу
    }

}
