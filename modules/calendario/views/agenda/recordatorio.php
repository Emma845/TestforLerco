<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use app\models\user\User;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use app\assets\FullCalendarAsset;
use app\models\agenda\Agenda;

FullCalendarAsset::register($this);
?>
<div class="panel">
    <div class="panel-body">
        <div class="fixed-fluid">
            <div class="fixed-sm-200 pull-sm-left fixed-right-border">
                <?php if (Yii::$app->user->can('admin')): ?>
                    <?= Html::label("User","user_id") ?>
                    <?=  Html::dropDownList('user_id', null, User::getItemAll(), ['id' => 'user_id','prompt' => 'Calendario - User', 'class' => 'max-width-170px form-control'])  ?>
                <?php endif ?>

                <hr class="bord-no">
                <p class="text-muted text-sm text-uppercase">TIPO DE NOTAS / RECORDATORIOS</p>
                <?= Html::button('Nota', [ 'class' =>  'btn btn-block btn-warning', "data-target" =>"#modal-show-agenda", "data-toggle" => "modal", "onclick" => "modal_ini_agenda(". Agenda::TIPO_NOTA .")"  ]) ?>

                <?= Html::button('Recordatorio', [ 'class' =>  'btn btn-block btn-success', "data-target" =>"#modal-show-agenda", "data-toggle" => "modal", "onclick" => "modal_ini_agenda(". Agenda::TIPO_RECORDATORIO .")" ]) ?>

                <?= Html::button('Junta - Padre', [ 'class' =>  'btn btn-block btn-purple', "data-target" =>"#modal-show-agenda", "data-toggle" => "modal", "onclick" => "modal_ini_agenda(". Agenda::TIPO_JUNTA.")" ]) ?>

                <?= Html::button('Tareas - Pendientes', [ 'class' =>  'btn btn-block btn-info', "data-target" =>"#modal-show-agenda", "data-toggle" => "modal", "onclick" => "modal_ini_agenda(" . Agenda::TIPO_TAREA .")" ]) ?>

                <?= Html::button('Llamada - Grupal', [ 'class' =>  'btn btn-block btn-danger', "data-target" =>"#modal-show-agenda", "data-toggle" => "modal", "onclick" => "modal_ini_agenda(" . Agenda::TIPO_LLAMADA .")" ]) ?>
            </div>
            <div class="fluid">
                <div id='demo-calendar'></div>
            </div>
        </div>
    </div>
