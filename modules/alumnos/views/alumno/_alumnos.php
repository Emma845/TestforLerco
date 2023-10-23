<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use app\models\documento\Documento;
use app\models\esys\EsysListaDesplegable;
?>
<p>
	<?= Html::button('Nuevo alumno',  [ 'class' => 'btn btn-mint', "data-target"=> "#demo-default-modal", "data-toggle" => "modal" ]) ?>
</p>

<div class="row">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Alumno(s)</h3>
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
							<th class="text-center">Creado</th>
							<th class="text-center">Accion</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($model->alumno as $key => $alumno): ?>
							<tr>
								<td><?= $alumno->id ?></td>
								<td><a href="#" class="btn-link">#<?= str_pad($alumno->id, 7 , "0",STR_PAD_LEFT)  ?></a></td>
								<td class="text-center"><?= $alumno->nombre ?></td>
                                <td class="text-center"><?= $alumno->apellidos ?></td>
                                <td class="text-center"><?= $alumno->nivelText->singular ?></td>
                                <td class="text-center"><?= $alumno->gradoText->singular ?></td>
                                <td class="text-center"><?= $alumno->fecha_nacimiento ?></td>
                                <td class="text-center"><?= $alumno->peso ?></td>
                                <td class="text-center"><?= isset( $alumno->tipoSangreText->singular) ?  $alumno->tipoSangreText->singular : '' ?></td>
								<td class="text-center"><?= date("Y-m-d",time()) ?></td>
								<td class="text-center"><?= Html::button('<i class="fa fa-eye"></i>', [ 'class' =>  'btn btn-circle btn-xs btn-mint', "data-target" =>"#modal-show-credito", "data-toggle" => "modal", "onclick" => "init_alumno($alumno->id)"  ]) ?>
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
                            <?=  Html::dropDownList('nivel_id', null, EsysListaDesplegable::getItems('nivel'), ['class' => 'max-width-170px form-control'])  ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= Html::label("Grado","grado_id") ?>
                            <?=  Html::dropDownList('grado_id', null, EsysListaDesplegable::getItems('grado'), ['class' => 'form-control'])  ?>
                        </div>
                        <div class="col-sm-6">
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
                        <div class="col-sm-4">
                            <?= Html::label("Tipo de sangre","tipo_sangre") ?>
                            <?=  Html::dropDownList('tipo_sangre_id', null, EsysListaDesplegable::getItems('tipo_sangre'), ['prompt' => 'Tipo de sangre','class' => 'form-control'])  ?>
                        </div>

                        <div class="col-sm-4">
                            <?= Html::label("Talla","talla_alumno") ?>
                            <?= Html::input('number', 'talla_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Peso","peso_alumno") ?>
                            <?= Html::input('number', 'peso_alumno', null, ['class' => 'form-control']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Html::label("Lugar de Nacimiento","lugar_nacimiento") ?>
                            <?= Html::input('text', 'lugar_nacimiento', null, ['class' => 'form-control']) ?>
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
                            <h4>DOCUMENTOS A SOLICITAR</h4>
                                <div class="row">
                            <?php foreach (Documento::getItems() as $key => $item): ?>
                                    <div class="col-sm-6">
                                        <?= Html::checkbox("Documento[$key]",
                                            false,
                                            [
                                                "id"    => "documento_id_{$key}_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label($item, "documento_id_{$key}_access", ["style" => "display:inline"]) ?>
                                    </div>
                            <?php endforeach ?>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
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



<div class="fade modal" id="modal-show-credito" role="dialog" tabindex="-1" aria-labelledby="modal-show-label">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height: 85%;">
            <!--Modal header-->

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title title-alumno"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                        	<h4>Alumno</h4>
                        	<table class="table table-condensed">
                        		<thead>
                        			<th>DOCUMENTO</th>
                        			<th class="text-center">CHECK</th>
                        		</thead>
                        		<tbody class="container_document_alumno">

                        		</tbody>
                        	</table>
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
            </div>
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

</script>