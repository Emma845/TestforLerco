<?php

$this->title = 'Nuevo pase de lista';
$this->params['breadcrumbs'][] = 'Gestión';
$this->params['breadcrumbs'][] = ['label' => 'Pases de lista', 'url' => ['index']];
?>

<div class="gestion-lista-create">

    <?= $this->render('_form', [
        'model' => $model,
        'user'  => $user,
    ]) ?>

</div>
