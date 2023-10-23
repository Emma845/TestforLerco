<?php
namespace app\models\user;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use kartik\password\StrengthValidator;
use app\models\Esys;
use app\models\alumno\Alumno;
use app\models\auth\AuthItem;
use app\models\auth\AuthAssignment;
use app\models\esys\EsysSucursal;
use app\models\esys\EsysDireccion;
use app\models\esys\EsysCambioLog;
use app\models\esys\EsysCambiosLog;
use app\models\esys\EsysListaDesplegable;
use app\models\sucursal\Sucursal;
use app\models\viaje\Viaje;
use yii\web\Response;
/**
 * This is the model class for table "user".
 *
 * @property int $id ID
 * @property string $username Nombre de usuario
 * @property string $email Correo electrónico
 * @property int $titulo_personal_id Titulo personal
 * @property string $nombre Nombre
 * @property string $apellidos Apellidos
 * @property string $sexo Sexo
 * @property int $fecha_nac Fecha de nacimiento
 * @property string $telefono Teléfono
 * @property string $telefono_movil Teléfono movil
 * @property int $departamento_id Departamento
 * @property string $cargo Cargo
 * @property string $auth_key
 * @property string $password_hash
 * @property string $account_activation_token
 * @property string $password_reset_token
 * @property string $informacion Información
 * @property string $comentarios Comentarios
 * @property int $status Estatus
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificador por
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property AuthItem[] $authItems
 * @property AuthItem[] $authItems0
 * @property EsysAcceso[] $esysAccesos
 * @property EsysCambiosLog[] $esysCambioLogs
 * @property EsysListaDesplegable[] $esysListaDesplegables
 * @property EsysListaDesplegable[] $esysListaDesplegables0
 * @property EsysSucursal[] $esysSucursals
 * @property EsysSucursal[] $esysSucursals0
 * @property InvProducto[] $invProductos
 * @property InvProducto[] $invProductos0
 * @property InvProductoCategoria[] $invProductoCategorias
 * @property InvProductoCategoria[] $invProductoCategorias0
 * @property NodoAuthItem[] $nodoAuthItems
 * @property NodoAuthItem[] $nodoAuthItems0
 * @property NodoEsysListaDesplegable[] $nodoEsysListaDesplegables
 * @property NodoEsysListaDesplegable[] $nodoEsysListaDesplegables0
 * @property User $createdBy
 * @property User[] $users
 * @property EsysListaDesplegable $departamento
 * @property EsysListaDesplegable $tituloPersonal
 * @property User $updatedBy
 * @property User[] $users0
 * @property UserAsignarPerfil[] $userAsignarPerfils
 * @property AuthItem[] $perfils
 */
class User extends UserIdentity
{
    const SCENARIO_CREATE          = 'create';
    const SCENARIO_UPDATE          = 'update';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';

    // the list of status values that can be stored in user table
    const STATUS_ACTIVE   = 10;
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED  = 0;

    const API_ACTIVE   = 10;
    const API_INACTIVE = 1;

    public static $statusApi = [
        self::API_ACTIVE   => 'Acceso Habilitado',
        self::API_INACTIVE => 'Acceso Inhabilitado',
        //self::STATUS_DELETED  => 'Eliminado'
    ];


    const ORIGEN_USA = 1;
    const ORIGEN_MX = 2;

    public static $origenList = [
        self::ORIGEN_MX   => 'México',
        self::ORIGEN_USA  => 'United States',
    ];

    const TIPO_MAESTRO = 10;
    const TIPO_USER = 20;

    public static $tipoList = [
        self::TIPO_MAESTRO  => 'Profesor / Maestro',
        self::TIPO_USER   => 'User',
    ];

    /**
     * List of names for each status.
     * @var array
     */
    public static $statusList = [
        self::STATUS_ACTIVE   => 'Habilitado',
        self::STATUS_INACTIVE => 'Inhabilitado',
        //self::STATUS_DELETED  => 'Eliminado'
    ];

    /**
     * We made this property so we do not pull hashed password from db when updating
     * @var string
     */
    public $password;

    /**
     * @var \app\rbac\models\Role
     */
    public $item_name;

