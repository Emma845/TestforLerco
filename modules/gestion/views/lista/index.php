<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\BootstrapTableAsset;
use app\models\esys\EsysListaDesplegable;
use kartik\daterange\DateRangePicker;


BootstrapTableAsset::register($this);


/* @var $this yii\web\View */

$this->title = 'Pase de lista';
$this->params['breadcrumbs'][] = $this->title;

$bttExport    = Yii::$app->name . ' - ' . $this->title . ' - ' . date('Y-m-d H.i');
$bttUrl       = Url::to(['listas-json-btt']);
$bttUrlView   = Url::to(['view?id=']);
$bttUrlDelete = Url::to(['delete?id=']);

?>
<p>
<?= $can['create']?
    Html::a('Nuevo pase de lista', ['create'], ['class' => 'btn btn-success add']): '' ?>
</p>

<div class="gestion-lista-index">
    <div class="btt-toolbar">
    </div>
    <table class="bootstrap-table"></table>
</div>

<script type="text/javascript">
    $(document).ready(function(){
         var  $filters = $('.btt-toolbar :input'),
           can     = JSON.parse('<?= json_encode($can) ?>'),
            actions = function(value, row) { return [
                '<a href="<?= $bttUrlView ?>' + row.id + '" title="Ver cliente" class="fa fa-eye"></a>',
                (can.delete? '<a href="<?= $bttUrlDelete ?>' + row.id + '" title="Eliminar cliente" class="fa fa-trash" data-confirm="Confirma que deseas eliminar el cliente" data-method="post"></a>': '')
            ].join(''); },
            columns = [
                {
                    field: 'id',
                    title: 'ID',
                    align: 'center',
                    width: '60',
                    sortable: true,
                    switchable:false,
                },
                {
                    field: 'profesor',
                    title: 'Profesor',
                    sortable: true,
                    switchable: false,
                },
                {
                    field: 'count_alumno',
                    title: 'N° de alumnos',
                    sortable: true,
                    align :'center',
                },
                {
                    field: 'asistencia',
                    title: 'N° de asistencias',
                    sortable: true,
                    align :'center',
                },
                {
                    field: 'ausente',
                    title: 'N° de fuera de linea',
                    sortable: true,
                    align :'center',
                },
                {
                    field: 'sin_asistencia',
                    title: 'N° de inasistencias',
                    sortable: true,
                    align :'center',
                },
                {
                    field: 'justificado',
                    title: 'N° de justificados',
                    sortable: true,
                    align :'center',
                },
                {
                    field: 'created_at',
                    title: 'Creado',
                    align: 'center',
                    sortable: true,
                    switchable: true,
                    formatter: btf.time.date,
                },
                {
                    field: 'created_by',
                    title: 'Creado por',
                    sortable: true,
                    switchable: true,
                    visible: false,
                    formatter: btf.user.created_by,
                },
                {
                    field: 'updated_at',
                    title: 'Modificado',
                    align: 'center',
                    sortable: true,
                    visible: false,
                    formatter: btf.time.date,
                },
                {
                    field: 'updated_by',
                    title: 'Modificado por',
                    sortable: true,
                    visible: false,
                    formatter: btf.user.updated_by,
                },
                {
                    field: 'action',
                    title: 'Acciones',
                    align: 'center',
                    switchable: false,
                    width: '100',
                    class: 'btt-icons',
                    formatter: actions,
                    tableexportDisplay:'none',
                },
            ],
            params = {
                id      : 'lista',
                element : '.gestion-lista-index',
                url     : '<?= $bttUrl ?>',
                bootstrapTable : {
                    columns : columns,
                    exportOptions : {"fileName":"<?= $bttExport ?>"},
                    onDblClickRow : function(row, $element){
                        window.location.href = '<?= $bttUrlView ?>' + row.id;
                    },
                }
            };

        bttBuilder = new MyBttBuilder(params);
        bttBuilder.refresh();
    });
</script>
