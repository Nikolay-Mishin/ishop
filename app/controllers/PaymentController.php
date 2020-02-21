<?php

namespace app\controllers;

use app\models\Payment; // модель оплаты

class PaymentController extends AppController {

	// отображает вид оплаты заказа
	public function payAction(){
		if(!empty($_POST)) Payment::setData($_POST);
		$pay = Payment::getData();
		if(!$pay) redirect('/payment/error');
		$this->setMeta("Оплата заказа №{$pay['id']}"); // устанавливаем мета-данные
		$this->set(compact('pay')); // передаем данные в вид
	}

	// отображает вид ошибки при оплате заказа
	public function errorAction(){
		$this->setMeta("Ошибка при оплате заказа"); // устанавливаем мета-данные
	}

	// отображает вид  успешной оплаты заказа
	public function successAction(){
		$this->setMeta("Оплата заказа произведена"); // устанавливаем мета-данные
	}

	// отображает вид ожидания зачисления оплаты заказа
	public function waitAction(){
		$this->setMeta("Оплата заказа ожидает зачисления"); // устанавливаем мета-данные
	}

}
