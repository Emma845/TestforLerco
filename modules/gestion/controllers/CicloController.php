<?php
namespace app\modules\gestion\controllers;

use Yii;
use app\models\ciclo\Ciclo;
use app\models\ciclo\CicloTarifa;
use app\models\ciclo\ViewCiclos;
use yii\web\NotFoundHttpException;



class CicloController extends \app\controllers\AppController
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
    public function actionIndex()
    {
        return $this->render('index',[
        	"can" => $this->can]);
    }

    public function actionView($id)
    {
        $primaria = CicloTarifa::getPrimaria($id);
        $secundaria = CicloTarifa::getSecundaria($id);
        $preparatoria = CicloTarifa::getPreparatoria($id);
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'can'   => $this->can,
            'pri'   => $primaria,
            'sec'   => $secundaria,
            'pre'   => $preparatoria,
        ]);
    }

    public function actionCreate()
    {
        $model = new Ciclo();
        $manda = Yii::$app->request->post();
        if ($manda){
            
            //return print_r($manda);
            $model->rango_a = strtotime($manda['Ciclo']['rango_a']);
            $model->rango_b = strtotime($manda['Ciclo']['rango_b']);
            $model->year = $manda["Ciclo"]['year'];
            $model->year_fin = $manda["Ciclo"]['year_fin'];
            $model->notas = $manda["Ciclo"]["notas"];

            if($model->validate())
            {
                if($model->rango_a > 0 && $model->rango_b > 0)
                {
                    if($model->save()) 
                    {
                        return $this->redirect(['view',
                            'id' => $model->id
                        ]);
                    }
                }
                else{
                    Yii::$app->session->setFlash('warning', "Ingrese los datos en el formulario correctamente");
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
        }
        else
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $manda = Yii::$app->request->post();

        // Si no se enviaron datos POST o no pasa la validación, cargamos formulario
        if($manda)
        {
            /* return print_r($manda); */
            $model->rango_a = strtotime($manda['Ciclo']['rango_a']);
            $model->rango_b = strtotime($manda['Ciclo']['rango_b']);
            $model->year = $manda["Ciclo"]['year'];
            $model->year_fin = $manda["Ciclo"]['year_fin'];
            $model->notas = $manda["Ciclo"]["notas"];
            if($model->validate())
            {
                if($model->rango_a > 0 && $model->rango_b > 0)
                {
                    $model->save();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    Yii::$app->session->setFlash('warning', "Ingrese los datos en el formulario correctamente");
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionTarifas($id)
    {
        $model = new CicloTarifa();
        $ciclo = Ciclo::find()->where(['=', 'id', $id])->one();

        $limit = CicloTarifa::getConfigCiclo($id);

        $grado_primaria = 0;
        $grado_secundaria = 0;
        $grado_preparatoria = 0;

        foreach($limit as $key => $lim)
        {
            if($lim->nivel == 10)
            {
                $grado_primaria = $lim->nivel;
            }
            if($lim->nivel == 20)
            {
                $grado_secundaria = $lim->nivel;
            }
            if($lim->nivel == 30)
            {
                $grado_preparatoria = $lim->nivel;
            }
        }

       /*         $array = [
            '1' => $grado_primaria,
            '2' => $grado_secundaria,
            '3' => $grado_preparatoria,
        ];

        echo('<pre>');
        print_r($array);
        echo('<pre>');
        die(); */


        $manda = Yii::$app->request->post();

            if($manda)
            {
                if($grado_primaria != 0 && $manda['CicloTarifa']['nivel'] == 10)
                {
                    Yii::$app->session->setFlash('danger', "LO SENTIMOS, YA SE HA CONFIGURADO PREESCOLAR");

                    return $this->redirect(['view',
                    'id'    => $id, 
                     ]);
                }

                if($grado_secundaria != 0 && $manda['CicloTarifa']['nivel'] == 20)
                {
                    Yii::$app->session->setFlash('danger', "LO SENTIMOS, YA SE HA CONFIGURADO LA PRIMARIA");

                    return $this->redirect(['view',
                    'id'    => $id, 
                     ]);
                }
                if($grado_preparatoria != 0 && $manda['CicloTarifa']['nivel'] == 30)
                {
                    Yii::$app->session->setFlash('danger', "LO SENTIMOS, YA SE HA CONFIGURADO LA SECUNDARIA");

                    return $this->redirect(['view',
                    'id'    => $id, 
                     ]);
                }

                    $model->ciclo_id = $id;
                    $model->nivel = $manda['CicloTarifa']['nivel'];
                    $model->inscripcion = $manda['CicloTarifa']['inscripcion'];
                    $model->colegiatura = $manda['CicloTarifa']['colegiatura'];
                    $model->mora = $manda['CicloTarifa']['mora'];
                    $model->notas = $manda['CicloTarifa']['notas'];
    
                        if($model->save()) 
                        { 
                            if($model->nivel == 10){
                                Yii::$app->session->setFlash('success', "Se han configurado correctamente la tarifas de preescolar");
                                return $this->redirect(['view',
                                'id'    => $model->ciclo_id, 
                            ]);
                            }
                            if($model->nivel == 20){
                                Yii::$app->session->setFlash('success', "Se han configurado correctamente la tarifas de primaria");
                                return $this->redirect(['view',
                                'id'    => $model->ciclo_id, 
                            ]);
                            }
                            if($model->nivel == 30){
                                Yii::$app->session->setFlash('success', "Se han configurado correctamente la tarifas de secundaria");
                                return $this->redirect(['view',
                                'id'    => $model->ciclo_id, 
                            ]);
                            }
                        }
            }

                return $this->render('tarifa',[
                    "can"   => $this->can,
                    "model" => $model,
                    'ciclo' => $ciclo,
                    ]);  
    }

    public function actionUpdateTarifas($id,$grado)
    {
        return 'hola';
        /* TODO:TRABAJAR EDICION DE TARIFAS */
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
    public function actionCicloJsonBtt(){
        return ViewCiclos::getJsonBtt(Yii::$app->request->get());
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
                $model = Ciclo::findOne($name);
                break;

            case 'view':
                $model = ViewCiclos::findOne($name);
                break;
        }

        if ($model !== null)
            return $model;

        else
            throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
