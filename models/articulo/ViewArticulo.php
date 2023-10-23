<?php

namespace app\models\articulo;

use Yii;
use yii\web\Response;
use yii\db\Query;


/**
 * This is the model class for table "view_articulo".
 *
 * @property int $id ID
 * @property string $nombre Nombre
 * @property string $image_src_filename Imagen Src Filename
 * @property string $image_web_filename Imagen Web Filename
 * @property double $precio Precio
 * @property int $inventario Inventario
 * @property int $status Estatus
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 * @property string $created_by_user
 * @property string $updated_by_user
 */
class ViewArticulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'inventario', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['nombre', 'created_at', 'created_by'], 'required'],
            [['precio'], 'number'],
            [['nombre'], 'string', 'max' => 150],
            [['image_src_filename', 'image_web_filename'], 'string', 'max' => 255],
            [['created_by_user', 'updated_by_user'], 'string', 'max' => 201],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'image_src_filename' => 'Image Src Filename',
            'image_web_filename' => 'Image Web Filename',
            'precio' => 'Precio',
            'inventario' => 'Inventario',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by_user' => 'Created By User',
            'updated_by_user' => 'Updated By User',
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
                    'precio',
                    'inventario',
                    'status',
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
                    ['like', 'nombre', $search],
                ]);

        return [
            'rows'  => $query->all(),
            'total' => \Yii::$app->db->createCommand('SELECT FOUND_ROWS()')->queryScalar(),
        ];
    }

    public static function getArticuloAjax($q,$search_opt = false)
    {
        // La respuesta sera en Formato JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = (new Query())
            ->select([
                "view_articulo.`id`",
                "nombre",

            ])
            ->from(self::tableName())
            ->orderBy('id desc')
            ->limit(50);

            $query->andWhere(['status' => Articulo::STATUS_ACTIVE]);

            //$query->andWhere('<',['inventario', 0 ]);


            if ($search_opt)
                $query->andWhere(['view_articulo.id' => $q]);
            else
                $query->andWhere(['like', 'nombre', $q]);

        // Imprime String de la consulta SQL
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return $search_opt ? $query->one() :$query->all();
    }
}
