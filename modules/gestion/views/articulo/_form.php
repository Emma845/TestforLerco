<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
//use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\esys\EsysListaDesplegable;
use app\models\articulo\Articulo;


/* @var $this yii\web\View */
/* @var $model app\models\sucursales\Sucursal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gestion-articulo-form">

    <?php $form = ActiveForm::begin(['id' => 'form-articulo','options' => ['enctype' => 'multipart/form-data']]) ?>

    <div class="row">
        <div class="col-lg-10">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información generales</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

                            <div class="row">
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'precio')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'inventario')->textInput(['maxlength' => true]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg">
                                    <?= $form->field($model, 'status')->dropDownList(Articulo::$statusList) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <p><strong>CARGA DE IMAGEN</strong></p>                            
                            <?= $form->field($model, 'image')->fileInput([ "class" => "btn btn-block", 'accept' => 'image/*' ,
                            'style' => 'font-size: 14px', 'id' => 'inputImage'])->label(false) ?>
                            <?= Html::img('',['id' => 'imagenPrevisualizacion', 'width' => '150px']);?>
                            <?php if ($model->image_web_filename!=''):?>
                                <?= Html::img(Yii::$app->homeUrl."img/articulos/".$model->image_web_filename,['id' => 'imagenPrevisualizacionUpdate', 'width' => '150px']);?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear articulo' : 'Guardar cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

    const $seleccionArchivos = document.querySelector("#inputImage"),
    $imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion"),
    $imagenPrevisualizacionUpdate = document.querySelector("#imagenPrevisualizacionUpdate");

    $seleccionArchivos.addEventListener("change", () => {
        const archivos = $seleccionArchivos.files;
        // Si no hay archivos salimos de la función y quitamos la imagen
        if (!archivos || !archivos.length) {
            $imagenPrevisualizacion.src = "";
            return;
        }
        // Ahora tomamos el primer archivo, el cual vamos a previsualizar
        const primerArchivo = archivos[0];
        // Lo convertimos a un objeto de tipo objectURL
        const objectURL = URL.createObjectURL(primerArchivo);
        // Y a la fuente de la imagen le ponemos el objectURL
        if($imagenPrevisualizacionUpdate)
            $imagenPrevisualizacionUpdate.src = "";
        $imagenPrevisualizacion.src = objectURL;
    });
</script>

