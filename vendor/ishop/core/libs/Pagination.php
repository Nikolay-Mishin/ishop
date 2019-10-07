<?php
// Класс пагинации (постраничной навигации)

namespace ishop\libs;

class Pagination{

    public $currentPage; // текущая страница
    public $perpage; // число записей на 1 странице
    public $total; // общее число записей
    public $countPages; // число старница для вывода всех записей
    public $uri; // строка запроса

    public function __construct($page, $perpage, $total){
        $this->perpage = $perpage;
        $this->total = $total;
        $this->countPages = $this->getCountPages(); // расчитываем необходимое число страниц для вывода всех записей
        $this->currentPage = $this->getCurrentPage($page); // получаем текущую страницу
        // получаем строку запроса, сформированную в нужном формате
        $this->uri = $this->getParams(); // http://ishop/category/casio?sort=name&filter=1,2,3&
    }

    // формирует html-разметку пагинации
    public function getHtml(){
        $back = null; // ссылка НАЗАД
        $forward = null; // ссылка ВПЕРЕД
        $startpage = null; // ссылка В НАЧАЛО
        $endpage = null; // ссылка В КОНЕЦ
        $page2left = null; // вторая страница слева
        $page1left = null; // первая страница слева
        $page1right = null; // первая страница справа
        $page2right = null; // вторая страница справа

        // startpage (1) back (-1) page2left (2) page1left (3) currentPage (4) page1right (5) page2right (6) forward (+1) endpage (7)

        // если текущая страница > 1 (находимся на 2 и тд странице)
        if( $this->currentPage > 1 ){
            $back = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage - 1). "'>&lt;</a></li>";
        }
        // если текущая страница < общего числа страниц (находимся не на последней странице)
        if( $this->currentPage < $this->countPages ){
            $forward = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 1). "'>&gt;</a></li>";
        }
        // ссылка на 1 страницу (> 3, тк мы показываем по 2 сслыки слева и справа - page2left (2) > startpage (1))
        if( $this->currentPage > 3 ){
            $startpage = "<li><a class='nav-link' href='{$this->uri}page=1'>&laquo;</a></li>";
        }
        // ссылка на последнюю страницу (page2right (6) < endpage (7))
        if( $this->currentPage < ($this->countPages - 2) ){
            $endpage = "<li><a class='nav-link' href='{$this->uri}page={$this->countPages}'>&raquo;</a></li>";
        }
        // ссылка на 2 страницы назад
        if( $this->currentPage - 2 > 0 ){
            $page2left = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage-2). "'>" .($this->currentPage - 2). "</a></li>";
        }
        // ссылка на 1 страницу назад
        if( $this->currentPage - 1 > 0 ){
            $page1left = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage-1). "'>" .($this->currentPage-1). "</a></li>";
        }
        // ссылка на 1 страницу вперед
        if( $this->currentPage + 1 <= $this->countPages ){
            $page1right = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 1). "'>" .($this->currentPage+1). "</a></li>";
        }
        // ссылка на 2 страницы вперед
        if( $this->currentPage + 2 <= $this->countPages ){
            $page2right = "<li><a class='nav-link' href='{$this->uri}page=" .($this->currentPage + 2). "'>" .($this->currentPage + 2). "</a></li>";
        }

        $left = $startpage . $back . $page2left . $page1left; // часть пагинации слева от текущей страницы
        $right = $page1right . $page2right . $forward . $endpage; // часть пагинации справа от текущей страницы

        return '<ul class="pagination">' . $left . '<li class="active"><a>' . $this->currentPage . '</a></li>' . $right . '</ul>';
    }

    // магический метод - переводит объект к строке
    // можем работать с переменной объекта одновременно как с объектом, так и со строкой (echo)
    public function __toString(){
        return $this->getHtml();
    }

    // расчитывает необходимое число страниц для вывода всех записей
    public function getCountPages(){
        return ceil($this->total / $this->perpage) ?: 1;
    }

    // получает текущую страницу
    public function getCurrentPage($page){
        // если текущая страница не передана или ее значение меньше 1, устанавливаем значение = 1
        if(!$page || $page < 1) $page = 1;
        // если передано значение текущей страницы > общего числа страниц, устанавливаем значение = общему числу страниц
        if($page > $this->countPages) $page = $this->countPages;
        return $page;
    }

    // расчитывает номер записи, с которого необходимо начинать выборку из БД
    public function getStart(){
        return ($this->currentPage - 1) * $this->perpage;
    }

    // получает параметры из строки запроса и формирует
    public function getParams(){
        $url = $_SERVER['REQUEST_URI']; // получаем строку запроса (http://ishop/category/casio?page=1&sort=name&filter=1,2,3)
        $url = explode('?', $url); // парсим (разбиваем) строку запроса по символу '?'
        /*
            [0] => 'http://ishop/category/casio',
            [1] => 'page=1&sort=name&filter=1,2,3'
        */
        $uri = $url[0] . '?'; // к концу строки до GET-параметров добавляем '?' (http://ishop/category/casio?)
        // если есть GET-параметры обрабатываем их
        if(isset($url[1]) && $url[1] != ''){
            $params = explode('&', $url[1]); // парсим строку GET-параметров по символу '&'
            /*
                [0] => 'page=1',
                [1] => 'sort=name',
                [2] => 'filter=1,2,3'
            */
            foreach($params as $param){
                // если параметр не содержит 'page=', прибавляем GET-параметр к строке запроса
                if(!preg_match("#page=#", $param)) $uri .= "{$param}&amp;";
            }
        }
        // http://ishop/category/casio?sort=name&filter=1,2,3&
        // page=1 - добавляется в ссылках в методе getHtml()
        return urldecode($uri);
    }

}