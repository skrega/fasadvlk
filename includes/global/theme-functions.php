<?php
// генерация ссылки для номера телефона
function getPhoneHref($phone)
{
    $phone = str_replace(array(' ', '–', '-'), '', $phone);
    return $phone;
}
