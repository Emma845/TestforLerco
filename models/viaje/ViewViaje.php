<?php
namespace app\models\viaje;

use Yii;
use yii\db\Query;
use yii\web\Response;
use app\models\user\User;

/**
 * This is the model class for table "view_viaje".
 *
 * @property int $id ID
 * @property string $nombre Nombre
 * @property int $fecha_ini Fecha inicio
 * @property int $fecha_expired Fecha fin
 * @property string $nota Nota
 * @property int $status Estatus
 * @property int $created_at Creado
 * @property string $created_by_user
 * @property int $created_by Creado por
 * @property int $updated_at
 * @property string $updated_by_user
 * @property int $updated_by Modificado por
 */
class ViewViaje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_viaje';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'fecha_ini' => 'Fecha Ini',
            'fecha_expired' => 'Fecha Expired',
            'nota' => 'Nota',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by_user' => 'Created By User',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by_user' => 'Updated By User',
            'updated_by' => 'Updated By',
        ];
    }

        //------------------------------------------------------------------------------------------------//
    // JSON Bootstrap Table
    //------------------------------------------------------------------------------------------------//
    public static function getJsonBtt($arr)
    {
        // La respuesta sera en Formato JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Preparamos las variables
        $sort    = isset($arr['sort'])?   $arr['sort']:   'id';
        $order   = isset($arr['order'])?  $arr['order']:  'asc';
        $orderBy = $sort . ' ' . $order;
        $offset  = isset($arr['offset'])? $arr['offset']: 0;
        $limit   = isset($arr['limit'])?  $arr['limit']:  50;

        $search = isset($arr['search'])? $arr['search']: false;
        parse_str($arr['filters'], $filters);


        /************************************
        / Preparamos consulta
        /***********************************/
            $query = (new Query())
                ->select([
                    "SQL_CALC_FOUND_ROWS `id`",
                        'nombre',
                        'fecha_ini',
                        'fecha_expired',
                        'status',
                        'created_at',
                        'created_by',
                        'created_by_user',
                        'updated_at',
                        'updated_by',
                        'updated_by_user',
                ])
                ->from(self::tableName())
                ->orderBy($orderBy)
                ->offset($offset)
                ->limit($limit);


        /************************************
        / Filtramos la consulta
        /***********************************/
            if (isset($filters['status']) && $filters['status'])
                $query->andWhere(['status' =>  $filters['status']]);


            if ((Yii::$app->user->identity->tipo == User::TIPO_AGENTE || Yii::$app->user->identity->show_acceso_informacion == User::OFF_INFORMACION) && Yii::$app->user->identity->tipo != User::TIPO_SUPERVISOR )
                $query->andWhere(['created_by' =>  Yii::$app->user->identity->id ]);


            if (Yii::$app->user->identity->tipo == User::TIPO_SUPERVISOR) {
                $supervisor = User::findOne(Yii::$app->user->id);
                $supervisor->setAgenteAsignarNames();
                $agentesIDs = [];

                foreach ($supervisor->agentes_asignados as $key => $item) {
                    array_push($agentesIDs, $item);
                }
                array_push($agentesIDs, $supervisor->id);

                $query->andWhere(['IN','created_by', $agentesIDs ]);
            }


            if($search)
                $query->andFilterWhere([
                    'or',
                    ['like', 'id', $search],
                ]);


        // Imprime String de la consulta SQL
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return [
            'rows'  => $query->all(),
            'total' => \Yii::$app->db->createCommand('SELECT FOUND_ROWS()')->queryScalar(),
        ];
    }


    //------------------------------------------------------------------------------------------------//
    // JSON Bootstrap Table
    //------------------------------------------------------------------------------------------------//
    public static function getViajeAutorizacionJsonBtt($arr)
    {
        // La respuesta sera en Formato JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Preparamos las variables
        $sort    = isset($arr['sort'])?   $arr['sort']:   'id';
        $order   = isset($arr['order'])?  $arr['order']:  'asc';
        $orderBy = $sort . ' ' . $order;
        $offset  = isset($arr['offset'])? $arr['offset']: 0;
        $limit   = isset($arr['limit'])?  $arr['limit']:  50;

        $search = isset($arr['search'])? $arr['search']: false;
        parse_str($arr['filters'], $filters);


        /************************************
        / Preparamos consulta
        /***********************************/
            $query = (new Query())
                ->select([
                    "SQL_CALC_FOUND_ROWS `id`",
                        'nombre',
                        'fecha_ini',
                        'fecha_expired',
                        'status',
                        'created_at',
                        'created_by',
                        'created_by_user',
                        'updated_at',
                        'updated_by',
                        'updated_by_user',
                ])
                ->from(self::tableName())
                ->orderBy($orderBy)
                ->offset($offset)
                ->limit($limit);


        /************************************
        / Filtramos la consulta
        /***********************************/
            if (isset($filters['status']) && $filters['status'])
                $query->andWhere(['status' =>  $filters['status']]);

            if($search)
                $query->andFilterWhere([
                    'or',
                    ['like', 'id', $search],
                ]);


        // Imprime String de la consulta SQL
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return [
            'rows'  => $query->all(),
            'total' => \Yii::$app->db->createCommand('SELECT FOUND_ROWS()')->queryScalar(),
        ];
    }
}
