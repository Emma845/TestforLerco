<?php


$this->title = 'CICLO ESCOLAR - '. $model->year.'-'.$model->year_fin;
$this->params['breadcrumbs'][] = ['label' => 'ciclo escolar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';

?>


<div class="gestion-articulo-update">

    <?= $this->render('_form', [
    	'model' => $model,
    ]) ?>

</div>