    public $sucursal_name;

    public $dir_obj;

    public $perfiles_names = [];

    public $sucursal_names = [];

    private $CambiosLog;


    private $response;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            ['password', 'required', 'on' => self::SCENARIO_CREATE],
            ['password', 'required', 'on' => self::SCENARIO_UPDATE_PASSWORD],
            [[], 'required', 'on' => self::SCENARIO_UPDATE],

            [['nombre', 'apellidos', 'username', 'email', 'telefono', 'telefono_movil', 'cargo'], 'filter', 'filter' => 'trim'],

            [['titulo_personal_id', 'departamento_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by','tipo'], 'integer'],
            [['sexo', 'informacion', 'comentarios'], 'string'],
            [['nombre', 'apellidos',  'item_name'], 'string', 'max' => 100],
            [['telefono', 'telefono_movil'],'integer', 'message' => 'El telefono debe ser numerico y sin espacios en blanco' ],

            [['auth_key'], 'string', 'max' => 32],
            [['cargo'], 'string', 'max' => 150],
            [['username', 'email', 'password_hash', 'password_reset_token', 'account_activation_token'], 'string', 'max' => 255],
            [['perfiles_names'], 'each', 'rule' => ['string']],
            [['sucursal_names'], 'each', 'rule' => ['string']],
            [['origen'],'integer'],

            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'match',  'not' => true,
                // we do not want to allow users to pick one of spam/bad usernames
                'pattern' => '/\b(' . Yii::$app->params['user.spamNames'] . ')\b/i',
                'message' => 'Es imposible usar ese nombre de usuario'],
            ['username', 'unique', 'message' => "Este nombre de usuario ya ha sido tomado."],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['email', 'username', 'account_activation_token', 'password_reset_token'], 'unique'],
            ['email', 'unique', 'message' => 'Esta dirección de correo electrónico ya se ha tomado.'],

            $this->passwordStrengthRule(), // method to determine password strength

            [['fecha_nac'], 'safe'],

            [['sexo'], 'default', 'value' => null],

            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['departamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['departamento_id' => 'id']],
            [['grado_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['grado_id' => 'id']],
            [['nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['nivel_id' => 'id']],
            [['titulo_personal_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['titulo_personal_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'password_hash' => 'Contraseña',
            'email' => 'Correo electrónico',
            'titulo_personal_id' => 'Título personal',
            'sexo' => 'Sexo',
            'fecha_nac' => 'Fecha de nacimiento',
            'telefono' => 'Teléfono',
            'telefono_movil' => 'Teléfono movil',
            'cargo' => 'Cargo',
            'departamento_id' => 'Departamento',
            'tituloPersonal.singular' => 'Titulo',
            'perfil.item_name' => 'Perfil',
            'departamento.singular' => 'Departamento',
            'informacion' => 'Información extra',
            'comentarios' => 'Comentarios',
            'status' => 'Estatus',
            'tipo' => 'Tipo',
            'nivel_id' => 'Nivel',
            'nivel.singular' => 'Nivel',
            'grado.singular' => 'Grado',
            'grado_id' => 'Grado',
            'origen' => 'Origen',
            'api_enabled' => 'Acceso App',
            'token' => 'Token',
            'item_name' => 'Perfil',
            'perfilesAsignarString' => 'Perfiles que pudiera asignar',
            'sucursalesAsignarString' => 'Sucursales que pudiera asignar',
            'account_activation_token' => 'Token de activación de cuenta',
            'password_reset_token' => 'Token de recuperación de contraseña',
            'created_at' => 'Creado',
            'created_by' => 'Creado por',
            'updated_at' => 'Modificado',
            'updated_by' => 'Modificado por',

        ];
    }

    /**
     * Set password rule based on our setting value (Force Strong Password).
     *
     * @return array Password strength rule.
     */
    private function passwordStrengthRule()
    {
        /**
         * Nota: Quitamos algunas validaciones para tener contraseñas mas amigables
         */
        // get setting value for 'Force Strong Password'
        //------>$fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator
        // presets are located in: vendor/kartik-v/yii2-password/presets.php
        //---->$strong = ['password', "kartik\password\StrengthValidator", 'preset' => 'normal'];

        // normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        //---->return $fsp? $strong: $normal;

        return  $normal;
    }


//------------------------------------------------------------------------------------------------//
// IdentityInterface
//------------------------------------------------------------------------------------------------//
    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function getItemAll()
    {
        $model = self::find()
            ->select([
                'id',
                new Expression("CONCAT_WS(' ', `nombre`, `apellidos`, CONCAT('[', `id`, ']')) as nombre"),
            ])
            ->orderBy('nombre');

        return ArrayHelper::map($model->all(), 'id', 'nombre');
    }


//------------------------------------------------------------------------------------------------//
// Relaciones
//------------------------------------------------------------------------------------------------//
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['created_by' => 'id']);
    }

    public function getAuthItems0()
    {
        return $this->hasMany(AuthItem::className(), ['updated_by' => 'id']);
    }

    public function getClientes()
    {
        return $this->hasMany(Cliente::className(), ['asignado_a_id' => 'id']);
    }

    public function getClientes0()
    {
        return $this->hasMany(Cliente::className(), ['created_by' => 'id']);
    }

    public function getClientes1()
    {
        return $this->hasMany(Cliente::className(), ['updated_by' => 'id']);
    }


    public function getEsysListaDesplegables()
    {
        return $this->hasMany(EsysListaDesplegable::className(), ['created_by' => 'id']);
    }

    public function getEsysListaDesplegables0()
    {
        return $this->hasMany(EsysListaDesplegable::className(), ['updated_by' => 'id']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['updated_by' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getGrado()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'grado_id']);
    }

    public function getNivel()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'nivel_id']);
    }

    public function getTituloPersonal()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'titulo_personal_id']);
    }

    public function getAsignarPerfils()
    {
        return $this->hasMany(UserAsignarPerfil::className(), ['user_id' => 'id']);
    }

    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getDepartamento()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'departamento_id']);
    }

    public function getPerfil()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }


    public function getPerfilesAsignar()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'perfil'])
            ->viaTable('user_asignar_perfil', ['user_id' => 'id']);
    }

    public function getAsignarSucursals()
    {
        return $this->hasMany(UserSucursal::className(), ['user_id' => 'id']);
    }

    public function getAsignarSupervisor()
    {
        return $this->hasMany(UserAUser::className(), ['user_agente_id' => 'id']);
    }

    public function getAsignarAgente()
    {
        return $this->hasMany(UserAUser::className(), ['user_supervisor_id' => 'id']);
    }

    public function getSucursalAsignar()
    {
        return $this->hasMany(Sucursal::className(), ['id' => 'sucursal_id'])
            ->viaTable('user_sucursal', ['user_id' => 'id']);
    }



    public function getDireccion()
    {
        return $this->hasOne(EsysDireccion::className(), ['cuenta_id' => 'id'])
            ->where(['cuenta' => EsysDireccion::CUENTA_USUARIO, 'tipo' => EsysDireccion::TIPO_PERSONAL]);
    }

    public function getCambiosLog()
    {
        return EsysCambioLog::find()
            ->andWhere(['or',
                ['modulo' => $this->tableName(), 'idx' => $this->id],
                ['modulo' => EsysDireccion::tableName(), 'idx' => $this->direccion->id],
            ])
            ->all();
    }


