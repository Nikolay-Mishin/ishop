<?php

namespace app\controllers;

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
			'js/calendar'
		);
		$this->setMeta('Calendar');
	}

}
