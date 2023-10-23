<?php
namespace app\modules\gestion\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\web\Response;
use yii\web\Controller;
use app\models\Esys;
use app\models\user\User;
use app\models\lista\Lista;
use app\models\lista\ViewLista;
use app\models\lista\ListaAlumno;
use yii\helpers\Html;

/**
 * Default controller for the `clientes` module
 */
class ListaController extends \app\controllers\AppController
{
    private $can;

    public function init()
    {
        parent::init();

        $this->can = [
            'create' => Yii::$app->user->can('listaCreate'),
            'delete' => Yii::$app->user->can('listaDelete'),
            'view' => Yii::$app->user->can('listaView'),
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
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'can'   => $this->can,
            'user'  => User::findOne($model->created_by),
        ]);
    }

    public function actionCreate()
    {
        $model   = new Lista();
        $user    = User::findOne(Yii::$app->user->identity->id);
        $paseday = Lista::find()->andWhere([ "date_format(from_unixtime(`lista`.`created_at`),'%Y-%m-%d')" => date("Y-m-d",time()) ])
                                ->andWhere([ "created_by" => $user->id ])->one();

        if (!isset($paseday->id)) {
            if ($request = Yii::$app->request->post()) {
                $model->profesor_id = $user->id;
                $model->nota        = isset(Yii::$app->request->post()["text_area_nota"]) ? Yii::$app->request->post()["text_area_nota"] : null;
                if($model->save()){
                    $asistencia = 0;
                    $fuera_linea = 0;
                    $inasistencia = 0;
                    $justificado = 0;
                    if (isset($request["Asistencia"])) {
                        foreach ($request["Asistencia"] as $key => $asistencia) {
                            $ListaAlumno = new ListaAlumno();
                            $ListaAlumno->lista_id  = $model->id;
                            $ListaAlumno->alumno_id = $key;
                            $ListaAlumno->tipo      = ListaAlumno::TIPO_ASISTENCIA;
                            $ListaAlumno->save();
                            $asistencia = $asistencia + 1;
                        }
                    }
                    if (isset($request["Ausente"])) {
                        foreach ($request["Ausente"] as $key => $asistencia) {
                            $ListaAlumno = new ListaAlumno();
                            $ListaAlumno->lista_id  = $model->id;
                            $ListaAlumno->alumno_id = $key;
                            $ListaAlumno->tipo      = ListaAlumno::TIPO_AUSENTE;
                            $ListaAlumno->save();
                            $fuera_linea = $fuera_linea + 1;
                        }
                    }
                    if (isset($request["SinAsistencia"])) {
                        foreach ($request["SinAsistencia"] as $key => $asistencia) {
                            $ListaAlumno = new ListaAlumno();
                            $ListaAlumno->lista_id  = $model->id;
                            $ListaAlumno->alumno_id = $key;
                            $ListaAlumno->tipo      = ListaAlumno::TIPO_SINASISTENCIA;
                            $ListaAlumno->save();
                            $inasistencia = $inasistencia + 1;
                        }
                    }
                    if (isset($request["justificado"])) {
                        foreach ($request["justificado"] as $key => $asistencia) {
                            $ListaAlumno = new ListaAlumno();
                            $ListaAlumno->lista_id  = $model->id;
                            $ListaAlumno->alumno_id = $key;
                            $ListaAlumno->tipo      = ListaAlumno::TIPO_JUSTIFICADO;
                            $ListaAlumno->save();
                            $justificado = $justificado + 1;
                        }
                    }
                    /*
                    $userEnvia      = User::findOne(Yii::$app->user->id);

                    try {
                        Yii::$app->mailer->compose('notificationMessageReembolso', ['user' => $userEnvia, 'message' => 'Se realizo el pase de lista del dia' . Esys::fecha_en_texto(time()),'asistencia' => $asistencia,'fuera_linea' => $fuera_linea,'inasistencia' => $inasistencia,'notaExtra' => $model->nota ])
                            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                            ->setTo(["leonardo@lerco.mx","magdli@yahoo.com.mx","erickgaytan53@gmail.com"])
                            ->setSubject('Pase de lista '. $userEnvia->username .'-'. Yii::$app->name)
                            ->send();
                    }catch (\Exception $e) {
                       return $this->redirect(['view', 'id' => $model->id]);
                    }
                    */
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            return $this->render('create', [
                'model' => $model,
                'user'  => $user,
            ]);
        }
        return $this->redirect(['update', "id" => $paseday->id ]);
    }

    public function actionUpdate($id)
    {
        $model   = $this->findModel($id);
        $user    = User::findOne(Yii::$app->user->identity->id);
        if ($request = Yii::$app->request->post()) {
            $model->nota        = isset(Yii::$app->request->post()["text_area_nota"]) ? Yii::$app->request->post()["text_area_nota"] : null;
            if($model->update()){
                $asistencia = 0;
                $fuera_linea = 0;
                $inasistencia = 0;
                $justificado = 0;
                ListaAlumno::deleteAll([ "lista_id" => $model->id ]);
                if (isset($request["Asistencia"])) {
                    foreach ($request["Asistencia"] as $key => $asistencia) {
                        $ListaAlumno = new ListaAlumno();
                        $ListaAlumno->lista_id  = $model->id;
                        $ListaAlumno->alumno_id = $key;
                        $ListaAlumno->tipo      = ListaAlumno::TIPO_ASISTENCIA;
                        $ListaAlumno->save();
                        $asistencia = $asistencia + 1;
                    }
                }
                if (isset($request["Ausente"])) {
                    foreach ($request["Ausente"] as $key => $asistencia) {
                        $ListaAlumno = new ListaAlumno();
                        $ListaAlumno->lista_id  = $model->id;
                        $ListaAlumno->alumno_id = $key;
                        $ListaAlumno->tipo      = ListaAlumno::TIPO_AUSENTE;
                        $ListaAlumno->save();
                        $fuera_linea = $fuera_linea + 1;
                    }
                }
                if (isset($request["SinAsistencia"])) {
                    foreach ($request["SinAsistencia"] as $key => $asistencia) {
                        $ListaAlumno = new ListaAlumno();
                        $ListaAlumno->lista_id  = $model->id;
                        $ListaAlumno->alumno_id = $key;
                        $ListaAlumno->tipo      = ListaAlumno::TIPO_SINASISTENCIA;
                        $ListaAlumno->save();
                        $inasistencia = $inasistencia + 1;
                    }
                }
                if (isset($request["justificado"])) {
                    foreach ($request["justificado"] as $key => $asistencia) {
                        $ListaAlumno = new ListaAlumno();
                        $ListaAlumno->lista_id  = $model->id;
                        $ListaAlumno->alumno_id = $key;
                        $ListaAlumno->tipo      = ListaAlumno::TIPO_JUSTIFICADO;
                        $ListaAlumno->save();
                        $justificado = $justificado + 1;
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'user'  => $user,
        ]);
    }

     /**
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param  integer $id The cliente id.
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try{

            foreach ($model->listaAlumnos as $key => $pase) {
                $pase->delete();
            }

            $model->delete();
            Yii::$app->session->setFlash('success', "Se ha eliminado correctamente el pase de lista #" . $id);

        }catch(\Exception $e){
            if($e->getCode() === 23000){
                Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la eliminación del pase de lista.');

                header("HTTP/1.0 400 Relation Restriction");
            }else{
                throw $e;
            }
        }

        return $this->redirect(['index', 'tab' => 'index']);
    }



    public function actionPrint($id)
    {

        $model = $this->findModel($id);

        /*echo "<pre>";
        print_r($alumno);
        die();*/
        $content = $this->renderPartial('lista-pdf', [
            'model' => $this->findModel($id),
            'can'   => $this->can,
            'user'  => User::findOne($model->created_by),
        ]);
         $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_LETTER,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'marginTop' => 30,
            'marginBottom' => 30,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '
            .tabla{
                border:hidden;
            }
            .nota{
                border: 1pt solid;
                
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
                                <td width='210'> ".Html::img('@web/img/logo-sep.jpg', ['height'=>'60px'])."</td>
                                <td align='center'>
                                    <h4><strong>AQUITIA</strong></h3>
                                    <h5>ESCUELA</h4>
                                    <h6>C.C.T. 21PJN13101 21PPR1077J</h5>
                                </td>
                                <td align='right' width='205'> ".Html::img('@web/img/logo-login.png', ['height'=>'60px'])."</td>
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

    //------------------------------------------------------------------------------------------------//
	// BootstrapTable list
	//------------------------------------------------------------------------------------------------//
    /**
     * Return JSON bootstrap-table
     * @param  array $_GET
     * @return json
     */

    public function actionListasJsonBtt(){
        return ViewLista::getJsonBtt(Yii::$app->request->get());
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
                $model = Lista::findOne($name);
                break;

            case 'view':
                $model = ViewLista::findOne($name);
                break;
        }

        if ($model !== null)
            return $model;

        else
            throw new NotFoundHttpException('La página solicitada no existe.');
    }


}
