<?php

namespace app\controllers\admin;

use app\models\admin\Currency;

class CurrencyController extends AppController{

    // экшен просмотра списка валют
    public function indexAction(){
        $currencies = Currency::updateCourse(); // получаем список валют
        $this->setMeta('Валюты магазина'); // устанавливаем мета-данные
        $this->set(compact('currencies')); // передаем данные в вид
    }

    // экшен удаления валют
    public function deleteAction(){
        Currency::delete($this->getRequestID()); // удаляем валюту из БД
    }

    // экшен редактирования валют
    public function viewAction(){
        $currency = Currency::getById($this->getRequestID()); // получаем валюту из БД
        $courses = Currency::getCourses(); // получаем список всех курсов на текущую дату
        $codeList = Currency::getCodeList(); // получаем список с кодами активных валют
        $this->setMeta("Редактирование валюты {$currency->title}"); // устанавливаем мета-данные
        $this->set(compact('currency', 'courses', 'codeList')); // передаем данные в вид
    }

    // экшен редактирования валют
    public function editAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            new Currency($_POST, 'update', [$this->getRequestID(false)]); // объект модели валют
        }
    }

    // экшен добавления валют
    public function addAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            new Currency($_POST); // объект модели валют
        }
        $courses = Currency::getCourses(); // получаем список всех курсов на текущую дату
        $codeList = Currency::getCodeList(); // получаем список с кодами активных валют
        $this->setMeta('Новая валюта'); // устанавливаем мета-данные
        $this->set(compact('courses', 'codeList')); // передаем данные в вид
    }

}
