<?php

namespace app\models\lista;

use Yii;
use yii\db\Query;
use yii\web\Response;
use app\models\user\User;

/**
 * This is the model class for table "view_lista".
 *
 * @property int $id ID
 * @property int $profesor_id Profesor ID
 * @property string $profesor
 * @property int $count_alumno
 * @property int $sin_asistencia
 * @property int $ausente
 * @property int $asistencia
 * @property string $nota Nota
 * @property int $created_at Creado
 * @property string $created_by_user
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property string $updated_by_user
 * @property int $updated_by Modificado por
 */
class ViewLista extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_lista';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profesor_id' => 'Profesor ID',
            'profesor' => 'Profesor',
            'count_alumno' => 'Count Alumno',
            'sin_asistencia' => 'Sin Asistencia',
            'ausente' => 'Ausente',
            'asistencia' => 'Asistencia',
            'nota' => 'Nota',
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
                    'profesor_id',
                    'profesor',
                    'count_alumno',
                    'sin_asistencia',
                    'ausente',
                    'asistencia',
                    'justificado',
                    'nota',
                    'created_at',
                    'created_by_user',
                    'created_by',
                    'updated_at',
                    'updated_by_user',
                    'updated_by',
                ])
                ->from(self::tableName())
                ->orderBy($orderBy)
                ->offset($offset)
                ->limit($limit);


        /************************************
        / Filtramos la consulta
        /***********************************/
            if(isset($filters['date_range']) && $filters['date_range']){
                $date_ini = strtotime(substr($filters['date_range'], 0, 10));
                $date_fin = strtotime(substr($filters['date_range'], 13, 23)) + 86340;

                $query->andWhere(['between','created_at', $date_ini, $date_fin]);
            }

            if (Yii::$app->user->identity->tipo == User::TIPO_MAESTRO) {
                $query->andWhere(['profesor_id' =>  Yii::$app->user->identity->id ]);
            }

            if($search)
                $query->andFilterWhere([
                    'or',
                    ['like', 'id', $search],
                    ['like', 'alumno_nombre', $search],
                    ['like', 'tutor_completo', $search],
                ]);

        return [
            'rows'  => $query->all(),
            'total' => \Yii::$app->db->createCommand('SELECT FOUND_ROWS()')->queryScalar(),
        ];
    }
}
