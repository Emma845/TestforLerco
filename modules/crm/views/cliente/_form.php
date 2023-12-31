<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;
use app\models\esys\EsysDireccionCodigoPostal;
use app\models\user\User;
use app\models\documento\Documento;
/*
use yii\web\JsExpression;
use kartik\date\DatePicker;
use app\models\user\UserAsignarPerfil;
*/
use app\models\cliente\Cliente;
use app\models\esys\EsysListaDesplegable;

/* @var $this yii\web\View */
/* @var $cliente app\models\cliente\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="clientes-cliente-form">

    <?php $form = ActiveForm::begin(['id' => 'form-cliente']) ?>

    <?= $form->field($model, 'titulo_personal_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información generales</h3>
                </div>
                <div class="panel-body">
                            <?= $form->field($model, 'atraves_de_id')->dropDownList(EsysListaDesplegable::getItems('origen_cliente'), ['prompt' => '']) ?>
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="row">
                                <div class="col-sm-4">
                                    <?= $form->field($model, 'titulo_personal_id')->dropDownList(EsysListaDesplegable::getItems('titulo_personal'), ['prompt' => ''])->label("&nbsp;") ?>
                                </div>
                                <div class="col-sm-8">
                                    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter e-mail')]) ?>

                            <?= $form->field($model, 'tipo_cliente_id')->dropDownList(EsysListaDesplegable::getItems('tipo_cliente'), ['prompt' => '']) ?>

                            <?= $form->field($model, 'parentesco')->dropDownList(EsysListaDesplegable::getItems('parentesco_alumno'), ['prompt' => '']) ?>

                            <?= $form->field($model, 'rfc')->textInput(['maxlength' => true]) ?>


                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'sexo')->dropDownList([ 10 => 'Hombre', 20 => 'Mujer', ], ['prompt' => '']) ?>
                            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'telefono_movil')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'whatsapp')->textInput(['type' => 'number' ]) ?>

                            <?= $form->field($model, 'asignado_id')->widget(Select2::classname(),
                            [
                                'language' => 'es',
                                    'data' => isset($model->asignado_id)  && $model->asignado_id ? [$model->asignadoCliente->id => $model->asignadoCliente->nombre ." ". $model->asignadoCliente->apellidos] : [],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        'language'   => [
                                            'errorLoading' => new JsExpression("function () { return 'Esperando los resultados...'; }"),
                                        ],
                                        'ajax' => [
                                            'url'      => Url::to(['/admin/user/user-ajax']),
                                            'dataType' => 'json',
                                            'cache'    => true,
                                            'processResults' => new JsExpression('function(data, params){  return {results: data} }'),
                                        ],
                                    ],
                                    'options' => [
                                        'placeholder' => 'Selecciona al usuario...',
                                    ],

                            ]) ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row div">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Estatus</h3>
                        </div>
                        <div class="panel-body">
                            <?= $form->field($model, 'status')->dropDownList(Cliente::$statusList)->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>

           <!--<div class="row div">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Configuración de pago</h3>
                        </div>
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
            </div>-->

            <?php /* ?>
            <div class="row div">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Documentos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="documentos_container mar-btm">
                                <?= Select2::widget([
                                    'id' => 'cliente-cliente_documento',
                                    'name' => 'Cliente[cliente_documento]',
                                    'value' => $model->cliente_documento,
                                    'data' => Documento::getItems(),
                                    'options' => [
                                        'placeholder' => 'Documentos a solicitar',
                                        'multiple' => true,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            */?>


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Información extra / Comentarios</h3>
                        </div>
                        <div class="panel-body">
                            <?= $form->field($model, 'notas')->textarea(['rows' => 6])->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div id="direccion_mx">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dirección MX</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <?= $form->field($model->dir_obj, 'codigo_search')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div id="error-codigo-postal" class="has-error" style="display: none">
                                <div class="help-block">Codigo postal invalido, verifique nuevamente ó busque la dirección manualmente</div>
                            </div>
                        </div>

                        <?= $form->field($model->dir_obj, 'estado_id')->widget(Select2::classname(), [
                            'language' => 'es',
                            'data' => EsysListaDesplegable::getEstados(),
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            'options' => [
                                'placeholder' => 'Selecciona el estado',
                            ],
                            'pluginEvents' => [
                                "change" => "function(){ onEstadoChange() }",
                            ]
                        ]) ?>

                        <?= $form->field($model->dir_obj, 'municipio_id')->widget(Select2::classname(), [
                            'language' => 'es',
                            'data' => $model->dir_obj->estado_id? EsysListaDesplegable::getMunicipios(['estado_id' => $model->dir_obj->estado_id]): [],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            'options' => [
                                'placeholder' => 'Selecciona el municipio'
                            ],
                        ]) ?>

                        <?= $form->field($model->dir_obj, 'codigo_postal_id')->widget(Select2::classname(), [
                            'language' => 'es',
                            'data' => $model->dir_obj->codigo_postal_id ? EsysDireccionCodigoPostal::getColonia(['codigo_postal' => $model->dir_obj->codigo_search]) : [],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            'options' => [
                                'placeholder' => 'Selecciona la colonia'
                            ],
                        ])->label('Colonia') ?>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <?= $form->field($model->dir_obj, 'direccion')->textInput(['maxlength' => true]) ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model->dir_obj, 'num_ext')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model->dir_obj, 'num_int')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <?= $form->field($model->dir_obj, 'referencia')->textArea(['rows' => 6 ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear cliente' : 'Guardar cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">
var $inputEstado       = $('#esysdireccion-estado_id'),
    $inputMunicipio    = $('#esysdireccion-municipio_id'),
    $inputCodigoSearch = $('#esysdireccion-codigo_search'),
    $inputColonia      = $('#esysdireccion-codigo_postal_id'),
    $error_codigo      = $('#error-codigo-postal'),
    $inputOrigen       = $('#cliente-origen'),
    $alertUsa          = $('#alert-usa'),
    $panel_direccion_mx = $('#direccion_mx'),
    $panel_direccion_usa = $('#direccion_usa'),
    municipioSelected  = null;


    $(document).ready(function() {

        $inputCodigoSearch.change(function() {
            $inputColonia.html('');
            $inputEstado.val(null).trigger("change");

            var codigo_search = $inputCodigoSearch.val();

            $.get('<?= Url::to('@web/municipio/codigo-postal-ajax') ?>', {'codigo_postal' : codigo_search}, function(json) {
                if(json.length > 0){
                    $error_codigo.hide();
                    $inputEstado.val(json[0].estado_id); // Select the option with a value of '1'
                    $inputEstado.trigger('change');
                    municipioSelected = json[0].municipio_id;

                    $.each(json, function(key, value){
                        $inputColonia.append("<option value='" + value.id + "'>" + value.colonia + "</option>\n");
                    });
                }
                else{
                    municipioSelected  = null;
                    $error_codigo.show();
                }

                $inputColonia
                    .val(null)
                    .trigger("change");

            }, 'json');
        });

    });

        $inputMunicipio.change(function(){
            console.log("entro");
            if ($inputEstado.val() != 0 && $inputMunicipio.val() != 0 && $inputCodigoSearch.val() != "" ) {
                $inputColonia.html('');
                $.get('<?= Url::to('@web/municipio/colonia-ajax') ?>', {'estado_id' : $inputEstado.val(), "municipio_id": $inputMunicipio.val()}, function(json) {
                    if(json.length > 0){
                        $.each(json, function(key, value){
                            $inputColonia.append("<option value='" + value.id + "'>" + value.colonia + "</option>\n");
                        });
                    }
                    else
                        municipioSelected  = null;

                    $inputColonia
                        .val(null)
                        .trigger("change");

                }, 'json');
            }
        });

    /************************************
    / Estados y municipios
    /***********************************/
    function onEstadoChange() {
        var estado_id = $inputEstado.val();
        municipioSelected = estado_id == 0 ? null : municipioSelected;

        $inputMunicipio.html('');

        $.get('<?= Url::to('@web/municipio/municipios-ajax') ?>', {'estado_id' : estado_id}, function(json) {
            $.each(json, function(key, value){
                $inputMunicipio.append("<option value='" + key + "'>" + value + "</option>\n");
            });

            $inputMunicipio.val(municipioSelected); // Select the option with a value of '1'
            $inputMunicipio.trigger('change');

        }, 'json');

    }

</script>

