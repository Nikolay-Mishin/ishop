<?php

namespace app\controllers\admin;

use app\models\admin\Currency;
use ishop\App; // подключаем класс базовый приложения

class CurrencyController extends AppController{

    // определяет является ли переданная строка регулярным выраженияем
    // This doesn't test validity; but it looks like the question is Is there a good way of test if a string is a regex or normal string in PHP? and it does do that.
    protected function isRegex($str){
        return preg_match("/^\/[\s\S]+\/$/", $str);
    }

    // создает новый массив на из переданного ассоциативного массива и ключа, по значению которого необходимо сформировать новые ключи
    // сортирует массив в по ключам/значениям в зависимости от переданного типа $sort_key ('key'/'value')
    // $sort_flag - флаги сортировки
    protected function newArray($array, $key = '', $patterns = [], $sort_key = '', $sort_flag = SORT_NATURAL){
        // формируем массив
        $newArray = []; // новый массив
        foreach ($array as $k => $v){
            // если переданы паттерны для замены значений, производим замену для каждого переданного паттерна
            if($patterns){
                foreach ($patterns as $key_p => $pattern){
                    // если передан массив паттернов, работаем с ним
                    if(is_array($patterns)){
                        // $pattern[0] - паттерн для поиска совпадения
                        // $pattern[1] - паттерн для замены совпадения
                        // $pattern[2] - если передана не пустая строка, вызывает указанную пользовательскую функцию
                        // опеределяем вызываемую функцию на основе типа паттерна (regExp/string)
                        $func = $this->isRegex($pattern[0]) ? 'preg_replace' : 'str_replace';
                        // call_user_func_array - Вызывает callback-функцию с массивом параметров
                        $value = call_user_func_array($func, [$pattern[0], $pattern[1], $v[$key_p]]);
                        // call_user_func - Вызывает callback-функцию, заданную в первом параметре
                        $value = !isset($pattern[2]) ? $value : call_user_func($pattern[2], $value);
                    }
                    elseif(is_string($pattern)){
                        $value = call_user_func($pattern, $value);
                    }
                    $v[$key_p] = $value;
                }
            }
            // если передан ключ для задания новых значений ключей для исходного массива массива, берем эти значения
            // иначе используем ключи из исходного массива
            $newArray[$key ? $v[$key] : $k] = $v;
        }

        // сортируем массив
        switch($sort_key){
            case 'key':
                ksort($newArray, $sort_flag); // Сортирует массив по ключам
            break;
            case 'value':
                sort($newArray, $sort_flag); // Сортирует массив
            break;
        }

        return $newArray;

        /* $key = 'CharCode'
        Исходный массив
        [0] => Array
        (
            [CharCode] => EUR
            [Value] => 68.7710
        )

        Массив с новыми ключами
        [EUR] => Array
        (
            [CharCode] => EUR
            [Value] => 68.7710
        )
        */
        
        /* флаги сортировки
         * SORT_REGULAR - обычное сравнение элементов;
         * SORT_NUMERIC - числовое сравнение элементов
         * SORT_STRING - строковое сравнение элементов
         * SORT_LOCALE_STRING - сравнивает элементы как строки с учетом текущей локали. Используется локаль, которую можно изменять с помощью функции setlocale()
         * SORT_NATURAL - сравнение элементов как строк, используя естественное упорядочение, как в функции natsort()
         * SORT_FLAG_CASE - может быть объединен (побитовое ИЛИ) с SORT_STRING или SORT_NATURAL для сортировки строк без учета регистра.
         */
        // natsort() - Эта функция реализует алгоритм сортировки, при котором порядок буквенно-цифровых строк будет привычным для человека. Такой алгоритм называется "natural ordering"
        /*
        Обычная сортировка
        Array
        (
            [3] => img1.png
            [1] => img10.png
            [0] => img12.png
            [2] => img2.png
        )

        Сортировка natural order
        Array
        (
            [3] => img1.png
            [2] => img2.png
            [1] => img10.png
            [0] => img12.png
        )
        */
    }

