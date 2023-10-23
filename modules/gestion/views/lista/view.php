<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\Esys;

$this->title =  "Pase de lista : [".$model->id ."] " . Esys::fecha_en_texto($model->created_at,true);

$this->params['breadcrumbs'][] = ['label' => 'Lista', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;

?>
<p>
    <?= $can['delete']?
    Html::a('Eliminar', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => '¿Estás seguro de que deseas eliminar el pase de lista?',
            'method' => 'post',
        ],
    ]): '' ?>
</p>
<p>
    <?= $can['view']?
    Html::a('Imprimir', ['print', 'id' => $model->id], [
        'class' => 'btn btn-info',
        'data' => [
            'confirm' => '¿Estás seguro de que deseas imprimir el pase de lista?',
            'method' => 'post',
        ],
    ]): '' ?>
</p>

<div class="gestion-lista-view">
    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-body">
                    <div class="col-lg-3 col-xs-3">
                        <div class="media pad-all">
                            <div class="media-left">
                                <i class="demo-pli-male icon-2x" width="64" height="64"></i>
                            </div>
                            <div class="media-body pad-lft">
                                <h4 class="mar-no text-main"><?= $user->nombreCompleto ?> </h4>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-3">
                        <div class="row mar-top">
                            <div class="col-sm-12 text-center">
                                <div class="text-lg"><p class="text-2x text-thin text-main mar-no"><?= $user->nivel->singular ?> <?= $user->grado->singular ?> </p></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 text-center" style="padding-top: 3%;">
                        <h5 class="text-uppercase text-muted text-normal  text-thin mar"><?= Esys::fecha_en_texto($model->created_at) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">PASE DE LISTA</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered invoice-summary">
                                <thead>
                                    <tr class="bg-trans-dark">
                                        <td class="text-center">ID</td>
                                        <td class="text-center">ALUMNO</td>
                                        <td class="text-center">ASISTENCIA</td>
                                        <td class="text-center">FUERA DE LINEA</td>
                                        <td class="text-center">INASISTENCIA</td>
                                        <td class="text-center">JUSTIFICADO</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($user->padresFamiliaLista($model->created_by) as $key => $alumno): ?>
                                    <tr>
                                        <td class="text-center"><?= Html::a( "[" . $alumno->id ."]",Url::to([ "/alumnos/alumno/view", "id" => $alumno->id ]),["class" => "text-primary"]) ?></td>
                                        <td><?= $alumno->nombreCompleto ?></td>
                                        <td class="text-center">
                                            <?php if ($model->validateAsistencia($alumno->id)): ?>
                                                <i class='fa fa-check-square-o' aria-hidden="true"></i>
                                            <?php else: ?>
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($model->validateAusente($alumno->id)): ?>
                                                <i class='fa fa-check-square-o' aria-hidden="true"></i>
                                            <?php else: ?>
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($model->validateSinAsistencia($alumno->id)): ?>
                                                <i class='fa fa-check-square-o' aria-hidden="true"></i>
                                            <?php else: ?>
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($model->validateJustificado($alumno->id)): ?>
                                                <i class='fa fa-check-square-o' aria-hidden="true"></i>
                                            <?php else: ?>
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                             <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'nota:ntext',
                                ]
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <?= app\widgets\CreatedByView::widget(['model' => $model]) ?>
        </div>
    </div>
</div>

