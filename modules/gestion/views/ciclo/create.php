<?php
$this->title = 'Nuevo ciclo escolar';
$this->params['breadcrumbs'][] = 'Ciclo escolar';
$this->params['breadcrumbs'][] = ['label' => 'Articulos', 'url' => ['index']];
?>

<div class="gestion-ciclo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
