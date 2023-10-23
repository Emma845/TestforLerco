<?php


$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Articulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';

?>


<div class="gestion-articulo-update">

    <?= $this->render('_form', [
    	'model' => $model,
    ]) ?>

</div>
