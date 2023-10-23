<?php
$this->title = 'Nuevo documento';
$this->params['breadcrumbs'][] = 'Documento';
$this->params['breadcrumbs'][] = ['label' => 'Documentos', 'url' => ['index']];
?>

<div class="gestion-documento-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
