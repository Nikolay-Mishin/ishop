<?php

namespace app\controllers\admin;

use app\models\admin\Currency;

class CurrencyController extends AppController{

    // экшен просмотра списка валют
    public function indexAction(){
        $currencies = \R::findAll('currency');// получаем список валют
        $this->setMeta('Валюты магазина'); // устанавливаем мета-данные
        $this->set(compact('currencies')); // передаем данные в вид
    }

    // экшен удаления валют
    public function deleteAction(){
        $id = $this->getRequestID(); // получаем id фильтра
        $currency = \R::load('currency', $id); // получаем валюту из БД
        \R::trash($currency); // удаляем валюту из БД
        $_SESSION['success'] = "Изменения сохранены";
        redirect();
    }

    // экшен редактирования валют
    public function editAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            $id = $this->getRequestID(false); // получаем id валюты
            $currency = new Currency(); // объект модели валют
            $data = $_POST; // данные из формы
            $currency->load($data); // загружаем данные в модель
            // конвертируем значения флага базовой валюты для записи в БД
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            // валидируем данные
            if(!$currency->validate($data)){
                $currency->getErrors();
                redirect();
            }
            // сохраняем валюту в БД
            if($currency->update($id)){
                $_SESSION['success'] = "Изменения сохранены";
                redirect();
            }
        }

        $id = $this->getRequestID(); // получаем id валюты
        $currency = \R::load('currency', $id); // получаем валюту из БД
        $this->setMeta("Редактирование валюты {$currency->title}"); // устанавливаем мета-данные
        $this->set(compact('currency')); // передаем данные в вид
    }

    // экшен добавления валют
    public function addAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            $currency = new Currency(); // объект модели валют
            $data = $_POST; // данные из формы
            $currency->load($data); // загружаем данные в модель
            // конвертируем значения флага базовой валюты для записи в БД
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            // валидируем данные
            if(!$currency->validate($data)){
                $currency->getErrors();
                redirect();
            }
            // сохраняем валюту в БД
            if($currency->save('currency')){
                $_SESSION['success'] = 'Валюта добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая валюта'); // устанавливаем мета-данные
    }

}