</div>
<div class="fade modal" id="modal-show-agenda" role="dialog" tabindex="-1" aria-labelledby="modal-show-label">
    <div class="modal-dialog "  style="height: 80%; width: 40%">
        <div class="modal-content" style="height: 100%;">
            <!--Modal header-->

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title title-alumno">AGENDA - <strong class="title-agenda"></strong></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::hiddenInput('agenda-tipo', false,["id" => "agenda-tipo"]) ?>
                            <?= Html::hiddenInput('usuario_id', false,["id" => "usuario_id"]) ?>
                            <?= Html::label("Titulo","agenda-titulo") ?>
                            <?= Html::input('text', 'agenda-titulo', null, ['class' => 'form-control','id' => 'agenda-titulo']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::label("Fecha inicio","agenda-fecha_id") ?>
                            <?= DateTimePicker::widget([
                                'id' => 'agenda-fecha_id',
                                'name' => 'agenda-fecha_id',
                                'options' => ['placeholder' => 'Fecha' , 'autocomplete' => 'off'],
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'language' => 'es',
                                'pluginOptions' => [
                                    'timePicker'=>true,
                                    'autoclose' => true,
                                    'daysOfWeekDisabled' => [0, 6],
                                    'format' => 'yyyy-mm-dd HH:ii P',
                                ]
                            ])  ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 div_fecha_fin">
                            <?= Html::label("Fecha fin","agenda-fecha_fin_id") ?>
                            <?= DateTimePicker::widget([
                                'id' => 'agenda-fecha_fin_id',
                                'name' => 'agenda-fecha_fin_id',
                                'options' => ['placeholder' => 'Fecha' , 'autocomplete' => 'off'],
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'language' => 'es',
                                'pluginOptions' => [
                                    'timePicker'=>true,
                                    'autoclose' => true,
                                    'daysOfWeekDisabled' => [0, 6],
                                    'format' => 'yyyy-mm-dd HH:ii P',
                                ]
                            ])  ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 div_agenda_padre_familia">
                            <?= Html::label("Padre familia","agenda-padre_familia_id") ?>
                            <?= Select2::widget([
                                'id' => 'agenda-padre_familia_id',
                                'name' => 'padre_familia_id',
                                'options' => [
                                    'placeholder' => 'Padre familia',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
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
                            ]) ?>
                        </div>
                    </div>
                    <?php /* ?>
                    <div class="row">
                        <div class="col-sm-12 div_alumno_agenda_alumno">
                            <?= Html::label("Alumno","agenda-alumno_id") ?>
                            <?=  Html::dropDownList('agenda-alumno_id', null, [], ['id' => 'agenda-alumno_id','prompt' => 'Selecciona alumno','class' => 'form-control'])  ?>
                        </div>
                    </div>
                    */?>

                    <div class="row">
                        <div class="col-sm-12">
                            <?= Html::label("Nota / Descripción", "agenda-text_area", ["style" => "display:inline"]) ?>
                            <?= Html::textarea('agenda-text_area', null, ['id' => 'agenda-text_area','class' => 'form-control','rows' => 6 ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                <?= Html::submitButton('Agendar', ['class' => 'finish btn btn-primary', 'id' => 'btnAddAgenda']) ?>
            </div>
        </div>
    </div>
</div>

<div class="fade modal" id="modal-show-evento" role="dialog" tabindex="-1" aria-labelledby="modal-evento-label">
    <div class="modal-dialog "  style="height: 75%; width: 30%">
        <div class="modal-content" style="height: 100%;">
            <!--Modal header-->

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Evento</strong></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h1 class="title-evento"></h1>
                            <strong>Fecha start: <span class="fecha_start-evento"></span></strong>
                            <br>
                            <strong>Fecha end: <span class="fecha_end-evento"></span></strong>
                            <hr>
                            <strong>Padre de Familia: <span class="padre_familia-evento"></span></strong>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h4 class="text-primary tipo-evento" style="font-weight: bold; "></h4>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 text-justify">
                            <p class="nota-evento"></p>
                        </div>
                    </div>
                </div>
                <?php if (Yii::$app->user->can('admin')): ?>
                    <button class="btn btn-circle btn-danger btn-delete-event"><i class="fa fa-trash"></i></button>
                <?php endif ?>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
var $title_agenda       = $('.title-agenda'),
    $select_user_id     = $('#user_id'),
    $agenda_titulo      = $('#agenda-titulo'),
    $agenda_fecha       = $('#agenda-fecha_id'),
    $agenda_fecha_fin   = $('#agenda-fecha_fin_id'),
    $btn_delete_event   = $('.btn-delete-event'),
    $div_fecha_fin      = $('.div_fecha_fin'),
    $agenda_padre_familia_id    = $('#agenda-padre_familia_id'),
    $agenda_alumno_id           = $('#agenda-alumno_id'),
    //$div_alumno_agenda_alumno   = $('.div_alumno_agenda_alumno'),
    $div_agenda_padre_familia   = $('.div_agenda_padre_familia'),
    $agenda_text_area           = $('#agenda-text_area'),
    $agenda_tipo                = $('#agenda-tipo'),
    $usuario_id                = $('#usuario_id'),
    $btnAddAgenda               = $('#btnAddAgenda'),
    agenda_array                = [];

var modal_ini_agenda = function($tipo){
    $usuario_id.val($select_user_id.val());
    switch($tipo){
        case 10:
            $title_agenda.html('NOTA');
            //$div_alumno_agenda_alumno.hide();
            $div_agenda_padre_familia.hide();
            $agenda_fecha.val(new Date().format("Y-m-d") +" 00:00");
            $div_fecha_fin.hide();
            $agenda_fecha.attr('disabled',true);
            $agenda_tipo.val(10);
        break;

        case 20:
            $title_agenda.html('RECORDATORIOS');
            //$div_alumno_agenda_alumno.hide();
            $div_agenda_padre_familia.hide();
            $agenda_fecha.val(new Date().format("Y-m-d") +" 00:00");
            $agenda_fecha.attr('disabled',false);
            $div_fecha_fin.hide();
            $agenda_tipo.val(20);
        break;

        case 30:
            $title_agenda.html('JUNTA');
            //$div_alumno_agenda_alumno.show();
            $div_agenda_padre_familia.show();
            $agenda_fecha.val(new Date().format("Y-m-d") +" 00:00");
            $agenda_fecha.attr('disabled',false);
            $div_fecha_fin.hide();
            $agenda_tipo.val(30);
        break;

        case 40:
            $title_agenda.html('TAREA');
            //$div_alumno_agenda_alumno.hide();
            $div_agenda_padre_familia.hide();
            $agenda_fecha.val(new Date().format("Y-m-d") +" 00:00");
            $agenda_fecha.attr('disabled',false);
            $div_fecha_fin.hide();
            $agenda_tipo.val(40);
        break;

        case 50:
            $title_agenda.html('Llamada grupal');
            //$div_alumno_agenda_alumno.hide();
            $div_agenda_padre_familia.hide();
            $agenda_fecha.val(new Date().format("Y-m-d") +" 00:00");
            $agenda_fecha.attr('disabled',false);
            $div_fecha_fin.show();
            $agenda_tipo.val(50);
        break;
    }
}

$select_user_id.change(function(){
    load_agenda($(this).val());
});

$btnAddAgenda.click(function(){
    if (validation_add($agenda_tipo.val())) {
        $.post("<?= Url::to(['add-agenda'])  ?>",
            {
                agenda_titulo       : $agenda_titulo.val(),
                agenda_fecha        : $agenda_fecha.val(),
                agenda_fecha_fin    : $agenda_fecha_fin.val(),
                agenda_padre_familia_id : $agenda_padre_familia_id.val(),
                //agenda_alumno_id : $agenda_alumno_id.val(),
                agenda_usuario_id  : $usuario_id.val(),
                agenda_text_area : $agenda_text_area.val(),
                agenda_tipo      : $agenda_tipo.val(),
            },function($response){
                if ($response.code == 202) {
                    $.niftyNoty({
                        type: 'success',
                        icon : 'pli-like-2 icon-2x',
                        message : 'Se agendo correctamente.',
                        container : 'floating',
                        timer : 5000
                    });

                    $("#modal-show-agenda").modal('hide');
                    load_agenda();
                }else{
                    $.niftyNoty({
                        type: 'danger',
                        icon : 'pli-cross icon-2x',
                        message : 'Ocurrio un error, intenta nuevamente.',
                        container : 'floating',
                        timer : 5000
                    });
                }
        });

    }
});

var load_agenda = function($user_id = null){
    agenda_array = [];
    $.get("<?=  Url::to(['get-agenda']) ?>",{ user_id : $user_id },function($response){

        if ($response.code == 202 ) {
            $.each($response.items,function(key,item){
                color = '';
                if (item.tipo == 10 )
                    color = 'warning';
                if (item.tipo == 20 )
                    color = 'success';
                if (item.tipo == 30 )
                    color = 'purple';
                if (item.tipo == 40 )
                    color = 'info';
                if (item.tipo == 50 )
                    color = 'danger';
                if (item.tipo == 50 ){
                    fecha_fin = item.fecha_fin ? new Date(item.fecha_fin *1000) : item.fecha ;
                    //fecha_fin.setDate(fecha_fin.getDate() + 1);
                    agenda_array.push({
                        title: item.titulo,
                        id: item.id,
                        start: new Date((new Date(item.fecha *1000)).toString().split('GMT')[0]+' UTC').toISOString(),
                        end:new Date(fecha_fin.toString().split('GMT')[0]+' UTC').toISOString(),
                        className: color
                    });
                }
                else
                    agenda_array.push({
                        id: item.id,
                        title: item.titulo,
                        start: new Date(item.fecha *1000).format("Y-m-d"),
                        className: color
                    });

            });
            $('#demo-calendar').fullCalendar('removeEvents');
            $('#demo-calendar').fullCalendar('addEventSource', agenda_array);
            $('#demo-calendar').fullCalendar('rerenderEvents' );

            // -----------------------------------------------------------------

        }
    },'json');

}

var validation_add = function($tipo){

    switch($tipo){
        case "10":
            if (!$agenda_titulo.val() || !$agenda_fecha.val() ) {
                $.niftyNoty({
                    type: 'warning',
                    icon : 'pli-cross icon-2x',
                    message : 'Verifica los datos, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
                return false;
            }
        break;

        case "20":
            if (!$agenda_titulo.val() || !$agenda_fecha.val() ) {
                $.niftyNoty({
                    type: 'warning',
                    icon : 'pli-cross icon-2x',
                    message : 'Verifica los datos, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
                return false;
            }
        break;

        case "30":
            if (!$agenda_titulo.val() || !$agenda_fecha.val() || !$agenda_padre_familia_id.val()) {
                $.niftyNoty({
                    type: 'warning',
                    icon : 'pli-cross icon-2x',
                    message : 'Verifica los datos, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
                return false;
            }
        break;

        case "40":
            if (!$agenda_titulo.val() || !$agenda_fecha.val()) {
                $.niftyNoty({
                    type: 'warning',
                    icon : 'pli-cross icon-2x',
                    message : 'Verifica los datos, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
                return false;
            }
        break;
        case "50":
            if (!$agenda_titulo.val() || !$agenda_fecha.val()  || !$agenda_fecha_fin.val()) {
                $.niftyNoty({
                    type: 'warning',
                    icon : 'pli-cross icon-2x',
                    message : 'Verifica los datos, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
                return false;
            }
        break;
        default :
                $.niftyNoty({
                    type: 'danger',
                    icon : 'pli-cross icon-2x',
                    message : 'Ocurrio un error al agendar, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
            return false;
        break;
    }
    return true;
}

var delete_event = function($event_id)
{
    if (confirm("Confirma que deseas eliminar el evento")) {
        $.post("<?= Url::to('delete-event') ?>",{ event_id : $event_id},function($response){
            if ($response.code == 202) {
                if ($select_user_id.val())
                    load_agenda($select_user_id.val());
                else
                    load_agenda();

                $.niftyNoty({
                    type: 'success',
                    icon : 'pli-like-2 icon-2x',
                    message : 'Se elimino correctamente',
                    container : 'floating',
                    timer : 5000
                });
                $("#modal-show-evento").modal('hide');
            }else
                $.niftyNoty({
                    type: 'danger',
                    icon : 'pli-cross icon-2x',
                    message : 'Ocurrio un error al eliminar el evento, intenta nuevamente.',
                    container : 'floating',
                    timer : 5000
                });
        });
    }
}

$(document).on('nifty.ready', function() {
    // initialize the external events
    // -----------------------------------------------------------------
    load_agenda();
    $('#demo-external-events .fc-event').each(function() {
        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true, // maintain when user navigates (see docs on the renderEvent method)
            className : $(this).data('class')
        });


        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 99999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });
    });

    $('#demo-calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        eventClick: function(item) {
            $('#modal-show-evento').modal('show');
            $.get("<?= Url::to(['get-evento']) ?>",{ id_evento : item.id },function($response){
                if ($response.code == 202) {
                    $('.title-evento').html($response.event.titulo);

                    $('.fecha_start-evento').html($response.event.tipo == 50 ? new Date((new Date($response.event.fecha_ini *1000)).toString().split('GMT')[0]+' UTC').toISOString() : new Date($response.event.fecha_ini * 1000).format("Y-m-d") );

                    $('.fecha_end-evento').html($response.event.tipo == 50 ? new Date((new Date($response.event.fecha_fin *1000)).toString().split('GMT')[0]+' UTC').toISOString()  : 'N/A');

                    $('.padre_familia-evento').html($response.event.tipo == 30 ? $response.event.padre_familia : 'N/A');
                    $('.tipo-evento').html($response.event.tipo_text);
                    $('.nota-evento').html($response.event.nota);
                    $btn_delete_event.attr("onclick","delete_event("+ $response.event.id +")");

                }else
                    $.niftyNoty({
                        type: 'danger',
                        icon : 'pli-cross icon-2x',
                        message : 'Ocurrio un error al obtener el evento, intenta nuevamente.',
                        container : 'floating',
                        timer : 5000
                    });
            },'json');
        },
        droppable: true, // this allows things to be dropped onto the calendar
        drop: function() {
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }
        },
        defaultDate: new Date().getFullYear()+"-" +  (new Date().getMonth()+1) + "-" + new Date().getDate(),
        eventLimit: true, // allow "more" link when too many events
        events: agenda_array,
    });


});
</script>