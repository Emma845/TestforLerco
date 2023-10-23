<?php
namespace app\models\alumno;

use Yii;
use yii\db\Query;
use yii\web\Response;
use app\models\user\User;
/**
 * This is the model class for table "view_alumno".
 *
 * @property int $id ID
 * @property int $cliente_id Cliente ID
 * @property string $nombre Nombre
 * @property string $apellidos Apellidos
 * @property string $nivel Singular
 * @property string $grado Singular
 * @property int $fecha_nacimiento Fecha Nacimiento
 * @property int $tipo_sangre Tipo de sangre
 * @property int $talla Talla
 * @property double $peso Peso
 * @property string $enfermedades_lesiones Enfermedades / Lesiones
 * @property string $antecedentes_enfermedades Enfermades cronicas
 * @property string $discapacidad Discapacidad
 * @property string $nota Nota
 * @property int $created_at Creado
 * @property string $created_by_user
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property string $updated_by_user
 * @property int $updated_by Modificado por
 */
class ViewAlumno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente ID',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'nivel' => 'Nivel',
            'grado' => 'Grado',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'tipo_sangre' => 'Tipo Sangre',
            'talla' => 'Talla',
            'peso' => 'Peso',
            'enfermedades_lesiones' => 'Enfermedades Lesiones',
            'antecedentes_enfermedades' => 'Antecedentes Enfermedades',
            'discapacidad' => 'Discapacidad',
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
                    'cliente_id',
                    'nombre',
                    'nombre_completo',
                    'apellidos',
                    'nivel',
                    'grado',
                    'fecha_nacimiento',
                    'tipo_sangre',
                    'talla',
                    'sexo',
                    'peso',
                    'edad',
                    'grado_id',
                    'nivel_id',
                    'enfermedades_lesiones',
                    'antecedentes_enfermedades',
                    'discapacidad',
                    'status',
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
                $maestro = User::findOne(Yii::$app->user->id);
                $query->andWhere(["nivel_id" => $maestro->nivel_id ]);
                $query->andWhere([ "grado_id" => $maestro->grado_id ]);
            }else{
                if (isset($filters['nivel']) && $filters['nivel'])
                $query->andWhere(['nivel_id' =>  $filters['nivel']]);

                if (isset($filters['grado']) && $filters['grado'])
                    $query->andWhere(['grado_id' =>  $filters['grado']]);

                if (isset($filters['status']) && $filters['status'])
                    $query->andWhere(['status' =>  $filters['status']]);
            }

            if($search)
                $query->andFilterWhere([
                    'or',
                    ['like', 'id', $search],
                    ['like', 'nombre_completo', $search],
                ]);


        // Imprime String de la consulta SQL
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';
//        die();

        return [
            'rows'  => $query->all(),
            'total' => \Yii::$app->db->createCommand('SELECT FOUND_ROWS()')->queryScalar(),
        ];
    }
}
