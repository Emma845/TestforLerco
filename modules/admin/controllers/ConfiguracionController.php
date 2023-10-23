<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Response;
use app\models\esys\EsysSetting;
use yii\web\UploadedFile;

/**
 * Default controller for the `admin` module
 */
class ConfiguracionController extends \app\controllers\AppController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionConfiguracionUpdate()
    {
    	$model = new EsysSetting();
    	if (Yii::$app->request->post()) {

    		$model->saveConfiguracion(Yii::$app->request->post());
    		return $this->render('configuracion-update',[ 'model' => $model ]);
    	}

        return $this->render('configuracion-update',[ 'model' => $model ]);
    }
}
