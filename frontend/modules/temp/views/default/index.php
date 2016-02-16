<?php

use yii\web\JsExpression;

$this->title = 'Документы';

?>

<div class="hidden-sm hidden-md hidden-lg col-xs-12">
    <?= \frontend\themes\site\widgets\ListGroupWidget::widget([
        'data' => $data,
        'options' => [
            'padding' => 10
        ]
    ]) ?>
</div>


<div class="container">
    <div class="hidden-xs col-sm-4 col-md-3 col-lg-3">

        <?= \wbraganca\fancytree\FancytreeWidget::widget([
            'options' => [
                'source' => $data,
                'childcounter' => [
                    'deep' => true,
                    'hideZeros' => true,
                    'hideExpanded' => true
                ],
                'activate' => new JsExpression('function(event, data) {
                    console.log(data.node.key);
                    if (data.node.key.substr(0,1) == "i") {
                        getInfo(data.node.key.substr(2));
                    } else if (data.node.key.substr(0,1) == "d") {
                        getDoc(data.node.key.substr(2));
                    }
                }'),
            ]
        ]); ?>
    </div>

    <div class="hidden-xs col-sm-8 col-md-9 col-lg-9">
        <div id="info" class="hidden-xs panel panel-default" style="display: none;">
            <div class="panel-heading">Информация</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tr>
                        <th>Наименование и адрес КСК</th>
                        <td id="address_ksk"></td>
                    </tr>
                    <tr>
                        <th>Кол-во домов</th>
                        <td id="amount_house"></td>
                    </tr>
                    <tr>
                        <th>Адреса домов</th>
                        <td id="address_house"></td>
                    </tr>
                    <tr>
                        <th>Ф.И.О. председателя</th>
                        <td id="fullname_chairman"></td>
                    </tr>
                    <tr>
                        <th>Телефон</th>
                        <td id="phone"></td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="doc" class="hidden-xs panel panel-default" style="display: none;">
            <div class="panel-heading">Документы</div>
            <div class="panel-body" id="doc_body">
            </div>
        </div>
    </div>
</div>

<?php

$js = <<<JS
function getInfo(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/info/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                $('#address_ksk').html(data.info.address_ksk);
                $('#amount_house').html(data.info.amount_house);
                $('#address_house').html(data.info.address_house);
                $('#fullname_chairman').html(data.info.fullname_chairman);
                $('#phone').html(data.info.phone);
                $('#info').show();
            }
        },'json');
}
function getDoc(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/doc/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                $('#doc_body').html('');
                $.each(data.files, function(key, value) {
                    var path = value.substr(0,2)+'/'+value.substr(3,2)+'/'+value.substr(6,2)+'/'+value;
                    path = 'http://statics.kskauezov.kz/attachments/store/' + path;
                    console.log(path);
                    $('#doc_body').append('<img src="'+path+'" style="width: 100%">');
                    $('#doc').show();
                });
            }
        },'json');
}
JS;

$this->registerJS($js, \yii\web\View::POS_HEAD);

$js = <<<JS
$(document).ready(function() {
});
JS;

$this->registerJS($js, \yii\web\View::POS_READY);

?>
