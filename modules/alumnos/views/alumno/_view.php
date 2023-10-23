<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\cliente\Cliente;
use app\models\Esys;
use app\models\esys\EsysCambiosLog;
use app\models\esys\EsysDireccion;
?>

<p>
    <?= $can['update'] ?
        Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-mint']): '' ?>

    <?= $can['delete']?
        Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este cliente?',
                'method' => 'post',
            ],
        ]): '' ?>


</p>

<div class="cliente-user-view">
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-7">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Cuenta de cliente y datos personalesaaa</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            'id',
                                            "email:email",
                                        ],
                                    ]) ?>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            "tituloPersonal.singular",
                                            "nombre",
                                            "apellidos",
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-5">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                          [
                                            'attribute' =>  "Sexo",
                                            'format'    => 'raw',
                                            'value'     => $model->sexo ?  Cliente::$sexoList[$model->sexo] : '',
                                         ]
                                        ],
                                    ]) ?>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            "telefono",
                                            "telefono_movil",
                                            [
                                                'attribute' => 'Servicio preferente',
                                                'format'    => 'raw',
                                                'value'     => isset($model->servicio_preferente) ?  Cliente::$servicioList[$model->servicio_preferente] : '',
                                            ],
                                            [
                                                'attribute' => 'Tipo de cliente',
                                                'format'    => 'raw',
                                                'value'     => isset($model->tipo->singular) ?  $model->tipo->singular : '',
                                            ],


                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                     [
                                         'attribute' => 'Asignado',
                                         'format'    => 'raw',
                                         'value'     =>  isset($model->asignadoCliente->nombre) ?  Html::a($model->asignadoCliente->nombre ." ". $model->asignadoCliente->apellidos , ['/admin/user/view', 'id' => $model->asignadoCliente->id], ['class' => 'text-primary']) : '' ,
                                     ]
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
                                        'attribute' => 'Se entero a través de',
                                        'format'    => 'raw',
                                        'value'     =>  isset($model->atravesDe->id) ?  $model->atravesDe->singular : '' ,
                                     ]
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    "documentoAsignarString",
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
                                    'notas:ntext',
                                ]
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                                <h3 class="panel-title"><?= Cliente::$statusList[$model->status] ?> </h3>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dirección</h3>
                        </div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model->direccion,
                                'attributes' => [
                                    'referencia',
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
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Historial de cambios</h3>
                </div>
                <div class="panel-body historial-cambios nano">
                    <div class="nano-content">
                        <?= EsysCambiosLog::getHtmlLog([
                            [new Cliente(), $model->id],
                            [new EsysDireccion(), $model->direccion->id],
                        ], 50, true) ?>
                    </div>
                </div>
                <div class="panel-footer">
                    <?= Html::a('Ver historial completo', ['historial-cambios', 'id' => $model->id], ['class' => 'text-primary']) ?>
                </div>
            </div>
            <?= app\widgets\CreatedByView::widget(['model' => $model]) ?>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $(".historial-cambios.nano").nanoScroller();

    });

</script>



