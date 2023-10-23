<?php
namespace app\modules\gestion\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\web\Response;
use app\models\caja\ViewCaja;
use app\models\caja\Caja;
use app\models\alumno\Alumno;
use app\models\alumn\Alumn;
use app\models\cliente\Cliente;
use app\models\cobro\CobroAlumno;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use app\models\ciclo\CicloTarifa;
use app\models\caja\PagoAlumno;
use app\models\caja\Mensualidad;
/**
 * Default controller for the `clientes` module
 */
class CajaController extends \app\controllers\AppController
{
    private $can;

    public function init()
    {
        parent::init();

        $this->can = [
            'create' => Yii::$app->user->can('cajaCreate'),
            'update' => Yii::$app->user->can('cajaUpdate'),
            'delete' => Yii::$app->user->can('cajaDelete'),
            'delete' => Yii::$app->user->can('cajaDelete'),
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'can' => $this->can,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'can'   => $this->can,
        ]);
    }

    public function actionCreate()
    {
        $model = new Caja();

        $model->cobroAlumno = new CobroAlumno();

        if ($model->load(Yii::$app->request->post()) && $model->cobroAlumno->load(Yii::$app->request->post())) {
            $model->alumno_id = Yii::$app->request->post()["alumno_select"];
            $model->mes_agosto     = isset(Yii::$app->request->post()["agosto_access"])     ? 10 : null;
            $model->mes_septiembre = isset(Yii::$app->request->post()["septiembre_access"]) ? 10 : null;
            $model->mes_octubre    = isset(Yii::$app->request->post()["octubre_access"])    ? 10 : null;
            $model->mes_noviembre  = isset(Yii::$app->request->post()["noviembre_access"])  ? 10 : null;
            $model->mes_diciembre  = isset(Yii::$app->request->post()["diciembre_access"])  ? 10 : null;
            $model->mes_enero      = isset(Yii::$app->request->post()["enero_access"])      ? 10 : null;
            $model->mes_febrero    = isset(Yii::$app->request->post()["febrero_id_access"]) ? 10 : null;
            $model->mes_marzo      = isset(Yii::$app->request->post()["marzo_access"])      ? 10 : null;
            $model->mes_abril      = isset(Yii::$app->request->post()["abril_access"])      ? 10 : null;
            $model->mes_mayo       = isset(Yii::$app->request->post()["mayo_access"])       ? 10 : null;
            $model->mes_junio      = isset(Yii::$app->request->post()["junio_access"])      ? 10 : null;
            $model->mes_julio      = isset(Yii::$app->request->post()["julio_access"])      ? 10 : null;

            // Guardar cliente
            if($model->save()){
                if($model->cobroAlumno->saveCobroAlumno($model->id)){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    public function actionImprimirTicket($id)
     {
        $model = $this->findModel($id);
        $lengh = 270;
        $width = 72;
        $count = 0;
        $total_piezas = 0;

        $content = $this->renderPartial('ticket', ["model" => $model]);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => array($width, $lengh),//Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
             // set mPDF properties on the fly
            'options' => ['title' => 'Ticket de cobro'],
             // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>[ 'Ticket  #' . $model->id ],
                //'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        $pdf->marginLeft = 3;
        $pdf->marginRight = 3;

        $pdf->setApi();

        return $pdf->render();

    }


    public function actionAlumnoInfo()
    {
         $request = Yii::$app->request;

        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $alumno_id = Yii::$app->request->get('id');
            $ciclo_id = Yii::$app->request->get('ciclo');
            $alumno   = Alumno::getDataAlumnoInformation($alumno_id,$ciclo_id);
            return $alumno;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }

    public function actionRegistroPago(){
        return 1;
    }
    public function actionVerificarTipo(){
        
        $request = Yii::$app->request;
       
        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $alumno_id = Yii::$app->request->get('alumno_id');
            $tutor_id = Yii::$app->request->get('tutor');
            $ciclo = Yii::$app->request->get('ciclo');
            
            $alumno   = CicloTarifa::getPagosList($alumno_id,$tutor_id,$ciclo);
            return $alumno;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');


    }

    public function actionGetMeses(){
         $request = Yii::$app->request;
       
        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $meses   = Mensualidad::getmeses();
            return $meses;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }

    public function actionGetConfirmMeses(){
        $request = Yii::$app->request;
       
        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $alumno_id = Yii::$app->request->get('alumno_id');
            $ciclo_id = Yii::$app->request->get('ciclo');
            $tipo = Yii::$app->request->get('tipo_pago');
            $mes = Yii::$app->request->get('mes');
            $confirmacion   = Mensualidad::confirmmeses($alumno_id,$ciclo_id,$tipo,$mes);
            return $confirmacion;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }

    public function actionGuardarPago(){
        $request = Yii::$app->request;
        // guardar datos
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $alumno_id = Yii::$app->request->get('alumno_id');
            $tutor = Yii::$app->request->get('tutor');
            $tipo = Yii::$app->request->get('tipo');
            $ciclo = Yii::$app->request->get('ciclo');
            $tarifa_r = Yii::$app->request->get('tarifa_regular');
            $tarifa_e = Yii::$app->request->get('tarifa_especial');
            $colegiatura_e = Yii::$app->request->get('col_especial');
            $colegiatura_r = Yii::$app->request->get('col_regular');
            $metodo = Yii::$app->request->get('metodo_pago');
            $total_neto = Yii::$app->request->get('total_neto');
           
            $confirmacion   = PagoAlumno::getGuardarPago($alumno_id,$tutor,$tipo,$ciclo,$tarifa_r,$colegiatura_r,$tarifa_e,$colegiatura_e,$metodo,$total_neto);
            if ($confirmacion) {
                Yii::$app->session->setFlash('success', "Registro realizado exitosamente.");
            }
            return $confirmacion;

        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }

    public function actionVerificarMeses(){
        
        $request = Yii::$app->request;
       
        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $alumno_id = Yii::$app->request->get('alumno_id');
            $tutor_id = Yii::$app->request->get('tutor');
            $ciclo = Yii::$app->request->get('ciclo');

            
            $meses_restantes  = PagoAlumno::getMesesEspeciales($alumno_id,$tutor_id,$ciclo);
            return $meses_restantes;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');


    }

    public function actionPadreAlumnoAll()
    {
        $request = Yii::$app->request;
        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {
 
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $padreTutorId = Yii::$app->request->get('padre_tutor_id');
            $alumnos      =  Alumno::getDataAlumno($padreTutorId);

          return $alumnos;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }



    //------------------------------------------------------------------------------------------------//
	// BootstrapTable list
	//------------------------------------------------------------------------------------------------//
    /**
     * Return JSON bootstrap-table
     * @param  array $_GET
     * @return json
     */

    public function actionCajasJsonBtt(){
        return ViewCaja::getJsonBtt(Yii::$app->request->get());
    }


 //------------------------------------------------------------------------------------------------//
// HELPERS
//------------------------------------------------------------------------------------------------//
    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return Model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name, $_model = 'model')
    {
        switch ($_model) {
            case 'model':
                $model = Caja::findOne($name);
                break;

            case 'view':
                $model = ViewCaja::findOne($name);
                break;
        }

        if ($model !== null)
            return $model;

        else
            throw new NotFoundHttpException('La página solicitada no existe.');
    }


}
