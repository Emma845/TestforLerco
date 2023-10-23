<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\documento\Documento;

/* @var $this yii\web\View */
/* @var $model common\models\ViewSucursal */

$this->title =  $model->nombre;

$this->params['breadcrumbs'][] = ['label' => 'Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="gestion-documento-view">
    <?php /* ?>
    <p>
        <?= $can['update']?
            Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-mint']): '' ?>

        <?= $can['delete']?
            Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de que deseas eliminar esta sucursal?',
                    'method' => 'post',
                ],
            ]): '' ?>
    </p>
    */?>
    <div class="row">
        <div class="col-md-7">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información Documento</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'nombre',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'tipoDocumento.singular',
                            'updateDocumento.singular',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>



