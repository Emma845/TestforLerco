<?php

use app\models\ciclo\CicloTarifa;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Asigne las tarifas al ciclo escolar';
$this->params['breadcrumbs'][] = ['label' => 'ciclo escolar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}


</style>

<div class="gestion-ciclo-index" style="text-transform:uppercase">
    <div class="panel">
        <div class="panel-content" style="height:650px;">
            <div class="container-fluid">
               <h1>Tarifas para el ciclo <?=$ciclo->year?> - <?=$ciclo->year_fin?></h1>
               <?php $form = ActiveForm::begin(['id' => 'form-ciclo','options' => ['enctype' => 'multipart/form-data']]); ?>
               <div class="col-lg-8" style="margin-top: 40px;">
                <div class="col-lg-6">
                     <?= $form->field($model, 'nivel')->dropDownList(CicloTarifa::$gradoList,['prompt' => '--select--','class'=> 'form-control', 'style'=>'font-weight:bold; font-size:24px;height:50px; border-width: 2px; border-color:black;border-radius:8px;']) ?>
                     <?= $form->field($model, 'inscripcion')->input('number',['class'=> 'form-control', 'style'=>'font-weight:bold; font-size:24px;height:50px; border-width: 2px; border-color:black;border-radius:8px;']) ?>
                     <?= $form->field($model, 'colegiatura')->input('number',['class'=> 'form-control', 'style'=>'font-weight:bold; font-size:24px;height:50px; border-width: 2px;border-color:black;border-radius:8px;']) ?> 
                     <?= $form->field($model, 'mora')->input('number',['class'=> 'form-control', 'style'=>'font-weight:bold; font-size:24px; height:50px; border-width: 2px; border-color:black;border-radius:8px;']) ?> 
                </div>
                <div class="col-lg-6">
                   
                   <?= $form->field($model, 'notas')->textarea(['class' => 'form-control', 'style'=>'font-weight:bold; font-size:24px;height:227px; border-width:2px; border-color:black; border-radius:8px;']) ?>
                </div>  
                <div class="col-lg-12" style="margin-top: 40px;">
                   <?= Html::submitButton('Guardar', ['class' => 'btn btn-mint', 'style' => 'padding:15px 30px; font-size:20px;']) ?>
                   </div>
               </div>
               <?php ActiveForm::end(); ?>
            </div>
            
        </div>
    </div>
</div>
