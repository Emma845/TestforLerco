<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
	<?php /* ?>
	<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
		Hola <?=  $userReceptor->nombre ? $userReceptor->nombre .' '. $userReceptor->apellidos  :  Html::encode($userReceptor->username) ?>
		</td>
	</tr>
	*/?>
	<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">

			<p style="font-weight:600">El <span style="color:#164f15;font-weight:800; font-size: 16px"> <?= (isset($user->perfil->item_name ) ? $user->perfil->item_name  : '') .' - '. $user->nombre . ' '. $user->apellidos ?> </span>  </p>

			<p style="font-size: 24px"><strong><?= $message  ?></strong></p>

            <tr style="width:100%;height:2px;border-bottom:1px solid gray">
                <th></th>
                <th></th>
            </tr>
		 	<tr>
                <td align="left" style="line-height:25px;padding:10px 0;word-break:break-word">ASISTENCIA</td>
                <td align="right" style="line-height:25px;padding:10px 0;word-break:break-word"><?= number_format($asistencia)  ?></td>
            </tr>

            <tr>
                <td align="left" style="line-height:25px;padding:10px 0;word-break:break-word">FUERA DE LINEA</td>
                <td align="right" style="line-height:25px;padding:10px 0;word-break:break-word"><?= number_format($fuera_linea)  ?> </td>
            </tr>

            <tr>
                <td align="left" style="line-height:25px;padding:10px 0;word-break:break-word">INASISTENCIA</td>
                <td align="right" style="line-height:25px;padding:10px 0;word-break:break-word"><?= number_format($inasistencia)  ?> </td>
            </tr>


            <tr style="width:100%;height:2px;border-bottom:1px solid gray">
                <th></th>
                <th></th>
            </tr>

            <?php if (isset($notaExtra) && $notaExtra ): ?>

            	<p style="font-size:16px; font-weight: 800; "><strong><?= $notaExtra ?></strong></p>

            <?php endif ?>

			<p>Para cualquier sugerencia o comentario te pedimos nos escribas al siguiente correo:</p>

			<p style="text-align:center"><?= Html::mailto(Yii::$app->params['adminEmail'], Yii::$app->params['adminEmail']) ?></p>
		</td>
	</tr>
	<tr style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		<td style="text-align:right;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"> &mdash; <?= Yii::$app->name ?></td>
	</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
	<tr>
		<td>
			<?= Html::img(Url::to("@web/img/email_footer.png", true), ['width' => '100%']) ?>
		</td>
	</tr>
</table>
