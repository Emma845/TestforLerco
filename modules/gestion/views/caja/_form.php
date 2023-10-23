<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
//use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\caja\Caja;
use app\models\ciclo\CicloTarifa;
use app\models\ciclo\ViewCiclos;
use app\models\esys\EsysListaDesplegable;
use app\models\cobro\CobroAlumno;

?>

<style>
input[type="checkbox"]{
visibility: hidden;
}

.mychecklabel {
  font-weight: bold;
  color: black;
  display: inline-block;
  width: 90px;
  /* height: 30px; */
  padding-top:10px;
  padding-bottom:8px;
  border-radius: 4px;
  border: solid 1px black;
  cursor: pointer;
  position: relative;
  justify-content: center;
  top: 10px;
  left: 10px;
  z-index: 1;
  transition: all .5s ease;
}

input[type=checkbox]:checked + .mychecklabel {
  background: lightgray;
}
</style>

<div class="cajas-caja-form">

    <?php $form = ActiveForm::begin(['id' => 'form-caja' ]) ?>
    <?= $form->field($model->cobroAlumno, 'cobroAlumnoArray')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Información generales</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <?= $form->field($model, 'padre_tutor_id')->widget(Select2::classname(),
                            [
                                'language' => 'es',
                                    'data' => isset($model->padre_tutor_id)  && $model->padre_tutor_id ? [$model->padreTutor->id => $model->padreTutor->nombre ." ". $model->padreTutor->apellidos] : [],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        'language'   => [
                                            'errorLoading' => new JsExpression("function () { return 'Esperando los resultados...'; }"),
                                        ],
                                        'ajax' => [
                                            'url'      => Url::to(['/crm/cliente/cliente-ajax']),
                                            'dataType' => 'json',
                                            'cache'    => true,
                                            'processResults' => new JsExpression('function(data, params){  return {results: data} }'),
                                        ],
                                    ],
                                    'options' => [
                                        'placeholder' => 'Selecciona al padre / tutor...',
                                        
                                    ],

                            ]) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::label("Alumnos","select_alumno") ?>
                            <?= Html::dropDownList("alumno_select","",[],['prompt' => '--- select ---', 'class' => 'form-control','id' => 'alumno_select_id'])  ?>
                        </div> 
                        <div class="col-sm-2">
                            <?= Html::label("Grado","select_alumno") ?>
                            <?= Html::label("","grado",['class' => 'form-control','id' => 'grado']) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($model, 'ciclo_escolar_id')->dropDownList(ViewCiclos::cicloEscolar()) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($model, 'tipo_id')->dropDownList( CicloTarifa::$pagoList,['prompt'=>'--seleccione--']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row container-info" style="display: none">
                <div class="col-sm-4 text-center">
                    <div class="media mar-btm">
                        <div>
                            <img src="<?= Url::to(['/img/profile-photos/4.png']) ?>" class="img-md img-circle" alt="Avatar">
                        </div>
                        <div class="media-body">
                            <p class="text-lg text-main text-semibold mar-no">Tutor / Padre</p>
                            <p class="title-padre-name"></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <div class="media mar-btm">
                        <div>
                            <img src="<?= Url::to(['/img/profile-photos/5.png']) ?>" class="img-md img-circle" alt="Avatar">
                        </div>
                        <div class="media-body">
                            <p class="text-lg text-main text-semibold mar-no">Alumno</p>
                            <p class="title-alumno-name"></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center" >
                    <Label id="Titulo_totales"></Label>
                    <h1><strong class="title-colegiatura" style="display: none; font-size:16px;"></strong></h1>
                </div> 
                <br><br>
                <div class="col-sm-12 text-center" >
                    <div id="Meses_consumidos" class="Meses_consumidos" style="display: none">
                    </div>
                </div>
                <br><br>
                    
                
            </div>
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">PAGO</h3>
                </div>
                
           
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3>Ingresa los pagos a realizar</h3>
                            <div class="row" style="border-style: double;padding: 2%;">
                                <div class="col-sm-4">
                                    <?= $form->field($model->cobroAlumno, 'metodo_pago')->dropDownList(CobroAlumno::$servicioList,['prompt'=>'--seleccione--'])->label("&nbsp;") ?>
                                </div>
                                <div class="col-sm-3">
                                    
                                <?= Html::label("Costo","total") ?>
                                <?= Html::label("","grado",['class' => 'form-control','id' => 'cobroalumno-cantidad']) ?>
                                </div>
                                
                                <div class="col-sm-3">
                                <?= Html::label("Pago con:","total",['id' => 'efectivoalumno-cantidad-titulo','style'=>['display'=>'none']]) ?>
                                <?= Html::input("","grado",'',['class' => 'form-control','id' => 'efectivoalumno-cantidad','placeholder'=>'escribe la cantidad','style'=>['display'=>'none']]) ?>
                                </div>
                                <div class="col-sm-2">
                                     <div class="form-group">
                                         <button  type="button"class="btn  btn-primary btn-xs" style="margin-top: 35%;" id="btnAgregarMetodoPago" onclick="cobro()" disabled>Ingresar pago</button>
                                     </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="margin-top: 5%;">
                                    <table class="table table-hover table-vcenter" style="background: aliceblue;">
                                        <thead>
                                            <tr>
                                                <th>Forma de pago</th>
                                                <th class="text-center">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody class="content_metodo_pago" style="text-align: center;">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" style="border: none" colspan="2"><span class="text-main text-semibold">Total cobrado: </span></td>
                                                <td><strong id= "pago_metodo_total">0</strong></td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <?php /* ?>
                            <div class="form-group">
                                <?= Html::checkbox("pago_parcial",
                                    false,
                                    [
                                        "id"    => "pago_parcial_access",
                                        "class" => "modulo magic-checkbox"
                                    ]
                                ) ?>
                                <?= Html::label("¿ PAGO PARCIAL?", "pago_parcial_access", ["style" => "display:inline;"]) ?>
                            </div>

                            <?= $form->field($model, 'monto')->textInput(['type' => 'number','step' => 'any']) ?>
                            */?>
                        </div>
                        <div class="col-sm-6">

                            <div class="row container_periodo" style="display: none">
                                <h5>Selecciona los meses a cobrar</h5>
                                <div class="col-sm-6" style="padding: 5%">
                                    <div class="form-group">
                                        <?= Html::checkbox("agosto_access",
                                            false,
                                            [
                                                "id"    => "agosto_access",
                                                "class" => "mycheck"
                                            ]
                                        ) ?>
                                        <?= Html::label("AGOSTO", "agosto_access", ["style" => "display:inline;"]) ?>
                                    </div>

                                    <div class="form-group">
                                        <?= Html::checkbox("septiembre_access",
                                            false,
                                            [
                                                "id"    => "septiembre_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("SEPTIEMBRE", "septiembre_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("octubre_access",
                                            false,
                                            [
                                                "id"    => "octubre_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("OCTUBRE", "octubre_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("noviembre_access",
                                            false,
                                            [
                                                "id"    => "noviembre_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("NOVIEMBRE", "noviembre_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("diciembre_access",
                                            false,
                                            [
                                                "id"    => "diciembre_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("DICIEMBRE", "diciembre_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("enero_access",
                                            false,
                                            [
                                                "id"    => "enero_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("ENERO", "enero_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding: 5%">
                                    <div class="form-group">
                                        <?= Html::checkbox("febrero_id_access",
                                            false,
                                            [
                                                "id"    => "febrero_id_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("FEBRERO", "febrero_id_access", ["style" => "display:inline;"]) ?>
                                    </div>

                                    <div class="form-group">
                                        <?= Html::checkbox("marzo_access",
                                            false,
                                            [
                                                "id"    => "marzo_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("MARZO", "marzo_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("abril_access",
                                            false,
                                            [
                                                "id"    => "abril_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("ABRIL", "abril_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("mayo_access",
                                            false,
                                            [
                                                "id"    => "mayo_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("MAYO", "mayo_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("junio_access",
                                            false,
                                            [
                                                "id"    => "junio_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("JUNIO", "junio_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::checkbox("julio_access",
                                            false,
                                            [
                                                "id"    => "julio_access",
                                                "class" => "modulo magic-checkbox"
                                            ]
                                        ) ?>
                                        <?= Html::label("JULIO", "julio_access", ["style" => "display:inline;"]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear pago' : 'Guardar cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'btnSavePago']) ?>
        <?= Html::a('Cancelar', ['index', 'tab' => 'index'], ['class' => 'btn btn-white']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<div class="display-none">
    <table>
        <tbody class="template_metodo_pago">
            <tr id = "metodo_id_{{metodo_id}}">
                <td ><?= Html::tag('p', "0",["class" => "text-main text-semibold" , "id"  => "table_metodo_id"]) ?></td>
                <td ><?= Html::tag('p', "",["class" => "text-main " , "id"  => "table_metodo_cantidad","style" => "text-align:center"]) ?></td>
            </tr>
        </tbody>
    </table>
</div>


<script>
    var $cajaTipoId = $('#caja-tipo_id');
    var alumno_id = [];
    var tutor_id = [];
    var tipo_id = [];
    var grado_selected = [];
    var data_pago = [];
    var nivel = 0;
    var costo_col_especial;
    let pago_neto;
    
    var meses_consumidos;
    var meses_especial;

    var mensualidad = [];
    var mensualidades_especiales = [];
    var mensualidades_regulares = [];

    var count = 0;
    var meses_sobrantes = [];
    var cliclo_selected = document.getElementById('caja-ciclo_escolar_id');
    var total;

    var $selectPadreTutorId = $("#caja-padre_tutor_id"),
        meses_frame = $('#Meses_consumidos'),
        select_metodo_pago = $('#cobroalumno-metodo_pago'),
        selectciclo = $("#caja-ciclo_escolar_id"),
        input_grado = $('#grado'),
        $selectAlumnoId = $("#alumno_select_id"),
        $selectalumnoId     = $("#alumno_select_id"),
        $inputCantidad      = $("#caja-cantidad"),
        selectNivel      = $("#caja-nota"),
        $cajaPeriodicidad   = $("#caja-periodicidad"),
        $content_metodo_pago    = $(".content_metodo_pago"),
        $inputcobroRembolsoEnvioArray = $('#cobroalumno-cobroalumnoarray'),
        $btnAgregarMetodoPago       =  $('#btnAgregarMetodoPago'),
        $template_metodo_pago    = $('.template_metodo_pago'),
        tipoList           = JSON.parse('<?= json_encode(CobroAlumno::$servicioList) ?>'),
        costoPago           = 0;
        colegiaturaPago     = 0;
        tipo_pago           = 0;
        data_alumno         = [];
        is_especial         = false;
        meses_especial      = 0;
        metodoPago_array   = [];
        $btnSavePago        = $('#btnSavePago'),
        $form_metodoPago = {
            $metodoPago : $('#cobroalumno-metodo_pago'),
            $cantidad   : $('#cobroalumno-cantidad'),
        };

    $selectPadreTutorId.change(function(){
        tutor_id = $(this).val();
        $("#alumno_select_id").html('');
        $('.container-info').hide();
        $cajaPeriodicidad.val(null).trigger('change');

        $.get("<?= Url::to(['padre-alumno-all'])  ?>",{ padre_tutor_id: $(this).val()},function($response){
            if ($response) {
                $.each($response, function(key, alumno){
                    $("#alumno_select_id").append(new Option(alumno.nombre +" "+ alumno.apellidos , alumno.id ));
                });
                $selectAlumnoId.trigger('change') //ESTO SE ASEGURA QUE SI EL ALUMNO CAMBIA, LAS CONDICIONES TAMBIEN 
            }
        });
    });

    select_metodo_pago.change(function(){
        cobro_mensualidades();
    });

    $selectAlumnoId.change(function(){
        $cajaPeriodicidad.val(null).trigger('change');
        alumno_id = $(this).val();
        ciclos_tarifas();
        tarifas();
    });

    function tarifas(){  
        //verrificara el costo de las tarifas segun sea su ciclo y id para saber el grado
        
        let ciclo = document.getElementById('caja-ciclo_escolar_id');

        $.get("<?= Url::to(['alumno-info'])  ?>",{ id:alumno_id, ciclo:ciclo.value  },function($response){
           
            meses_frame.hide();
            
            if ($response) {
                let gradoinput = document.getElementById('grado');
                let gradotext;
                switch ($response.grado) {
                    case 2710:
                        gradotext = 'Prescolar';
                        nivel = 1;
                        break;  

                    case 2720:
                        gradotext = 'Primaria';
                        nivel = 2;                        
                        break;  
                    case 2730:
                        gradotext = 'Secundaria';
                        nivel = 3;
                        
                        break;
                
                    default:
                        break;
                }

                gradoinput.innerHTML = gradotext;
                grado_selected = $response.grado;

                if ($response.es_especial != null) {
                    //Costo de pago especial
                    costo_col_especial = $response.costo_especial;
                    meses_especial = $response.colegiaturas_especiales;
                    // deduccion de meses consumidos
                    let ciclo = document.getElementById('caja-ciclo_escolar_id');
                    $.get("<?= Url::to(['verificar-meses'])  ?>",{ alumno_id: alumno_id , tutor: tutor_id,ciclo: ciclo.value},function(response){
                        
                        meses_consumidos = response;
                    
                    });
                    
                } else {
                    costo_col_especial = null;
                    meses_especial = null;
                    
                }

                data_pago = $response.tarifa;
                $('.container-info').show();
                $('.title-padre-name').html($('option:selected', $selectPadreTutorId).text());
                $('.title-alumno-name').html($response.nombre+" [ "+$response.id+" ] ");
                if (parseFloat($response.grado) > 0 ) {
                    $('.title-colegiatura').html( ( costoPago ));
                }else{
                    $('.title-colegiatura').html('NULL');
                }
            }
        });
        
    }

    selectciclo.change(function(){
    ciclos_tarifas();
    tarifas();
    });
    
    $cajaTipoId.click(function()
    {
        tipo_id = $(this).val();
        vinculacion();
    });

    function ciclos_tarifas(){
         
        let ciclo = document.getElementById('caja-ciclo_escolar_id');
      //Verifica si ya se realizo la inscripcion que solo puede hacerse una sola vez
        $.get("<?= Url::to(['verificar-tipo'])  ?>",{ alumno_id: alumno_id , tutor: tutor_id,ciclo: ciclo.value},function(response){
          $('#caja-tipo_id').html('');
           $.each(response, function(key, alumno){
                    $("#caja-tipo_id").append(new Option(alumno.name , alumno.id ));
                });
        });

    }
    
    function Checked($id){
        var datos_registrados;
        
        var meses=[];
        var index=[];                      
        let boton_pago = document.getElementById('btnAgregarMetodoPago');           

        var indice = mensualidades_especiales.indexOf($id);
        let mes_especial = mensualidad.indexOf($id);
        let mes_regular = mensualidades_regulares.indexOf($id);

        let meses_restantes = meses_especial-meses_consumidos ;
        $.each(mensualidades_especiales, function(key, meses){
                count = key+1;
            });

            if(mes_especial !== -1){
               mensualidad.splice(mes_especial, 1);
             }else{
               mensualidad.push($id);
             }

            if(indice !== -1){
                mensualidades_especiales.splice(indice, 1);
                count = count-1;

            }else{

                if (count < meses_restantes ) {
                    mensualidades_regulares.splice(indice, 1);
                    mensualidades_especiales.push($id);
                    count = count+1;

                if (count >= meses_restantes) {
                    console.log('Has consumido todas tus colegiaturas especiales')
                
                    /*$.each(mensualidad, function(key, mes){
                        let check = document.getElementById(`mes_${mes}`);
                    });*/

                }
                }else{
                    console.log('consumiste todas tus colegiaturas especiales, se aplicara el cargo total regular.');
                    if(mes_regular !== -1){
                        mensualidades_regulares.splice(mes_regular, 1);
                    }else{
                            mensualidades_regulares.push($id);
                    }
                }
                
            }
            
        boton_pago.disabled = false;

        if (mensualidad.length==12) {
            boton_pago.disabled = true;  
        }   
        cobro_mensualidades();

    }

    function cobro(){
        
        let dropdown_metodo_pago = document.getElementById('cobroalumno-metodo_pago').value;

        //Guardar datos
        $.get("<?= Url::to(['guardar-pago'])  ?>",{ alumno_id:alumno_id, tutor:tutor_id, tipo:tipo_id, ciclo:cliclo_selected.value, tarifa_regular:total, tarifa_especial:costo_col_especial, col_especial:mensualidades_especiales, col_regular:mensualidades_regulares, metodo_pago: dropdown_metodo_pago, total_neto:pago_neto },function(response){
        
            if (response != 'Error al guardar datos de pago.' || response!=null) {

                window.location.reload();
                
            }
        });


    }

    function cobro_mensualidades(){
        
        let colegiaturas_especiales_pago;
        let input_pago = document.getElementById('efectivoalumno-cantidad');
        let input_pago_titulo = document.getElementById('efectivoalumno-cantidad-titulo');
        let monto_extra_operacion;
        let monto_operacion;
        let total_especiales = 0;
        let total_regulares= 0;
        let suma_totales = 0;


        //componentes
        let dropdown_metodo_pago = document.getElementById('cobroalumno-metodo_pago').value;

        if (tipo_id==2||tipo_id==1) {
            total_especiales = mensualidades_especiales.length * costo_col_especial;
            total_regulares =  mensualidades_regulares.length * total;

            suma_totales = total_especiales + total_regulares;

            switch (dropdown_metodo_pago) {
                case '10':
                    monto_extra_operacion = 0;
                    //Aqui se muestan los campos ocultos para ingresar con cuanto se paga
                    
                    input_pago.style.display = 'block';
                    input_pago_titulo.style.display = 'block';


                    break;
                case '40':
                    monto_extra_operacion = (suma_totales/100)*3;
                    //aqui debe guardar el campo de ingresar con cuanto se paga
                    
                    input_pago.style.display = 'none';
                    input_pago_titulo.style.display = 'none';

                    break; 
                case '50':
                    monto_extra_operacion = (suma_totales/100)*3;
                    //aqui debe guardar el campo de ingresar con cuanto se paga

                    input_pago.style.display = 'none';
                    input_pago_titulo.style.display = 'none';

                    break;
                default:
                    monto_extra_operacion = 0;
                    break;
            }
            
            pago_neto = (suma_totales) + monto_extra_operacion;
            
            //console.log(pago_neto);

            
            if (mensualidades_especiales!=null) {
                colegiaturas_especiales_pago = mensualidades_especiales;
            }

            if (mensualidades_regulares!=null) {
              let Colegiatura_regulares_pago = mensualidades_regulares;
            }
            let campo_pago = document.getElementById('cobroalumno-cantidad');
                campo_pago.innerHTML = pago_neto;           
        }

    }

    function vinculacion(){
        //funcion solo para imprimir el resultado de las tarifas
       var data = 0;
       var total_titulo = document.getElementById("Titulo_totales");

        if(nivel == 1)
        {
            data = 0;   
        }
        if(nivel ==2)
        {
            data = 1;  
        }
        if(nivel ==3)
        {
            data = 2;   
        }

        if(tipo_id == 1)
        {
            total = data_pago[data].inscripcion;   
           
            total_titulo.innerHTML = 'TOTAL INSCRIPCION'
            meses_frame.innerHTML='';
            meses_frame.hide();
        }
        if(tipo_id ==2)
        {
            total = data_pago[data].colegiatura;  

            total_titulo.innerHTML = 'TOTAL COLEGIATURA'
          
        }
        if(tipo_id ==3)
        {
            total = data_pago[data].mora;   
            total_titulo.innerHTML = 'TOTAL MORA'
        }
        if (tipo_id==2) {
            
            let meses = meses_especial - meses_consumidos;  

            let checkeds = document.getElementById('Meses_consumidos');
            checkeds.innerHTML = '';
            $.get("<?= Url::to(['get-meses']) ?>",{},function(response){
                
         
            let ciclo = document.getElementById('caja-ciclo_escolar_id');
                $.each(response, function(key, Mes){

                    mensualidad.push(Mes.id);
                    //aqui es cuanco selecciona el mes
                    $.get("<?= Url::to(['get-confirm-meses']) ?>",{alumno_id: alumno_id, ciclo:ciclo.value, tipo_pago: tipo_id, mes:Mes.id},function(response){
                      
                      if (response.mes_pago!=0) {
                        if (response.mes_pago != Mes.id) {
                            $("#Meses_consumidos").append(`<input type="checkbox" name="checked[${Mes.id}]" id="mes_${Mes.id}" onclick="Checked(${Mes.id})" class="exp mycheck"> 
                                                          <label class="exp mychecklabel" style="margin-bottom: 5px; display: inline-block; " for="mes_${Mes.id}">${Mes.name}</label>`);
                        }else{
                            $("#Meses_consumidos").append(`<input type="checkbox" name="checked[${Mes.id}]" id="mes_${Mes.id}" onclick="Checked(${Mes.id})" class="exp mycheck" checked disabled>
                                                         <label class="exp mychecklabel" style="margin-bottom: 5px; display: inline-block;" for="mes_${Mes.id}" >${Mes.name}</label>`);  
                        }
                      }
                        
                    });
                });
                meses_frame.show();
            });

            if (costo_col_especial>0) {
                
                $('.title-colegiatura').html( ('Colegiatura especial: $'+costo_col_especial +' | Colegiatura regular: $'+total ));
                
            }else{
                
                $('.title-colegiatura').html( ( total ));

            }
            $('.title-colegiatura').show();
            
        } else {

            $('.title-colegiatura').html( ( total ));
            $('.title-colegiatura').show();
        }

    }

    $inputCantidad.change(function(){
        $('#caja-monto').val(parseFloat(costoPago * parseInt($(this).val())).toFixed(2));
    });

    $cajaPeriodicidad.change(function(){
        $('.container_periodo').hide();
        if ($('#caja-ciclo_escolar_id').val()) {
            if ($(this).val()) {
                if ($(this).val() == 10 ) {
                    $('.container_periodo').show();
                    if (!costoColegiatura) {
                        $.niftyNoty({
                            type: 'warning',
                            icon : 'pli-like-2 icon-2x',
                            message : 'El alumno se debe configurar el COSTO DE COLEGIATURA para continuar',
                            container : 'floating',
                            timer : 5000
                        });

                        $btnSavePago.attr('disabled',true);
                    }else
                        $btnSavePago.attr('disabled',false);
                }else
                    $btnSavePago.attr('disabled',false);
            }
        }else{
            $(this).val(null);
            $.niftyNoty({
                type: 'warning',
                icon : 'pli-like-2 icon-2x',
                message : 'Debes seleccionar un CICLO ESCOLAR para poder continuar',
                container : 'floating',
                timer : 5000
            });
            $btnSavePago.attr('disabled',true);
        }

    });


$('#caja-ciclo_escolar_id').change(function(){
    if ($('#caja-ciclo_escolar_id').val())
        $selectPadreTutorId.attr('disabled',false);
    else
        $selectPadreTutorId.attr('disabled',true);
})

/*====================================================
*               RENDERIZA TODO LOS METODS DE PAGO
*====================================================*/
var render_metodo_template = function(){
    $content_metodo_pago.html("");
    pago_total = 0;
    $.each(metodoPago_array, function(key, metodo){
        if (metodo.metodo_id) {

            metodo.metodo_id = key + 1;

            template_metodo_pago = $template_metodo_pago.html();
            template_metodo_pago = template_metodo_pago.replace("{{metodo_id}}",metodo.metodo_id);

            $content_metodo_pago.append(template_metodo_pago);

            $tr        =  $("#metodo_id_" + metodo.metodo_id, $content_metodo_pago);
            $tr.attr("data-metodo_id",metodo.metodo_id);
            $tr.attr("data-origen",metodo.origen);

            $("#table_metodo_id",$tr).html(metodo.metodo_pago_text);
            $("#table_metodo_cantidad",$tr).html( btf.conta.money(metodo.cantidad) );

            pago_total = pago_total + parseFloat(metodo.cantidad);

            if (metodo.origen != 2) {
                $tr.append("<button type='button' class='btn btn-warning btn-circle' onclick='refresh_metodo(this)'><i class='fa fa-trash'></i></button>");
            }
        }
    });

    $('#pago_metodo_total').html("$ "+ pago_total.toFixed(2));

    $inputcobroRembolsoEnvioArray.val(JSON.stringify(metodoPago_array));
}


var refresh_metodo = function(ele){
    $ele_paquete_val = $(ele).closest('tr');

    $ele_paquete_id  = $ele_paquete_val.attr("data-metodo_id");
    $ele_origen_id   = $ele_paquete_val.attr("data-origen");

      $.each(metodoPago_array, function(key, metodo){
        if (metodo) {
            if (metodo.metodo_id == $ele_paquete_id && metodo.origen == $ele_origen_id ) {
                metodoPago_array.splice(key, 1 );
            }
        }
    });

    $(ele).closest('tr').remove();
    $inputcobroRembolsoEnvioArray.val(JSON.stringify(metodoPago_array));
    render_metodo_template();
}

$btnAgregarMetodoPago.click(function(){

    if(!$form_metodoPago.$metodoPago.val() || !$form_metodoPago.$cantidad.val()){
        return false;
    }

    metodo = {
        "metodo_id"         : metodoPago_array.length + 1,
        "metodo_pago_id"    : $form_metodoPago.$metodoPago.val(),
        "metodo_pago_text"  : $('option:selected', $form_metodoPago.$metodoPago).text(),
        "cantidad"          : parseFloat($form_metodoPago.$cantidad.val()),
        "origen"            : 1,
    };
    console.log($form_metodoPago.$cantidad.val());

    metodoPago_array.push(metodo);
    render_metodo_template();

});

</script>


