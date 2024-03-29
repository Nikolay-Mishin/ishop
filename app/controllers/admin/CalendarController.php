<?php

namespace app\admin\controllers;

class CalendarController extends AppController {

	public function indexAction(): void {
		$this->setStyle(
			'/plugins/fontawesome-free/css/all.min',
			'/plugins/fullcalendar/main.min',
			'/plugins/fullcalendar-daygrid/main.min',
			'/plugins/fullcalendar-timegrid/main.min',
			'/plugins/fullcalendar-bootstrap/main.min'
		);
		$this->setScript(
			'/plugins/moment/moment.min',
			'/plugins/fullcalendar/main.min',
			'/plugins/fullcalendar-daygrid/main.min',
			'/plugins/fullcalendar-timegrid/main.min',
			'/plugins/fullcalendar-interaction/main.min',
			'/plugins/fullcalendar-bootstrap/main.min',
			'calendar'
		);
		$this->setMeta('Календарь');
	}

}
