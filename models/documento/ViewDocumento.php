<?php
namespace app\models\documento;

use Yii;
use yii\db\Query;
use yii\web\Response;
use yii\db\Expression;

/**
 * This is the model class for table "view_documento".
 *
 * @property int $id ID
 * @property string $nombre Nombre
 * @property string $compete Singular
 * @property string $doc_partida Singular
 * @property string $aplica_a Singular
 * @property string $periodicidad Singular
 * @property int $documento_partida Documento Partida
 * @property int $plazo_adicional Plazo adicional
 * @property int $bloqueo Bloqueo
 * @property int $created_at Creado
 * @property string $created_by_user
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property string $updated_by_user
 * @property int $updated_by Modificado por
 */
class ViewDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_documento';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'compete' => 'Compete',
            'doc_partida' => 'Doc Partida',
            'aplica_a' => 'Aplica A',
            'periodicidad' => 'Periodicidad',
            'documento_partida' => 'Documento Partida',
            'plazo_adicional' => 'Plazo Adicional',
            'bloqueo' => 'Bloqueo',
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
                    'tipo',
                    'update',
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

            if (isset($filters['origen']) && $filters['origen'])
                $query->andWhere(['origen' =>  $filters['origen']]);

            if($search)
                $query->andFilterWhere([
                    'or',

                    ['like', 'nombre', $search],
                    ['like', 'email', $search],

                ]);


        // Imprime String de la consulta SQL
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        $rows = $query->all();

        return [
            'total' => \Yii::$app->db->createCommand('SELECT FOUND_ROWS()')->queryScalar(),
            'rows'  => $rows
        ];
    }



}