//------------------------------------------------------------------------------------------------//
// HELPERS
//------------------------------------------------------------------------------------------------//
    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    /**
     * Returns the user status in nice format.
     *
     * @param  integer $status Status integer value.
     * @return string          Nicely formatted status.
     */
    public function getStatusName($status)
    {
        return $this->statusList[$status];
    }

    public function getPerfilesAsignarString()
    {
        $perfiles_string = [];

        foreach ($this->perfilesAsignar as $key => $value) {
            $perfiles_string[] = $value->name;
        }

        return implode(', ', $perfiles_string);
    }

    public function getSucursalesAsignarString()
    {
        $sucursales_string = [];

        foreach ($this->sucursalAsignar as $key => $value) {
            $sucursales_string[] = $value->nombre;
        }

        return implode(', ', $sucursales_string);
    }

     public function saveSucursales()
    {
        $userId  = $this->getId();
        switch ($this->scenario) {
            case self::SCENARIO_CREATE:
                // Agregamos sucursales que pudiera asiganar
                foreach ($this->sucursal_names as $key => $sucursal) {
                     $UserSucursal = new UserSucursal([
                            'user_id'      => $userId,
                            'sucursal_id'  => $sucursal,
                        ]);
                    $UserSucursal->save();
                }
                break;
            case self::SCENARIO_UPDATE:
                // Recorremos todos las sucursales  del sistema
                foreach (Sucursal::getItems() as $sucursal_id => $sucursal_name) {
                    $UserSucursal = UserSucursal::find()->where(['user_id' => $userId, 'sucursal_id' => $sucursal_id])->one();

                    // Comprobamos si es necesario eliminar del usuario el perfil que pudiera asignar
                    if($UserSucursal && !in_array($sucursal_id, $this->sucursal_names)) {
                        $UserSucursal->delete();

                        // Guardamos un registro de los cambios
                        EsysCambiosLog::createExpressLog(self::tableName(), ['sucursalesAsignarString' => ['old' => $sucursal_name, 'dirty' => '**Removido**']], $this->id);
                    }

                    // Comprobamos si es necesario agregar el nuevo perfil que pudiera asignar el usuario
                    if(!$UserSucursal && in_array($sucursal_id, $this->sucursal_names)) {
                        $UserSucursal = new UserSucursal([
                            'user_id' => $userId,
                            'sucursal_id'  => $sucursal_id,
                        ]);

                        $UserSucursal->save();

                        // Guardamos un registro de los cambios
                        EsysCambiosLog::createExpressLog(self::tableName(), ['sucursalesAsignarString' => ['old' => $sucursal_name, 'dirty' => '**Agregado**']], $this->id);
                    }
                }
                break;
        }
    }

    public function savePerfiles()
    {
        $auth    = Yii::$app->authManager;
        $userId  = $this->getId();
        $newRole = $auth->getRole($this->item_name);

        switch ($this->scenario) {
            case self::SCENARIO_CREATE:
                // Asignamos el perfil del nuevo usuario
                $auth->assign($newRole, $userId);

                // Agregamos perfiles que pudiera asignar
                foreach ($this->perfiles_names as $key => $perfil) {
                    $UserAsignarPerfil = new UserAsignarPerfil([
                        'user_id' => $userId,
                        'perfil'  => $perfil,
                    ]);

                    $UserAsignarPerfil->save();
                }
                break;

            case self::SCENARIO_UPDATE:
                // Obtenemos perfil anterior del usuario
                $userRoles = $auth->getRolesByUser($userId);
                $oldRole   = empty($userRoles)? false: $auth->getRole(array_keys($userRoles)[0]);
                $oldRoleName = $oldRole? $oldRole->name: '';

                // Si cambios de perfil
                if($oldRoleName != $newRole->name){
                    if($oldRole)
                        $auth->revoke($oldRole, $userId);

                    $auth->assign($newRole, $userId);

                    // Guardamos un registro de los cambios
                    EsysCambiosLog::createExpressLog(self::tableName(), ['item_name' => ['old' => $oldRoleName, 'dirty' => $newRole->name]], $this->id);
                }


                // Recorremos todos los perfiles del sistema
                foreach ($auth->getRoles() as $perfil => $value) {
                    $UserAsignarPerfil = UserAsignarPerfil::find()->where(['user_id' => $userId, 'perfil' => $perfil])->one();

                    // Comprobamos si es necesario eliminar del usuario el perfil que pudiera asignar
                    if($UserAsignarPerfil && !in_array($perfil, $this->perfiles_names)) {
                        $UserAsignarPerfil->delete();

                        // Guardamos un registro de los cambios
                        EsysCambiosLog::createExpressLog(self::tableName(), ['perfilesAsignarString' => ['old' => $perfil, 'dirty' => '**Removido**']], $this->id);
                    }

                    // Comprobamos si es necesario agregar el nuevo perfil que pudiera asignar el usuario
                    if(!$UserAsignarPerfil && in_array($perfil, $this->perfiles_names)) {
                        $UserAsignarPerfil = new UserAsignarPerfil([
                            'user_id' => $userId,
                            'perfil'  => $perfil,
                        ]);

                        $UserAsignarPerfil->save();

                        // Guardamos un registro de los cambios
                        EsysCambiosLog::createExpressLog(self::tableName(), ['perfilesAsignarString' => ['old' => $perfil, 'dirty' => '**Agregado**']], $this->id);
                    }
                }
                break;
        }
    }


    public function getPadresFamilia()
    {
        $padresFamiliaArray = [];

        $Alumnos = Alumno::find()->andWhere(["nivel" => $this->nivel_id ])->andWhere([ "grado" => $this->grado_id ])->andWhere([ "status" => Alumno::STATUS_ACTIVE ])->all();
        if ($Alumnos) {
            foreach ($Alumnos as $key => $alumno) {
                $padresFamiliaArray[] = $alumno->cliente->id;
            }
        }
        return $padresFamiliaArray;
    }

    public function padresFamiliaLista($user_id = false )
    {
        if (Yii::$app->user->can('admin') && Yii::$app->user->identity->tipo != User::TIPO_MAESTRO && $user_id){
            $user = User::findOne($user_id);
            return Alumno::find()->andWhere(["nivel" => $user->nivel_id ])->andWhere([ "grado" => $user->grado_id ])->andWhere([ "status" => Alumno::STATUS_ACTIVE ])->all();
        }
        else
           return Alumno::find()->andWhere(["nivel" => $this->nivel_id ])->andWhere([ "grado" => $this->grado_id ])->andWhere([ "status" => Alumno::STATUS_ACTIVE ])->all();
    }

    public function setPerfilesAsignarNames()
    {
        $this->perfiles_names = [];

        foreach ($this->asignarPerfils as $key => $value) {
            $this->perfiles_names[] = $value->perfil;
        }
    }

    public function setSucursalesPermisosNames()
    {
        $this->sucursal_names = [];
        foreach ($this->asignarSucursals as $key => $value) {
            $this->sucursal_names[] = $value->sucursal_id;

        }
    }




    public static function getAvatar()
    {
        if(Yii::$app->user->identity){
            switch (Yii::$app->user->identity->sexo) {
                case 'hombre':
                    return '@web/img/profile-photos/2.png';
                    break;

                case 'mujer':
                    return '@web/img/profile-photos/8.png';
                    break;

                default:
                    return '@web/img/profile-photos/5.png';
                    break;
            }
        }
    }



    /**
     * @return JSON string
     */
    public static function getItems($params = [])
    {
        $params['id']   = array_key_exists('id', $params)? $params['id']: false;

        $query = Self::find()
            ->select([
                "id",
                new Expression("CONCAT_WS(' ', `nombre`, `apellidos`, CONCAT('[', `id`, ']')) as text"),
            ])
            ->asArray()
            ->orderBy('nombre, apellidos');

        if($params['id'])
            $query->where(['id' => $params['id']]);

        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return ArrayHelper::map($query->all(), 'id', 'text');
    }

    public static function getUserItems($params = [])
    {
        $params['id']   = array_key_exists('id', $params)? $params['id']: false;

        $query = Self::find()
            ->select([
                "id",
                new Expression("CONCAT_WS(' ', `nombre`, `apellidos`, CONCAT('[', `id`, ']')) as text"),
            ])
            ->asArray()
            ->orderBy('nombre, apellidos');

        if($params['id'])
            $query->where(['id' => $params['id']]);

        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        $query->andWhere(["tipo" => self::TIPO_USER]);

        return ArrayHelper::map($query->all(), 'id', 'text');
    }

    public static function getProfesorItems($params = [])
    {
        $params['id']   = array_key_exists('id', $params)? $params['id']: false;

        $query = Self::find()
            ->select([
                "id",
                new Expression("CONCAT_WS(' ', `nombre`, `apellidos`, CONCAT('[', `id`, ']')) as text"),
            ])
            ->asArray()
            ->orderBy('nombre, apellidos');

        if($params['id'])
            $query->where(['id' => $params['id']]);

            $query->andWhere(["tipo" => self::TIPO_MAESTRO]);

        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return ArrayHelper::map($query->all(), 'id', 'text');
    }




    public static function getSucursales($user_id = false)
    {
        if(!$user_id && isset(Yii::$app->user->identity))
            $user_id = Yii::$app->user->identity->id;

        $CatSucursales  = Sucursal::find()->select(["id", 'nombre'])->andWhere(['status' => Sucursal::STATUS_ACTIVE])->asArray()->all();
        $UserSucursal   = UserSucursal::find()->select(["sucursal_id"])->where(['user_id' => $user_id])->asArray()->all();
        $userSucursales = [];
        $sucursales     = [];

        foreach ($UserSucursal as $key => $value) {
            $userSucursales[] = $value['sucursal_id'];
        }

        foreach ($CatSucursales as $key => $sucursal) {
            if(/*Yii::$app->user->can('admin') ||*/ in_array($sucursal['id'], $userSucursales))
                $sucursales[$sucursal['id']] = $sucursal['nombre'];
        }

        return $sucursales;
    }


