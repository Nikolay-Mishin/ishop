const statuses = ['loadstart', 'onprogress', 'success', 'error']; // Константа Статусов загрузки

let readed = 0; // переменная для хранения количества прочитанных файлов (прогресс загрузки)

// Функция выводит сообщение в консоль
const msg = (txt, msg = false, prefix = '', after = '') => {
    if (msg) { prefix = '=> '; after = '\n' }; console.log (prefix, txt, after);
    if (msg) console.log (msg);
};

// Функция округляет число в меньшую сторону и возвращает целое либо дробное значение в зависимости от параметра округления
const floor = (n, i = 0) => Math.floor (n * 10**i) / (1 * 10**i);

// Функция переводит байты в килобайты и мегабайты в зависимости от размера файла
const file_size = size => {
    if (size < 1024) return size + ' bytes';
    if (size < 1048576) return floor (size / 1024) + ' Kb';
    else return floor (size / 1048576, 2) + ' Mb';
};

// Функция выводит сообщение в textarea
const printMessage = (destination, status, text, json, multy, readed, i = 0, size = '') => {
    // если получен статус успешной загрузки
    if (status == 'success' && json) {
        if ('files' in json) {
            // обрабатываем файлы и формируем сообщение ответа
            let files_type = json.files.__proto__.constructor.name;
            if (files_type == 'Array') {
                for (let file of json.files) {
                    if ('error' in json.info[i]) status = 'Error!\n' + json.info[i].error; // записываем ошибку в статус
                    else if ('status' in json.info[i]) status = json.info[i].status; // получаем статус файла, если нет ошибки
                    size = file_size (file.size) + ' '; // получаем размер файла
                    // формируем сообщение ответа
                    i++;
                    if (!multy) text += `${i}. ${file.name} ${size}${status}\n`;
                    else {
                        if (readed > 1) text = $(destination[0]).html();
                        text += `${readed}. ${file.name} ${size}${status}\n`;
                    }
                }
            }
            else text = `Получен неверный тип данных (${files_type}) => Требуется (Array)`;

            // => выводим сообщение в консоль
                //msg ('printMessage (files_type) => json.files.__proto__.constructor.name => line 29', files_type);
            // /=> выводим сообщение в консоль
        }
    }
    else if (status == 'error') text = 'Произошла ошибка при загрузке файла.\n' + text; // если получен статус ошибки
    $(destination[0]).html (text); // выводим превью файла
};

// Функция проверяем получены ли от пользователя файлы
const empty_files = target => printMessage (target, 'empty_files', 'Ошибка!\nНе получены файлы для обработки.');

// Функция расчета прогресс бара для загрузки
const set_progress = (e, pBar = 'pBar', loaded, total, summary) => {
    let bg = $(`#${pBar}`).find ('.bg'),
        val = $(`#${pBar}`).find ('.val');
    if (e.lengthComputable) {
        let complete = (loaded / total * 100).toFixed();
        let text = loaded + ' / ' + total + ' (' + complete + '%)';
        if (!summary) {
            loaded = [e.loaded, file_size (e.loaded)];
            total = [e.total, file_size (e.total)];
            complete = (loaded[0] / total[0] * 100).toFixed();
            text = loaded[1] + ' / ' + total[1] + ' (' + complete + '%)';
        }
        bg.css ('width', complete + '%'); // меняем ширину полосы загрузки прогресс бара
        val.text (text); // выводим текстовое сообщение с информацией о загрузке в полосу прогресс бара

        // => выводим сообщение в консоль
            //if (typeof e.target.result == 'undefined') msg ('ajax (xhr) => set_progress => line 206, 207', e);
            //if (pBar == 'pBar') msg (`${loaded} read_image (onload) => e => line 95`, e);
        // /=> выводим сообщение в консоль
    }
};

// Функция формирует прогресс бар в div превью картинок
const build_progress = (pBar, i) => `<div id="${pBar}-${i}" class="progress-bar"><div class="bg"></div><div class="val">0%</div></div>`;

// Функция формирует превью картинок
const build_preview = (pBar, i, progress) => {
    if (progress) progress = build_progress (pBar, i);
    else progress = '';
    return  `<div class="adaptive_img"><img id="img-${i}" src=""></img>${progress}</div>`;
};

// Функция читает файл и выводит превью и прогресс бар
const read_image = (file, out, pBar = 'pBar', i = 0, progress, total) => {
    // Переменная для хранения превью картинки
    let preview_img = build_preview (pBar, i, progress);
    let reader = new FileReader(); // Создадим объект чтения файла
    // Функция отрабатывает при начале чтения файла
    reader.onloadstart = () => out.append (preview_img); // заполняем блок для превью;
    // Функция отрабатывает во время чтения файла до его окончательной загрузки
    if (progress) reader.onprogress = e => {
        set_progress (e, `${pBar}-${i}`);

        // => выводим сообщение в консоль 
            //msg (`${i} readImage (onprogress) => e => line 97`, e);
        // /=> выводим сообщение в консоль
    };
    // Функция отрабатывает после окончательной загрузки файла
    reader.onload = e => {
        out.find (`#img-${i}`).attr ('src', e.target.result); // заполняем ссылку на ресурс для картинки
        if (progress) {
            readed++;
            set_progress (e, pBar, readed, total, true); // отрисовываем общий прогресс бар
            $(`#${pBar}-${i}`).css ('width', 'auto'); // меняем ширину прогресс бара на ширину родительского блока превью
        }
    };
    reader.readAsDataURL (file);
};

