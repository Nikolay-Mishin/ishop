<?php

namespace app\controllers\admin;

use app\models\admin\Currency;

class CurrencyController extends AppController{

    // экшен просмотра списка валют
    public function indexAction(){
        $currencies = $this->checkChangeCourse(); // получаем список валют
        foreach($currencies as $currency){
            $currency->update_at = (new \DateTime($currency->update_at))->format('d-m-Y');
        }
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
            $id = $this->getRequestID(false); // получаем id валюты
            $currency = new Currency(); // объект модели валют
            $data = $_POST; // данные из формы
            $currency->load($data); // загружаем данные в модель
            // конвертируем значения флага базовой валюты для записи в БД
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            // вычисляем значение курса валюта для пересчета цен
            $currency->attributes['value'] = $this->getValue($data['course']); // значение курса валюты для пересчета цен
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
            // вычисляем значение курса валюта для пересчета цен
            $currency->attributes['value'] = $this->getValue($data['course']);
            // валидируем данные
            if(!$currency->validate($data)){
                $currency->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            // сохраняем валюту в БД
            if($currency->save('currency')){
                $_SESSION['success'] = 'Валюта добавлена';
                redirect();
            }
        }
        $courses = Currency::getCourses(); // получаем список всех курсов на текущую дату
        $codeList = Currency::getCodeList(); // получаем список с кодами активных валют
        $this->setMeta('Новая валюта'); // устанавливаем мета-данные
        $this->set(compact('courses', 'codeList')); // передаем данные в вид
    }

    // возвращает список курсов по кодам переданных валют
    protected function checkChangeCourse($changeTitle = false){
        $currencies = Currency::getAll();// получаем список валют
        $change = false;
        $codeList = Currency::getCodeList(); // получаем список с кодами активных валют
        $courses = Currency::getCoursesByCode($codeList); // получаем список курсов для активных валют
        foreach ($currencies as $currency){
            // array_key_exists - Проверяет, присутствует ли в массиве указанный ключ или индекс
            // if (array_key_exists($currency->code, $courses)){
            // если валюта является небазовой, сравниваем ее значение с текущим курсом
            if ($currency->base == '0'){
                $course = $courses[$currency->code]['Value']; // текущий курс данной валюты
                $value = Currency::getValue($course); // значение курса валюты для пересчета цен
                $change = Currency::updateCourse($course, $value, $currency);
            }
        }
        if($change) redirect(true);
        return $currencies;
    }

}
