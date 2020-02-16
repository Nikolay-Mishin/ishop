<?php

namespace app\controllers\admin;

use app\models\admin\Currency;

class CurrencyController extends AppController{

    public function getUsd(){
        $courses = json_decode(file_get_contents(API_PB), true);
        if (!$courses) return false;
        $cours_curr_usd = false;
        foreach ($courses as $cours){
            if ($cours['ccy'] == 'USD'){
                $cours_curr_usd = $cours['buy'];
                break;
            }
        }
        return $cours_curr_usd;
    }

    public function getEur(){
        $courses = json_decode(file_get_contents(API_PB), true);
        if (!$courses) return false;
        $cours_curr_eur = false;
        foreach ($courses as $cours){
            if ($cours['ccy'] == 'EUR'){
                $cours_curr_eur = $cours['buy'];
                break;
            }
        }
        return $cours_curr_eur;
    }

    /*
    $data = ['USD', 'EUR'];
    return [
        'USD' => 25.00000,
        'EUR' => 27.00000,
    ]
    */
    protected function getCourses($data){
        $xml = simplexml_load_string(file_get_contents(API_CB_XML));
        $json = json_encode($xml);
        $courses_xml = json_decode($json, true);
        $courses_json = json_decode(file_get_contents(API_CB), true);

        $courses = [];
        foreach ($courses_xml['Valute'] as $cours){
            $courses[$cours['CharCode']] = $cours;
        }
        return ['xml' => $courses, 'json' => $courses_json];

        if (!$courses) return false;

        $courses_curr = [];
        foreach ($courses as $cours){
            // если валюта есть в переданном массиве - возьмем ее
            if(in_array($cours['ccy'], $data)){
                $courses_curr['ccy'] = $cours['buy'];
            }
        }
        return $courses_curr;
    }

    // экшен просмотра списка валют
    public function indexAction(){
        $codeList = \R::getCol("SELECT code FROM currency"); // получаем список с кодами валют
        debug($codeList);
        debug(API_CB_XML);
        $courses = $this->getCourses($codeList);
        debug($courses);
        
        /*
        $cours_curr_usd = $this->getUsd();
        $cours_curr_eur = $this->getEur();
        $usd = \R::getRow('select * from currency where code like ?', ['%USD%']);
        $eur = \R::getRow('select * from currency where code like ?', ['%EUR%']);
        if ($usd['value'] != $cours_curr_usd){
            \R::exec("UPDATE currency SET value = ? WHERE code = 'USD'", [$cours_curr_usd]);
        }
        debug($cours_curr_usd);
        debug($cours_curr_eur);
        debug($usd);
        debug($eur);
        */
        $currencies = \R::findAll('currency');// получаем список валют
        $this->setMeta('Валюты магазина'); // устанавливаем мета-данные
        $this->set(compact('currencies', 'courses')); // передаем данные в вид
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
