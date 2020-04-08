// Функция, выполняемая по окночании загрузки страницы
$(document).ready (() => {
    let uploads = $('#uploads'); // переменная. ссылка на textarea
    let upload = $('#upload'); // переменная. ссылка на кнопку submit для отправки данных на сервер
    let preview = $('.image-preview'); // переменная. ссылка на div с preview картинок
    let files; // переменная. будет содержать данные файлов
    let info; // переменная. будет содержать данные о загрузке файлов
    let id_pBar = 'pBar';
    
    upload.on ('change', function () {
        files = this.files; // заполняем переменную данными, при изменении значения поля file
        if (typeof files == 'undefined') return empty_files (uploads); // ничего не делаем если files пустой
        preview.html(''); // очищаем превью
        info = files_info (this, [uploads, preview], statuses[0]); // получаем массив данных с краткой информацией о файлах

        // => выводим сообщение в консоль
            //msg ('upload.on (change) => info => line 14', info);
        // /=> выводим сообщение в консоль
    });

    // AJAX запрос
    $('#upload-form').on ('submit', async e => {
        e.stopPropagation(); // остановка всех текущих JS событий
        e.preventDefault(); // остановка дефолтного события для текущего элемента

        if (typeof files == 'undefined') return; // ничего не делаем если files пустой
        let data = get_data (files, 1); // записываем в переменную данные для отправки в форму

        if (!'msg' in info) info.append ('msg', ''); // добавляем в массив инфо файлов
        else info['msg'] = '';
        // формируем сообщение с информацией
        for (let file of info) info['msg'] += `${file[0]}. ${file[1]} ${file[2]} ${statuses[1]}\n`;
        printMessage (uploads, statuses[1], info['msg']); // выводим сообщение в поле textarea

        //let _ajax = await ajax ('handler.php', 'POST', data, uploads, statuses[2], statuses[3], id_pBar);
        let _ajax_multy = ajax_multy ('handler.php', 'POST', data, uploads, statuses[2], statuses[3], id_pBar, true, files.length);

        // => выводим сообщение в консоль
            //msg ('$(#upload-form).on (submit) => await ajax() => line 34', _ajax);
            //msg ('$(#upload-form).on (submit) => ajax_multy (await ajax()) => line 35', _ajax_multy);
        // /=> выводим сообщение в консоль
    });
});