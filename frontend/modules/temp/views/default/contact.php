<style>
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

    .other-info li .glyphicon.glyphicon-map-marker {
        font-size: 22px;
        padding-top: 35px;
    }

    .other-info li a {
        color: #666666;
    }

    .btn-cta {
        background: #2ecc71;
        border: 2px solid #2ecc71;
        color: #fff;
    }

    .btn-cta:hover {
        background: #2eec71;
        border: 2px solid #2eec71;
        color: #fff;
    }

    .btn-cta:focus {
        color: #fff;
    }
</style>

<ul class="other-info list-unstyled col-md-4 col-sm-5 col-xs-12">
    <li><i class="glyphicon glyphicon-send"></i>
        <a href="#" data-toggle="modal" data-target="#contact_us">Отправить сообщение</a>
    </li>
    <li><i class="glyphicon glyphicon-envelope"></i><a href="#">ksk_auezov@mail.ru</a></li>
    <li><i class="glyphicon glyphicon-earphone"></i><a href="tel:+77017687548">+7(701)768-75-48</a></li>
    <li><i class="glyphicon glyphicon-map-marker"></i>Респубика Казахстан,<br> город Алматы,<br> улица Бухар
        жырау,64 <?= \app\models\LeaveMessage::find()->where(['user_ip' => '127.0.0.1'])
            ->andWhere(['>', 'created_at', new \yii\db\Expression('NOW() - INTERVAL 1 HOUR')])->count()?>
    </li>
</ul>
<div class="col-md-8 col-sm-7 col-xs-12">
    <div id="map" style="width: 100%; height: 300px"></div>
</div>

<div class="modal fade" id="contact_us" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Отправить сообщение</h4>
            </div>
            <div class="modal-body">
                <?php $form = \yii\bootstrap\ActiveForm::begin(); ?>
                <?= $form->field($model, 'name')
                    ->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false)?>
                <?= $form->field($model, 'email')
                    ->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false)?>
                <?= $form->field($model, 'message')
                    ->textarea(['rows' => 6, 'placeholder' => $model->getAttributeLabel('message')])
                    ->label(false)?>
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-cta">Send Message</button>
                </div>
                <!--//row-->
                <div id="form-messages"></div>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

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
    myPlacemark = new ymaps.Placemark([43.22248655, 76.865814], { hintContent: 'Москва!', balloonContent: 'Столица России' });
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