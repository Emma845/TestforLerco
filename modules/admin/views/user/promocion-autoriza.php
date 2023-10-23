<?php
use yii\helpers\Url;
use app\models\cliente\ClienteCodigoPromocion;

?>

<div class="user-promocion-view">
	<div class="row">
		<?php foreach ($model as $key => $promocion): ?>
			<?php
			$datetime1 = new DateTime(date("Y-m-d",$promocion->fecha_rango_ini));
			$datetime2 = new DateTime(date("Y-m-d",$promocion->fecha_rango_fin));
			$interval = $datetime1->diff($datetime2);
			$expira = false;
			if (time() > $promocion->fecha_rango_fin )
				$expira = true;

	 	?>
			<div class="col-sm-4">
				<div class="panel">
			        <div class="panel-body text-center bg-<?= $expira ? 'dark' : ClienteCodigoPromocion::$statusAlertList[$promocion->status]  ?>">
			            <img alt="Avatar" class="img-lg img-circle img-border mar-btm" src="https://image.flaticon.com/icons/png/512/40/40299.png">

			            <h4 class="text-light">Solicitud de <?= $promocion->descuento  ?> USD </h4>
			            <ul class="list-unstyled text-center pad-top mar-no row">
			                <li class="col-xs-4">
			                    <span class="text-lg text-semibold"><?= $promocion->requiered_libras  ?> lb</span>
			                    <p class="text-sm mar-no">Libras requeridas</p>
			                </li>
			                <li class="col-xs-4">
			                    <span class="text-lg text-semibold"><?= $promocion->descuento  ?> USD</span>
			                    <p class="text-sm mar-no">Descuento aplicar</p>
			                </li>
			                <li class="col-xs-4">
			                    <span class="text-lg text-semibold"><?= $interval->format('%R%a días')  ?> </span>
			                    <p class="text-sm mar-no">Duración</p>
			                </li>
			            </ul>
			        </div>
			        <div class="list-group bg-trans pad-btm">
			            <a class="list-group-item" href="#"><i class="demo-pli-information icon-lg icon-fw"></i> Agente : <?= $promocion->createdBy->nombreCompleto  ?></a>
			            <a class="list-group-item" href="#"><i class="demo-pli-mine icon-lg icon-fw"></i> Cliente: <?= $promocion->cliente->nombreCompleto  ?></a>
			            <a class="list-group-item" href="#"><i class="demo-pli-credit-card-2 icon-lg icon-fw"></i> <span><strong>Fecha inicio: </strong></span> <?= date("Y-m-d", $promocion->fecha_rango_ini)  ?> / <span><strong>Fecha fin</strong></span> <?= date("Y-m-d", $promocion->fecha_rango_fin)  ?> </a>


			            <li class="list-group-item" href="#">
			            	<?php if (!$expira): ?>
				            	<?php if ($promocion->status == ClienteCodigoPromocion::STATUS_NO_AUTORIZADO): ?>
				            		<a class="btn btn-mint btn-circle" data-confirm="¿ Estas seguro que deseas aprobar la promocion especial?" href="<?= Url::to(['check-promocion-especial','promocion_id' => $promocion->id ])  ?>"><i class="fa fa-check"></i></a>
									<span class="label label-danger pull-right">Promoción especial cancelado </span>
				            	<?php endif ?>
			            		<?php if ($promocion->status == ClienteCodigoPromocion::STATUS_PROGESO): ?>
				            		<a class="btn btn-mint btn-circle" data-confirm="¿ Estas seguro que deseas aprobar la promocion especial?" href="<?= Url::to(['check-promocion-especial','promocion_id' => $promocion->id ])  ?>"><i class="fa fa-check"></i></a>

				            		<a class="btn btn-danger btn-circle" data-confirm = "¿ Estas seguro que deseas cancelar la promoción especial ?" href="<?= Url::to(['cancel-promocion-especial','promocion_id' => $promocion->id ])  ?>"><i class="fa fa-close"></i></a>
				            		<span class="label label-warning pull-right">Promoción especial en progreso</span>
			            		<?php endif ?>
				            	<?php if ($promocion->status == ClienteCodigoPromocion::STATUS_ACTIVE): ?>
				            		<a class="btn btn-danger btn-circle" data-confirm = "¿ Estas seguro que deseas cancelar la promoción especial ?" href="<?= Url::to(['cancel-promocion-especial','promocion_id' => $promocion->id ])  ?>"><i class="fa fa-close"></i> </a>
				            		<span class="label label-purple pull-right">Promoción especial activado </span>
				            	<?php endif ?>

			            		<?php if ($promocion->status == ClienteCodigoPromocion::STATUS_USADO): ?>
			            			<span class="label label-mint pull-right">Promoción especial Usado / Utilizado </span>
			            		<?php endif ?>

		            		<?php else: ?>
		            			<span class="label label-dark pull-right">Expiro la promoción especial</span>

			            	<?php endif ?>

			            </li>
			        </div>
			    </div>
			</div>
		<?php endforeach ?>
	</div>
</div>
