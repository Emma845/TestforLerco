<?php
namespace app\models\caja;

use Yii;
use yii\db\Query;
use yii\web\Response;
 
/**
 * This is the model class for table "view_caja".
 *
 * @property int $id ID
 * @property int $tipo_id Tipo pago
 * @property string $tipo Singular
 * @property int $padre_tutor_id Padre / Tutor
 * @property int $alumno_id Alumno
 * @property string $alumno_nombre
 * @property string $tutor_completo
 * @property double $monto Monto
 * @property int $cantidad Cantidad
 * @property string $nota Nota
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 */
class ViewCaja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_caja';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_id' => 'Tipo ID',
            'tipo' => 'Tipo',
            'padre_tutor_id' => 'Padre Tutor ID',
            'alumno_id' => 'Alumno ID',
            'alumno_nombre' => 'Alumno Nombre',
            'tutor_completo' => 'Tutor Completo',
            'monto' => 'Monto',
            'cantidad' => 'Cantidad',
            'nota' => 'Nota',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
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
                    'tipo_id',
                    'tipo',
                    'padre_tutor_id',
                    'alumno_id',
                    'alumno_completo',
                    'tutor_completo',
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
