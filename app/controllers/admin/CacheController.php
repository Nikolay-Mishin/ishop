<?php

namespace app\controllers\admin;

use ishop\Cache;

class CacheController extends AppController {

    public function indexAction(): void {
        $this->setMeta('Очистка кэша');
    }

    public function deleteAction(): void {
        $key = isset($_GET['key']) ? $_GET['key'] : null; // получаем ключ кэша
        // проверяем совпадение полученного ключа кэша с ключом имеющегося кэша и удаляем кэш при совпадении
        switch ($key) {
            case 'category':
                Cache::delete('cats');
                Cache::delete('ishop_menu');
                break;
            case 'filter':
                Cache::delete('filter_group');
                Cache::delete('filter_attrs');
                break;
        }
        $_SESSION['success'] = 'Выбранный кэш удален';
        redirect();
    }

}
