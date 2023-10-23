<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Esys;
use app\models\user\ViewUserLista;

class CronController extends Controller
{
	public function actionMailSend()
    {
    	$ViewUserLista = ViewUserLista::find()->all();
		try {

            Yii::$app->mailer->compose('notificationMessageAviso', ['userLista' => $ViewUserLista, 'message' => 'REPORTE DE PASE LISTA DEL DIA ' . Esys::fecha_en_texto(time()) ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo(["leonardo@lerco.mx","magdli@yahoo.com.mx","erickgaytan53@gmail.com","sistemas@movi.red"])
                ->setSubject('REPORTE DE PASE LISTA '. date("Y-m-d",time()) .' - '. Yii::$app->name)
                ->send();
     		echo "success";

        }catch (\Exception $e) {
           echo "error";
        }
	}
}