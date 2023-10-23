<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use app\models\alumno\Alumno;
use app\models\documento\Documento;
use app\models\esys\EsysListaDesplegable;
use app\models\ciclo\CicloTarifa;
use app\models\ciclo\ViewCiclos;
?>
<p>
    <?= $can['createAlumno'] ?
        Html::button('Nuevo alumno',  [ 'class' => 'btn btn-mint', "data-target"=> "#demo-default-modal", "data-toggle" => "modal" ]): '' ?>
</p>

<div class="row">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Alumno(as)</h3>
			</div>
			<div class="panel-body">
				<table class="table  table-condensed">
					<thead>
						<tr>
							<th>ID</th>
							<th>Matricula</th>
							<th class="text-center">Nombre</th>
                            <th class="text-center">Apellido</th>
                            <th class="text-center">Nivel</th>
                            <th class="text-center">Grado</th>
                            <th class="text-center">Edad</th>
                            <th class="text-center">Peso</th>
                            <th class="text-center">Tipo sangre</th>
                            <th class="text-center">Estatus</th>
							<th class="text-center">Creado</th>
							<th class="text-center">Accion</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($model->alumno as $key => $alumno): ?>
                            <?php
                            $hoy = new DateTime();
                            if ($alumno->fecha_nacimiento)
                                $edad = $hoy->diff(new DateTime(date("Y-m-d",($alumno->fecha_nacimiento)) ));
                            ?>
							<tr>
								<td><?= $alumno->id ?></td>
								<td><a href="#" class="btn-link">#<?= str_pad($alumno->id, 7 , "0",STR_PAD_LEFT)  ?></a></td>
								<td class="text-center"><?= $alumno->nombre ?></td>
                                <td class="text-center"><?= $alumno->apellidos ?></td>
                                <td class="text-center"><?= $alumno->nivelText->singular ?></td>
                                <td class="text-center"><?= $alumno->gradoText->singular ?></td>
                                <td class="text-center"><?= $alumno->fecha_nacimiento && isset($edad->y) ?  $edad->y : 'N/A'  ?></td>
                                <td class="text-center"><?= $alumno->peso ?></td>
                                <td class="text-center"><?= isset( $alumno->tipoSangreText->singular) ?  $alumno->tipoSangreText->singular : '' ?></td>
								<td class="text-center"><?= Alumno::$statusList[$alumno->status] ?></td>
                                <td class="text-center"><?= date("Y-m-d",$alumno->created_at) ?></td>
								<td class="text-center">


                                     <?= $can['updateAlumno'] ?  Html::button('<i class="fa fa-edit"></i>', [ 'class' =>  'btn btn-circle btn-xs btn-warning', "data-target" =>"#modal-edit-alumno", "data-toggle" => "modal", "onclick" => "init_edit_alumno($alumno->id)"  ])  : '' ?>

                                    <?= $can['deleteAlumno'] ? Html::a('<i class="fa fa-trash"></i>', ['delete-alumno', 'id' => $alumno->id], [
                                        'class' => 'btn btn-danger btn-xs btn-circle',
                                        'data' => [
                                            'confirm' => '¿Estás seguro de que deseas eliminar este alumno?',
                                            'method' => 'post',
                                        ],
                                    ]) : '' ?>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="fade modal" id="demo-default-modal" role="dialog" tabindex="-1" aria-labelledby="modal-show-label" >
    <div class="modal-dialog modal-lg" style="height: 100%;width: 80%;">
        <div class="modal-content" style="height: 100%;">
            <!--Modal header-->
            <?php $form = ActiveForm::begin(['id' => 'form-envios','action' => 'create-alumno']) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Nuevo alumno</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                             <?= Html::hiddenInput('cliente_id', $model->id) ?>

                            <?= Html::label("Nombre","nombre_alumno") ?>
                            <?= Html::input('text', 'nombre_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-4">
                             <?= Html::label("Apellido","apellido_alumno") ?>
                            <?= Html::input('text', 'apellido_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Nivel","nivel_id") ?>
                            <?=  Html::dropDownList('nivel_id', null, EsysListaDesplegable::getItems('nivel'), ['class' => 'form-control'])  ?>
                        </div>
                    </div>
                    <div class="row">

<!--                         <div class="col-sm-3">
                            < ?= Html::label("Costo de colegiatura DEFAULT","costo_colegiatura") ?>
                            < ?=  Html::input("number",'costo_colegiatura', 0 , ['class' => 'form-control'])  ?>
                        </div> -->
                        <div class="col-sm-3" style="padding-top: 2%;">
                            <div class="row">
                                <?= Html::checkbox("alumno_especial",
                                    false,
                                    [
                                        "id"    => "especial_id_access",
                                        "class" => "modulo magic-checkbox"
                                    ]
                                ) ?>
                                <?= Html::label("¿ ESPECIAL / DESCUENTO ?", "especial_id_access", ["style" => "display:inline;"]) ?>

                            </div>
                            <div class="row">
                                <?= Html::checkbox("factura",
                                    false,
                                    [
                                        "id"    => "factura_id_access",
                                        "class" => "modulo magic-checkbox"
                                    ]
                                ) ?>
                                <?= Html::label("FACTURA", "factura_id_access", ["style" => "display:inline;"]) ?>

                            </div>
                        </div>
                        <div class="col-sm-1">
                            <?= Html::label("Colegiaturas","colegiaturas") ?>
                            <?=  Html::dropDownList('colegiaturas', null, [
                                1 => '1 Mes',
                                2 => '2 Meses',
                                3 => '3 Meses',
                                4 => '4 Meses',
                                5 => '5 Meses',
                                6 => '6 Meses',
                                7 => '7 Meses',
                                8 => '8 Meses',
                                9 => '9 Meses',
                                10 => '10 Meses',
                                11 => '11 Meses',
                                12 => '12 Meses',
                            ], ['class' => 'form-control','disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Html::label("Ciclo escolar","ciclo_escolar_id") ?>
                            <?=  Html::dropDownList('ciclo_escolar_id', null, ViewCiclos::cicloEscolar() , ['class' => 'form-control','disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Costo de colegiatura ESPECIAL","costo_colegiatura_especial") ?>
                            <?=  Html::input("number",'costo_colegiatura_especial', 0 , ['class' => 'form-control', 'disabled' => 'disabled'])  ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= Html::label("Sexo","sexo_id") ?>
                            <?=  Html::dropDownList('sexo_id', null, [ 10 => "Masculino", 20 => "Femenino"], ['class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Grado","grado_id") ?>
                            <?=  Html::dropDownList('grado_id', null, EsysListaDesplegable::getItems('grado'), ['class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Fecha de Nacimiento","fecha_nacimiento_id") ?>
                            <?= DatePicker::widget([
                                    'name' => 'fecha_nacimiento_id',
                                    'options' => ['placeholder' => 'Fecha de nacimiento'],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd',
                                    ]
                            ])  ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            <?= Html::label("Tipo de sangre","tipo_sangre") ?>
                            <?=  Html::dropDownList('tipo_sangre_id', null, EsysListaDesplegable::getItems('tipo_sangre'), ['prompt' => 'Tipo de sangre','class' => 'form-control'])  ?>
                        </div>

                        <div class="col-sm-1">
                            <?= Html::label("Talla","talla_alumno") ?>
                            <?= Html::input('number', 'talla_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-1">
                            <?= Html::label("Peso","peso_alumno") ?>
                            <?= Html::input('number', 'peso_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Lugar de Nacimiento","lugar_nacimiento") ?>
                            <?= Html::input('text', 'lugar_nacimiento', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Html::label("Vive con","vive_con") ?>
                            <?=  Html::dropDownList('vive_con', null, EsysListaDesplegable::getItems('vive_con'), ['prompt' => 'Vive con','class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Nombre con quien vive","nombre_vive_con") ?>
                            <?= Html::input('text', 'nombre_vive_con', null, ['class' => 'form-control']) ?>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= Html::label("Enfermedades / Lesiones","enfermedades_lesiones") ?>
                             <?= Html::textarea('enfermedades_lesiones', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>

                        <div class="col-sm-4">
                            <?= Html::label("Antecedentes familiares","antecedentes_familiares") ?>
                            <?= Html::textarea('antecedentes_familiares', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("¿ Discapacidad ?","discapacidad_tipo") ?>
                            <?= Html::textarea('discapacidad_tipo', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::label("Nota / Descripción", "text_area_descripcion", ["style" => "display:inline"]) ?>
                            <?= Html::textarea('text_area_descripcion', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                <?= Html::submitButton('Guardar Alumno', ['class' => 'finish btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<div class="fade modal" id="modal-edit-alumno" role="dialog" tabindex="-1" aria-labelledby="modal-edit-label" >
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content" >
            <!--Modal header-->
            <?php $formEdit = ActiveForm::begin(['id' => 'form-edit-cliente','action' => 'edit-form-alumno']) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Editar alumno</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= Html::hiddenInput('edit_cliente_id', $model->id) ?>
                             <?= Html::hiddenInput('edit_alumno_id', null) ?>

                            <?= Html::label("Nombre","edit_nombre_alumno") ?>
                            <?= Html::input('text', 'edit_nombre_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-4">
                             <?= Html::label("Apellido","edit_apellido_alumno") ?>
                            <?= Html::input('text', 'edit_apellido_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Nivel","edit_nivel_id") ?>
                            <?=  Html::dropDownList('edit_nivel_id', null, EsysListaDesplegable::getItems('nivel'), ['class' => 'max-width-170px form-control'])  ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <?= Html::label("Costo de colegiatura DEFAULT","edit_costo_colegiatura") ?>
                            <?=  Html::input("number",'edit_costo_colegiatura', 0 , ['class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-3" style="padding-top: 2%;">
                            <div class="row">
                                <?= Html::checkbox("edit_alumno_especial",
                                    false,
                                    [
                                        "id"    => "edit_especial_id_access",
                                        "class" => "modulo magic-checkbox"
                                    ]
                                ) ?>
                                <?= Html::label("¿ ESPECIAL / DESCUENTO ?", "edit_especial_id_access", ["style" => "display:inline;"]) ?>

                            </div>
                            <div class="row">
                                <?= Html::checkbox("edit_factura",
                                    false,
                                    [
                                        "id"    => "edit_factura_id_access",
                                        "class" => "modulo magic-checkbox"
                                    ]
                                ) ?>
                                <?= Html::label("FACTURA", "edit_factura_id_access", ["style" => "display:inline;"]) ?>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <?= Html::label("Colegiaturas","edit_colegiaturas") ?>
                            <?=  Html::dropDownList('edit_colegiaturas', null, [
                                1 => '1 Mes',
                                2 => '2 Meses',
                                3 => '3 Meses',
                                4 => '4 Meses',
                                5 => '5 Meses',
                                6 => '6 Meses',
                                7 => '7 Meses',
                                8 => '8 Meses',
                                9 => '9 Meses',
                                10 => '10 Meses',
                                11 => '11 Meses',
                                12 => '12 Meses',
                            ], ['class' => 'form-control','disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Html::label("Ciclo escolar","edit_ciclo_escolar_id") ?>
                            <?=  Html::dropDownList('edit_ciclo_escolar_id', null, EsysListaDesplegable::getItems('ciclo_escolar') , ['class' => 'form-control','disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Costo de colegiatura ESPECIAL","edit_costo_colegiatura_especial") ?>
                            <?=  Html::input("number",'edit_costo_colegiatura_especial', 0 , ['class' => 'form-control', 'disabled' => 'disabled'])  ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= Html::label("Sexo","edit_sexo_id") ?>
                            <?=  Html::dropDownList('edit_sexo_id', null, [ 10 => "Masculino", 20 => "Femenino"], ['class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Grado","edit_grado_id") ?>
                            <?=  Html::dropDownList('edit_grado_id', null, EsysListaDesplegable::getItems('grado'), ['class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Fecha de Nacimiento","edit_fecha_nacimiento_id") ?>
                            <?= DatePicker::widget([
                                    'name' => 'edit_fecha_nacimiento_id',
                                    'options' => ['placeholder' => 'Fecha de nacimiento'],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd',
                                    ]
                            ])  ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            <?= Html::label("Tipo de sangre","edit_tipo_sangre") ?>
                            <?=  Html::dropDownList('edit_tipo_sangre_id', null, EsysListaDesplegable::getItems('tipo_sangre'), ['prompt' => 'Tipo de sangre','class' => 'form-control'])  ?>
                        </div>

                        <div class="col-sm-1">
                            <?= Html::label("Talla","edit_talla_alumno") ?>
                            <?= Html::input('number', 'edit_talla_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-1">
                            <?= Html::label("Peso","edit_peso_alumno") ?>
                            <?= Html::input('number', 'edit_peso_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Lugar de Nacimiento","edit_lugar_nacimiento") ?>
                            <?= Html::input('text', 'edit_lugar_nacimiento', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Html::label("Vive con","edit_vive_con") ?>
                            <?=  Html::dropDownList('edit_vive_con', null, EsysListaDesplegable::getItems('vive_con'), ['prompt' => 'Vive con','class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Nombre con quien vive","edit_nombre_vive_con") ?>
                            <?= Html::input('text', 'edit_nombre_vive_con', null, ['class' => 'form-control']) ?>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= Html::label("Enfermedades / Lesiones","edit_enfermedades_lesiones") ?>
                             <?= Html::textarea('edit_enfermedades_lesiones', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>

                        <div class="col-sm-4">
                            <?= Html::label("Antecedentes familiares","edit_antecedentes_familiares") ?>
                            <?= Html::textarea('edit_antecedentes_familiares', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("¿ Discapacidad ?","edit_discapacidad_tipo") ?>
                            <?= Html::textarea('edit_discapacidad_tipo', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::label("Nota / Descripción", "edit_text_area_descripcion", ["style" => "display:inline"]) ?>
                            <?= Html::textarea('edit_text_area_descripcion', null, ['class' => 'form-control','rows' => 2 ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                <?= Html::submitButton('Guardar Alumno', ['class' => 'finish btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>




<script>
	var $alumno_id 			        = $('#alumno_id'),
		$container_document_alumno 	= $('.container_document_alumno'),
	 	$title_alumno 		        = $('.title-alumno');

	var init_alumno = function($alumno_id) {
		$.get("<?= Url::to(['alumno-data'])  ?>",{ alumno_id : $alumno_id },function($response){
			if ($response) {
				$container_document_alumno.html(null);
				$title_alumno.html($response.alumno.nombre);
				$.each($response.documentoAlumno,function($key, $item){
					$container_document_alumno.append("<tr><td>"+ $item.documento +"</td><td class='text-center'><i class='fa fa-check-square-o' aria-hidden='true'></i></td></tr>");
				});

			}
		},'json');
	}

    var init_edit_alumno = function($alumno_id) {
        $.get("<?= Url::to(['edit-alumno'])  ?>",{ alumno_id : $alumno_id },function($response){
            if ($response) {

                $("input[name='edit_alumno_id']").val($response.alumno ?    $response.alumno.id : null);

                $("input[name='edit_nombre_alumno']").val($response.alumno ?    $response.alumno.nombre : null);
                $("input[name='edit_apellido_alumno']").val($response.alumno ?  $response.alumno.apellidos : null);
                $("input[name='edit_costo_colegiatura']").val($response.alumno ?  $response.alumno.costo_colegiatura : null);

                $("select[name='edit_nivel_id'] option:selected" ).each(function() {
                   $(this).attr("selected", false)
                });

                $("input[name='edit_nombre_vive_con']").val($response.alumno ?    $response.alumno.nombre_vive_con : null);
                $response.alumno.vive_con ? $("select[name='edit_vive_con'] option[value='"+ $response.alumno.vive_con +"']").attr("selected", true) : $("select[name='edit_vive_con']").val(false);

                if($response.alumno.factura){
                    $('#edit_factura_id_access').prop('checked', true);
                }

                if ($response.alumno.is_especial == 10 ){
                    $('#edit_especial_id_access').prop('checked', true);
                    $("select[name='edit_colegiaturas']").attr("disabled",false);
                    $("select[name='edit_ciclo_escolar_id']").attr("disabled",false);
                    $("input[name='edit_costo_colegiatura_especial']").attr("disabled",false);
                }


                $response.alumno.colegiaturas_especial ? $("select[name='edit_colegiaturas'] option[value='"+ $response.alumno.colegiaturas_especial +"']").attr("selected", true) : $("select[name='edit_colegiaturas']").val(false);

                $("input[name='edit_costo_colegiatura_especial']").val($response.alumno ?    $response.alumno.costo_colegiatura_especial : null);


                $response.alumno.nivel ? $("select[name='edit_nivel_id'] option[value='"+ $response.alumno.nivel +"']").attr("selected", true) : $("select[name='edit_nivel_id']").val(false);

                $("select[name='edit_sexo_id'] option:selected" ).each(function() {
                   $(this).attr("selected", false)
                });

                $response.alumno.sexo ? $("select[name='edit_sexo_id'] option[value='"+ $response.alumno.sexo +"']").attr("selected", true) : $("select[name='edit_sexo_id']").val(false);

                $("select[name='edit_grado_id'] option:selected" ).each(function() {
                   $(this).attr("selected", false)
                });

                $response.alumno.grado ? $("select[name='edit_grado_id'] option[value='"+ $response.alumno.grado +"']").attr("selected", true) : $("select[name='edit_grado_id']").val(false);

                $("input[name='edit_fecha_nacimiento_id']").val($response.alumno.fecha_nacimiento ? new Date($response.alumno.fecha_nacimiento *1000).format("Y-m-d") : null);

                $("select[name='edit_tipo_sangre_id'] option:selected" ).each(function() {
                   $(this).attr("selected", false)
                });

                 $response.alumno.tipo_sangre ? $("select[name='edit_tipo_sangre_id'] option[value='"+ $response.alumno.tipo_sangre +"']").attr("selected", true) : $("select[name='edit_tipo_sangre_id']").val(false);


                $("input[name='edit_talla_alumno']").val($response.alumno ?     $response.alumno.talla : null);

                $("input[name='edit_peso_alumno']").val($response.alumno ?  $response.alumno.peso : null);

                $("input[name='edit_lugar_nacimiento']").val($response.alumno ?  $response.alumno.lugar_nacimiento : null);

                $("textarea[name='edit_enfermedades_lesiones']").val($response.alumno ?  $response.alumno.enfermedades_lesiones : null);

                $("textarea[name='edit_antecedentes_familiares']").val($response.alumno ?  $response.alumno.antecedentes_enfermedades : null);

                $("textarea[name='edit_discapacidad_tipo']").val($response.alumno ?  $response.alumno.discapacidad : null);

                $("textarea[name='edit_text_area_descripcion']").val($response.alumno ?  $response.alumno.nota : null);
            }
        },'json');
    }

    $('#especial_id_access').change(function(){
        if( $(this).is(':checked') ) {
            $("select[name='colegiaturas']").attr("disabled",false);
            $("select[name='ciclo_escolar_id']").attr("disabled",false);
            $("input[name='costo_colegiatura_especial']").attr("disabled",false);
        }else{
            $("select[name='colegiaturas']").attr("disabled",true);
            $("select[name='ciclo_escolar_id']").attr("disabled",true);
            $("input[name='costo_colegiatura_especial']").attr("disabled",true);
        }
    });

    $('#edit_especial_id_access').change(function(){
        if( $(this).is(':checked') ) {
            $("select[name='edit_colegiaturas']").attr("disabled",false);
            $("select[name='edit_ciclo_escolar_id']").attr("disabled",false);
            $("input[name='edit_costo_colegiatura_especial']").attr("disabled",false);
        }else{
            $("select[name='edit_colegiaturas']").attr("disabled",true);
            $("select[name='edit_ciclo_escolar_id']").attr("disabled",true);
            $("input[name='edit_costo_colegiatura_especial']").attr("disabled",true);
        }
    });
</script>