const preview_image = (targets, out) => {
    let i = 0;
    for (let target of targets) {
        target.on ('change', function () {
            if (typeof this.files == 'undefined') return empty_files (this.files); // ничего не делаем если files пустой
            read_image (this.files[0], out[i]);
            i++;
        });
    }
};

// Функция формирует текстовое сообщение и превью картинок и выводит их в textarea и div
const files_info = (el, out, status, pBar = 'pBar', txt = '', i = 0, arr = []) => {
    if ('files' in el && el.files.length != 0) {
        // читаем файлы и добавляем информацию о них в сообщение ответа и массив для работы с информацией
        readed = 0;
        for (let file of el.files) {
            i++;
            read_image (file, out[1], pBar, i, true, el.files.length); // читаем файл
            if ('name' in file && 'size' in file) {
                txt += `${i}. ${file.name} ${file_size (file.size)} ${status}\n`; // добавляем инфо файла в сообщение
                arr.push ([i, file.name, file_size (file.size), status]); // добавляем инфо файла в массив
            }
        }
        printMessage (out[0], status, txt); // выводим сообщение в поле textarea
        return arr;
    }
};

// Функция формирует и возвращает объект FormData на основе объекта FileList
const get_data = (data, session_id) => {
    form_data = new FormData(); // Создадим объект данных формы
    $.each (data, (key, value) => form_data.append (key, value)); // Заполняем объект данных файлами в подходящем для отправки формате
    form_data.append ('session_id', session_id); // создадим в данных формы запись для идентификатора сессии
    return form_data;
};

// Функция Ajax запроса
const ajax = async (url, type, data, target, done, err, pBar = 'pBar', multy, total, i = 0) => {
    // ajax запрос
    const result = await $.ajax ({
        // функция обработки прогресса загрузки
        xhr: () => {
            let xhr = new window.XMLHttpRequest();
            if (!multy) xhr.upload.addEventListener ('progress', e => set_progress (e, pBar), false);
            else xhr.upload.addEventListener ('load', e => set_progress (e, pBar, i, total, true), false);
            return xhr;
        },
        url: url, // Скрипт обработчика
        type: type, // Тип запроса
        data: data, // Данные которые мы передаем
        cache: false, // В запросах POST отключено по умолчанию, но перестрахуемся
        contentType: false, // Тип кодирования данных мы задали в форме, это отключим
        processData: false, // Отключаем, так как передаем файл
        // функция успешного ответа сервера
        success: (respond, status, jqXHR) => {
            // => выводим сообщение в консоль
                // msg ('ajax (success) => respond => line 164', respond);
            // => выводим сообщение в консоль

            let json = $.parseJSON (respond); // конвертируем ответ запроса в json формат и записываем в переменную
            // выведем ответ в блок 'uploads'
            if (typeof json.error === 'undefined') printMessage (target, done, 'Файлы успешно загружены.\n', json, multy, i);
            else printMessage (target, done, json.error);

            // => выводим сообщение в консоль
                if ('entries' in data) data = Array.from (data.entries());
                msg ('ajax (success) => json => line 169', json);
                msg ('ajax (success) => Object => line 177', {
                    url: url,
                    type: type,
                    data: data,
                    target: target[0],
                    status: done,
                    statusText: {
                        json: json,
                        respond: respond,
                        status: status,
                        jqXHR: jqXHR
                    },
                    xhr: { progress: set_progress }
                });
            // /=> выводим сообщение в консоль
        },
        // функция ошибки ответа сервера
        error: (jqXHR, status, errorThrown) => {
            printMessage (target, err, 'ОШИБКА AJAX запроса: ' + status + ' ' + jqXHR.statusText); // выводим сообщение в поле textarea

            // => выводим сообщение в консоль
                msg ('ajax (error) => jqXHR => line 194', jqXHR);
                msg ('ajax (error) => Object => line 199', {
                    url: url,
                    type: type,
                    data: Array.from (data.entries()),
                    target: target[0],
                    status: err,
                    statusText: {
                        status: status,
                        statusText: jqXHR.statusText,
                        jqXHR: jqXHR
                    },
                    xhr: { progress: set_progress }
                });
            // /=> выводим сообщение в консоль
        }
    });
    return {ajax: result};
};

const ajax_multy = async (url, type, data, target, done, err, pBar = 'pBar', multy, total, i = 1, result = []) => {
    for (let file of data) {
        if (file[0] != 'session_id') {
            file = get_data (file, 2);
            result.push (await ajax (url, type, file, target, done, err, pBar, multy, total, i));
            i++;
        }
    }
    return result;
};