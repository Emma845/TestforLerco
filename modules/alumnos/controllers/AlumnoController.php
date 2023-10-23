<?php
namespace app\modules\alumnos\controllers;

use Yii;
use yii\base\Model;
use app\models\Esys;
use yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use app\models\alumno\Alumno;
use app\models\file\FileUpload;
use app\models\alumno\ViewAlumno;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use app\models\cliente\Cliente;
use app\models\file\FileCheck;
use app\models\esys\EsysListaDesplegable;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class AlumnoController extends \app\controllers\AppController
{
    private $can;

    public function init()
    {
        parent::init();

        $this->can = [
            'create' => Yii::$app->user->can('alumnosCreate'),
            'update' => Yii::$app->user->can('alumnosUpdate'),
            'cancel' => Yii::$app->user->can('alumnosCancel'),
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



    /**
     * Updates an existing Cliente and Role models.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param  integer $id The cliente id.
     * @return string|\yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->fecha_nacimiento = Esys::unixTimeToString($model->fecha_nacimiento);

        // Si no se enviaron datos POST o no pasa la validación, cargamos formulario
        if($model->load(Yii::$app->request->post())){
            $model->is_especial      = $model->is_especial ? 10 : 1;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Se ha guardado los cambios correctamente del alumno # ". $model->nombreCompleto);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Displays a single Cliente model.
     *
     * @param  integer $id The cliente id. * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'can'   => $this->can,
        ]);
    }

    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $model->status = Alumno::STATUS_BAJA;
        if ($model->update()) {
            Yii::$app->session->setFlash('success', "Se ha realizado la BAJA  correctamente al alumno #" . $id);

        }else{

            Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la BAJA del alumno.');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }


    public function actionActivarAlumno($id)
    {
        $model = $this->findModel($id);
        $model->status = Alumno::STATUS_ACTIVE;
        if ($model->update()) {
            Yii::$app->session->setFlash('success', "Se ha realizado la ACTIVACION correctamente al alumno #" . $id);

        }else{

            Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la ACTIVACION del alumno.');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionAddFiles()
    {
        if (Yii::$app->request->post()['Alumno']) {
            $request = Yii::$app->request->post();
            $model   = $this->findModel(Yii::$app->request->post()['Alumno']['id']);
            $model->load(Yii::$app->request->post()['Alumno']);
            $model->file_expediente  = UploadedFile::getInstance($model, 'file_expediente');

            $pertenece_id   = isset($request["pertenece_id"]) && $request["pertenece_id"] ? $request["pertenece_id"] : false;
            $is_expira      = isset($request["check_fecha_evidencia"]) && $request["check_fecha_evidencia"] ? true : false;
            $fecha          =  $is_expira == true && $request["fecha_vigencia"] ? strtotime($request["fecha_vigencia"]) : null;
            if ($pertenece_id && $model) {
                $model->uploadFiles($pertenece_id, $is_expira, $fecha);
                Yii::$app->session->setFlash('success', "Se actualizo correctamente");
                return $this->redirect(['view', 'id' => $model->id ]);
            }else{
                Yii::$app->session->setFlash('warning', "Verifica tu informacion, Intenta nuevamente");
                return $this->redirect(['view', 'id' => $model->id ]);
            }
        }
    }

    public function actionAddFilesAlumno()
    {
        if (Yii::$app->request->post()['Alumno']) {
            $request = Yii::$app->request->post();


            $files = FileCheck::getAllFilesAlumno($request['Alumno']['id']);

            if($files){
                foreach($files as $file){
                    $file->delete();
                }
            }

            if(isset($request["pertenece_id"]) && $request["pertenece_id"]){
                foreach($request["pertenece_id"] as $file){
                    $fileCheck = new FileCheck();
                    $fileCheck->alumno_id        = $request['Alumno']['id'];
                    $fileCheck->pertenece_id    = $file;
                    $fileCheck->tipo           = FileCheck::TIPO_ALUMNO;
                    $fileCheck->save();

                }
            }
            Yii::$app->session->setFlash('success', "Se actualizo correctamente");
            return $this->redirect(['view', 'id' => $request['Alumno']['id'] ]);
        }else{
            Yii::$app->session->setFlash('warning', "Verifica tu informacion, Intenta nuevamente");
            return $this->redirect(['view', 'id' => $request['Alumno']['id'] ]);

        }
    }

    public function actionRemoverFile($alumno_id, $file_id)
    {
        $model = FileUpload::findOne($file_id);

        try{
            // Eliminamos el cliente
            $model->delete();

            Yii::$app->session->setFlash('success', "Se ha removido correctamente el Documento #" . $file_id);

        }catch(\Exception $e){
            if($e->getCode() == 23000){
                Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la eliminación del documento.');

                header("HTTP/1.0 400 Relation Restriction");
            }else{
                throw $e;
            }
        }

        return $this->redirect(['view', 'id' => $alumno_id ]);

    }

    public function actionUpdateAlumno()
    {
        $request = Yii::$app->request->post();
        if (isset($request['Alumno']["cliente_id"]) && $request['Alumno']["cliente_id"] ) {
            $model   = $this->findModel($request['Alumno']['id']);
            $model->cliente_id = $request['Alumno']["cliente_id"];
            if ($model->update()) {
                Yii::$app->session->setFlash('success', "Se actualizo correctamente");
                return $this->redirect(['view', 'id' => $model->id ]);
            }
        }
        Yii::$app->session->setFlash('warning', "Verifica tu informacion, Intenta nuevamente");
        return $this->redirect(['view', 'id' => $model->id ]);

    }

    public function actionPrintFicha($id)
    {

        $alumno = Alumno::find()->where(['id' => $id])->one();
        $tutor = Cliente::find()->where(['id' => $alumno->cliente_id])->one();

        /*echo "<pre>";
        print_r($alumno);
        die();*/
        $content = $this->renderPartial('ficha-inscripcion', ["model" => $alumno, "tutor" => $tutor]);
         $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_LETTER,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'marginTop' => 28,
            'marginBottom' => 30,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '
            .tabla{
                border:hidden;
            }
            .firma{
                width: 50%;
                border-top: 1pt solid black;
                text-align:center;
                margin: auto;
                
            }
            .div-firma{
                padding-top: 40%;
            }',
            'options' => ['title' => 'Ficha Inscripción'],
            'methods' => [  
                'SetHeader' => [
                    "
                    <table>
                        <tbody>
                            <tr>
                                <td width='210'> ".Html::img('@web/img/logo-login.png', ['height'=>'60px'])."</td>
                                <td align='center'>
                                    <h4><strong>AQUITIA</strong></h3>
                                    <h5>ESCUELA</h4>
                                    <h6>C.C.T. 21PJN13101 21PPR1077J</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>"
                    ,

                ],
                'SetFooter' => [
                    '<div class="text-center">
                        <h6>Calle Cedro no. 36 Col. Lomas Flor del Bosque, C.P. 72360</h6>
                        <h6>Teléfono: 222 253 5233</h6>
                        <h6>Puebla,Pue.</h6>
                    </div>'
                ]  
            ]
        ]);
        return $pdf->render();
    }

    public function actionPrintCartaCompromiso()
    {

        if (Yii::$app->request->post()['Alumno']) {
            $request = Yii::$app->request->post();

            $alumno = Alumno::find()->where(['id' => $request['Alumno']['id']])->one();
            $tutor = Cliente::find()->where(['id' => $alumno->cliente_id])->one();
            $files = [];
            foreach($request['pertenece_id'] as $fileId){
                $file = EsysListaDesplegable::find()->where(['id' => $fileId])->one();
                array_push($files, $file);
            }
        }
        $content = $this->renderPartial('carta-compromiso', ["model" => $alumno, "tutor" => $tutor, "files" => $files]);
         $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_LETTER,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'marginTop' => 28,
            'marginBottom' => 30,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '
            .tabla{
                border:hidden;
            }
            .firma{
                width: 50%;
                border-top: 1pt solid black;
                text-align:center;
                margin: auto;
                
            }
            .div-firma{
                padding-top: 40%;
            }',
            'options' => ['title' => 'Carta Compromiso'],
            'methods' => [  
                'SetHeader' => [
                    "
                    <table>
                        <tbody>
                            <tr>
                                <td width='210'> ".Html::img('@web/img/logo-login.png', ['height'=>'60px'])."</td>
                                <td align='center'>
                                    <h4><strong>AQUITIA</strong></h3>
                                    <h5>ESCUELA</h4>
                                    <h6>C.C.T. 21PJN13101 21PPR1077J</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>"
                    ,

                ],
                'SetFooter' => [
                    '<div class="text-center">
                        <h6>Calle Cedro no. 36 Col. Lomas Flor del Bosque, C.P. 72360</h6>
                        <h6>Teléfono: 222 253 5233</h6>
                        <h6>Puebla,Pue.</h6>
                    </div>'
                ]  
            ]
        ]);
        return $pdf->render();
    }
    
    
    public function actionImprimirEtiqueta($id)
    {
        $model = $this->findModel($id);
        $content = $this->renderPartial('etiqueta', ["model" => $model]);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => array(120,110),//Pdf::FORMAT_LETTER,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
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
             // call mPDF methods on the fly
        ]);

        $pdf->marginLeft = 1;
        $pdf->marginRight = 1;

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

//------------------------------------------------------------------------------------------------//
// BootstrapTable list
//------------------------------------------------------------------------------------------------//
    /**
     * Return JSON bootstrap-table
     * @param  array $_GET
     * @return json
     */
    public function actionAlumnosJsonBtt(){
        return ViewAlumno::getJsonBtt(Yii::$app->request->get());
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
    protected function findModel($id, $_model = 'model')
    {
        switch ($_model) {
            case 'model':
                $model = Alumno::findOne($id);
                break;

            case 'view':
                $model = ViewAlumno::findOne($id);
                break;
        }

        if ($model !== null)
            return $model;

        else
            throw new NotFoundHttpException('La página solicitada no existe.');
    }

}
