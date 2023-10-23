<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\password\PasswordInput;
use app\models\user\User;
use app\models\user\UserAsignarPerfil;
use app\models\esys\EsysListaDesplegable;
use app\models\sucursal\Sucursal;
use app\models\esys\EsysDireccionCodigoPostal;

/* @var $this yii\web\View */
/* @var $user app\models\user\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sucursal-user-form">

    <?php $form = ActiveForm::begin(['id' => 'form-user']) ?>

    <?= $form->field($user, 'titulo_personal_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información generales</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <?= $form->field($user, 'titulo_personal_id')->dropDownList(EsysListaDesplegable::getItems('titulo_personal'), ['prompt' => ''])->label("&nbsp;") ?>
                                </div>
                                <div class="col-sm-8">
                                    <?= $form->field($user, 'nombre')->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <?= $form->field($user, 'apellidos')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($user, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter e-mail')]) ?>
                            <?= $form->field($user, 'username')->textInput(['placeholder' => Yii::t('app', 'Create username'), 'autofocus' => true]) ?>
                            <?= $form->field($user, 'password')->widget(PasswordInput::classname(), ['options' => ['placeholder' => $user->scenario === 'create'? 'Crear contraseña': 'Cambiar contraseña (si lo desea)']]) ?>
                            <?= $form->field($user, 'status')->dropDownList(User::$statusList) ?>
                            <?= $form->field($user, 'departamento_id')->dropDownList(EsysListaDesplegable::getItems('departamento_laboral'), ['prompt' => '']) ?>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-5">
                                    <?= $form->field($user, 'sexo')->dropDownList([ 'hombre' => 'Hombre', 'mujer' => 'Mujer', ], ['prompt' => '']) ?>
                                </div>
                                <div class="col-sm-7">
                                <?= $form->field($user, 'fecha_nac')->widget(DatePicker::classname(), [
                                    'options' => ['placeholder' => 'Fecha de nacimiento'],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd',
                                    ]
                                ]) ?>
                                </div>
                            </div>

                            <?= $form->field($user, 'cargo')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($user, 'telefono')->textInput(['maxlength' => 10]) ?>
                            <?= $form->field($user, 'telefono_movil')->textInput(['maxlength' => 10 ]) ?>
                            <?= $form->field($user, 'tipo')->dropDownList(User::$tipoList) ?>
                            <?= $form->field($user, 'nivel_id')->dropDownList(EsysListaDesplegable::getItems('nivel'), ['prompt' => ''], ['prompt' => '']) ?>
                            <?= $form->field($user, 'grado_id')->dropDownList(EsysListaDesplegable::getItems('grado'), ['prompt' => '']) ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Permisos</h3>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($user, 'item_name')->dropDownList(UserAsignarPerfil::getItems(['withTheCreator' => true, 'with@uthenticated' => true])) ?>

                            <div class="perfiles_container mar-btm">
                                <?= Html::label('Perfiles que pudiera asignar', 'user-perfiles_names', ['class' => 'control-label']) ?>
                                <?= Select2::widget([
                                    'id' => 'user-perfiles_names',
                                    'name' => 'User[perfiles_names]',
                                    'value' => $user->perfiles_names,
                                    'data' => UserAsignarPerfil::getItems(),
                                    'options' => [
                                        'placeholder' => 'Perfiles que pudiera asignar',
                                        'multiple' => true,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]) ?>
                            </div>

                            <div class="sucursales_container mar-btm">
                                <?= Html::label('Sucursales asignadas', 'user-sucursal_names', ['class' => 'control-label']) ?>
                                <?= Select2::widget([
                                    'id' => 'user-sucursal_names',
                                    'name' => 'User[sucursal_names]',
                                    'value' => $user->sucursal_names,
                                    'data' => Sucursal::getItems(),
                                    'options' => [
                                        'placeholder' => 'Sucursales que pudiera asignar',
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
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información extra / Comentarios</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($user, 'informacion')->textarea(['rows' => 6]) ?>
                    <?= $form->field($user, 'comentarios')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
             <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Origen</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($user, 'origen')->dropDownList(User::$origenList)->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-warning" id ="alert-usa" style="display: none">
                <strong>
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">¡Advertencia! </font>
                    </font>
                </strong>
                <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;"> Captura la dirección de forma correcta antes de guardar, ya que se utilizara como referencia en el sistema.</font>
                </font>
            </div>
            <div id="direccion_mx" style="display: none">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dirección MX</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <?= $form->field($user->dir_obj, 'codigo_search')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div id="error-codigo-postal" class="has-error" style="display: none">
                                <div class="help-block">Codigo postal invalido, verifique nuevamente ó busque la dirección manualmente</div>
                            </div>
                        </div>

                        <?= $form->field($user->dir_obj, 'estado_id')->widget(Select2::classname(), [
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

                        <?= $form->field($user->dir_obj, 'municipio_id')->widget(Select2::classname(), [
                            'language' => 'es',
                            'data' => $user->dir_obj->estado_id? EsysListaDesplegable::getMunicipios(['estado_id' => $user->dir_obj->estado_id]): [],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            'options' => [
                                'placeholder' => 'Selecciona el municipio'
                            ],
                        ]) ?>

                        <?= $form->field($user->dir_obj, 'codigo_postal_id')->widget(Select2::classname(), [
                            'language' => 'es',
                            'data' => $user->dir_obj->codigo_postal_id ? EsysDireccionCodigoPostal::getColonia(['codigo_postal' => $user->dir_obj->codigo_search]) : [],
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
            <div id="direccion_usa" style="display: none">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dirección USA</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <?= $form->field($user->dir_obj, 'codigo_postal_usa')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <?= $form->field($user->dir_obj, 'estado_usa')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($user->dir_obj, 'municipio_usa')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($user->dir_obj, 'colonia_usa')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <?= $form->field($user->dir_obj, 'direccion')->textInput(['maxlength' => true]) ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($user->dir_obj, 'num_ext')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($user->dir_obj, 'num_int')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($user->isNewRecord ? 'Crear usuario' : 'Guardar cambios', ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id' => 'btnSaveUser']) ?>
        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    /************************************
    / Estados y municipios
    /***********************************/
