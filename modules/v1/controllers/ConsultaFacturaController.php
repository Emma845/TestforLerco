<?php
namespace app\modules\v1\controllers;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use app\models\esysfact\Advans;

class ConsultaFacturaController extends DefaultController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['*'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Allow-Origin' => ['*'],
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        return $behaviors;
    }

  	/*****************************************
     *  CONSULTA CAJA (LAX - TIERRA) Y CAJA
    *****************************************/
    public function actionConsulta()
    {
        $post = Yii::$app->request->post();
        // Validamos Token
        $user       = $this->authToken($post["token"]);

        if (isset($post["UUID"]) && isset($post["rfc_emisor"]) && isset($post["rfc_receptor"]) && isset($post["total"]) ) {

            $Advans = new Advans(Yii::$app->params['advans']);

            $consultarEstadoSAT = $Advans->consultarEstadoSAT($post["rfc_emisor"],$post["rfc_receptor"],$post["total"],$post["UUID"]);

            if ($consultarEstadoSAT) {
                return [
                    "code" => 202,
                    "name" => "Consulta",
                    "data" => [
                        "UUID"          => $post["UUID"],
                        "rfc_emisor"    => $post["rfc_emisor"],
                        "rfc_receptor"  => $post["rfc_receptor"],
                        "total"         => $post["total"],
                        "CodigoEstatus" => $Advans->response()['CodigoEstatus'],
                        "EsCancelable"  => $Advans->response()['EsCancelable'],
                        "Estado"        => $Advans->response()['Estado'],
                        "EstatusCancelacion"   => $Advans->response()['EstatusCancelacion'],
                    ],
                    "type" => "Success",
                ];
            }
            return [
                "code" => 10,
                "message" => "Error al realizar la consulta al servicio del SAT",
            ];

        }

        return [
            "code"    => 10,
            "name"    => "Consulta",
            "message" => 'El UUID, RFC emisor, RFC receptor y total es requerido, intente nuevamente',
            "type"    => "Error",
        ];
    }
}
