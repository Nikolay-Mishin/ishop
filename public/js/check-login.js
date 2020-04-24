$(document).ready(function () {
	var check_login = (function () {
		// Переменные модуля
		var isValid = true,
			form = $('form'),
			_button = $('#button'),
			formGroup,
			tooltip,
			email = 'mail@mail.com',
			password = '123',
			inputEmail;

		// Метод инициализации (запуска) модуля
		var init = function () {
			_sayHello(); // Запускаем модули которые должны стартовать при запуске модуля
			_setUpListeners(); // Запускаем прослушку событий
		};

		// Метод прослушки событий
		// В нем прослушиваем события на странице, например клики по кнопкам, и при срабатывании события запускаем нужный метод нашего модуля
		var _setUpListeners = function () {
			_button.on('click', _validateForm).on('click', _sendEmail);
		};

		// Приватные методы

		var _sayHello = function () {
			console.log('Hello from _sayHello()');
		};

		var _validateForm = function (e) {
			e.preventDefault();
			console.log('private method _validateForm - runs');

			var inputs = $('input, textarea');

			// Loop through each input field
			$.each(inputs, function (i, val) {
				var input = $(val),
					value = input.val().trim();
				formGroup = input.parents('.form__group');
				var textError = 'Введите ' + input.attr('placeholder').toLowerCase(),
					dataError = `
						<div class="notify no-paddings">
							<div class="notify no-radius-bottom notify--error">Неверный email или пароль</div>
							<div class="notify no-radius-top">
								<p>Введите верные данные для входа или воспользуйтесь
									<a href="#!">восстановлением пароля </a>, чтобы войти на сайт.</p>
							</div>
						</div>`;
				tooltip = $('<div class="notify notify--error">' + textError + '</div>');
				
				if (value.length === 0) {
					// Show errors
					_showError();
				}
				else {
					_hideError();
				}

				// Check if input type is 'email', not a textarea, and is Valid email
				if (!(input.is('textarea'))) {
					if (input.attr('type').toLowerCase() === 'email') {
						if (value !== '') {
							var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
							if (pattern.test(value)) {
								inputEmail = value;
								_hideError(true);
								console.log('Email is VALID');
							}
							else {
								textError = 'Неверный формат email';
								tooltip = $('<div class="notify notify--error">' + textError + '</div>'),
								_showError();
								console.log('Email is INVALID');
							}
						}
					}
				}
				
				if (input.attr('type').toLowerCase() === 'password') {
					if (value !== '') {
						console.log('postValid = ' + isValid);
						console.log('inputEmail = ' + inputEmail);
						console.log('value = ' + value);
						if (isValid === true) {
							if (value === password && inputEmail === email) {
								_hideError();
								console.log('Password and Email is CORRECT');
							}
							else {
								tooltip = $(dataError);
								_showError();
								console.log('Password or Email is INCORRECT');
							}
						}
						console.log('dataValid = ' + isValid);
					}
				}

				// Hide errors
				input.on('focus', function () {
					formGroup = input.parents('.form__group');
					_hideError();
				});
				input.on('keydown', function () {
					formGroup = input.parents('.form__group');
					_hideError();
				});
			});
		};

		var _sendEmail = function () {
			console.log('formValidation.isValid = ' + isValid);
			if (isValid === true) {
				console.log('Sending form!');
				form.submit();
			}
			else {
				console.log('Validation FAILED!');
			}
		};

		var _showError = function () {
			isValid = false;
			formGroup.find('.notify').remove();
			tooltip.appendTo(formGroup);
		};

		var _hideError = function (valid = false) {
			isValid = valid ? valid : isValid;
			formGroup.find('.notify').remove();
			console.log(formGroup);
		};

		// Возвращаем публичные медоты, которые будут доступны снаружи
		return {
			init
		};
	}());

	// Запускаем модуль
	check_login.init();
});
