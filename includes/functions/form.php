<?php
add_action('wp_enqueue_scripts', 'art_feedback_scripts');
/**
 * Подключение файлов скрипта формы обратной связи
 *
 * @see https://wpruse.ru/?p=3224
 */
function art_feedback_scripts()
{

    // Обрабтка полей формы
    wp_enqueue_script('jquery-form');

    // Подключаем файл скрипта
    wp_enqueue_script(
        'feedback',
        get_stylesheet_directory_uri() . '/assets/js/mail.js',
        array('jquery'),
        1.0,
        true
    );

    // Задаем данные обьекта ajax
    wp_localize_script(
        'feedback',
        'feedback_object',
        array(
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('feedback-nonce'),
        )
    );
}

add_action('wp_ajax_feedback_action', 'ajax_action_callback');
add_action('wp_ajax_nopriv_feedback_action', 'ajax_action_callback');
/**
 * Обработка скрипта
 *
 * @see https://wpruse.ru/?p=3224
 */
function ajax_action_callback()
{

    // Массив ошибок
    $err_message = array();

    // Проверяем nonce. Если проверкане прошла, то блокируем отправку
    if (!wp_verify_nonce($_POST['nonce'], 'feedback-nonce')) {
        wp_die('Данные отправлены с левого адреса');
    }

    // Проверяем на спам. Если скрытое поле заполнено или снят чек, то блокируем отправку
    if (false === $_POST['art_anticheck'] || !empty($_POST['art_submitted'])) {
        wp_die('Блокировка отправки');
    }

    // Проверяем полей имени, если пустое, то пишем сообщение в массив ошибок
    if (empty($_POST['art_name']) || !isset($_POST['art_name'])) {
        $err_message['name'] = 'Пожалуйста, введите ваше имя.';
    } else {
        $art_name = sanitize_text_field($_POST['art_name']);
    }

    // Проверяем поле телефон, если пустое, то пишем сообщение в массив ошибок
    if (empty($_POST['art_phone']) || !isset($_POST['art_phone'])) {
        $err_message['phone'] = 'Пожалуйста, введите ваш номер.';
    } else {
        $art_phone = sanitize_text_field($_POST['art_phone']);
    }

    // Проверяем поле checkbox, если пустое, то пишем сообщение в массив ошибок
    if (empty($_POST['whatsapp']) || !isset($_POST['whatsapp'])) {
        $art_whatsapp = '';
    } else {
        $art_whatsapp = sanitize_text_field($_POST['whatsapp']);
    }
    if (empty($_POST['popup-whatsapp']) || !isset($_POST['popup-whatsapp'])) {
        $popup_whatsapp = '';
    } else {
        $popup_whatsapp = sanitize_text_field($_POST['popup-whatsapp']);
    }

    if (empty($_POST['buy_whatsapp']) || !isset($_POST['buy_whatsapp'])) {
        $buy_whatsapp = '';
    } else {
        $buy_whatsapp = sanitize_text_field($_POST['buy_whatsapp']);
    }
    
    
    if (empty($_POST['container']) || !isset($_POST['container'])) {
        $container = '';
    } else {
        $container = sanitize_text_field($_POST['container']);
    }
    if (empty($_POST['price']) || !isset($_POST['price'])) {
        $price = '';
    } else {
        $price =  'Цена: ' . $_POST['price'];
    }

    if (empty($_POST['tarif']) || !isset($_POST['tarif'])) {
        $tarif = '';
    } else {
        $tarif =  sanitize_text_field($_POST['tarif']);
    }


    if ($art_whatsapp) {
        $whatsapp = $art_whatsapp;
    } else {
        $whatsapp = $popup_whatsapp;
    }
    if($art_whatsapp == '' && $popup_whatsapp == ''){
        $whatsapp = $buy_whatsapp;
    }

    $art_subject = 'Новое письмо с сайта REFKINGS!';
    // Проверяем массив ошибок, если не пустой, то передаем сообщение. Иначе отправляем письмо
    if ($err_message) {
        wp_send_json_error($err_message);
    } else {
        // Указываем адресата
        $email_to = '';
        // Если адресат не указан, то берем данные из настроек сайта
        if (!$email_to) {
            // $email_to = get_option('admin_email');
            $email_to = 'skorpionn440@gmail.com';
        }

        $body    = "Имя: $art_name \nТелефон: $art_phone \n$whatsapp\n\n$tarif$container\n$price";
        $headers = 'From: ' . $art_name . ' <' . $email_to . '>' . "\r\n" . 'Reply-To: ' . $email_to;

        // Отправляем письмо
        wp_mail($email_to, $art_subject, $body, $headers);

        // Отправляем сообщение об успешной отправке
        $message_success = 'Собщение отправлено. В ближайшее время я свяжусь с вами.';
        wp_send_json_success($message_success);
    }

    // На всякий случай убиваем еще раз процесс ajax
    wp_die();
}
