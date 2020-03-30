<?php
// Контроллер продукта - карточка товара
/**
 * информация о текущем продукте
 * хлебные крошки
 * связанные товары
 * запись в куки запрошенного товара
 * просмотренные товары
 * галерея
 * модификации */

namespace app\controllers\admin;

class CalendarController extends AppController {

	public function indexAction(){
		$this->setStyle(
			'plugins/fontawesome-free/css/all.min',
			'plugins/fullcalendar/main.min',
			'plugins/fullcalendar-daygrid/main.min',
			'plugins/fullcalendar-timegrid/main.min',
			'plugins/fullcalendar-bootstrap/main.min'
		);
		$this->setScript(
			'plugins/moment/moment.min',
			'plugins/fullcalendar/main.min',
			'plugins/fullcalendar-daygrid/main.min',
			'plugins/fullcalendar-timegrid/main.min',
			'plugins/fullcalendar-interaction/main.min',
			'plugins/fullcalendar-bootstrap/main.min',
			'/calendar/calendar'
		);
		$this->setMeta('Calendar');
	}

}
