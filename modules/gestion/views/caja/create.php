<?php

$this->title = 'Nueva caja';
$this->params['breadcrumbs'][] = 'Cajas';
$this->params['breadcrumbs'][] = ['label' => 'Caja', 'url' => ['index']];

?>


<div class="cajas-caja-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 