<?php

namespace app\modules\v1\controllers;

use Yii;
use app\models\cliente\Cliente;
use yii\data\ActiveDataProvider;
use app\models\Esys;

class UserController extends DefaultController
{
    public function actionMe()
    {
    	$post = Yii::$app->request->post();
		// Validamos Token
        $paquete  = $this->authToken($post["token"]);

        return [
        	"code" => 202,
        	"name" => "User",
        	"data" => Cliente::find()->where(["id" => $paquete->cliente_id ])->asArray()->one(),
        	"type" => "Success",
        ];
    }
}
?>
