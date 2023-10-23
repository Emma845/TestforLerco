<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\BootstrapTableAsset;
use app\models\esys\EsysListaDesplegable;
use app\models\cliente\Cliente;
use kartik\daterange\DateRangePicker;


BootstrapTableAsset::register($this);


/* @var $this yii\web\View */

$this->title = 'Caja / Cobro';
$this->params['breadcrumbs'][] = $this->title;

$bttExport    = Yii::$app->name . ' - ' . $this->title . ' - ' . date('Y-m-d H.i');
$bttUrl       = Url::to(['cajas-json-btt']);
$bttUrlView   = Url::to(['view?id=']);
?> 

<p>
<?= $can['create']?
    Html::a('Nuevo cobro', ['create'], ['class' => 'btn btn-success add']): '' ?>
</p>

<div class="cajas-caja-index">
    <div class="btt-toolbar">
        <div class="panel mar-btm-5px">
           <div class="panel-heading">
                <div class="panel-control">
                    <button class="btn reset-form" ><i class="demo-pli-repeat-2"></i></button>
                    <button class="btn collapsed" data-target="#toolbar-panel-collapse" data-toggle="collapse" aria-expanded="false"><i class="demo-pli-arrow-down"></i></button>
                </div>
                <br>
               <div class="DateRangePicker   kv-drp-dropdown  col-sm-5">
                    <?= DateRangePicker::widget([
                        'name'           => 'date_range',
                        //'presetDropdown' => true,
                        'hideInput'      => true,
                        'useWithAddon'   => true,
                        'convertFormat'  => true,
                        'startAttribute' => 'from_date',
                        'endAttribute' => 'to_date',
                        'startInputOptions' => ['value' => '2019-01-01'],
                        'endInputOptions' => ['value' => '2019-12-31'],
                        'pluginOptions'  => [
                            'locale' => [
                                'format'    => 'Y-m-d',
                                'separator' => ' - ',
                            ],
                            'opens' => 'left',
                            "autoApply" => true,
                        ],
                    ])
                    ?>
                </div>
            </div>
            <div id="toolbar-panel-collapse" class="collapse in" aria-expanded="false">
                <div class="panel-body pad-btm-15px">
                    <div>
                        <strong class="pad-rgt">Filtrar:</strong>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="bootstrap-table"></table>
</div>

<script type="text/javascript">
    $(document).ready(function(){


        var $filters = $('.btt-toolbar :input'),
            can     = JSON.parse('<?= json_encode($can) ?>'),
            actions = function(value, row) { return [
                '<a href="<?= $bttUrlView ?>' + row.id + '" title="Ver cliente" class="fa fa-eye"></a>'
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
                    field: 'tutor_completo',
                    title: 'Tutor',
                    switchable: false,
                    sortable: true,
                },
                 {
                    field: 'alumno_completo',
                    title: 'Alumno',
                    switchable: false,
                    sortable: true,
                },
                {
                    field: 'tipo',
                    title: 'Tipo cobro',
                    align: 'center',
                    sortable: true,
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    sortable: true,
                    align:'center'
                },
                {
                    field: 'monto',
                    title: 'Monto',
                    sortable: true,
                    align:'center',
                    formatter: btf.conta.money
                },
                {
                    field: 'created_at',
                    title: 'Creado',
                    align: 'center',
                    sortable: true,
                    switchable: false,
                    formatter: btf.time.date,
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
                id      : 'caja',
                element : '.cajas-caja-index',
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
