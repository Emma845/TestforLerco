<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\models\esys\EsysListaDesplegable;
use app\models\Esys;
?>

<div class="gestion-lista-form">
    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-body">
                    <div class="col-lg-3 col-xs-3">
                        <div class="media pad-all">
                            <div class="media-left">
                                <i class="demo-pli-male icon-2x" width="64" height="64"></i>
                            </div>
                            <div class="media-body pad-lft">
                                <h4 class="mar-no text-main"><?= $user->nombreCompleto ?> </h4>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-3">
                        <div class="row mar-top">
                            <div class="col-sm-12 text-center">
                                <div class="text-lg"><p class="text-2x text-thin text-main mar-no"><?= isset($user->nivel->singular) ? $user->nivel->singular : '' ?> <?= isset($user->grado->singular) ? $user->grado->singular : '' ?> </p></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 text-center" style="padding-top: 3%;">
                        <h5 class="text-uppercase text-muted text-normal  text-thin mar"><?= Esys::fecha_en_texto(time()) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">PASE DE LISTA</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'form-lista']) ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered invoice-summary">
                                <thead>
                                    <tr class="bg-trans-dark">
                                        <td class="text-center">ID</td>
                                        <td class="text-center">ALUMNO</td>
                                        <td class="text-center">ASISTENCIA</td>
                                        <td class="text-center">FUERA DE LINEA</td>
                                        <td class="text-center">INASISTENCIA</td>
                                        <td class="text-center">JUSTIFICADO</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($user->padresFamiliaLista() as $key => $alumno): ?>
                                    <tr>
                                        <td class="text-center"><?= Html::a( "[" . $alumno->id ."]",Url::to([ "/alumnos/alumno/view", "id" => $alumno->id ]),["class" => "text-primary"]) ?></td>
                                        <td><?= $alumno->nombreCompleto ?></td>
                                        <td class="text-center">
                                            <?= Html::checkbox("Asistencia[$alumno->id]",
                                                 $model->id ?  $model->validateAsistencia($alumno->id) : false,
                                                [
                                                    "id"    => "asistencia_id_{$alumno->id}_access",
                                                    "class" => "modulo magic-checkbox class_$alumno->id",
                                                    "onChange" => "validar(this);"
                                                ]
                                            ) ?>
                                            <?= Html::label("", "asistencia_id_{$alumno->id}_access", ["style" => "display:inline"]) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= Html::checkbox("Ausente[$alumno->id]",
                                                $model->id ?  $model->validateAusente($alumno->id) : false,
                                                [
                                                    "id"    => "ausente{$alumno->id}_access",
                                                    "class" => "modulo magic-checkbox class_$alumno->id",
                                                    "onChange" => "validar(this);"
                                                ]
                                            ) ?>
                                            <?= Html::label("", "ausente{$alumno->id}_access", ["style" => "display:inline"]) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= Html::checkbox("SinAsistencia[$alumno->id]",
                                                $model->id ?  $model->validateSinAsistencia($alumno->id) : false,
                                                [
                                                    "id"    => "sin_asistencia{$alumno->id}_access",
                                                    "class" => "modulo magic-checkbox class_$alumno->id",
                                                    "onChange" => "validar(this);"
                                                ]
                                            ) ?>
                                            <?= Html::label("", "sin_asistencia{$alumno->id}_access", ["style" => "display:inline"]) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= Html::checkbox("justificado[$alumno->id]",
                                                $model->id ?  $model->validateJustificado($alumno->id) : false,
                                                [
                                                    "id"    => "justificado{$alumno->id}_access",
                                                    "class" => "modulo magic-checkbox class_$alumno->id",
                                                    "onChange" => "validar(this);"
                                                ]
                                            ) ?>
                                            <?= Html::label("", "justificado{$alumno->id}_access", ["style" => "display:inline"]) ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::label("Nota", "text_area_nota", ["style" => "display:inline"]) ?>
                            <?= Html::textarea('text_area_nota', $model->id ? $model->nota : null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Guardar pase de lista' : 'Guardar cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validar(object){
        var select = document.getElementsByClassName($('#'+object.id).attr('class'));
        $.each(select, function(key, value){
            if(object.id != value.id){
                $('#'+value.id).prop("checked", false);
            }
        });
        
    }
</script>




