<?php
namespace app\modules\gestion\controllers;

use Yii;
use yii\web\Controller;
use app\models\articulo\Articulo;
use app\models\articulo\ViewArticulo;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;



class ArticuloController extends \app\controllers\AppController
{
    private $can;

    public function init()
    {
        parent::init();

        $this->can = [
            'create' => Yii::$app->user->can('articuloCreate'),
            'update' => Yii::$app->user->can('articuloUpdate'),
            'delete' => Yii::$app->user->can('articuloDelete'),
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[
        	"can" => $this->can]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'can'   => $this->can,
        ]);
    }

    public function actionCreate()
    {
        $model = new Articulo();

        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $model->image = UploadedFile::getInstance($model, 'image');
                if($model->image){
                    $model->upload();
                }
                if($model->save(false)) {
                    return $this->redirect(['view',
                        'id' => $model->id
                    ]);
                }
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Si no se enviaron datos POST o no pasa la validación, cargamos formulario
        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                $model->image = UploadedFile::getInstance($model, 'image');
                if($model->image){
                    $model->upload();
                }
            }
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try{
            // Eliminamos el articulo
            if ($model->image_web_filename!='')
                $model->deleteImage();
            $this->findModel($id)->delete();

            Yii::$app->session->setFlash('success', "Se ha eliminado correctamente al articulo #" . $id);

        }catch(\Exception $e){
            if($e->getCode() === 23000){
                Yii::$app->session->setFlash('danger', 'Existen dependencias que impiden la eliminación del articulo.');

                header("HTTP/1.0 400 Relation Restriction");
            }else{
                throw $e;
            }
        }

        return $this->redirect(['index', 'tab' => 'index']);
    }

    //------------------------------------------------------------------------------------------------//
	// BootstrapTable list
	//------------------------------------------------------------------------------------------------//
    /**
     * Return JSON bootstrap-table
     * @param  array $_GET
     * @return json
     */
    public function actionArticulosJsonBtt(){
        return ViewArticulo::getJsonBtt(Yii::$app->request->get());
    }

    public function actionArticulosAjax($q = false, $articulo_id = false)
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

            if (is_null($text) && $articulo_id)
                $user = ViewArticulo::getArticuloAjax($articulo_id,true);
            else
                $user = ViewArticulo::getArticuloAjax($text,false);
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
    protected function findModel($name, $_model = 'model')
    {
        switch ($_model) {
            case 'model':
                $model = Articulo::findOne($name);
                break;

            case 'view':
                $model = ViewArticulo::findOne($name);
                break;
        }

        if ($model !== null)
            return $model;

        else
            throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
