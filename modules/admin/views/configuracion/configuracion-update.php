<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\esys\EsysSetting;
/* @var $this yii\web\View */
/* @var $model app\models\sucursales\Sucursal */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Configuraciones del Sitio';

?>

<div class="configuraciones-configuracion-form">
    <div class="row">
        <div class="col-lg-7">
            <?php $form = ActiveForm::begin(['id' => 'form-configuracion' ]) ?>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($model->configuracionAll as $key => $item): ?>
                            <?php switch ( $item->clave ) {
                                case EsysSetting::SITE_NAME: ?>
                                    <div class="form-group">
                                        <?= Html::label('NOMBRE DEL SITIO', 'esysSetting_list') ?>

                                        <?= Html::input('text', 'esysSetting_list['.$item->clave.']',$item->valor,['class' => 'form-control']) ?>
                                    </div>
                                <?php  break;
                                case EsysSetting::SITE_EMAIL: ?>
                                    <div class="form-group">
                                        <?= Html::label('EMAIL DEL SITIO', 'esysSetting_list') ?>

                                        <?= Html::input('text', 'esysSetting_list['.$item->clave.']',$item->valor,['class' => 'form-control']) ?>
                                    </div>
                                <?php  break;
                            } ?>
                        <?php endforeach ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton( 'Guardar cambios', ['class' =>  'btn btn-primary']) ?>
                        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

