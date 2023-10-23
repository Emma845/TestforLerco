<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\sucursal\Sucursal;
use app\models\caja\Caja;
/* @var $this yii\web\View */
/* @var $model common\models\ViewSucursal */

$this->title =  "#".$model->id ." - ". $model->cicloEscolar->singular;

$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;

?>

<div class="cajas-caja-view">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Información padre / tutor</h3>
                        </div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'padreTutor.id',
                                    'padreTutor.nombreCompleto',
                                    "padreTutor.email:email",
                                    "padreTutor.telefono",
                                    "padreTutor.telefono_movil",
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Alumno</h3>
                        </div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'alumno.id',
                                    'alumno.nombreCompleto',
                                    'alumno.gradoText.singular',
                                    'alumno.nivelText.singular',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $totalCobrado = 0 ?>

            <?php foreach ($model->cobros as $key => $item): ?>
                <?php $totalCobrado = $item->cantidad  ?>
            <?php endforeach ?>

            <div class="panel">
                <div class="panel-body">
                    <h5><?= $model->cicloEscolar->singular  ?> -  [<?= $model->tipo->singular ?>]</h5>
                    <div class="row totales cobros">
                        <div class="col-sm-12">
                            <span class="label">Total</span>
                            <span class="total monto">$ <?= $totalCobrado ?> </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($model->periodicidad ==  Caja::PERIODO_MES ): ?>
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $model->tipo->singular  ?> </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6 ">
                                <h5><i class="fa <?=  $model->mes_agosto == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> AGOSTO</h5>
                                <h5><i class="fa <?=  $model->mes_septiembre == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> SEPTIEMBRE</h5>
                                <h5><i class="fa <?=  $model->mes_octubre == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> OCTUBRE</h5>
                                <h5><i class="fa <?=  $model->mes_noviembre == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> NOVIEMBRE</h5>
                                <h5><i class="fa <?=  $model->mes_diciembre == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> DICIEMBRE</h5>
                                <h5><i class="fa <?=  $model->mes_enero == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> ENERO</h5>
                            </div>
                            <div class="col-sm-6">
                                <h5><i class="fa <?=  $model->mes_febrero == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> FEBRERO</h5>
                                <h5><i class="fa <?=  $model->mes_marzo == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> MARZO</h5>
                                <h5><i class="fa <?=  $model->mes_abril == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> ABRIL</h5>
                                <h5><i class="fa <?=  $model->mes_mayo == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> MAYO</h5>
                                <h5><i class="fa <?=  $model->mes_junio == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> JUNIO</h5>
                                <h5><i class="fa <?=  $model->mes_julio == 10 ? 'fa-check-square-o' : 'fa-times' ?>" aria-hidden="true"></i> JULIO</h5>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información extra / Comentarios</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'nota:ntext',
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <?= Html::a('Imprimir Ticket', false, ['class' => 'btn btn-warning btn-lg btn-block', 'id' => 'imprimir-ticket','style'=>'    padding: 6%;'])?>
            </div>
            <iframe width="100%" class="panel" height="500px" src="<?= Url::to(['imprimir-ticket', 'id' => $model->id ])  ?>"></iframe>
        </div>
    </div>
</div>

<script>
    $('#imprimir-ticket').click(function(event){
        event.preventDefault();
        window.open("<?= Url::to(['imprimir-ticket', 'id' => $model->id ])  ?>",
        'imprimir',
        'width=600,height=500');
    });
</script>