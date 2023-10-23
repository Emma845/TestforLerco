<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\sucursal\Sucursal;

/* @var $this yii\web\View */
/* @var $model common\models\ViewSucursal */

$this->title =  $model->nombre;

$this->params['breadcrumbs'][] = ['label' => 'Sucursales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="sucursales-sucursal-view">
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
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Sucursal::$statusList[$model->status] ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información sucursal</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'nombre',
                            "email:email",
                            "rfc",
                            "centro_costo",
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'telefono',
                            "telefono_movil",
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                             [
                                 'attribute' => 'Encargado',
                                 'format'    => 'raw',
                                 'value'     =>  isset($model->encargadoSucursal->nombre) ?  Html::a($model->encargadoSucursal->nombre ." ". $model->encargadoSucursal->apellidos , ['/admin/user/view', 'id' => $model->encargadoSucursal->id], ['class' => 'text-primary']) : '' ,
                             ]
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información extra / Comentarios</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'informacion:ntext',
                            'comentarios:ntext',
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Dirección</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model->direccion,
                        'attributes' => [
                            'direccion',
                            'num_ext',
                            'num_int',
                            'esysDireccionCodigoPostal.colonia',
                        ]
                    ]) ?>
                    <?= DetailView::widget([
                        'model' => $model->direccion,
                        'attributes' => [
                            "esysDireccionCodigoPostal.estado.singular",
                            "esysDireccionCodigoPostal.municipio.singular",
                        ]
                    ]) ?>

                    <?= DetailView::widget([
                        'model' => $model->direccion,
                        'attributes' => [
                            'esysDireccionCodigoPostal.codigo_postal',
                        ]
                    ]) ?>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model->direccion,
                        'attributes' => [
                            'lng',
                            'lat',
                        ]
                    ]) ?>
                </div>
            </div>
            <?= app\widgets\CreatedByView::widget(['model' => $model]) ?>
        </div>
    </div>
     <div class="row">
        <div class="col-ms-12">
            <div class="panel">
                 <div class="panel-heading">
                    <h3 class="panel-title">Google Maps</h3>
                </div>
                <div class="panel-body">
                    <div id="map" style="height: 400px; width: 100%; "></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function initMap() {
  // The location of Uluru
  var uluru = {lat: <?= $model->direccion->lat  ?>, lng: <?= $model->direccion->lng  ?>};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 15, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsJAAEIYeTJb9Q-ZZtQYiiUND4HNaZ0Ok&callback=initMap">
</script>