    // возвращает список всех курсов на текущую дату (если дата не передана)
    // $date - '18.02.2020' или '18/02/2020'
    protected function getCourses($date = null){
        // если дата передана, форматируем ее
        $date = $date ? '?date_req=' . (new \DateTime($date))->format('d.m.Y') : ''; // '2020/02/18' => 18.02.2020
        $xml = simplexml_load_string(file_get_contents(CURRENCY_API . $date)); // получаем xml файл
        $courses = App::arrayToObject($xml); // декодируем xml объект в массив
        $courses = $this->newArray($courses['Valute'], 'CharCode', ['Value' => [',', '.', 'floatval']], 'key');
        return $courses ?: false;
    }

    /*
    $data = ['USD', 'EUR'];
    return [
        'USD' => 25.00000,
        'EUR' => 27.00000,
    ]
    */
    // возвращает список курсов по кодам переданных валют
    protected function getCoursesByCode($codeList){
        $courses = $this->getCourses(); // получаем список всех курсов на текущую дату
        if (!$courses) return false;

        $courses_curr = [];
        foreach ($courses as $code => $cours){
            // если валюта есть в переданном массиве - возьмем ее
            if(in_array($code, $codeList)){
                $courses_curr[$code] = $cours;
            }
        }
        return $courses_curr;
    }

    // метод получения списка с кодами активных валют
    protected function getCodeList(){
        return \R::getCol("SELECT code FROM currency");
    }

    // метод вычисления значения курса валюты для пересчета цен
    protected function getValue($course){
        return round(1 / $course, CURRENCY_ROUND);
    }

    // возвращает список курсов по кодам переданных валют
    protected function updateCourse($arr, $sql_part = 'value = ?, course = ?'){
        \R::exec("UPDATE currency SET $sql_part WHERE code = ?", $arr);
    }

    // возвращает список курсов по кодам переданных валют
    protected function checkChangeCourse($changeTitle = false){
        $currencies = \R::findAll('currency');// получаем список валют
        $change = false;
        $codeList = $this->getCodeList(); // получаем список с кодами активных валют
        $courses = $this->getCoursesByCode($codeList); // получаем список курсов для активных валют
        foreach ($currencies as $currency){
            // array_key_exists - Проверяет, присутствует ли в массиве указанный ключ или индекс
            // if (array_key_exists($currency->code, $courses)){
            // если валюта является небазовой, сравниваем ее значение с текущим курсом
            if ($currency->base == '0'){
                $course = $courses[$currency->code]['Value']; // текущий курс данной валюты
                $value = $this->getValue($course); // значение курса валюты для пересчета цен
                // для небазовых валют, присутствующих в списке курсов валют (code является одним из ключей массива $courses)
                // приверяем разницу между текущим курсом валюты и данным значением в БД
                if ($course != $currency->course || $value != $currency->value){
                    $change = true;
                    $sql_part = 'value = ?, course = ?';
                    $arr = [$value, $course, $currency->code];
                    if($changeTitle && $courses[$currency->code]['Name'] != $currency->title){
                        $sql_part = 'value = ?, course = ?, title = ?';
                        $arr = [$value, $course, $courses[$currency->code]['Name'], $currency->code];
                    }
                    $this->updateCourse($arr, $sql_part);
                }
            }
        }
        if($change) redirect(true);
        return $currencies;
    }

    // экшен просмотра списка валют
    public function indexAction(){
        $currencies = $this->checkChangeCourse(); // получаем список валют
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
            // вычисляем значение курса валюта для пересчета цен
            $currency->attributes['value'] = $this->getValue($data['course']); // значение курса валюты для пересчета цен
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
        $courses = $this->getCourses(); // получаем список всех курсов на текущую дату
        $codeList = $this->getCodeList(); // получаем список с кодами активных валют
        $this->setMeta('Новая валюта'); // устанавливаем мета-данные
        $this->set(compact('courses', 'codeList')); // передаем данные в вид
    }

}