var $inputEstado       = $('#esysdireccion-estado_id'),
    $inputMunicipio    = $('#esysdireccion-municipio_id'),
    $inputCodigoSearch = $('#esysdireccion-codigo_search'),
    $inputColonia      = $('#esysdireccion-codigo_postal_id'),
    $error_codigo      = $('#error-codigo-postal'),
    $inputOrigen       = $('#user-origen'),
    $alertUsa          = $('#alert-usa'),
    $user_tipo         = $('#user-tipo'),
    $item_name         = $('#user-item_name'),
    $btnSaveUser       = $('#btnSaveUser'),
    $panel_direccion_mx = $('#direccion_mx'),
    $panel_direccion_usa = $('#direccion_usa'),
    $div_asignado_supervisor    = $('.agentes_asignados_container'),
    $div_asignado_agentes       = $('.supervisor_asignado_container'),
    $user_agentes_asignados     = $('#user-agentes_asignados'),
    $user_supervisor_asignados     = $('#user-supervisor_asignado'),



    municipioSelected  = null;

    $(document).ready(function() {

        alertWarning();

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

        $inputMunicipio.change(function(){
            if ($inputEstado.val() != 0 && $inputMunicipio.val() != 0 && $inputCodigoSearch.val() == "" ) {
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

        $inputOrigen.change(function(){
            alertWarning();
        });



        $btnSaveUser.on('click', function(event){
            event.preventDefault();
            if ($item_name.val() != '@uthenticated' ) {
                $(this).submit();
            }else
                $.niftyNoty({
                    type: 'danger',
                    icon : 'pli-cross icon-2x',
                    message : 'Debes asignarle un peril al usuario',
                    container : 'floating',
                    timer : 5000
                });

        });

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
    var alertWarning = function()
    {
        if($inputOrigen.val() == 1)
        {
            $alertUsa.show();
            $panel_direccion_usa.show();
            $panel_direccion_mx.hide();
        }
        else
        {
            $alertUsa.hide();
            $panel_direccion_mx.show();
            $panel_direccion_usa.hide();
        }
    }

</script>
