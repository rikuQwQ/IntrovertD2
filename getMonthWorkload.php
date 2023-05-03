<?php
require_once('intr-sdk/autoload.php');

function countRepeatsOfDate($array)
{
    $repeats = array();
    foreach ($array as $date) {
        if (isset($repeats[$date])) {
            $repeats[$date]++;
        } else {
            $repeats[$date] = 1;
        }
    }

    $results = array();
    foreach ($repeats as $date => $count) {
        $results[] = array(
            'date' => $date,
            'count' => $count
        );
    }
    return $results;
}
function getMonthWorkload()
{
    $statuses = [57166318, 41477662];
    $customFiledID = 1525683;

    Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', 'secretKey');
    $api = new Introvert\ApiClient();
    $crm_user_id = [];
    $status = $statuses;
    $id = [];
    $ifmodif = "";
    $count = 5; // int | Количество запрашиваемых элементов
    $offset = 0; // int | смещение, относительно которого нужно вернуть элементы
    $DayLeadArray = array();

    try {
        $result = $api->lead->getAll($crm_user_id, $status, $id, $ifmodif, $count, $offset); // делаем первый запрос на $count количество записей
        while ($result['count'] == $count) { //проверяем, есть ли еще записи
            foreach ($result['result'] as $lead) { //запускаем цикл foreach для каждой сделки в ответе на запрос
                foreach ($lead['custom_fields'] as $customField) {
                    if ($customField['id'] == $customFiledID) {
                        array_push($DayLeadArray, $customField['values'][0]['value']);
                    }
                }
            }
            $offset = $offset + $count; // смещаем элемент, относительно которого нужно вернуть элементы
            $result = $api->lead->getAll($crm_user_id, $status, $id, $ifmodif, $count, $offset); //отправляем еще один запрос
        }
    } catch (Exception $e) {
        echo 'Exception when calling LeadApi->getAll: ', $e->getMessage(), PHP_EOL;
    }
    return countRepeatsOfDate($DayLeadArray);
}
getMonthWorkload();
?>