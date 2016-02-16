<?php

namespace console\controllers;

use common\models\ParseKsk;
use common\models\ParseOtchet;
use common\models\ParseRegion;
use console\helpers\NokogiriHelper;
use Yii;
use yii\helpers\Json;
use \yii\console\Controller;

/**
 * Default controller for the `temp` module
 */
class ParseController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionGo()
    {
        $data = [
            ['ЖСК "Аврора"', 'http://kskforum.kz/forum/31-zhsk-avrora/'],
            ['ПЖК "Азамат"', 'http://kskforum.kz/forum/35-pzhk-azamat/'],
            ['ПКСК "А-3/5"', 'http://kskforum.kz/forum/30-pksk-a-35/'],
            ['ПКСК "Адал"', 'http://kskforum.kz/forum/33-pksk-adal/'],
            ['ЖК "Алма"', 'http://kskforum.kz/forum/51-zhk-alma/'],
            ['ПКСК "Алмаз"', 'http://kskforum.kz/forum/53-pksk-almaz/'],
            ['ПКСК "Айман"', 'http://kskforum.kz/forum/38-pksk-ajman/'],
            ['КСК "Академия"', 'http://kskforum.kz/forum/40-ksk-akademiia/'],
            ['ПКСК "Аксай-3 а"', 'http://kskforum.kz/forum/41-pksk-aksaj-3-a/'],
            ['ПКСК "Алмас"', 'http://kskforum.kz/forum/55-pksk-almas/'],
            ['ПКСК "Алуа"', 'http://kskforum.kz/forum/56-pksk-alua/'],
            ['ПК "Аксай-Монолит"', 'http://kskforum.kz/forum/43-pk-aksaj-monolit/'],
            ['ПКСК "Аксай-4"', 'http://kskforum.kz/forum/44-pksk-aksaj-4/'],
            ['ПКСК "Алия"', 'http://kskforum.kz/forum/49-pksk-aliia/'],
            ['ПКСК "Арна"', 'http://kskforum.kz/forum/65-pksk-arna/'],
            ['ПКСК "Асель"', 'http://kskforum.kz/forum/66-pksk-asel/'],
            ['ЖК "Астра"', 'http://kskforum.kz/forum/69-zhk-astra/'],
            ['ПК "АК Отау"', 'http://kskforum.kz/forum/993-pk-ak-otau/'],
            ['ПК "Arау Hofnung"', 'http://kskforum.kz/forum/1020-pk-arau-hofnung/'],
            ['ТОО "Алматы Тұрғын Үй"', 'http://kskforum.kz/forum/2783-too-almaty-t%D2%B1r%D2%93yn-%D2%AFj/'],
            ['ПК "АСУ"', 'http://kskforum.kz/forum/2907-pk-asu/'],
            ['ПКСК "Автомобилист"', 'http://kskforum.kz/forum/3027-pksk-avtomobilist/'],
            ['ПКСК "Байторы"', 'http://kskforum.kz/forum/73-pksk-bajtory/'],
            ['ПКСК "Батыр"', 'http://kskforum.kz/forum/74-pksk-batyr/'],
            ['ПКСК "Бектур"', 'http://kskforum.kz/forum/76-pksk-bektur/'],
            ['КСК "Берёзка"', 'http://kskforum.kz/forum/79-ksk-beryozka/'],
            ['ПКСК "Беркут"', 'http://kskforum.kz/forum/80-pksk-berkut/'],
            ['ПКСК "Болашак"', 'http://kskforum.kz/forum/88-pksk-bolashak/'],
            ['ПКСК "Бояулы"', 'http://kskforum.kz/forum/90-pksk-boiauly/'],
            ['ПКСК "Бiрлiк"', 'http://kskforum.kz/forum/1023-pksk-birlik/'],
            ['ПК "БН Жастар"', 'http://kskforum.kz/forum/3036-pk-bn-zhastar/'],
            ['ПКСК Верный', 'http://kskforum.kz/forum/92-pksk-vernyj/'],
            ['ПКСК "Верный-2"', 'http://kskforum.kz/forum/93-pksk-vernyj-2/'],
            ['ПКСК "Восход"', 'http://kskforum.kz/forum/97-pksk-voskhod/'],
            ['ПК "Виктория – Жетысу 21"', 'http://kskforum.kz/forum/1024-pk-viktoriia-%E2%80%93-zhetysu-21/'],
            ['ЖК "Галактика"', 'http://kskforum.kz/forum/99-zhk-galaktika/'],
            ['ПКСК "Домостроитель-3"', 'http://kskforum.kz/forum/110-pksk-domostroitel-3/'],
            ['ПКСК "Достык"', 'http://kskforum.kz/forum/112-pksk-dostyk/'],
            ['ЖК "Достык"', 'http://kskforum.kz/forum/113-zhk-dostyk/'],
            ['ПКСК "Дубок"', 'http://kskforum.kz/forum/114-pksk-dubok/'],
            ['ЖК "Дастан"', 'http://kskforum.kz/forum/102-zhk-dastan/'],
            ['ПКСК "Даулет"', 'http://kskforum.kz/forum/105-pksk-daulet/'],
            ['ПК "Дом - Адылет"', 'http://kskforum.kz/forum/2915-pk-dom-adylet/'],
            ['ПК "Достык Жетысу"', 'http://kskforum.kz/forum/3012-pk-dostyk-zhetysu/'],
            ['ЖК "Домостроитель-2"', 'http://kskforum.kz/forum/3025-zhk-domostroitel-2/'],
            ['ПК "Жанна АБК"', 'http://kskforum.kz/forum/117-pk-zhanna-abk/'],
            ['КСК "Жана-Нур"', 'http://kskforum.kz/forum/118-ksk-zhana-nur/'],
            ['ЖК "Жемчуг"', 'http://kskforum.kz/forum/120-zhk-zhemchug/'],
            ['ПКСК "Жетысу"', 'http://kskforum.kz/forum/121-pksk-zhetysu/'],
            ['ПК "Жетысу kulta 2"', 'http://kskforum.kz/forum/122-pk-zhetysu-kulta-2/'],
            ['ПКСК "Жетысу-2"', 'http://kskforum.kz/forum/123-pksk-zhetysu-2/'],
            ['ПКСК "Жетысу-24"', 'http://kskforum.kz/forum/124-pksk-zhetysu-24/'],
            ['ПКСК "Жетысу-Нуры"', 'http://kskforum.kz/forum/2218-pksk-zhetysu-nury/'],
            ['ПКСК "Жулдыз-16"', 'http://kskforum.kz/forum/125-pksk-zhuldyz-16/'],
            ['ПКСК "Жулдыз-19"', 'http://kskforum.kz/forum/126-pksk-zhuldyz-19/'],
            ['ПКСК "Журавушка-Тырна"', 'http://kskforum.kz/forum/127-pksk-zhuravushka-tyrna/'],
            ['ПК "Жолдастар"', 'http://kskforum.kz/forum/2969-pk-zholdastar/'],
            ['КСК "Жарык"', 'http://kskforum.kz/forum/2992-ksk-zharyk/'],
            ['ПКСК "Западный"', 'http://kskforum.kz/forum/129-pksk-zapadnyj/'],
            ['ПК "Заря"', 'http://kskforum.kz/forum/130-pk-zaria/'],
            ['КСП "Ісмер"', 'http://kskforum.kz/forum/133-ksp-%D1%96smer/'],
            ['ПКСК "КААС"', 'http://kskforum.kz/forum/134-pksk-kaas/'],
            ['КСК "Кайрат"', 'http://kskforum.kz/forum/135-ksk-kajrat/'],
            ['ПКСК "Каратал"', 'http://kskforum.kz/forum/138-pksk-karatal/'],
            ['ПКСК "Космос"', 'http://kskforum.kz/forum/143-pksk-kosmos/'],
            ['ПК "Куаныш мекені"', 'http://kskforum.kz/forum/146-pk-kuanysh-meken%D1%96/'],
            ['КСК "Лидер-1"', 'http://kskforum.kz/forum/148-ksk-lider-1/'],
            ['ПКСК "Луч"', 'http://kskforum.kz/forum/151-pksk-luch/'],
            ['ПКСК "Максат"', 'http://kskforum.kz/forum/152-pksk-maksat/'],
            ['ЖК "Максат"', 'http://kskforum.kz/forum/153-zhk-maksat/'],
            ['ПКСК "Мамыр"', 'http://kskforum.kz/forum/155-pksk-mamyr/'],
            ['ПКСК "Мамыр-7"', 'http://kskforum.kz/forum/156-pksk-mamyr-7/'],
            ['ПКСК "Марс"', 'http://kskforum.kz/forum/160-pksk-mars/'],
            ['ПК "Мекен – Жай"', 'http://kskforum.kz/forum/166-pk-meken-%E2%80%93-zhaj/'],
            ['ПКСК "Мейрам"', 'http://kskforum.kz/forum/167-pksk-mejram/'],
            ['ЖК "Меруерт"', 'http://kskforum.kz/forum/170-zhk-meruert/'],
            ['ЖК "Мир"', 'http://kskforum.kz/forum/173-zhk-mir/'],
            ['КСК Надежда', 'http://kskforum.kz/forum/178-ksk-nadezhda/'],
            ['ПКСК "Надежда"', 'http://kskforum.kz/forum/179-pksk-nadezhda/'],
            ['ПКСК "Наурыз"', 'http://kskforum.kz/forum/180-pksk-nauryz/'],
            ['ПКСК "Нейрон"', 'http://kskforum.kz/forum/2966-pksk-nejron/'],
            ['ПК "Орын уй"', 'http://kskforum.kz/forum/186-pk-oryn-uj/'],
            ['ПКСК "Отау"', 'http://kskforum.kz/forum/188-pksk-otau/'],
            ['ПК "Отау-8 Virta"', 'http://kskforum.kz/forum/189-pk-otau-8-virta/'],
            ['ПК "Oberon"', 'http://kskforum.kz/forum/2990-pk-oberon/'],
            ['ПК Премьера 29', 'http://kskforum.kz/forum/11-pk-premera-29/'],
            ['ПКСК "Позитив"', 'http://kskforum.kz/forum/194-pksk-pozitiv/'],
            ['ЖК "Правда"', 'http://kskforum.kz/forum/195-zhk-pravda/'],
            ['ПК "Престиж 7"', 'http://kskforum.kz/forum/196-pk-prestizh-7/'],
            ['ПКСК "Прогресс"', 'http://kskforum.kz/forum/197-pksk-progress/'],
            ['ЖК "Прометей"', 'http://kskforum.kz/forum/198-zhk-prometej/'],
            ['ПКСК "Радуга"', 'http://kskforum.kz/forum/199-pksk-raduga/'],
            ['ПКСК "Сайран"', 'http://kskforum.kz/forum/206-pksk-sajran/'],
            ['ПКСК "Сайран-2"', 'http://kskforum.kz/forum/207-pksk-sajran-2/'],
            ['ПКСК "Север"', 'http://kskforum.kz/forum/212-pksk-sever/'],
            ['ПКСК Серпин', 'http://kskforum.kz/forum/213-pksk-serpin/'],
            ['КСК "Солнышко"', 'http://kskforum.kz/forum/215-ksk-solnyshko/'],
            ['ПКСК "Согласие"', 'http://kskforum.kz/forum/216-pksk-soglasie/'],
            ['ЖК "Спутник"', 'http://kskforum.kz/forum/220-zhk-sputnik/'],
            ['ПКСК "Спутник"', 'http://kskforum.kz/forum/221-pksk-sputnik/'],
            ['ЖК "Сункар"', 'http://kskforum.kz/forum/223-zhk-sunkar/'],
            ['ПКСК "Тастак-1-Пуск"', 'http://kskforum.kz/forum/226-pksk-tastak-1-pusk/'],
            ['ПК Тау Самал Навои', 'http://kskforum.kz/forum/2020-pk-tau-samal-navoi/'],
            ['ПКСК "Таугуль"', 'http://kskforum.kz/forum/227-pksk-taugul/'],
            ['ЖК "Тянь-Шань"', 'http://kskforum.kz/forum/231-zhk-tian-shan/'],
            ['ПКСК "Тюльпан"', 'http://kskforum.kz/forum/232-pksk-tiulpan/'],
            ['ПКСК "Улан"', 'http://kskforum.kz/forum/233-pksk-ulan/'],
            ['ПКСК "Управление домами №3"', 'http://kskforum.kz/forum/234-pksk-upravlenie-domami-%E2%84%963/'],
            ['ПКСК "Управление домами-14"', 'http://kskforum.kz/forum/235-pksk-upravlenie-domami-14/'],
            ['ПКСК "Уют-28"', 'http://kskforum.kz/forum/236-pksk-uiut-28/'],
            ['ПКСК Школьник', 'http://kskforum.kz/forum/239-pksk-shkolnik/']
        ];

        $data2 = [
            ['ЖСК "Аврора"', 'http://kskforum.kz/forum/31-zhsk-avrora/', 'http://kskforum.kz/forum/1216-otchety-plany-rabot/'],
            ['ПЖК "Азамат"', 'http://kskforum.kz/forum/35-pzhk-azamat/', 'http://kskforum.kz/forum/1063-otchety-plany-rabot/'],
            ['ПКСК "А-3/5"', 'http://kskforum.kz/forum/30-pksk-a-35/', 'http://kskforum.kz/forum/1249-otchety-plany-rabot/'],
            ['ПКСК "Адал"', 'http://kskforum.kz/forum/33-pksk-adal/', 'http://kskforum.kz/forum/1260-otchety-plany-rabot/'],
            ['ЖК "Алма"', 'http://kskforum.kz/forum/51-zhk-alma/', 'http://kskforum.kz/forum/1291-otchety-plany-rabot/'],
            ['ПКСК "Алмаз"', 'http://kskforum.kz/forum/53-pksk-almaz/', 'http://kskforum.kz/forum/1357-otchety-plany-rabot/'],
            ['ПКСК "Айман"', 'http://kskforum.kz/forum/38-pksk-ajman/', 'http://kskforum.kz/forum/1371-otchety-plany-rabot/'],
            ['КСК "Академия"', 'http://kskforum.kz/forum/40-ksk-akademiia/', 'http://kskforum.kz/forum/1415-otchety-plany-rabot/'],
            ['ПКСК "Аксай-3 а"', 'http://kskforum.kz/forum/41-pksk-aksaj-3-a/', 'http://kskforum.kz/forum/1426-otchety-plany-rabot/'],
            ['ПКСК "Алмас"', 'http://kskforum.kz/forum/55-pksk-almas/', 'http://kskforum.kz/forum/1404-otchety-plany-rabot/'],
            ['ПКСК "Алуа"', 'http://kskforum.kz/forum/56-pksk-alua/', 'http://kskforum.kz/forum/1460-otchety-plany-rabot/'],
            ['ПК "Аксай-Монолит"', 'http://kskforum.kz/forum/43-pk-aksaj-monolit/', 'http://kskforum.kz/forum/1482-otchety-plany-rabot/'],
            ['ПКСК "Аксай-4"', 'http://kskforum.kz/forum/44-pksk-aksaj-4/', 'http://kskforum.kz/forum/2940-otchety-plany-rabot/'],
            ['ПКСК "Алия"', 'http://kskforum.kz/forum/49-pksk-aliia/', 'http://kskforum.kz/forum/3033-otchety-plany-rabot/'],
            ['ПКСК "Арна"', 'http://kskforum.kz/forum/65-pksk-arna/', 'http://kskforum.kz/forum/2908-otchety-plany-rabot/'],
            ['ПКСК "Асель"', 'http://kskforum.kz/forum/66-pksk-asel/', 'http://kskforum.kz/forum/3146-otchety-plany-rabot/'],
            ['ЖК "Астра"', 'http://kskforum.kz/forum/69-zhk-astra/', 'http://kskforum.kz/forum/2963-otchety-plany-rabot/'],
            ['ПК "АК Отау"', 'http://kskforum.kz/forum/993-pk-ak-otau/', 'http://kskforum.kz/forum/1269-otchety-plany-rabot/'],
            ['ПК "Arау Hofnung"', 'http://kskforum.kz/forum/1020-pk-arau-hofnung/', 'http://kskforum.kz/forum/1544-otchety-plany-rabot/'],
            ['ТОО "Алматы Тұрғын Үй"', 'http://kskforum.kz/forum/2783-too-almaty-t%D2%B1r%D2%93yn-%D2%AFj/', 'http://kskforum.kz/forum/2785-otchety-plany-rabot/'],
            ['ПК "АСУ"', 'http://kskforum.kz/forum/2907-pk-asu/', 'http://kskforum.kz/forum/2909-otchety-plany-rabot/'],
            ['ПКСК "Автомобилист"', 'http://kskforum.kz/forum/3027-pksk-avtomobilist/', 'http://kskforum.kz/forum/3028-otchety-plany-rabot/'],
            ['ПКСК "Байторы"', 'http://kskforum.kz/forum/73-pksk-bajtory/', 'http://kskforum.kz/forum/317-otchety-plany-rabot/'],
            ['ПКСК "Батыр"', 'http://kskforum.kz/forum/74-pksk-batyr/', 'http://kskforum.kz/forum/2956-otchety-plany-rabot/'],
            ['ПКСК "Бектур"', 'http://kskforum.kz/forum/76-pksk-bektur/', 'http://kskforum.kz/forum/2910-otchety-plany-rabot/'],
            ['КСК "Берёзка"', 'http://kskforum.kz/forum/79-ksk-beryozka/', 'http://kskforum.kz/forum/2939-otchety-plany-rabot/'],
            ['ПКСК "Беркут"', 'http://kskforum.kz/forum/80-pksk-berkut/', 'http://kskforum.kz/forum/2964-otchety-plany-rabot/'],
            ['ПКСК "Болашак"', 'http://kskforum.kz/forum/88-pksk-bolashak/', 'http://kskforum.kz/forum/3166-otchety-plany-rabot/'],
            ['ПКСК "Бояулы"', 'http://kskforum.kz/forum/90-pksk-boiauly/', 'http://kskforum.kz/forum/2974-otchety-plany-rabot/'],
            ['ПКСК "Бiрлiк"', 'http://kskforum.kz/forum/1023-pksk-birlik/', 'http://kskforum.kz/forum/1616-otchety-plany-rabot/'],
            ['ПК "БН Жастар"', 'http://kskforum.kz/forum/3036-pk-bn-zhastar/', 'http://kskforum.kz/forum/3037-otchety-plany-rabot/'],
            ['ПКСК Верный', 'http://kskforum.kz/forum/92-pksk-vernyj/', 'http://kskforum.kz/forum/1110-otchety-plany-rabot/'],
            ['ПКСК "Верный-2"', 'http://kskforum.kz/forum/93-pksk-vernyj-2/', 'http://kskforum.kz/forum/2635-otchety-plany-rabot/'],
            ['ПКСК "Восход"', 'http://kskforum.kz/forum/97-pksk-voskhod/', 'http://kskforum.kz/forum/3062-otchety-plany-rabot/'],
            ['ПК "Виктория – Жетысу 21"', 'http://kskforum.kz/forum/1024-pk-viktoriia-%E2%80%93-zhetysu-21/', 'http://kskforum.kz/forum/1638-otchety-plany-rabot/'],
            ['ЖК "Галактика"', 'http://kskforum.kz/forum/99-zhk-galaktika/', 'http://kskforum.kz/forum/2748-otchety-plany-rabot/'],
            ['ПКСК "Домостроитель-3"', 'http://kskforum.kz/forum/110-pksk-domostroitel-3/', 'http://kskforum.kz/forum/3063-otchety-plany-rabot/'],
            ['ПКСК "Достык"', 'http://kskforum.kz/forum/112-pksk-dostyk/', 'http://kskforum.kz/forum/2951-otchety-plany-rabot/'],
            ['ЖК "Достык"', 'http://kskforum.kz/forum/113-zhk-dostyk/', 'http://kskforum.kz/forum/2955-otchety-plany-rabot/'],
            ['ПКСК "Дубок"', 'http://kskforum.kz/forum/114-pksk-dubok/', 'http://kskforum.kz/forum/3043-otchety-plany-rabot/'],
            ['ЖК "Дастан"', 'http://kskforum.kz/forum/102-zhk-dastan/', 'http://kskforum.kz/forum/2897-otchety-plany-rabot/'],
            ['ПКСК "Даулет"', 'http://kskforum.kz/forum/105-pksk-daulet/', 'http://kskforum.kz/forum/2893-otchety-plany-rabot/'],
            ['ПК "Дом - Адылет"', 'http://kskforum.kz/forum/2915-pk-dom-adylet/', 'http://kskforum.kz/forum/2916-otchety-plany-rabot/'],
            ['ПК "Достык Жетысу"', 'http://kskforum.kz/forum/3012-pk-dostyk-zhetysu/', 'http://kskforum.kz/forum/3013-otchety-plany-rabot/'],
            ['ЖК "Домостроитель-2"', 'http://kskforum.kz/forum/3025-zhk-domostroitel-2/', 'http://kskforum.kz/forum/3026-otchety-plany-rabot/'],
            ['ПК "Жанна АБК"', 'http://kskforum.kz/forum/117-pk-zhanna-abk/', 'http://kskforum.kz/forum/3231-otchety-plany-rabot/'],
            ['КСК "Жана-Нур"', 'http://kskforum.kz/forum/118-ksk-zhana-nur/', 'http://kskforum.kz/forum/3064-otchety-plany-rabot/'],
            ['ЖК "Жемчуг"', 'http://kskforum.kz/forum/120-zhk-zhemchug/', 'http://kskforum.kz/forum/3060-otchety-plany-rabot/'],
            ['ПКСК "Жетысу"', 'http://kskforum.kz/forum/121-pksk-zhetysu/', 'http://kskforum.kz/forum/2913-otchety-plany-rabot/'],
            ['ПК "Жетысу kulta 2"', 'http://kskforum.kz/forum/122-pk-zhetysu-kulta-2/', 'http://kskforum.kz/forum/1671-otchety-plany-rabot/'],
            ['ПКСК "Жетысу-2"', 'http://kskforum.kz/forum/123-pksk-zhetysu-2/', 'http://kskforum.kz/forum/2904-otchety-plany-rabot/'],
            ['ПКСК "Жетысу-24"', 'http://kskforum.kz/forum/124-pksk-zhetysu-24/', 'http://kskforum.kz/forum/3233-otchety-plany-rabot/'],
            ['ПКСК "Жетысу-Нуры"', 'http://kskforum.kz/forum/2218-pksk-zhetysu-nury/', 'http://kskforum.kz/forum/2226-otchety-plany-rabot/'],
            ['ПКСК "Жулдыз-16"', 'http://kskforum.kz/forum/125-pksk-zhuldyz-16/', 'http://kskforum.kz/forum/3023-otchety-plany-rabot/'],
            ['ПКСК "Жулдыз-19"', 'http://kskforum.kz/forum/126-pksk-zhuldyz-19/', 'http://kskforum.kz/forum/2898-otchety-plany-rabot/'],
            ['ПКСК "Журавушка-Тырна"', 'http://kskforum.kz/forum/127-pksk-zhuravushka-tyrna/', 'http://kskforum.kz/forum/2899-otchety-i-plany-rabot/'],
            ['ПК "Жолдастар"', 'http://kskforum.kz/forum/2969-pk-zholdastar/', 'http://kskforum.kz/forum/2970-otchety-plany-rabot/'],
            ['КСК "Жарык"', 'http://kskforum.kz/forum/2992-ksk-zharyk/', 'http://kskforum.kz/forum/2993-otchety-plany-rabot/'],
            ['ПКСК "Западный"', 'http://kskforum.kz/forum/129-pksk-zapadnyj/', 'http://kskforum.kz/forum/3050-otchety-plany-rabot/'],
            ['ПК "Заря"', 'http://kskforum.kz/forum/130-pk-zaria/', 'http://kskforum.kz/forum/2973-otchety-plany-rabot/'],
            ['КСП "Ісмер"', 'http://kskforum.kz/forum/133-ksp-%D1%96smer/', 'http://kskforum.kz/forum/2903-otchety-plany-rabot/'],
            ['ПКСК "КААС"', 'http://kskforum.kz/forum/134-pksk-kaas/', 'http://kskforum.kz/forum/1715-otchety-plany-rabot/'],
            ['КСК "Кайрат"', 'http://kskforum.kz/forum/135-ksk-kajrat/', 'http://kskforum.kz/forum/2957-otchety-plany-rabot/'],
            ['ПКСК "Каратал"', 'http://kskforum.kz/forum/138-pksk-karatal/', 'http://kskforum.kz/forum/2905-otchety-plany-rabot/'],
            ['ПКСК "Космос"', 'http://kskforum.kz/forum/143-pksk-kosmos/', 'http://kskforum.kz/forum/2960-otchety-plany-rabot/'],
            ['ПК "Куаныш мекені"', 'http://kskforum.kz/forum/146-pk-kuanysh-meken%D1%96/', 'http://kskforum.kz/forum/3038-otchety-plany-rabot/'],
            ['КСК "Лидер-1"', 'http://kskforum.kz/forum/148-ksk-lider-1/', 'http://kskforum.kz/forum/3034-otchety-plany-rabot/'],
            ['ПКСК "Луч"', 'http://kskforum.kz/forum/151-pksk-luch/', 'http://kskforum.kz/forum/1858-otchety-plany-rabot/'],
            ['ПКСК "Максат"', 'http://kskforum.kz/forum/152-pksk-maksat/', 'http://kskforum.kz/forum/1244-otchety-plany-rabot/'],
            ['ЖК "Максат"', 'http://kskforum.kz/forum/153-zhk-maksat/', 'http://kskforum.kz/forum/2999-otchety-plany-rabot/'],
            ['ПКСК "Мамыр"', 'http://kskforum.kz/forum/155-pksk-mamyr/', 'http://kskforum.kz/forum/2912-otchety-plany-rabot/'],
            ['ПКСК "Мамыр-7"', 'http://kskforum.kz/forum/156-pksk-mamyr-7/', 'http://kskforum.kz/forum/2882-otchety-plany-rabot/'],
            ['ПКСК "Марс"', 'http://kskforum.kz/forum/160-pksk-mars/', 'http://kskforum.kz/forum/3065-otchety-plany-rabot/'],
            ['ПК "Мекен – Жай"', 'http://kskforum.kz/forum/166-pk-meken-%E2%80%93-zhaj/', 'http://kskforum.kz/forum/2806-otchety-plany-rabot/'],
            ['ПКСК "Мейрам"', 'http://kskforum.kz/forum/167-pksk-mejram/', 'http://kskforum.kz/forum/1880-otchety-plany-rabot/'],
            ['ЖК "Меруерт"', 'http://kskforum.kz/forum/170-zhk-meruert/', 'http://kskforum.kz/forum/2896-otchety-plany-rabot/'],
            ['ЖК "Мир"', 'http://kskforum.kz/forum/173-zhk-mir/', 'http://kskforum.kz/forum/3029-otchety-plany-rabot/'],
            ['КСК Надежда', 'http://kskforum.kz/forum/178-ksk-nadezhda/', 'http://kskforum.kz/forum/1052-otchety-plany-rabot/'],
            ['ПКСК "Надежда"', 'http://kskforum.kz/forum/179-pksk-nadezhda/', 'http://kskforum.kz/forum/2891-otchety-plany-rabot/'],
            ['ПКСК "Наурыз"', 'http://kskforum.kz/forum/180-pksk-nauryz/', 'http://kskforum.kz/forum/3270-otchety-plany-rabot/'],
            ['ПКСК "Нейрон"', 'http://kskforum.kz/forum/2966-pksk-nejron/', 'http://kskforum.kz/forum/2967-otchety-plany-rabot/'],
            ['ПК "Орын уй"', 'http://kskforum.kz/forum/186-pk-oryn-uj/', 'http://kskforum.kz/forum/2958-otchety-plany-rabot/'],
            ['ПКСК "Отау"', 'http://kskforum.kz/forum/188-pksk-otau/', 'http://kskforum.kz/forum/2901-otchety-plany-rabot/'],
            ['ПК "Отау-8 Virta"', 'http://kskforum.kz/forum/189-pk-otau-8-virta/', 'http://kskforum.kz/forum/1916-otchety-plany-rabot/'],
            ['ПК "Oberon"', 'http://kskforum.kz/forum/2990-pk-oberon/', 'http://kskforum.kz/forum/2991-otchety-plany-rabot/'],
            ['ПК Премьера 29', 'http://kskforum.kz/forum/11-pk-premera-29/', 'http://kskforum.kz/forum/2639-otchety-i-plany-rabot/'],
            ['ПКСК "Позитив"', 'http://kskforum.kz/forum/194-pksk-pozitiv/', 'http://kskforum.kz/forum/3283-otchety-plany-rabot/'],
            ['ЖК "Правда"', 'http://kskforum.kz/forum/195-zhk-pravda/', 'http://kskforum.kz/forum/2920-otchety-plany-rabot/'],
            ['ПК "Престиж 7"', 'http://kskforum.kz/forum/196-pk-prestizh-7/', 'http://kskforum.kz/forum/3015-otchety-plany-rabot/'],
            ['ПКСК "Прогресс"', 'http://kskforum.kz/forum/197-pksk-progress/', 'http://kskforum.kz/forum/3030-otchety-plany-rabot/'],
            ['ЖК "Прометей"', 'http://kskforum.kz/forum/198-zhk-prometej/', 'http://kskforum.kz/forum/2954-otchety-plany-rabot/'],
            ['ПКСК "Радуга"', 'http://kskforum.kz/forum/199-pksk-raduga/', 'http://kskforum.kz/forum/2889-otchety-plany-rabot/'],
            ['ПКСК "Сайран"', 'http://kskforum.kz/forum/206-pksk-sajran/', 'http://kskforum.kz/forum/2952-otchety-plany-rabot/'],
            ['ПКСК "Сайран-2"', 'http://kskforum.kz/forum/207-pksk-sajran-2/', 'http://kskforum.kz/forum/2424-otchety-plany-rabot/'],
            ['ПКСК "Север"', 'http://kskforum.kz/forum/212-pksk-sever/', 'http://kskforum.kz/forum/3003-otchety-plany-rabot/'],
            ['ПКСК Серпин', 'http://kskforum.kz/forum/213-pksk-serpin/', 'http://kskforum.kz/forum/1075-otchety-plany-rabot/'],
            ['КСК "Солнышко"', 'http://kskforum.kz/forum/215-ksk-solnyshko/', 'http://kskforum.kz/forum/3066-otchety-plany-rabot/'],
            ['ПКСК "Согласие"', 'http://kskforum.kz/forum/216-pksk-soglasie/', 'http://kskforum.kz/forum/2914-otchety-plany-rabot/'],
            ['ЖК "Спутник"', 'http://kskforum.kz/forum/220-zhk-sputnik/', 'http://kskforum.kz/forum/2968-otchety-plany-rabot/'],
            ['ПКСК "Спутник"', 'http://kskforum.kz/forum/221-pksk-sputnik/', 'http://kskforum.kz/forum/2906-otchety-plany-rabot/'],
            ['ЖК "Сункар"', 'http://kskforum.kz/forum/223-zhk-sunkar/', 'http://kskforum.kz/forum/2887-otchety-plany-rabot/'],
            ['ПКСК "Тастак-1-Пуск"', 'http://kskforum.kz/forum/226-pksk-tastak-1-pusk/', 'http://kskforum.kz/forum/2902-otchety-plany-rabot/'],
            ['ПК Тау Самал Навои', 'http://kskforum.kz/forum/2020-pk-tau-samal-navoi/', 'http://kskforum.kz/forum/2028-otchety-plany-rabot/'],
            ['ПКСК "Таугуль"', 'http://kskforum.kz/forum/227-pksk-taugul/', 'http://kskforum.kz/forum/2959-otchety-plany-rabot/'],
            ['ЖК "Тянь-Шань"', 'http://kskforum.kz/forum/231-zhk-tian-shan/', 'http://kskforum.kz/forum/3222-otchety-plany-rabot/'],
            ['ПКСК "Тюльпан"', 'http://kskforum.kz/forum/232-pksk-tiulpan/', 'http://kskforum.kz/forum/3067-otchety-plany-rabot/'],
            ['ПКСК "Улан"', 'http://kskforum.kz/forum/233-pksk-ulan/', 'http://kskforum.kz/forum/2917-otchety-plany-rabot/'],
            ['ПКСК "Управление домами №3"', 'http://kskforum.kz/forum/234-pksk-upravlenie-domami-%E2%84%963/', 'http://kskforum.kz/forum/3035-otchety-plany-rabot/'],
            ['ПКСК "Управление домами-14"', 'http://kskforum.kz/forum/235-pksk-upravlenie-domami-14/', 'http://kskforum.kz/forum/2953-otchety-plany-rabot/'],
            ['ПКСК "Уют-28"', 'http://kskforum.kz/forum/236-pksk-uiut-28/', 'http://kskforum.kz/forum/2911-otchety-plany-rabot/'],
            ['ПКСК Школьник', 'http://kskforum.kz/forum/239-pksk-shkolnik/', 'http://kskforum.kz/forum/2130-otchety-plany-rabot/']
        ];


        foreach ($data as $title => $url) {
            echo "qwe\n";
            return;
        }

    }

    public function actionUpdateKsk()
    {
        foreach (ParseRegion::find()->all() as $region) {
            /* @var $region ParseRegion*/
            echo $region->url."\n";

            $changes = [];
            $news = [];
            $errors = [];
            $updated = 0;
            $created = 0;
            $saw = new NokogiriHelper(file_get_contents($region->url));
            foreach ($saw->get('ol.subforums li a') as $a) {
                $color = 0;
                $name = $a['title'];
                if (isset($a['font'])) {
                    $name = $a['font'][0]['#text'][0];
                    $color = 1;
                }
                $name = trim($name);

                $model = ParseKsk::find()->where(['name' => $name, 'parse_region_id' => $region->id])->one();
                if ($model === null) {
                    $model = new ParseKsk();
                    $model->name = $name;
                    $model->parse_region_id = $region->id;
                }

                $update = false;
                if (!$model->isNewRecord && trim($a['href']) != '' && $model->url_ksk != $a['href']) {
                    $changes[] = "({$model->name}) {$model->url_ksk} -> " . $a['href'];
                    $model->url_ksk = $a['href'];
                    $update = true;
                }
                if (!$model->isNewRecord && $model->color != $color) {
                    $changes[] = "({$model->name}) {$model->color} -> " . $color;
                    $model->color = $color;
                    $update = true;
                }

                if ($update || $model->isNewRecord) {
                    $model->updated_at = new \yii\db\Expression('utc_timestamp()');
                    if ($model->isNewRecord) {
                        $news[] = $model->name;
                        $created += $model->save() ? 1 : 0;
                    } else {
                        $updated += $model->save() ? 1 : 0;
                    }
                    if ($model->hasErrors()) {
                        $errors[] = $model->errors;
                    }
                }
            }

            if ($created + $updated > 0) {
                $region->updated_at = new \yii\db\Expression('utc_timestamp()');
                $region->save();
            }
            echo "created - {$created}, updated - {$updated}\n";
        }
    }

    public function actionUpdateReportLink()
    {
        foreach (ParseRegion::find()->all() as $region) {
            /* @var $region ParseRegion*/
            echo $region->url."\n";

            $i = 0;
            $errors = [];
            $models = ParseKsk::find()
                ->where(['parse_region_id' => $region->id, 'url_otchet' => null])
                ->andWhere(['IS NOT', 'url_ksk', null])
                ->all();
            foreach ($models as $model) {
                /* @var $model ParseKsk */
                $saw = new NokogiriHelper(file_get_contents($model->url_ksk));

                $model = ParseKsk::findOne($model->id);
                $ok = false;
                foreach ($saw->get('.col_c_forum a') as $a) {
                    if (strpos(mb_strtolower($a['#text'][0]), 'отчеты') !== false) {
                        $model->url_otchet = $a['href'];
                        $ok = true;
                        break;
                    }
                }

                if ($ok) {
                    $model->updated_at = new \yii\db\Expression('utc_timestamp()');
                    if ($model->save()) {
                        $i++;
                    } else {
                        $errors[] = $model->errors;
                    }
                }

            }

            if ($i > 0) {
                $region = ParseRegion::findOne($region->id);
                $region->updated_at = new \yii\db\Expression('utc_timestamp()');
                $region->save();
            }

            echo "updated - {$i}\n";
        }
    }

    public function actionUpdateReport()
    {
        foreach (ParseRegion::find()->all() as $region) {
            /* @var $region ParseRegion*/
            echo $region->url."\n";

            $saved = 0;
            $errors = [];
            $models = ParseKsk::find()
                ->where(['parse_region_id' => $region->id])
                ->andWhere(['IS NOT', 'url_otchet', null])
                ->all();

            foreach ($models as $model) {
                /* @var $model ParseKsk */
                echo '  '.$model->url_otchet."\n";

                $saw = new NokogiriHelper(file_get_contents($model->url_otchet));
                $ok = $saved;
                foreach ($saw->get('.col_f_content') as $forum_name) {
                    $h4s = $forum_name['h4'];
                    if (isset($forum_name['span'][0]['span'])) {
                        $posted_at = $forum_name['span'][0]['span'][0]['#text'][0];
                    } else {
                        $posted_at = $forum_name['span'][1]['span'][0]['#text'][0];
                    }
                    foreach ($h4s as $h4) {
                        $a = $h4['a'][0];
                        $name = trim($a['span'][0]['#text'][0]);
                        $url = $a['href'];
                        $model_otchet = ParseOtchet::find()->where(['name' => $name])->one();
                        if (is_null($model_otchet)) {
                            $model_otchet = new ParseOtchet();
                            $model_otchet->parse_ksk_id = $model->id;
                            $model_otchet->name = $name;
                            $model_otchet->url_otchet = $url;
                            $model_otchet->posted_at = date('Y-m-d H:i:s', strtotime($posted_at));
                            $model_otchet->updated_at = new \yii\db\Expression('utc_timestamp()');
                            $model_otchet->save() ? $saved++ : $errors[] = $model_otchet->errors;
                        }
                    }
                }

                if ($ok < $saved) {
                    $model = ParseKsk::findOne($model->id);
                    $model->updated_at = new \yii\db\Expression('utc_timestamp()');
                    $model->save();
                }
            }

            if ($saved > 0) {
                $region = ParseRegion::findOne($region->id);
                $region->updated_at = new \yii\db\Expression('utc_timestamp()');
                $region->save();
            }

            echo "saved - {$saved}\n";
        }
    }

    public function actionUpdateReportFile()
    {
        $count = 0;
        $files = 0;
        foreach (ParseOtchet::find()->all() as $model) {
            if ($model->type === null) {
                $saw = new NokogiriHelper(file_get_contents($model->url_otchet));
                $url_base = Yii::$app->basePath . "/statics/web/ksk/otchet/{$model->id}/";
                $size = scandir($url_base);
                $text = [];
                foreach ($saw->get('div.post p') as $p) {
                    if (isset($p['a'])) {
                        foreach($p['a'] as $a) {
                            $file = file_get_contents($a['href']);
                            if (file_put_contents("{$url_base}/{$size}.jpg", $file) !== false) {
                                $size ++;
                                $modelFile = new ParseOtchetFile();
                                $modelFile->file_name = $a['title'];
                                $modelFile->path = "{$url_base}/{$size}.jpg";
                                $modelFile->parse_otchet_id = $model->id;
                                if ($modelFile->save()) {
                                    $files ++;
                                    echo str_pad("otchet-{$model->id}", 15).
                                        str_pad("{$modelFile->path}", 50).
                                        str_pad("SUCCESS", 15)."\n";
                                } else {
                                    echo str_pad("otchet-{$model->id}", 15).
                                        str_pad(Json::encode($modelFile->errors), 50).
                                        str_pad("ERROR MODEL", 15)."\n";
                                }
                            } else {
                                echo str_pad("otchet-{$model->id}", 15).
                                    str_pad("{$url_base}/{$size}.jpg", 50).
                                    str_pad("ERROR FILE", 15)."\n";
                            }
                        }
                    }
                    $text[] = $p['#text'];
                }
                $model = ParseOtchet::findOne($model->id);
                $model->text = $this->formatText($text);
                $model->save();
                $count ++;
            }
        }
        echo "updated - {$count}\ncreated files - {$files}\n";
    }

    private function formatText($text)
    {
        if (is_array($text)) {
            $result = '';
            foreach ($text as $sub_text) {
                $result .= $this->formatText($text).'<br>';
            }
            return $result;
        }
        return $text;
    }
}
