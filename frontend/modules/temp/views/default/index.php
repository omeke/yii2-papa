<?php

use yii\web\JsExpression;

$this->title = 'Документы';

?>

<div class="container">
    <div class="col-md-3">

        <?= \wbraganca\fancytree\FancytreeWidget::widget([
            'options' => [
                'source' => $data,
                'activate' => new JsExpression('function(event, data) {
                    console.log(data.node.key);
                    if (data.node.key.substr(0,1) == "i") {
                        getInfo(data.node.key.substr(2));
                    }
                    if (data.node.key.substr(0,1) == "d") {
                        getDoc(data.node.key.substr(2));
                    }
                    if (data.node.key.substr(0,1) == "emp") {
                        if ($("#employee-user_id").val() != "") {
                            $("#employee-user_id").select2("val", "");
                        }
                        if ($("#list").data("\'"+data.node.key+"\'") == undefined){
                            $("#list").data("\'"+data.node.key+"\'", true);
                            getEmployeeInfo(data.node.key.substr(4, data.node.key.length-4));
                            console.log("::"+data.node.key);
                        } else {
                            var key = "profile-"+data.node.key.substr(4, data.node.key.length-4);
                            $("#"+key).show();
                            $("#title-"+key).show();
                        }
                    }
                }'),
            ]
        ]); ?>
    </div>

    <div class="col-md-9">
        <div id="info" class="panel panel-default">
            <div class="panel-heading">Информация</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tr>
                        <th>Наименование и адрес КСК</th>
                        <td id="address_ksk">А-3/5, АПК, Аксай-3, д. 10, кв. 5</td>
                    </tr>
                    <tr>
                        <th>Кол-во домов</th>
                        <td id="amount_house">5</td>
                    </tr>
                    <tr>
                        <th>Адреса домов</th>
                        <td id="address_house">Аксай-3, д. 2, 8-11</td>
                    </tr>
                    <tr>
                        <th>Ф.И.О. председателя</th>
                        <td id="fullname_chairman">Вергель Ольга Сергеевна</td>
                    </tr>
                    <tr>
                        <th>Телефон</th>
                        <td id="phone">р.т 231031, ав 231757</td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="doc" class="panel panel-default">
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
                $('#address_ksk').html(data.data.address_ksk);
                $('#amount_house').html(data.data.amount_house);
                $('#address_house').html(data.data.address_house);
                $('#fullname_chairman').html(data.data.fullname_chairman);
                $('#phone').html(data.data.phone);
                $('#doc').hide();
                $('#info').show();
            }
        },'json');
}
function getDoc(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/doc/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                console.log(data.data);
                $('#doc_body').html('');
                $.each(data.data, function(key, value) {
                    var path = value.substr(0,2)+'/'+value.substr(3,2)+'/'+value.substr(6,2)+'/'+value;
                    path = 'http://statics.ksk.local/attachments/store/' + path;
                    console.log(path);
                    $('#doc_body').append('<img src="'+path+'" style="width: 100%">')
                });
                $('#info').hide();
                $('#doc').show();
            }
        },'json');
}
JS;

$this->registerJS($js, \yii\web\View::POS_HEAD);

$js = <<<JS
$(document).ready(function() {
    console.log('ready');
    $('#info').hide();
    $('#doc').hide();
});
JS;

$this->registerJS($js, \yii\web\View::POS_READY);

?>
