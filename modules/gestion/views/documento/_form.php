<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
//use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\esys\EsysListaDesplegable;


/* @var $this yii\web\View */
/* @var $model app\models\sucursales\Sucursal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gestion-documento-form">

    <?php $form = ActiveForm::begin() ?>

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

                            <?= $form->field($model, 'tipo')->dropDownList(EsysListaDesplegable::getItems('tipo'), ['prompt' => '']) ?>

                            <?= $form->field($model, 'update')->dropDownList(EsysListaDesplegable::getItems('update_document'), ['prompt' => '']) ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear documento' : 'Guardar cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

