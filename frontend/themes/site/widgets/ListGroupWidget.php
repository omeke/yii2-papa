<?php
/**
 * usage sample
 * <?= \frontend\themes\site\widgets\ListGroupWidget::widget([
 * 'data' => $data,
 * 'options' => [
 * 'padding' => 5
 * ]
 * ])?>
 *
 */


namespace frontend\themes\site\widgets;

use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\View;

class ListGroupWidget extends Widget
{

    public $data = null;
    public $options = [];

    public function init()
    {
        parent::init();
        if (is_null($this->data)) {
            throw new NotFoundHttpException('attribute $data is required');
        }
        $this->registerJS();
        $this->registerCSS();
    }

    private function initModal()
    {
        return '
            <div class="modal fade" id="info_m" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Информация</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <tr><th class="text-center">Наименование и адрес КСК:</th></tr>
                                <tr><td class="text-center" id="address_ksk_m"></td></tr>

                                <tr><th class="text-center">Кол-во домов:</th></tr>
                                <tr><td class="text-center" id="amount_house_m"></td></tr>

                                <tr><th class="text-center">Адреса домов:</th></tr>
                                <tr><td class="text-center" id="address_house_m"></td></tr>

                                <tr><th class="text-center">Ф.И.О. председателя:</th></tr>
                                <tr><td class="text-center" id="fullname_chairman_m"></td></tr>

                                <tr><th class="text-center">Телефон:</th></tr>
                                <tr><td class="text-center" id="phone_m"></td></tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div><!-- /.modal -->
        ';
    }

    private function rec($key, $list, $padding, $collapse = true)
    {
        $result = '<div id="' . $key . '" class="list-group ' . ($collapse ? 'collapse' : '') . '" style="padding-left:' . $padding . 'px">';
        foreach ($list as $item) {
            if (isset($item['children']) && sizeof($item['children']) > 0) {
                $result .= Html::button(
                    '<span class="badge">' . $item['count'] . '</span>' . $item['title'],
                    [
                        'class' => 'list-group-item accordion-toggle collapsed',
                        'data-toggle' => 'collapse',
                        'data-target' => '#' . $item['key'],
                    ]);
                $result .= $this->rec($item['key'], $item['children'], $padding + $this->options['padding']);
            } else {
                $result .= Html::button($item['title'], [
                    'class' => 'list-group-item list-group-item-info accordion-file',
                    'onclick' => 'tryInfoM(\'' . $item['key'] . '\')'
                ]);
            }
        }
        return $result . '</div>';
    }

    public function run()
    {
//        return VarDumper::dump($this->data, 10, true);
        if (!isset($this->options['padding'])) {
            $this->options['padding'] = 5;
        }
        $response = '';
        foreach ($this->data as $item) {
            $response .= '<h3 class="text-center">' . $item['title'] . '</h3>';
            if (isset($item['children']) && sizeof($item['children']) > 0) {
                $response .= $this->rec($item['key'], $item['children'], 0, false);
            }
        }
        $response .= $this->initModal();
        return $response;
    }

    private function registerJS()
    {
        $js = <<<JS
function getInfoM(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/info/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                $('#address_ksk_m').html(data.info.address_ksk);
                $('#amount_house_m').html(data.info.amount_house);
                $('#address_house_m').html(data.info.address_house);
                $('#fullname_chairman_m').html(data.info.fullname_chairman);
                $('#phone_m').html(data.info.phone);
                $('#info_m').modal('show');
            }
        },'json');
}

function getDocM(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/doc/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                $('#doc_body').html('');
                $.each(data.files, function(key, value) {
                    var path = value.substr(0,2)+'/'+value.substr(3,2)+'/'+value.substr(6,2)+'/'+value;
                    path = 'http://statics.kskauezov.kz/attachments/store/' + path;
                    console.log(path);
                    $('#doc_body').append('<img src="'+path+'" style="width: 100%">')
                });
                $('#doc_m').modal('show');
            }
        },'json');
}

function tryInfoM(key) {
    id = key.substr(2);
    switch (key.substr(0,1)) {
        case 'i':
            getInfoM(id);
            break;
        case 'd':
            getDocM(id);
            break;
    }
}
JS;
        $this->view->registerJs($js, View::POS_HEAD);
    }

    private function registerCSS()
    {
        $css = <<<CSS
        .accordion-file {
            width: 100%;
        }
        .accordion-file:before {
            font-family: 'Glyphicons Halflings';
            content: "\\e086";
            padding-right: 10px;
            float:left;
            color: gray;
        }
        .accordion-toggle {
            width: 100%;
        }
        .accordion-toggle:before {
            font-family: 'Glyphicons Halflings';
            content: "\\e118";
            padding-right: 10px;
            float:left;
            color: gray;
        }
        .accordion-toggle.collapsed:before {
            font-family: 'Glyphicons Halflings';
            content: "\\e117";
            padding-right: 10px;
            float:left;
            color: gray;
        }
CSS;
        $this->view->registerCss($css);
    }

}