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
use app\models\esys\EsysListaDesplegable;
use kartik\date\DatePicker;
use app\models\file\FileUpload;
use app\models\file\FileCheck;

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

<div class="cliente-user-view" >
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-7">
                    <div class="panel" >
                        <div class="panel-heading">
                            <h3 class="panel-title">Cuenta de cliente y datos personales</h3>
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
                                            "whatsapp",
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
                        <div class="panel-heading">
                            <h3 class="panel-title">Documentación</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="file-list">
                                <?php foreach (EsysListaDesplegable::getItems("document_tutor") as $key => $item_documento): ?>
                                    <li>
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <strong><?=$item_documento?></strong>
                                            </div>
                                            <?php if(FileCheck::getFilesTutor($model->id, $key)) :?>
                                                <div class="col-lg-1">
                                                    <span style="font-size: 25px; color: Dodgerblue;">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            <?php else :?>
                                                <div class="col-lg-1">
                                                    <span style="font-size: 25px; color: Tomato;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <?php endif ?>
                                        </div>
                                    </li>
                                    
                                <?php endforeach ?>
                            </ul>
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
            </div>
        </div>
        <div class="col-lg-3">
            <?php if ($model->status != Cliente::STATUS_INACTIVE): ?>
                <div class="panel">
                    <?= Html::a('<i class = "fa fa-book"></i> DOCUMENTACIÓN', false, ['class' => 'btn btn-warning btn-lg btn-block', 'data-target' => '#modal-add-file', 'data-toggle'=> 'modal' ,'style'=>'padding: 6%;', 'id' => 'btnShowModal' ])?>
                </div>
            <?php endif ?>
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

<!--Modal Files-->

<div class="fade modal" id="modal-add-file" role="dialog" tabindex="-1" aria-labelledby="modal-show-label">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">DOCUMENTACIÓN</h4>
            </div>
            <!--Modal body-->
            <?php $form = ActiveForm::begin(['id' => 'form-files','action' => 'add-files-tutor','options' => ['enctype' => 'multipart/form-data'] ] ) ?>

            <?= Html::hiddenInput('Cliente[id]', $model->id ) ?>
            <div class="modal-body">
                <br>
                <br>
                <?php foreach (EsysListaDesplegable::getItems("document_tutor") as $key => $item_documento): ?>
                    <div class="row text-left">
                        <div class="col-sm offset-sm-1">
                            <?php if(FileCheck::getFilesTutor($model->id, $key)) :?>
                                <?= Html::checkbox("pertenece_id[]", true, ['class' => 'checkbox-list','value' => $key,'label' =>"<span style='font-size: 15px;'>$item_documento</span>"]);?>
                            <?php else :?>
                                <?= Html::checkbox("pertenece_id[]", false, ['class' => 'checkbox-list','value' => $key,'label' =>"<span style='font-size: 15px;'>$item_documento</span>"]);?>
                            <?php endif ?>
                        </div>
                    </div>
                    <br>
                <?php endforeach ?>

                <br>
                <div class="text-center">
                </div>
                <br>
                <div class="row container-fecha-vigencia" style="display:none">
                    <div class="col-sm-6 col-sm-offset-3">
                       
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                <?= Html::submitButton('Guardar cambios', ['class' => 'finish btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!--Fin Modal-->

<script type="text/javascript">

    var $btnVigenciaDocumento = $('#btnVigenciaDocumento');

    $(document).ready(function(){
        $(".historial-cambios.nano").nanoScroller();
    });

    $('#btnShowModal').click(function(){
        $('.container-fecha-vigencia').hide();
        $('#check_fecha_evidencia').val(null);
        $('#fecha_vigencia').val(null);
    });

    $btnVigenciaDocumento.click(function(){
        $('.container-fecha-vigencia').show();
        $('#check_fecha_evidencia').val(10);
    });

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".historial-cambios.nano").nanoScroller();

    });

</script>



