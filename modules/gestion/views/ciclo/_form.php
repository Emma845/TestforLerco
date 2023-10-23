<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\sucursales\Sucursal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gestion-articulo-form">

    <?php $form = ActiveForm::begin(['id' => 'form-ciclo','options' => ['enctype' => 'multipart/form-data']]) ?>

    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información generales</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-6">
                                <?= $form->field($model, 'rango_a')->widget(kartik\date\DatePicker::className(), [
                                    'options' => ['class' => 'form-control'],
                                ]) ?>
                                </div>
                                <div class="col-lg-6">
                                <?= $form->field($model, 'rango_b')->widget(kartik\date\DatePicker::className(), [
                                    'options' => ['class' => 'form-control'],
                                ]) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'year')->input('number') ?>
                                </div>
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'year_fin')->input('number') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'notas')->textarea(['style' => 'height:250px'] ,['maxlength' => true]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Agregar ciclo' : 'Guardar cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

