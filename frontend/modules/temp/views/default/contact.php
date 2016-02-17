<?php


$this->title = 'Контакты';

?>
    <style>
        .modal input[type="text"], .modal input[type="password"] {
            width: 100%;
        }

        .other-info li {
            margin-bottom: 15px;
            padding: 15px;
            position: relative;
            color: #666666;
            text-align: left;
            padding-left: 90px;
            -webkit-box-shadow: 0 4px 1px rgba(0, 0, 0, 0.05);
            -moz-box-shadow: 0 4px 1px rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 1px rgba(0, 0, 0, 0.05);
            border: 1px solid #eeeeee;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -ms-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
            -moz-background-clip: padding;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
        }

        .other-info li .glyphicon {
            position: absolute;
            left: 0;
            top: 0;
            background: #2ecc71;
            color: #fff;
            width: 60px;
            height: 100%;
            font-size: 20px;
            padding-top: 15px;
            text-align: center;
            -webkit-border-top-right-radius: 0;
            -webkit-border-bottom-right-radius: 0;
            -webkit-border-bottom-left-radius: 4px;
            -webkit-border-top-left-radius: 4px;
            -moz-border-radius-topright: 0;
            -moz-border-radius-bottomright: 0;
            -moz-border-radius-bottomleft: 4px;
            -moz-border-radius-topleft: 4px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-top-left-radius: 4px;
            -moz-background-clip: padding;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
        }

        .other-info li .button {
            cursor: pointer;
        }

        .other-info li .button:hover {
            background: #2eec71;
        }

        .other-info li .button:active {
            padding-top: 17px;
        }

        .other-info li .glyphicon.glyphicon-map-marker {
            font-size: 22px;
            padding-top: 35px;
        }

        .other-info li a {
            /*color: #666666;*/
        }

    </style>

<!--    <ul class="other-info list-unstyled col-md-4 col-sm-5 col-xs-12">-->
<!--        <li><i class="glyphicon glyphicon-send button" data-toggle="modal" data-target="#contact_us"></i>-->
<!--            <a href="#" data-toggle="modal" data-target="#contact_us">Отправить сообщение</a></li>-->
<!--        <li><i class="glyphicon glyphicon-envelope button"></i><a href="mailto:ksk_auezov@mail.ru">ksk_auezov@mail.ru</a></li>-->
<!--        <li><i class="glyphicon glyphicon-earphone"></i>-->
<!--            <a href="tel:+77013508558">+7(701)350-85-58</a></li>-->
<!--        <li><i class="glyphicon glyphicon-map-marker"></i>Респубика Казахстан,<br> город Алматы,<br> улица-->
<!--            Алтынсарина,23-->
<!--        </li>-->
<!--    </ul>-->

<?php $form = \yii\bootstrap\ActiveForm::begin(); ?>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <h4>Отправить сообщение</h4>
        <?= $form->field($model, 'name')
            ->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false) ?>
        <?= $form->field($model, 'email')
            ->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>
        <?= $form->field($model, 'message')
            ->textarea(['rows' => 6, 'placeholder' => $model->getAttributeLabel('message')])
            ->label(false) ?>
        <div class="form-group">
            <?= \yii\bootstrap\Html::submitButton('Отправить сообщение',
                ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
        <div id="map" style="width: 100%; height: 300px"></div>
    </div>
<?php \yii\bootstrap\ActiveForm::end(); ?>

<?php
$js = <<<JS
var myMap;
var myPlacemark;
function init(){
    myMap = new ymaps.Map("map", {
        center: [43.22248655, 76.865814],
        zoom: 15,
        controls: ["zoomControl", "fullscreenControl"]
    });
    myPlacemark = new ymaps.Placemark([43.22248655, 76.865814], {
        hintContent: 'Мы здесь!',
        balloonContent: 'Кабинет №?'});
    myMap.geoObjects.add(myPlacemark);
}

function loadMap() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = "https://api-maps.yandex.ru/2.1/?lang=ru_RU&onload=init";

    // Fire the loading
    document.head.appendChild(script);
}
JS;

$this->registerJS($js, \yii\web\View::POS_HEAD);

$js = <<<JS
    loadMap();
JS;

$this->registerJS($js, \yii\web\View::POS_READY);

?>