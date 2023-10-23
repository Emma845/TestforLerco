<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/admin/user/activate-account', 'token' => $user->account_activation_token]);
?>

<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
	<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
		Hola <?= Html::encode($user->username) ?>
		</td>
	</tr>
	<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
			<p>¡Gracias por haberte registrado para hacer uso del sistema de <span style="color:#E8C21A;font-weight:600"><?= Yii::$app->name ?></span></p>

			<p>Sige este enlace para activar tu cuenta:</p>

			<?= Html::a('Por favor, haga clic aquí para activar tu cuenta.', $resetLink) ?>

			<p>Para cualquier sugerencia o comentario te pedimos nos escribas al siguiente correo:</p>

			<p style="text-align:center"><?= Html::mailto(Yii::$app->params['adminEmail'], Yii::$app->params['adminEmail']) ?></p>
		</td>
	</tr>
	<tr style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		<td style="text-align:right;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"> &mdash; <?= Yii::$app->name ?></td>
	</tr>
	<tr>
		<td bgcolor="#62CEAD" valign="bottom" background="https://www.cresca.mx/images/LogoCresca.jpg" style="background-size:cover;background-image:url('https://www.cresca.mx/images/LogoCresca.jpg')" align="center">
		</td>
	</tr>
</table>
