<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\Esys;
use app\models\esys\EsysCambiosLog;
use app\models\esys\EsysDireccion;
use app\models\esys\EsysListaDesplegable;
use app\models\articulo\Articulo;

?>

<p>
    <?= $can['update'] ?
        Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-mint']): '' ?>

    <?= $can['delete']?
        Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas eliminar este articulo?',
                'method' => 'post',
            ],
        ]): '' ?>


</p>

<div class="articulos-articulo-view">
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-7">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Información General</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            'id',
                                            "nombre",
                                            "inventario",
                                            "precio"
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Imagen</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md text-center">
                                    <?= Html::img(Yii::$app->homeUrl."img/articulos/".$model->image_web_filename,['id' => 'imagenPrevisualizacionUpdate', 'width' => '150px']);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel panel-info ">
                <div class="panel-heading">
                        <h3 class="panel-title"><?= Articulo::$statusList[$model->status] ?> </h3>
                </div>
            </div>
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Historial de cambios</h3>
                </div>
                <div class="panel-body historial-cambios nano">
                    <div class="nano-content">
                        <?= EsysCambiosLog::getHtmlLog([
                            [new Articulo(), $model->id],
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



