<?php

namespace app\controllers\admin;

use ishop\Cache;

class CacheController extends AppController {

    public function indexAction(){
        $this->setMeta('Очистка кэша');
    }

    public function deleteAction(){
        $key = isset($_GET['key']) ? $_GET['key'] : null; // получаем ключ кэша
        $cache = Cache::instance(); // инициализируем кэш
        // проверяем совпадение полученного ключа кэша с ключом имеющегося кэша и удаляем кэш при совпадении
        switch($key){
            case 'category':
                $cache->delete('cats');
                $cache->delete('ishop_menu');
                break;
            case 'filter':
                $cache->delete('filter_group');
                $cache->delete('filter_attrs');
                break;
        }
        $_SESSION['success'] = 'Выбранный кэш удален';
        redirect();
    }

}