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

<div class="gestion-lista-view">
    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col text-center"> 
                            <div class="text-lg"><p class="text-2x text-thin text-main mar-no"><?= $user->nivel->singular ?> <?= $user->grado->singular ?> </p></div>
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr class="tabla">
                                <td align="left"><h4 class="mar-no text-main"><?=$user->tituloPersonal->singular.' '. $user->nombreCompleto ?> </h4></strong></td>
                                <td align="right"><h5 class="text-uppercase text-muted text-normal  text-thin mar"><?= Esys::fecha_en_texto($model->created_at) ?></h5></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">PASE DE LISTA</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered invoice-summary">
                                <thead>
                                    <tr class="bg-trans-dark">
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
                                        <td><?= $alumno->nombreCompleto ?></td>
                                        <td class="text-center">
                                            <?php if ($model->validateAsistencia($alumno->id)): ?>
                                                <?= Html::img('@web/img/icon-check.png', ["height"=>"20px"]) ?>
                                            <?php else: ?>
                                                <?= Html::img('@web/img/icon-times.png', ["height"=>"20px"]) ?>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($model->validateAusente($alumno->id)): ?>
                                                <?= Html::img('@web/img/icon-check.png', ["height"=>"20px"]) ?>
                                            <?php else: ?>
                                                <?= Html::img('@web/img/icon-times.png', ["height"=>"20px"]) ?>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($model->validateSinAsistencia($alumno->id)): ?>
                                                <?= Html::img('@web/img/icon-check.png', ["height"=>"20px"]) ?>
                                            <?php else: ?>
                                                <?= Html::img('@web/img/icon-times.png', ["height"=>"20px"]) ?>
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($model->validateJustificado($alumno->id)): ?>
                                                <?= Html::img('@web/img/icon-check.png', ["height"=>"20px"]) ?>
                                            <?php else: ?>
                                                <?= Html::img('@web/img/icon-times.png', ["height"=>"20px"]) ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                    <tr>
                                        <td width="276" align="center"><h5>Nota:</h5></strong></td>
                                        <td colspan="4" align="left"><?= $model->nota ?></td>
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

