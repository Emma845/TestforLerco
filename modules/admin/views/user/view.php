<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\user\User;
use app\models\esys\EsysCambiosLog;
use app\models\esys\EsysDireccion;

/* @var $this yii\web\View */
/* @var $model common\models\ViewUser */

$this->title = $model->nombreCompleto;
$this->params['breadcrumbs'][] = 'Administración';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios internos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>

<div class="admin-user-view">
    <p>
        <?= $can['update']?
            Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-mint']): '' ?>

        <?= $can['delete']?
            Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de que deseas eliminar este usuario?',
                    'method' => 'post',
                ],
            ]): '' ?>
    </p>
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-7">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Cuenta de usuario y datos personales</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            'id',
                                            "username",
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
                                            "sexo",
                                            "fecha_nac:date",
                                            "cargo",
                                            "departamento.singular",
                                        ],
                                    ]) ?>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            "telefono",
                                            "telefono_movil",
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Permisos</h3>
                        </div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    "perfil.item_name",
                                    "perfilesAsignarString",
                                    "sucursalesAsignarString",
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
                                ]
                            ]) ?>
                            <?= DetailView::widget([
                                'model' => $model->direccion,
                                'attributes' => [
                                    "estado.singular",
                                    "municipio.singular",
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' =>  "Tipo",
                                        'format'    => 'raw',
                                        'value'     => $model->tipo ?  User::$tipoList[$model->tipo] : '',
                                    ],
                                    "nivel.singular",
                                    "grado.singular",
                                ]
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel">
                <?php if ($model->api_enabled != User::API_ACTIVE): ?>
                    <?= Html::a('<i class="fa fa-mobile mar-rgt-5px"></i> Habilitar acceso APP',['enable-acceso-app','user_id' => $model->id],['class' => 'btn btn-mint btn-lg btn-block', 'style'=>'padding: 6%;' ])?>
                <?php endif ?>
                <?php if ($model->api_enabled == User::API_ACTIVE): ?>
                    <?= Html::a('<i class="fa fa-mobile mar-rgt-5px"></i> Deshabilitar acceso APP',['desabled-acceso-app','user_id' => $model->id],['class' => 'btn btn-dark btn-lg btn-block', 'style'=>'padding: 6%;' ])?>
                <?php endif ?>
            </div>
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Historial de cambios</h3>
                </div>
                <div class="panel-body historial-cambios nano">
                    <div class="nano-content">
                        <?= EsysCambiosLog::getHtmlLog([
                            [new User(), $model->id],
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
