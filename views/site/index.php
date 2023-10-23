<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use app\models\esys\EsysListaDesplegable;
use app\models\cliente\Cliente;
use app\assets\HighchartsAsset;

HighchartsAsset::register($this);

$this->title = '';

?>
<?php if(!isset(Yii::$app->user->identity)): ?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Sistema de verificación de gastos </h1>
    </div>
</div>

<?php else: ?>

<div class="site-index">
    <div id="page-head">
		<div class="pad-all text-center">
		    <h3>Bienvenido al panel de Control <strong><?= Yii::$app->name  ?></strong>.</h3>
		    <p1>Control de gastos y presupuesto.</p>
		</div>
    </div>
    <div id="page-content">

    </div>
</div>


<?php endif ?>
