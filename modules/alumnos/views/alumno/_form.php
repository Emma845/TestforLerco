<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use app\models\esys\EsysListaDesplegable;
?>

<div class="alumnos-alumno-form">

    <?php $form = ActiveForm::begin(['id' => 'form-alumno']) ?>
    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información generales</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'sexo')->dropDownList([ 10 => 'Hombre', 20 => 'Mujer', ], ['prompt' => '']) ?>

                            <?= $form->field($model, 'vive_con')->dropDownList( EsysListaDesplegable::getItems('vive_con'), ['prompt' => '']) ?>


                            <?= $form->field($model, 'lugar_nacimiento')->textInput(['maxlength' => true]) ?>

                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::classname(), [
                                'options' => ['placeholder' => 'Fecha de nacimiento'],
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'language' => 'es',
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd',
                                ]
                            ]) ?>

                            <?= $form->field($model, 'nombre_vive_con')->textInput(['placeholder' => 'Nombre de la persona con la que vive','maxlength' => true]) ?>



                        </div>
                    </div>
                </div>
            </div>
             <hr>
            <div class="panel">
                <div class="panel-body">
                    <h3>INFORMACION ESCOLAR</h3>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'nivel')->dropDownList( EsysListaDesplegable::getItems('nivel')) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'grado')->dropDownList(EsysListaDesplegable::getItems('grado')) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'ciclo_escolar_id')->dropDownList( EsysListaDesplegable::getItems('ciclo_escolar')) ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="panel">
                <div class="panel-body">
                    <h3>INFORMACION CLINICA</h3>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'tipo_sangre')->dropDownList( EsysListaDesplegable::getItems('tipo_sangre'), ['prompt' => '']) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'peso')->textInput(['type' => 'number' ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'talla')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="panel">
                <div class="panel-body">
                    <h3>PADECIMIENTOS</h3>
                    <div class="row">
                        <div class="col-lg-4">

                            <?= $form->field($model, 'enfermedades_lesiones')->textarea(['rows' => 6]) ?>
                        </div>
                        <div class="col-lg-4">

                            <?= $form->field($model, 'antecedentes_enfermedades')->textarea(['rows' => 6]) ?>
                        </div>
                        <div class="col-lg-4">

                            <?= $form->field($model, 'discapacidad')->textarea(['rows' => 6]) ?>
                        </div>
                    </div>
                </div>
            </div>

             <div class="panel">
                <div class="panel-body">
                    <div style="display: block;">
                        <h3 style="display: inline-block;">PAGOS Y COLEGIATURA <small style="display: inline-block;"><?= $form->field($model, 'is_especial')->checkbox(['checked' =>  $model->is_especial == 10 ?  true : false ]) ?>
                        <?= $form->field($model, 'factura')->checkbox(); ?></small></h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'costo_colegiatura')->textInput(['type' => 'number' , "style" => "text-align: center", "disabled" => $model->is_especial == 10 ? false: true ]) ?>
                        </div>
                        <div class="col-lg-4">


                            <?= $form->field($model, 'colegiaturas_especial')->dropDownList([
                                1 => '1 Mes',
                                2 => '2 Meses',
                                3 => '3 Meses',
                                4 => '4 Meses',
                                5 => '5 Meses',
                                6 => '6 Meses',
                                7 => '7 Meses',
                                8 => '8 Meses',
                                9 => '9 Meses',
                                10 => '10 Meses',
                                11 => '11 Meses',
                                12 => '12 Meses',
                            ], ["disabled" => $model->is_especial == 10 ? false: true ])->label("Colegiaturas") ?>
                        </div>
                        <div class="col-lg-4">

                            <?= $form->field($model, 'costo_colegiatura_especial')->textInput(['type' => 'number',"style" => "text-align: center", "disabled" => $model->is_especial == 10 ? false: true ])->label("Costo de colegiatura ESPECIAL") ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Información extra / Comentarios</h3>
                        </div>
                        <div class="panel-body">
                            <?= $form->field($model, 'cuenta_equipo_internet')->dropDownList([ 10 => 'Sí', 20 => 'No', ], ['prompt' => '']) ?>

                            <?= $form->field($model, 'nota')->textarea(['rows' => 6]) ?>
                        </div>
                    </div>
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

<script>
    var $CheckInputEspecial = $('#alumno-is_especial');

    $CheckInputEspecial.change(function(){
        if ($(this).is(':checked')) {
            $('#alumno-costo_colegiatura').attr('disabled',false);
            $('#alumno-colegiaturas_especial').attr('disabled',false);
            $('#alumno-costo_colegiatura_especial').attr('disabled',false);
        }else{
            $('#alumno-costo_colegiatura').attr('disabled',true);
            $('#alumno-colegiaturas_especial').attr('disabled',true);
            $('#alumno-costo_colegiatura_especial').attr('disabled',true);
        }
    });
</script>