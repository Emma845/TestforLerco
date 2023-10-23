<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\BootstrapTableAsset;
use app\models\esys\EsysListaDesplegable;
use kartik\daterange\DateRangePicker;


BootstrapTableAsset::register($this);


/* @var $this yii\web\View */

$this->title = 'Articulos';
$this->params['breadcrumbs'][] = $this->title;

$bttExport    = Yii::$app->name . ' - ' . $this->title . ' - ' . date('Y-m-d H.i');
$bttUrl       = Url::to(['articulos-json-btt']);
$bttUrlView   = Url::to(['view?id=']);
$bttUrlUpdate = Url::to(['update?id=']);
$bttUrlDelete = Url::to(['delete?id=']);

?>
<p>
<?= $can['create']?
    Html::a('Nuevo articulo', ['create'], ['class' => 'btn btn-success add']): '' ?>
</p>

<div class="gestion-articulo-index">
    <div class="btt-toolbar">
    </div>
    <table class="bootstrap-table"></table>
</div>

<script type="text/javascript">
    $(document).ready(function(){


        var  $filters = $('.btt-toolbar :input'),
        can     = JSON.parse('<?= json_encode($can) ?>'),
        actions = function(value, row) { return [
            '<a href="<?= $bttUrlView ?>' + row.id + '" title="Ver articulo" class="fa fa-eye"></a>',
            (can.update? '<a href="<?= $bttUrlUpdate ?>' + row.id + '" title="Editar articulo" class="fa fa-pencil"></a>': ''),
            (can.delete? '<a href="<?= $bttUrlDelete ?>' + row.id + '" title="Eliminar articulo" class="fa fa-trash" data-confirm="Confirma que deseas eliminar el articulo" data-method="post"></a>': '')
        ].join(''); },
        columns = [
            {
                field: 'nombre',
                title: 'Nombre',
                sortable: true,
                align:'center'

            },
            {
                field: 'inventario',
                title: 'Inventario',
                switchable: true,
                sortable: true,
                align:'center'
            },
            {
                field: 'precio',
                title: 'Precio',
                sortable: true,
                align:'center',
                formatter: btf.conta.money,
            },
            {
                field: 'status',
                title: 'Estatus',
                align: 'center',
                sortable: false,
                switchable: true,
                formatter: btf.status.opt_o,
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
            id      : 'articulo',
            element : '.gestion-articulo-index',
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
