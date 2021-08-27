<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("learning");
?>

<?

use Bitrix\Main\Application;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

Loader::includeModule('highloadblock');
Loader::includeModule('iblock');

$hlBlockId = 4; //id hl-блока

$hlblock = HighloadBlockTable::getById($hlBlockId)->fetch();

/** @var \Bitrix\Main\Entity\Base $entity */
$entity = HighloadBlockTable::compileEntity($hlblock);

/** @var \Bitrix\Main\Entity\DataManager $dataClass */
$dataClass = $entity->getDataClass();

Application::getConnection()->startTracker();

$result = $dataClass::getList([
    'select' => [
        //все поля элемента hl-блока
        '*',
        //нужные для вывода значения новости
        'ELEM_NAME' => 'ELEM.NAME',
        'PREVIEW_PICTURE' => 'ELEM.PREVIEW_PICTURE',
        'PREVIEW_TEXT' => 'ELEM.PREVIEW_TEXT',
        //нужные для вывода значения пользователя
        'LOGIN' => 'USER.LOGIN',
        'USER_NAME' => 'USER.NAME',
        'LAST_NAME' => 'USER.LAST_NAME',
        'EMAIL' => 'USER.EMAIL',
        //все данные об изображении
        'IMAGE_' => 'IMAGE.*'
    ],
    'runtime' => [
        //данные привязанной новости
        'ELEM' => [
            'data_type' => '\Bitrix\Iblock\ElementTable',
            'reference' => [
                '=this.UF_NEWS_ID' => 'ref.ID'
            ],
            'join_type' => 'inner'
        ],
        //данные привязанного пользователя
        'USER' => [
            'data_type' => '\Bitrix\Main\UserTable',
            'reference' => [
                '=this.UF_USER_ID' => 'ref.ID'
            ],
            'join_type' => 'inner'
        ],
        //данные изображения
        'IMAGE' => [
            'data_type' => '\Bitrix\Main\FileTable',
            'reference' => [
                '=this.UF_FILE' => 'ref.ID'
            ],
            'join_type' => 'inner'
        ]
    ],
    'limit' => 10
]);

//для проверки запроса
//echo '<pre>', $result->getTrackerQuery()->getSql(), '</pre>';

//вывод массива данных
while ($row = $result->fetch()) {
    echo '<pre>';
    print_r($row);
    echo '</pre>';
}
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
