<?php
use app\models\Esys;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\widgets\CreatedByView;
use app\models\alumno\Alumno;
use app\models\file\FileUpload;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\models\esys\EsysListaDesplegable;
use app\models\esys\EsysCambiosLog;
use app\models\file\FileCheck;

$this->title = $model->nombre . ' '. $model->apellidos;

$this->params['breadcrumbs'][] = ['label' => 'Alumno', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
$this->params['breadcrumbs'][] = 'Editar';

?>
<p>
<?php if ($model->status != Alumno::STATUS_BAJA): ?>
    <?= $can['update'] ?
        Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-mint']): '' ?>

    <?= $can['cancel']?
        Html::a('BAJA', ['cancel', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas realizar la BAJA del alumno?',
                'method' => 'post',
            ],
        ]): '' ?>
<?php endif ?>

<?= $can['update'] && $model->status == Alumno::STATUS_BAJA ?
    Html::a('ACTIVAR ALUMNO', ['activar-alumno', 'id' => $model->id], [
        'class' => 'btn btn-success',
        'data' => [
            'confirm' => '¿Estás seguro de que deseas realizar la ACTIVACIÓN del alumno?',
            'method' => 'post',
        ],
    ]): '' ?>
</p>

<div class="alumnos-alumno-view">
    <div class="row">
        <div class="col-lg-9">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Cuenta de alumno y datos personales</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                ],
                            ])?> 
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    "nombre",
                                    "apellidos",
                                    "fecha_nacimiento:date",
                                    'hobbies',
                                    'deporte',

                                    
                                ],
                            ]); 
