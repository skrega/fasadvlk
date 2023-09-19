$(function () {
    let add_form = $('.ajax-form');

    // Сброс значений полей
    $('.ajax-form input').on('blur', function () {
        $('.ajax-form input').removeClass('error');
        $('.error-name,.error-phone,.error-comments,.message-success').remove();
        $('.submit-feedback').val('Отправить заявку');
    });

    // Отправка значений полей
    let options = {
        url: feedback_object.url,
        data: {
            action: 'feedback_action',
            nonce: feedback_object.nonce
        },
        type: 'POST',
        dataType: 'json',
        beforeSubmit: function (xhr) {
            // При отправке формы меняем надпись на кнопке
            $('.submit-feedback').val('Отправляем...');
        },
        success: function (request, xhr, status, error) {

            if (request.success === true) {
                // Если все поля заполнены, отправляем данные и меняем надпись на кнопке
                $('.feedback-message-success').append('<div class="message-success">' + request.data + '</div>').slideDown()
                //add_form.after('<div class="message-success">' + request.data + '</div>').slideDown();
                $('.submit-feedback').val('Отправить заявку');
            } else {
                // Если поля не заполнены, выводим сообщения и меняем надпись на кнопке
                $.each(request.data, function (key, val) {
                    $('.art_' + key).addClass('error');
                    // $('.art_' + key).after('<span class="error-' + key + '">' + val + '</span>');
                });
                $('.submit-feedback').val('Ошибка');

            }
            // При успешной отправке сбрасываем значения полей
            $('.ajax-form')[0].reset();
            $('.ajax-form')[1].reset();
            $('.ajax-form')[2].reset();
            $('.ajax-form')[3].reset();
        },
        error: function (request, status, error) {
            $('.submit-feedback').val('Ошибка');
        }
    };
    // Отправка формы
    add_form.ajaxForm(options);
})