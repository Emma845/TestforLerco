<?php
use yii\helpers\Html;

$this->title = $model->nombre . ' '. $model->apellidos;

$this->params['breadcrumbs'][] = ['label' => 'Padres / Tutores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
$this->params['breadcrumbs'][] = 'Editar';


?>

<div class="tab-base">
    <ul class="nav nav-tabs" ">
        <li class="active">
            <a data-toggle="tab" href="#tab-index">Padre / Tutor</a>
        </li>
        <li>
            <a data-toggle="tab" href="#tab-linea-credito">Alumno(s)</a>
        </li>
    </ul>
    <div class="tab-content" style="background:white;">
        <div id="tab-index" class="tab-pane fade active in">
            <?= $this->render('_view',[
                "can"   => $can,
                "model" => $model,
                ]) ?>
        </div>
        <div id="tab-linea-credito" class="tab-pane fade">
                <?= $this->render('_alumnos',[
                "can"   => $can,
                 "model" => $model,
                ]) ?>
        </div>
    </div>
</div>