?>
                        </div>
                        <div class="col-md-5">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                  [
                                    'attribute' =>  "Sexo",
                                    'format'    => 'raw',
                                    'value'     => $model->sexo ?  Alumno::$sexoList[$model->sexo] : '',
                                 ]
                                ],
                            ]) ?>

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                [
                                    'attribute' =>  "Nivel",
                                    'format'    => 'raw',
                                    'value'     => isset($model->nivelText->singular) ? $model->nivelText->singular : '',
                                ],
                                [
                                    'attribute' =>  "Grado",
                                    'format'    => 'raw',
                                    'value'     => isset($model->gradoText->singular) ?  $model->gradoText->singular : '',
                                ],
                                ],
                            ]) ?>

                        </div>
                    </div>
                </div>
            </div>


            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">EXPEDIENTE ALUMNO</h3>
                </div>
                <div class="panel-body">
                    <ul class="file-list">
                        <?php foreach (EsysListaDesplegable::getItems("document_alumno") as $key => $item_documento): ?>
                            <li>
                                <div class="row">
                                    <div class="col-lg-10">
                                        <strong><?=$item_documento?></strong>
                                    </div>
                                    <?php if(FileCheck::getFilesAlumno($model->id, $key)) :?>
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
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Configuración de pago</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' =>  "Especial",
                                'format'    => 'raw',
                                'value'     => $model->is_especial == 10  ? 'SI' : 'NO',
                            ],
                            'costo_colegiatura',
                            'colegiaturas_especial',
                            'cicloEscolar.singular',
                        ]
                    ]) ?>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Datos clinicos / medicos</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'talla',
                            'peso',
                            'tipoSangreText.singular',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Datos clinicos / medicos</h3>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'enfermedades_lesiones:ntext',
                            'antecedentes_enfermedades:ntext',
                            'discapacidad:ntext',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <?php if ($model->status != Alumno::STATUS_BAJA): ?>
                <div class="panel">
                    <?= Html::a('<i class = "fa fa-book"></i> DOCUMENTACIÓN', false, ['class' => 'btn btn-warning btn-lg btn-block', 'data-target' => '#modal-add-file', 'data-toggle'=> 'modal' ,'style'=>'padding: 6%;', 'id' => 'btnShowModal' ])?>
                </div>
            <?php endif ?>
            <?php if ($model->status != Alumno::STATUS_BAJA): ?>
                <div class="panel">
                    <?= Html::a('<i class = "fa fa-file-text"></i> FICHA DE INSCRIPCIÓN', ['print-ficha', 'id' => $model->id], 
                    [
                        'class' => 'btn btn-info btn-lg btn-block',
                        'data' => [
                            'confirm' => '¿ Generar ficha de inscripción ?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            <?php endif ?>
            <?php if ($model->status != Alumno::STATUS_BAJA): ?>
                <div class="panel">
                    <?= Html::a('<i class = "fa fa-file-text"></i> CARTA COMPROMISO', false, ['class' => 'btn btn-danger btn-lg btn-block', 'data-target' => '#modal-carta-compromiso', 'data-toggle'=> 'modal' ,'style'=>'padding: 6%;', 'id' => 'btnShowModal' ])?>
                </div>
            <?php endif ?>
            <div class="panel">
                <div class="panel-body text-center">
                    <div class="float-right">
                        <?= Html::Button('<i class="fa fa-edit"></i>', [ "class" => "btn btn-xs btn-success",'data-target' => '#modal-edit-alumno', 'data-toggle'=> 'modal', ]) ?>
                    </div>
                    <img alt="Profile Picture" class="img-lg img-circle mar-btm" src="<?= Url::to(['/img/profile-photos/5.png']) ?>">
                    <p class="text-lg text-semibold mar-no text-main"><?= $model->cliente->nombreCompleto ?></p>
                    <p class="text-muted">TUTOR / PADRE DE FAMILIA</p>
                    <div class="mar-top">
                        <?= Html::a("CONSULTAR INFO", ["/crm/cliente/view", "id" => $model->cliente_id], ["class" => "btn btn-mint"]) ?>

                    </div>
                </div>
            </div>
            <div class="panel <?= Alumno::$statusAlertList[$model->status] ?>">
                <div class="panel-heading">
                        <h3 class="panel-title"><?= Alumno::$statusList[$model->status] ?> </h3>
                </div>
            </div>
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Historial de cambios</h3>
                </div>
                <div class="panel-body historial-cambios nano">
                    <div class="nano-content">
                        <?= EsysCambiosLog::getHtmlLog([
                            [new Alumno(), $model->id],
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


<div class="fade modal" id="modal-add-file" role="dialog" tabindex="-1" aria-labelledby="modal-show-label">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">DOCUMENTACIÓN</h4>
            </div>
            <!--Modal body-->
            <?php $form = ActiveForm::begin(['id' => 'form-files','action' => 'add-files-alumno','options' => ['enctype' => 'multipart/form-data'] ] ) ?>

            <?= Html::hiddenInput('Alumno[id]', $model->id ) ?>
            <?= Html::hiddenInput('check_fecha_evidencia', null, ["id" => "check_fecha_evidencia"]) ?>
            <div class="modal-body">
                <br>
                <br>
                <?php foreach (EsysListaDesplegable::getItems("document_alumno") as $key => $item_documento): ?>
                    <div class="row text-left">
                        <div class="col-sm offset-sm-1">
                            <?php if(FileCheck::getFilesAlumno($model->id, $key)) :?>
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

<div class="fade modal" id="modal-carta-compromiso" role="dialog" tabindex="-1" aria-labelledby="modal-show-label">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">CARTA COMPROMISO</h4>
            </div>
            <!--Modal body-->
            <?php $form = ActiveForm::begin(['id' => 'form-files','action' => 'print-carta-compromiso','options' => ['enctype' => 'multipart/form-data'] ] ) ?>
            <?= Html::hiddenInput('Alumno[id]', $model->id ) ?>
            <div class="modal-body">
            <div class="row">
                <div class="col">
                    <p>Seleccione los documentos a incluir en la carta compromiso</p>
                </div>
            </div>
                <br>
                <br>
                <h5>Alumno </h5>
                <?php foreach (EsysListaDesplegable::getItems("document_alumno") as $key => $item_documento): ?>
                    <div class="row text-left">
                        <div class="col-sm offset-sm-1">
                            <?php if(!FileCheck::getFilesAlumno($model->id, $key)) :?>
                                <?= Html::checkbox("pertenece_id[]", false, ['class' => 'checkbox-list','value' => $key,'label' =>"<span style='font-size: 15px;'>$item_documento</span>"]);?>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <br>
                <h5>Tutor</h5>
                <?php foreach (EsysListaDesplegable::getItems("document_tutor") as $key => $item_documento): ?>
                    <div class="row text-left">
                        <div class="col-sm offset-sm-1">
                            <?php if(!FileCheck::getFilesAlumno($model->id, $key)) :?>
                                <?= Html::checkbox("pertenece_id[]", false, ['class' => 'checkbox-list','value' => $key,'label' =>"<span style='font-size: 15px;'>$item_documento</span>"]);?>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <br>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                <?= Html::submitButton('Generar Carta Compromiso', ['class' => 'finish btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="fade modal" id="modal-edit-alumno" role="dialog" tabindex="-1" aria-labelledby="modal-show-label">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">CAMBIAR TUTOR</h4>
            </div>
            <!--Modal body-->
            <?php $form = ActiveForm::begin(['id' => 'form-files','action' => 'update-alumno' ] ) ?>

            <?= Html::hiddenInput('Alumno[id]', $model->id ) ?>
            <div class="modal-body">
                <h4>TUTOR / PADRE DE FAMILIA: <?= $model->cliente_id ?  $model->cliente->nombreCompleto: 'N/A' ?></h4>
                <br>
                <div class="row text-center">
                    <div class="col-sm-6 col-sm-offset-3">
                        <p><strong>TUTORES / PADRES DE FAMILIA </strong></p>
                        <?= Select2::widget([
                            'id' => 'Alumno-cliente_id',
                            'name' => 'Alumno[cliente_id]',
                            'value' => $model->cliente_id,
                            'data' => $model->cliente_id ? [ $model->cliente_id => $model->cliente->nombreCompleto]: [],
                            'options' => [
                                'placeholder' => 'Tutores que pudiera asignar',

                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'ajax' => [
                                    'url'      => Url::to(['/crm/cliente/cliente-ajax']),
                                    'dataType' => 'json',
                                    'cache'    => true,
                                    'processResults' => new JsExpression('function(data, params){  return {results: data} }'),
                                ],
                            ],
                        ]) ?>
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
    
    
    $('#imprimir-etiqueta').click(function(event){
        event.preventDefault();
        window.open("<?= Url::to(['imprimir-etiqueta', 'id' => $model->id ])  ?>",
        'imprimir',
        'width=600,height=500');
    });


</script>



