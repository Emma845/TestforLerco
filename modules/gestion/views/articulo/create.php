<?php
$this->title = 'Nuevo articulo';
$this->params['breadcrumbs'][] = 'Articulo';
$this->params['breadcrumbs'][] = ['label' => 'Articulos', 'url' => ['index']];
?>

<div class="gestion-articulo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
