<?php
namespace app\models\cliente;

use Yii;
use yii\db\Query;
use yii\web\Response;
use app\models\esys\EsysDireccion;
use app\models\user\User;
use app\models\esys\EsysDireccionCodigoPostal;
use app\models\alumno\Alumno;

/**
 * This is the model class for table "view_cliente".
 *
 * @property int $id Id
 * @property int $titulo_personal_id Titulo personal
 * @property string $titulo_personal Singular
 * @property string $email Correo electrónico
 * @property string $email2 Correo secundario
 * @property string $empresa Empresa
 * @property string $nombre Nombre
 * @property string $apellidos Apellidos
 * @property string $sexo Sexo
 * @property string $cargo Cargo
 * @property string $departamento Departamento
 * @property int $origen_id Se entero través de
 * @property string $origen Singular
 * @property int $asignado_a_id Asignado a
 * @property string $asignado_a
 * @property string $tel Teléfono trabajo
 * @property string $tel_ext Extensión
 * @property string $tel2 Otro teléfono
 * @property string $movil Teléfono movil
 * @property string $pag_web Página web
 * @property string $notas Notas / Comentarios
 * @property int $api_enabled Habilitar API
 * @property string $api_username Nombre de usuario (API)
 * @property int $status Estatus
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property string $created_by_user
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 * @property string $updated_by_user
 */
class ViewCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo_personal_id' => 'Titulo Personal ID',
            'titulo_personal' => 'Titulo Personal',
            'nombre_completo' => 'Nombre Completo',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'email' => 'Email',
            'sexo' => 'Sexo',
            'origen' => 'Origen',
            'telefono' => 'Telefono',
            'telefono_movil' => 'Telefono Movil',
            'status' => 'Status',
            'notas' => 'Notas',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'created_by_user' => 'Created By User',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'updated_by_user' => 'Updated By User',
        ];
        /*return [
            'id' => 'Id',
            'titulo_personal_id' => 'Titulo personal',
            'titulo_personal' => 'Singular',
            'email' => 'Correo electrónico',
            'email2' => 'Correo secundario',
            'empresa' => 'Empresa',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'sexo' => 'Sexo',
            'cargo' => 'Cargo',
            'departamento' => 'Departamento',
            'origen_id' => 'Se entero través de',
            'origen' => 'Singular',
            'asignado_a_id' => 'Asignado a',
            'asignado_a' => 'Asignado A',
            'tel' => 'Teléfono trabajo',
            'tel_ext' => 'Extensión',
            'tel2' => 'Otro teléfono',
            'movil' => 'Teléfono movil',
            'pag_web' => 'Página web',
            'notas' => 'Notas / Comentarios',
            'api_enabled' => 'Habilitar API',
            'api_username' => 'Nombre de usuario (API)',
            'status' => 'Estatus',
            'created_at' => 'Creado',
            'created_by' => 'Creado por',
            'created_by_user' => 'Created By User',
            'updated_at' => 'Modificado',
            'updated_by' => 'Modificado por',
            'updated_by_user' => 'Updated By User',
        ];*/
    }

    public static function primaryKey()
    {
        return ['id'];
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
                    'titulo_personal_id',
                    'titulo_personal',
                    'nombre_completo',
                    'nombre',
                    'apellidos',
                    'email',
                    'sexo',
                    'origen',
                    'telefono',
                    'telefono_movil',
                    'whatsapp',
                    'status',
                    'tipo_cliente',
                    'notas',
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
            if(isset($filters['date_range']) && $filters['date_range']){
                $date_ini = strtotime(substr($filters['date_range'], 0, 10));
                $date_fin = strtotime(substr($filters['date_range'], 13, 23)) + 86340;

                $query->andWhere(['between','created_at', $date_ini, $date_fin]);
            }

            if (Yii::$app->user->identity->tipo != User::TIPO_MAESTRO) {

                if (isset($filters['nivel_id']) && $filters['nivel_id']){
                    $alumno = Alumno::getPadreFamiliaNivel($filters['nivel_id']);

                    $NivelIDs = [];
                    foreach ($alumno as $key => $item) {
                        array_push($NivelIDs, $key);
                    }
                    $query->andWhere(['IN','id', $NivelIDs ]);
                }

                if (isset($filters['grado_id']) && $filters['grado_id']){
                    $alumno = Alumno::getPadreFamiliaGrado($filters['grado_id']);

                    $GradoIDs = [];
                    foreach ($alumno as $key => $item) {
                        array_push($GradoIDs, $key);
                    }
                    $query->andWhere(['IN','id', $GradoIDs ]);
                }

                if (isset($filters['profesor_id']) && $filters['profesor_id']){
                    $user = User::findOne($filters['profesor_id']);
                    $alumno = Alumno::getPadreFamiliaGrado($user->grado_id,$user->nivel_id);

                    $NivelGradoIDs = [];
                    foreach ($alumno as $key => $item) {
                        array_push($NivelGradoIDs, $key);
                    }
                    $query->andWhere(['IN','id', $NivelGradoIDs ]);
                }
            }



            /**========================================================
             * Filtamos por supervisor usar IN ( ARRAY(AGENTE_ID,AGENTE_ID,AGENTE_ID))
             =========================================================*/
            if (Yii::$app->user->identity->tipo == User::TIPO_MAESTRO) {
                $maestro = User::findOne(Yii::$app->user->id);
                $padresFamilia_asignados = $maestro->getPadresFamilia();

                $padreFamiliaIDs = [];
                foreach ($padresFamilia_asignados as $key => $item) {
                    array_push($padreFamiliaIDs, $item);
                }

                $query->andWhere(['IN','id', $padreFamiliaIDs ]);
            }


            if($search)
                $query->andFilterWhere([
                    'or',
                    ['like', 'id', $search],
                    ['like', 'telefono_movil', $search],
                    ['like', 'telefono', $search],
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

    public static function getClienteAjax($q,$search_opt = false)
    {
        // La respuesta sera en Formato JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = (new Query())
            ->select([
                "view_cliente.`id`",
                "CONCAT_WS(' ', `nombre_completo`,'[', id ,']') AS `text`",
                "nombre",
                "apellidos",
                "email",
                "telefono",
                "telefono_movil",

            ])
            ->from(self::tableName())
            ->orderBy('id desc')
            ->limit(50);


            if (Yii::$app->user->identity->tipo == User::TIPO_MAESTRO) {
                $maestro = User::findOne(Yii::$app->user->id);
                $padresFamilia_asignados = $maestro->getPadresFamilia();

                $padreFamiliaIDs = [];
                foreach ($padresFamilia_asignados as $key => $item) {
                    array_push($padreFamiliaIDs, $item);
                }

                $query->andWhere(['IN','id', $padreFamiliaIDs ]);
            }

            $query->andWhere(['status' => Cliente::STATUS_ACTIVE]);

            if ($search_opt)
                $query->andWhere(['view_cliente.id' => $q]);
            else
                $query->andWhere(['like', 'nombre_completo', $q]);

        // Imprime String de la consulta SQL
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return $search_opt ? $query->one() :$query->all();
    }

}