//------------------------------------------------------------------------------------------------//
// ACTIVE RECORD
//------------------------------------------------------------------------------------------------//
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->fecha_nac = Esys::stringToTimeUnix($this->fecha_nac);

            if ($insert) {
                $this->created_at = time();
                $this->created_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;

            }else{
                // Creamos objeto para log de cambios
                $this->CambiosLog = new EsysCambiosLog($this);

                // Remplazamos manualmente valores del log de cambios
                foreach($this->CambiosLog->getListArray() as $attribute => $value) {
                    switch ($attribute) {
                        case 'titulo_personal_id':
                        case 'departamento_id':
                        case 'grado_id':
                        case 'nivel_id':
                            if($value['old'])
                                $this->CambiosLog->updateValue($attribute, 'old', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['old']])->one()->singular);

                            if($value['dirty'])
                                $this->CambiosLog->updateValue($attribute, 'dirty', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['dirty']])->one()->singular);
                            break;

                        case 'fecha_nac':
                            if($value['old'])
                                $this->CambiosLog->updateValue($attribute, 'old', Esys::unixTimeToString($value['old']));

                            if($value['dirty'])
                                $this->CambiosLog->updateValue($attribute, 'dirty', Esys::unixTimeToString($value['dirty']));
                            break;

                        case 'status':
                            $this->CambiosLog->updateValue($attribute, 'old', self::$statusList[$value['old']] );
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$statusList[$value['dirty']]);
                            break;

                        case 'origen':
                            $this->CambiosLog->updateValue($attribute, 'old', isset(self::$origenList[$value['old']]) ? self::$origenList[$value['old']] : '');
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$origenList[$value['dirty']]);
                            break;

                        case 'api_enabled':
                            $this->CambiosLog->updateValue($attribute, 'old', isset(self::$statusApi[$value['old']]) ? self::$statusApi[$value['old']] : '');
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$statusApi[$value['dirty']]);
                            break;

                        case 'tipo':
                            $this->CambiosLog->updateValue($attribute, 'old', isset(self::$tipoList[$value['old']]) ? self::$tipoList[$value['old']] : '');
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$tipoList[$value['dirty']]);
                            break;


                        case 'password_hash':
                        case 'password_reset_token':
                        case 'account_activation_token':
                            $this->CambiosLog->updateValue($attribute, 'old', '********');
                            $this->CambiosLog->updateValue($attribute, 'dirty', '*********');
                            break;
                    }
                }

                // Quién y cuando
                $this->updated_at = time();
                $this->updated_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;
            }

            return true;

        } else
            return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {

        }else{
            // Guardamos un registro de los cambios
            $this->CambiosLog->createLog($this->id);
        }

        if(in_array($this->scenario, [self::SCENARIO_CREATE, self::SCENARIO_UPDATE])) {
            if($insert)
                $this->dir_obj->cuenta_id = $this->id;

           //Guardamos las Sucursales
            $this->saveSucursales();


            // Guardamos los Perfiles
            $this->savePerfiles();


            // Guardar dirección
            $this->dir_obj->save();
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $this->direccion->delete();

        foreach ($this->cambiosLog as $key => $value) {
           $value->delete();
        }
    }

}
