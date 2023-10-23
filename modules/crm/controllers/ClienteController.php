<?php
namespace app\modules\crm\controllers;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use app\models\Esys;
use app\models\cliente\Cliente;
use app\models\alumno\Alumno;
use app\models\alumno\AlumnoDocumento;
use app\models\cliente\ViewCliente;
use app\models\esys\EsysDireccion;
use yii\web\UploadedFile;
use app\models\file\FileUpload;
use app\models\file\FileCheck;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends \app\controllers\AppController
{
    private $can;

    public function init()
    {
        parent::init();

        $this->can = [
            'create' => Yii::$app->user->can('padreTutorCreate'),
            'update' => Yii::$app->user->can('padreTutorUpdate'),
            'delete' => Yii::$app->user->can('padreTutorDelete'),
            'viewAlumno' => Yii::$app->user->can('alumnosView'),
            'createAlumno' => Yii::$app->user->can('alumnosCreate'),
            'updateAlumno' => Yii::$app->user->can('alumnosUpdate'),
            'deleteAlumno' => Yii::$app->user->can('alumnosDelete'),
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

    /**
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cliente(['scenario' => Cliente::SCENARIO_CREATE]);

        $model->dir_obj = new EsysDireccion([
            'cuenta' => EsysDireccion::CUENTA_CLIENTE,
            'tipo'   => EsysDireccion::TIPO_PERSONAL,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->dir_obj->load(Yii::$app->request->post()) ) {
            if ($model->validate()) {
                // Guardar cliente
                if($model->save())
                    return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateAlumno()
    {
        $Alumno = new Alumno();
        if ($request = Yii::$app->request->post()) {
            $Alumno->nombre             = isset($request["nombre_alumno"]) ? $request["nombre_alumno"] : null;
            $Alumno->apellidos          = isset($request["apellido_alumno"]) ? $request["apellido_alumno"] : null;
            $Alumno->cliente_id         = isset($request["cliente_id"]) ? $request["cliente_id"] : null;
            $Alumno->nivel              = isset($request["nivel_id"]) ? $request["nivel_id"] : null;
            $Alumno->grado              = isset($request["grado_id"]) ? $request["grado_id"] : null;
            $Alumno->factura              = isset($request["factura"]) ? $request["factura"] : 0;
            $Alumno->sexo               = isset($request["sexo_id"]) ? $request["sexo_id"] : null;
            $Alumno->costo_colegiatura  = isset($request["costo_colegiatura"]) ? $request["costo_colegiatura"] : null;
            $Alumno->is_especial        = isset($request["alumno_especial"]) ? 10 : null;
            $Alumno->vive_con        = isset($request["vive_con"]) ? $request["vive_con"] : null;
            $Alumno->nombre_vive_con        = isset($request["nombre_vive_con"]) ? $request["nombre_vive_con"] : null;
            $Alumno->colegiaturas_especial = isset($request["colegiaturas"]) ? $request["colegiaturas"] : null;
            $Alumno->ciclo_escolar_id   = isset($request["ciclo_escolar_id"]) ? $request["ciclo_escolar_id"] : null;
            $Alumno->costo_colegiatura_especial = isset($request["costo_colegiatura_especial"]) ? $request["costo_colegiatura_especial"] : null;
            $Alumno->tipo_sangre        = isset($request["tipo_sangre_id"]) ? $request["tipo_sangre_id"] : null;
            $Alumno->talla              = isset($request["talla_alumno"]) ? $request["talla_alumno"] : null;
            $Alumno->peso               = isset($request["peso_alumno"]) ? $request["peso_alumno"] : null;
            $Alumno->enfermedades_lesiones     = isset($request["enfermedades_lesiones"]) ? $request["enfermedades_lesiones"] : null;
            $Alumno->antecedentes_enfermedades = isset($request["antecedentes_familiares"]) ? $request["antecedentes_familiares"] : null;
            $Alumno->discapacidad       = isset($request["discapacidad_tipo"]) ? $request["discapacidad_tipo"] : null;
            $Alumno->lugar_nacimiento   = isset($request["lugar_nacimiento"]) ? $request["lugar_nacimiento"] : null;
            $Alumno->fecha_nacimiento   = isset($request["fecha_nacimiento_id"]) ? Esys::stringToTimeUnix($request["fecha_nacimiento_id"])  : null;
            $Alumno->nota          = isset($request["text_area_descripcion"]) ? $request["text_area_descripcion"] : null;
            if ($Alumno->save()) {
                Yii::$app->session->setFlash('success', "Se genero correctamente el alumno #" . $Alumno->nombre );

                return $this->redirect(['view', 'id' => $Alumno->cliente_id ]);
            }else{
                Yii::$app->session->setFlash('warning', "Ocurrio un error al generar al alumno #" . $Alumno->nombre );
            }
        }
        return $this->redirect(['view', 'id' => $Alumno->cliente_id ]);
    }

    public function actionEditFormAlumno()
    {
        $Alumno = Alumno::findOne(Yii::$app->request->post()["edit_alumno_id"]);

        if ($Alumno->id ) {
            $request = Yii::$app->request->post();
            $Alumno->nombre             = isset($request["edit_nombre_alumno"]) ? $request["edit_nombre_alumno"] : null;
            $Alumno->apellidos          = isset($request["edit_apellido_alumno"]) ? $request["edit_apellido_alumno"] : null;
            $Alumno->cliente_id         = isset($request["edit_cliente_id"]) ? $request["edit_cliente_id"] : null;
            $Alumno->nivel              = isset($request["edit_nivel_id"]) ? $request["edit_nivel_id"] : null;
            $Alumno->grado              = isset($request["edit_grado_id"]) ? $request["edit_grado_id"] : null;
            $Alumno->sexo               = isset($request["edit_sexo_id"]) ? $request["edit_sexo_id"] : null;
            $Alumno->costo_colegiatura  = isset($request["edit_costo_colegiatura"]) ? $request["edit_costo_colegiatura"] : null;
            $Alumno->is_especial        = isset($request["edit_alumno_especial"]) ? 10 : null;
            $Alumno->colegiaturas_especial  = isset($request["edit_colegiaturas"]) ? $request["edit_colegiaturas"] : null;
            $Alumno->ciclo_escolar_id       = isset($request["edit_ciclo_escolar_id"]) ? $request["edit_ciclo_escolar_id"] : null;
            $Alumno->costo_colegiatura_especial = isset($request["edit_costo_colegiatura_especial"]) ? $request["edit_costo_colegiatura_especial"] : null;
            $Alumno->tipo_sangre        = isset($request["edit_tipo_sangre_id"]) ? $request["edit_tipo_sangre_id"] : null;
            $Alumno->talla              = isset($request["edit_talla_alumno"]) ? $request["edit_talla_alumno"] : null;
            $Alumno->peso               = isset($request["edit_peso_alumno"]) ? $request["edit_peso_alumno"] : null;
            $Alumno->enfermedades_lesiones     = isset($request["edit_enfermedades_lesiones"]) ? $request["edit_enfermedades_lesiones"] : null;
            $Alumno->antecedentes_enfermedades = isset($request["edit_antecedentes_familiares"]) ? $request["edit_antecedentes_familiares"] : null;
            $Alumno->discapacidad       = isset($request["edit_discapacidad_tipo"]) ? $request["edit_discapacidad_tipo"] : null;
            $Alumno->fecha_nacimiento   = isset($request["edit_fecha_nacimiento_id"]) ? Esys::stringToTimeUnix($request["edit_fecha_nacimiento_id"])  : null;
            $Alumno->nota          = isset($request["edit_text_area_descripcion"]) ? $request["edit_text_area_descripcion"] : null;
            $Alumno->vive_con          = isset($request["edit_vive_con"]) ? $request["edit_vive_con"] : null;
            $Alumno->nombre_vive_con          = isset($request["edit_nombre_vive_con"]) ? $request["edit_nombre_vive_con"] : null;
            $Alumno->factura          = isset($request["edit_factura"]) ? $request["edit_factura"] : null;


            $Alumno->lugar_nacimiento          = isset($request["edit_lugar_nacimiento"]) ? $request["edit_lugar_nacimiento"] : null;

            if ($Alumno->update()) {
                Yii::$app->session->setFlash('success', "Se actualizo correctamente el Alumno #" . $Alumno->nombre );

                return $this->redirect(['view', 'id' => $Alumno->cliente_id ]);
            }else{
                echo "<pre>";
                print_r($Alumno);
                die();
                Yii::$app->session->setFlash('warning', "Ocurrio un error al actualizar al Alumno #" . $Alumno->nombre );
            }
        }else
            Yii::$app->session->setFlash('danger', 'Ocurrio un error al actualizar al Alumno.');
        return $this->redirect(['view', 'id' => Yii::$app->request->post()["edit_cliente_id"] ]);
    }


    public function actionEditAlumno()
    {
        $request = Yii::$app->request;

        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $alumno_id = Yii::$app->request->get('alumno_id');

            $Alumno = [];
            if ($alumno_id)
                $Alumno             = Alumno::findOne($alumno_id);

            // Devolvemos datos CHOSEN.JS
            $response = [
                "alumno" => $Alumno
            ];

            return $response;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
    }

    public function actionAlumnoData()
    {
        $request = Yii::$app->request;

        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $alumno_id = Yii::$app->request->get('alumno_id');

            if ($alumno_id) {
                $Alumno             = Alumno::findOne($alumno_id);
                $documentoAlumno    = [];
                $creditoArray       = [];
                foreach ($Alumno->alumnoDocumentos as $key => $alumDocumento) {
                    array_push($documentoAlumno, [
                        "documento"     => $alumDocumento->documento->nombre,
                        "documento_id"  => $alumDocumento->documento->id,
                    ]);
                }
                           }
            // Devolvemos datos CHOSEN.JS
            $response = [
                "documentoAlumno" => $documentoAlumno,
                "alumno" => $Alumno
            ];

            return $response;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');

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

        $model->dir_obj   = $model->direccion;

        $model->dir_obj->codigo_search   = isset($model->direccion->esysDireccionCodigoPostal->codigo_postal)  ? $model->direccion->esysDireccionCodigoPostal->codigo_postal : null;

        // Si no se enviaron datos POST o no pasa la validación, cargamos formulario
        if($model->load(Yii::$app->request->post()) && $model->dir_obj->load(Yii::$app->request->post()) ){
            $model->scenario = Cliente::SCENARIO_UPDATE;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        //$model->setDocumentoAsignarNames();

        return $this->render('update', [
            'model' => $model,
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
            // Eliminamos el cliente
            $this->findModel($id)->delete();

            Yii::$app->session->setFlash('success', "Se ha eliminado correctamente al cliente #" . $id);

        }catch(\Exception $e){
            if($e->getCode() === 23000){
                Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la eliminación del cliente.');

                header("HTTP/1.0 400 Relation Restriction");
            }else{
                throw $e;
            }
        }

        return $this->redirect(['index', 'tab' => 'index']);
    }

    public function actionDeleteAlumno($id)
    {
        $model = Alumno::findOne($id);

        try{
            // Eliminamos el alumno
            foreach ($model->alumnoDocumentos as $key => $value) {
                $value->delete();
            }

            $model->delete();

            Yii::$app->session->setFlash('success', "Se ha eliminado correctamente al Alumno #" . $id);

        }catch(\Exception $e){
            if($e->getCode() === 23000){
                Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la eliminación del Alumno.');

                header("HTTP/1.0 400 Relation Restriction");
            }else{
                throw $e;
            }
        }

        return $this->redirect(['index', 'tab' => 'index']);

    }

    public function actionAddFilesTutor()
    {
        if (Yii::$app->request->post()['Cliente']) {
            $request = Yii::$app->request->post();

            $files = FileCheck::getAllFilesTutor($request['Cliente']['id']);

            if($files){
                foreach($files as $file){
                    $file->delete();
                } 
            }

            if(isset($request["pertenece_id"]) && $request["pertenece_id"]){
                foreach($request["pertenece_id"] as $file){
                    $fileCheck = new FileCheck();
                    $fileCheck->tutor_id        = $request['Cliente']['id'];
                    $fileCheck->pertenece_id    = $file;
                    $fileCheck->tipo           = FileCheck::TIPO_TUTOR;
                    $fileCheck->save();
                }
            }
            Yii::$app->session->setFlash('success', "Se actualizo correctamente");
            return $this->redirect(['view', 'id' => $request['Cliente']['id'] ]);
        }else{
            Yii::$app->session->setFlash('warning', "Verifica tu informacion, Intenta nuevamente");
            return $this->redirect(['view', 'id' => $request['Cliente']['id'] ]);

        }
    }

    public function actionRemoverFile($tutor_id, $file_id)
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

        return $this->redirect(['view', 'id' => $tutor_id ]);

    }

    public function actionHistorialCambios($id)
    {
        $model = $this->findModel($id);

        return $this->render("historial-cambios", [
            'model' => $model,
        ]);
    }


//------------------------------------------------------------------------------------------------//
// BootstrapTable list
//------------------------------------------------------------------------------------------------//
    /**
     * Return JSON bootstrap-table
     * @param  array $_GET
     * @return json
     */
    public function actionClientesJsonBtt(){
        return ViewCliente::getJsonBtt(Yii::$app->request->get());
    }

    public function actionClienteAjax($q = false, $cliente_id = false)
    {
        $request = Yii::$app->request;

        // Cadena de busqueda
        if ($request->validateCsrfToken() && $request->isAjax) {

            if ($q) {
                $text = $q;

            } else {
                $text = Yii::$app->request->get('data');
                $text = $text['q'];
            }

            if (is_null($text) && $cliente_id)
                $user = ViewCliente::getClienteAjax($cliente_id,true);
            else
                $user = ViewCliente::getClienteAjax($text,false);
            // Obtenemos user


            // Devolvemos datos YII2 SELECT2
            if ($q) {
                return $user;
            }

            // Devolvemos datos CHOSEN.JS
            $response = ['q' => $text, 'results' => $user];

            return $response;
        }
        throw new BadRequestHttpException('Solo se soporta peticiones AJAX');
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
                $model = Cliente::findOne($id);
                break;

            case 'view':
                $model = ViewCliente::findOne($id);
                break;
        }

        if ($model !== null)
            return $model;

        else
            throw new NotFoundHttpException('La página solicitada no existe.');
    }

}
