<?php
namespace app\modules\calendario\controllers;

use Yii;
use yii\web\Response;
use app\models\Esys;
use app\models\agenda\Agenda;
use yii\web\BadRequestHttpException;

/**
 * Default controller for the `clientes` module
 */
class AgendaController extends \app\controllers\AppController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionRecordatorio()
    {
        return $this->render('recordatorio');
    }

	public function actionAddAgenda(){
		 // Cadena de busqueda
	 	Yii::$app->response->format = Response::FORMAT_JSON;

	 	$Agenda 	= new Agenda();
	 	$response 	= [];
        if ($request = Yii::$app->request->post()) {

            $Agenda->titulo            = isset($request["agenda_titulo"]) ? $request["agenda_titulo"] : null;
            $Agenda->tipo          	   = isset($request["agenda_tipo"]) ? $request["agenda_tipo"] : null;
            $Agenda->padre_familia_id  = isset($request["agenda_padre_familia_id"]) ? $request["agenda_padre_familia_id"] : null;
            $Agenda->alumno_id         = isset($request["agenda_alumno_id"]) ? $request["agenda_alumno_id"] : null;
            $Agenda->usuario_asignado_id = isset($request["agenda_usuario_id"]) ? $request["agenda_usuario_id"] : null;
            $Agenda->nota              = isset($request["agenda_text_area"]) ? $request["agenda_text_area"] : null;
            $Agenda->fecha             = isset($request["agenda_fecha"])     && $request["agenda_fecha"] ? strtotime($request["agenda_fecha"]) : null;
            $Agenda->fecha_fin         = isset($request["agenda_fecha_fin"]) && $request["agenda_fecha_fin"] ? strtotime($request["agenda_fecha_fin"]) : null;

            if ($Agenda->save()) {
            	$response = [
            		"code" 		=> 202,
            		"message" 	=> "Se agendo correctamente.",
            		"type" 		=> "success",
            	];
            }else{
				$response = [
            		"code" 		=> 10,
            		"message" 	=> "Ocurrio un error, intenta nuevamente.",
            		"type" 		=> "danger",
            	];
            }
        }

        return $response;

	}

	public function actionGetAgenda()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
        $created_by = Yii::$app->request->get('user_id') ? Yii::$app->request->get('user_id') :  Yii::$app->user->identity->id;
        
        /*return [
        	"code" => 202,
        	"items" => Agenda::find()->where(['or', ['created_by' => $created_by], ['usuario_asignado_id' => [$created_by]]])->all(),
        ];*/
        
        return [
        	"code" => 202,
        	"items" => Agenda::find()->where(['and', ['created_by' => $created_by] ])->all(),
        ];
	}

    public function actionGetEvento()
    {
        $request = Yii::$app->request;
        if ($request->validateCsrfToken() && $request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ( Yii::$app->request->get('id_evento')) {
                $Agenda = Agenda::findOne(Yii::$app->request->get('id_evento'));
                if ($Agenda) {
                    return [
                        "code" => 202,
                        "event" => [
                            "id" => $Agenda->id,
                            "titulo" => $Agenda->titulo,
                            "fecha_ini" => $Agenda->fecha,
                            "fecha_fin" => $Agenda->fecha_fin,
                            "tipo" => $Agenda->tipo,
                            "tipo_text" => Agenda::$statusList[$Agenda->tipo],
                            "padre_familia_id" => $Agenda->padre_familia_id ? $Agenda->padre_familia_id : null,
                            "padre_familia" => $Agenda->padre_familia_id ? $Agenda->padreFamilia->nombreCompleto : null,
                            "nota" => $Agenda->nota,
                        ],
                    ];
                }
            }
            return [
                "code" => 10,
                "message" => "Ocurrio un error, intente nuevamente",
                "type" => "error",
            ];
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }

    public function actionDeleteEvent()
    {
        $request = Yii::$app->request;
        if ($request->validateCsrfToken() && $request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ( Yii::$app->request->post('event_id')) {
                $Agenda = Agenda::findOne(Yii::$app->request->post('event_id'));
                if ($Agenda->delete()) {
                    return [
                        "code" => 202,
                        "event" => "Se elimino correctamente",
                    ];
                }
            }
            return [
                "code" => 10,
                "message" => "Ocurrio un error, intente nuevamente",
                "type" => "error",
            ];
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }
}
