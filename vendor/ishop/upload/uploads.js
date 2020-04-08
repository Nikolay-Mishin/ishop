// Функция, выполняемая по окночании загрузки страницы
$(document).ready (() => {
    let uploads = [$('#file'), $('#file-2')];
    let previews = [$('#image-preview-1'), $('#image-preview-2')];
    preview_image (uploads, previews);
});