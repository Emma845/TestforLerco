<?php
use yii\helpers\Html;

?>

<p>
    <?= $can['update'] ?
        Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-mint']): '' ?>

</p>

<div class="articulos-articulo-view" style="text-transform:uppercase">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="jumbotron col-md-5" style="background-color: white; height:650px">
                                <h2 style="font-size: 40px;">CICLO ESCOLAR <?=$model->year?> - <?=$model->year_fin?></h2>
                                <p class="lead" style="margin-top:60px; text-transform: uppercase;"> <strong>NOTAS: </strong> <em style="color:blue; font-weight:bold; font-size:24px;"><?=$model->notas?></em></p>
                                <div class="row">
                                     <p style="margin-left: 10px; text-transform:uppercase;"><strong>Inicio del ciclo escolar: </strong> <em style="color:blue; font-weight:bold;"><?=date("m/d/Y",$model->rango_a);?></em></p>
                                     <p style="margin-left: 10px; text-transform:uppercase;"><strong>Fin del ciclo escolar: <em style="color:blue; font-weight:bold;"></strong><?=date("m/d/Y",$model->rango_b)?></em></p>
                                </div>
                                <div class="row">
                                    <div class="col-md-8" style="margin-top: 50px;">                                    
                                        <p class="lead">
                                            <?= Html::a('Generar tarifas', ['tarifas', 'id' => $model->id], ['class' => 'btn btn-mint btn-large', 'style' => 'padding:15px 30px; font-size:20px;'])?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7" style="margin-top:30px; text-transform: uppercase;">
                            <h2 style="font-size: 40px;">tarifas </h2>
                            <table class="table" style="margin-top: 60px; font-size:18px;">
                                <thead>
                                    <tr>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">inscripcion</th>
                                    <th scope="col">colegiatura</th>
                                    <th scope="col">cargo de mora</th>
                                    <th scope="col">Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">preescolar</th>
                                        <td><?= isset($pri->inscripcion)? $pri->inscripcion : 'Sin asignar'?></td>
                                        <td><?= isset($pri->colegiatura)? $pri->colegiatura : 'Sin asignar'?></td>
                                        <td><?= isset($pri->mora)? $pri->mora : 'Sin asignar'?></td>
                                        <td><?=isset($pri->mora) && isset($pri->colegiatura) && isset($pri->inscripcion)? Html::a('<i>', ['update-tarifas', 'id' => $model->id, 'grado' =>10], ['class' => 'fa fa-edit','style' => 'color:blue;']) : "config pendiente"?></i></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">primaria</th></th>
                                        <td><?= isset($sec->inscripcion)? $sec->inscripcion : 'Sin asignar'?></td>
                                        <td><?= isset($sec->colegiatura)? $sec->colegiatura : 'Sin asignar'?></td>
                                        <td><?= isset($sec->mora)? $sec->mora : 'Sin asignar'?></td>
                                        <td><?=isset($sec->mora) && isset($sec->colegiatura) && isset($sec->inscripcion)? Html::a('<i>', ['update-tarifas', 'id' => $model->id, 'grado' =>20], ['class' => 'fa fa-edit','style' => 'color:blue;']) : "config pendiente"?></i></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">secundaria</th>
                                        <td><?= isset($pre->inscripcion)? $pre->inscripcion : 'Sin asignar'?></td>
                                        <td><?= isset($pre->colegiatura)? $pre->colegiatura : 'Sin asignar'?></td>
                                        <td><?= isset($pre->mora)? $pre->mora : 'Sin asignar'?></td>
                                        <td><?=isset($pre->mora) && isset($pre->colegiatura) && isset($pre->inscripcion)? Html::a('<i>', ['update-tarifas', 'id' => $model->id, 'grado' =>30], ['class' => 'fa fa-edit','style' => 'color:blue;']) : "config pendiente"?></i></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


