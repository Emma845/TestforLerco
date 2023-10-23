<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\BootstrapTableAsset;
use kartik\daterange\DateRangePicker;


BootstrapTableAsset::register($this);


/* @var $this yii\web\View */

$this->title = 'Ciclo escolar';
$this->params['breadcrumbs'][] = $this->title;

$bttExport    = Yii::$app->name . ' - ' . $this->title . ' - ' . date('Y-m-d H.i');
$bttUrl       = Url::to(['ciclo-json-btt']);
$bttUrlView   = Url::to(['view?id=']);
$bttUrlUpdate = Url::to(['update?id=']);
$bttUrlDelete = Url::to(['delete?id=']);

?>
<p>
<?= $can['create']?
    Html::a('Nuevo ciclo', ['create'], ['class' => 'btn btn-success add']): '' ?>
</p>

<div class="gestion-ciclo-index" style="text-transform:uppercase">
    <div class="btt-toolbar">
    </div>
    <table class="bootstrap-table"></table>
</div>

<script type="text/javascript">
    $(document).ready(function(){


        var  $filters = $('.btt-toolbar :input'),
        can     = JSON.parse('<?= json_encode($can) ?>'),
        actions = function(value, row) { return [
            '<a href="<?= $bttUrlView ?>' + row.id + '" title="Ver ciclo" class="fa fa-eye"></a>',
            (can.update? '<a href="<?= $bttUrlUpdate ?>' + row.id + '" title="Editar ciclo" class="fa fa-pencil"></a>': '')
        ].join(''); },
        columns = [
            {
                field: 'year',
                title: 'CICLO ESCOLAR',
                sortable: true,
                align:'center',
            },
            {
                field: 'rango_a',
                title: 'Inicio el dia',
                align: 'center',
                sortable: true,
                switchable: false,
                formatter: btf.time.date,
                
            },
            {
                field: 'rango_b',
                title: 'termino el dia',
                align: 'center',
                sortable: true,
                switchable: false,
                formatter: btf.time.date,
               
            },
            {
                field: 'notas',
                title: 'Notas',
                switchable: true,
                sortable: true,
                align:'left'
            },
            {
                field: 'created_at',
                title: 'Creado',
                align: 'center',
                sortable: true,
                switchable: false,
                formatter: btf.time.date,
                visible: false
            },
            {
                field: 'created_by',
                title: 'Creado por',
                sortable: true,
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
            id      : 'ciclo',
            element : '.gestion-ciclo-index',
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
