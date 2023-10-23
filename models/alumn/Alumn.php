<?php
namespace app\models\alumn;

use Yii;
use app\models\cliente\Cliente;
use app\models\user\User;
use yii\helpers\ArrayHelper;
use app\models\file\FileUpload;
use app\models\ciclo\Ciclo;
use app\models\ciclo\CicloTarifa;

/**
 * This is the model class for table "alumno".
 *
 * @property int $id ID
 * @property int $id_padre ID padre
 * @property int $ciclo_escolar ciclo escolar
 * @property string $nombre Nombre
 * @property string $apellido apellido
 * @property int $sexo sexo
 * @property int $nivel Nivel
 * @property int $grado Grado
 * @property int $fecha_nacimiento Fecha Nacimiento
 * @property int $tipo_sangre tipo_sangre
 * @property int $internet internet
 * @property int $status
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property Cliente $cliente
 * @property Ciclo $ciclo
 * @property User $createdBy
 * @property User $updatedBy
 * @property AlumnoDocumento[] $alumnoDocumentos
 * @property Credito[] $creditos
 */
class Alumn extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE   = 10;
    const STATUS_BAJA     = 20;
    const STATUS_INACTIVE = 1;

    const SEXO_HOMBRE = 10;
    const SEXO_MUJER = 20;

    const SI_EQUIPO = 10;
    const NO_EQUIPO = 20;

    const NIVEL_PREESCOLAR = 10;
    const NIVEL_PRIMARIA   = 20;
    const NIVEL_SECUNDARIA = 30;

    public static $nivel = [
        self::NIVEL_PREESCOLAR => 'PREESCOLAR',
        self::NIVEL_PRIMARIA   => 'PRIMARIA',
        self::NIVEL_SECUNDARIA => 'SECUNDARIA',
    ];
    

    public static $equipoList = [
        self::SI_EQUIPO   => 'Sí',
        self::NO_EQUIPO => 'No',
    ];

    public static $sexoList = [
        self::SEXO_HOMBRE   => 'Hombre',
        self::SEXO_MUJER => 'Mujer',
    ];

    public static $statusList = [
        self::STATUS_ACTIVE     => 'Habilitado',
        self::STATUS_BAJA       => 'Baja',
        self::STATUS_INACTIVE   => 'Inhabilitado',
        //self::STATUS_DELETED  => 'Eliminado'
    ];


    public static $statusAlertList = [
        self::STATUS_ACTIVE     => 'panel-success',
        self::STATUS_BAJA       => 'panel-danger',
        self::STATUS_INACTIVE   => 'panel-warning',
        //self::STATUS_DELETED  => 'Eliminado'
    ];

    private $CambiosLog;


    public $file_expediente;


    public static function tableName()
    {
        return 'alumn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_padre','ciclo_escolar', 'nombre', 'nivel', 'grado'], 'required'],
            [['cliente_id', 'factura','cuenta_equipo_internet', 'sexo', 'nivel', 'grado', 'tipo_sangre', 'vive_con', 'talla', 'created_at', 'created_by', 'updated_at', 'updated_by','status','is_especial','colegiaturas_especial','ciclo_escolar_id'], 'integer'],
            [['nombre','apellido','enfermedades_lesiones'], 'string'],
            [['peso','costo_colegiatura','costo_colegiatura','costo_colegiatura_especial'], 'number'],
            [["fecha_nacimiento"], 'safe'],
            [['id_padre'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_padre' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ciclo_escolar' => 'ciclo_escolar',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'sexo' => 'Genero',
            'nivel' => 'Nivel',
            'grado' => 'Grado',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'tipo_sangre' => 'Tipo de sangre',
            'enfermedades_lesiones' => 'enfermedades o lesiones?',
            'internet' => 'Cuenta con internet?',
            'status' => 'Estatus',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    public function getEdad()
    {
        list($Y,$m,$d) = explode("-",date('Y-m-d',$this->fecha_nacimiento));
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_padre']);
    }


    public static function getDataCiclo($id)
    {
        $ciclo = Ciclo::findOne($id);
        return $ciclo;
    }

    public static function getDataAlumno($id_padre)
    {
        $array_h =[]; 
        $hijos = Alumn::find()->select(['id','ciclo_escolar','nivel', 'grado','nombre','apellido'])->where(['=', 'id_padre',$id_padre])->all(); //obtengo los datos del alumno
        $response_array = []; 
        foreach($hijos as $key => $hijo)
        { 
            $array_h[$key] = [
                'id' => $hijo->id,
                'id_ciclo' => $hijo->ciclo_escolar, //1
                'nombre' => $hijo->nombre, //leonardo jr
                'apellido' => $hijo->apellido, //luna
                'nivel' =>  Alumn::NIVEL_PREESCOLAR, //10
                'grado' => $hijo->grado, //10
            ];
            $response_array[$key] = $array_h[$key];
        }
        return $response_array;
    }

    public static function getDataAlumnoInformation($id_alumno)
    {   
        $array_h =[]; 
        $array_c =[];
        $response_array = [];

        $hijo = Alumn::find()->select(['id','ciclo_escolar','nivel', 'grado','nombre','apellido'])->where(['=', 'id',$id_alumno])->one(); //obtengo los datos del alumno
        $ciclo_data = CicloTarifa::find()->select(['ciclo_id','nivel','inscripcion', 'colegiatura', 'mora'])->where(['=', 'ciclo_id',$hijo->ciclo_escolar])->all(); //obtengo las tarifas asignadas en ciclos escolares
            $count = 0;
            foreach($ciclo_data as $key2 => $ciclo)
            {
                if($ciclo->ciclo_id == $hijo->ciclo_escolar)
                {
                 $array_c[$count] = $ciclo;
                 $count++;
                }
            }

            $array_h = [
                'id' => $hijo->id,
                'id_ciclo' => $hijo->ciclo_escolar, //1
                'nombre' => $hijo->nombre, //leonardo jr
                'apellido' => $hijo->apellido, //luna
                'nivel' =>  Alumn::NIVEL_PREESCOLAR, //10
                'grado' => $hijo->grado, //10
                'tarifa' => $array_c
            ];

            $response_array = $array_h;
        
        return $response_array;
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public static function getPadreFamiliaNivel($nivel){
        $query = Self::find()
            ->select([
                "alumno.cliente_id",
                "alumno.nombre",
            ])
            ->asArray();

        if($nivel)
            $query->where(['nivel' => $nivel ]);

        $query->andWhere([ "status" => self::STATUS_ACTIVE ]);
        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return ArrayHelper::map($query->all(), 'cliente_id', 'nombre');
    }

    public static function getPadreFamiliaGrado($grado){
        $query = Self::find()
            ->select([
                "alumno.cliente_id",
                "alumno.nombre",
            ])
            ->asArray();

        if($grado)
            $query->where(['grado' => $grado ]);

        $query->andWhere([ "status" => self::STATUS_ACTIVE ]);

        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return ArrayHelper::map($query->all(), 'cliente_id', 'nombre');
    }

    public static function getPadreFamiliaGradoNivel($grado,$nivel){
        $query = Self::find()
            ->select([
                "alumno.cliente_id",
                "alumno.nombre",
            ])
            ->asArray();

        if($grado && $nivel)
            $query->where(["and",['nivel' => $nivel ],['grado' => $grado ]]);

        $query->andWhere([ "status" => self::STATUS_ACTIVE ]);

        //echo ($query->createCommand()->rawSql) . '<br/><br/>';

        return ArrayHelper::map($query->all(), 'cliente_id', 'nombre');
    }


    public function uploadFiles($pertenece_id, $is_expira = false, $fecha = null)
    {
        /*************************************************************************************
                                GUARDAMOS LA IMAGEN CON SU EXTENSION
        /************************************************************************************/
        $name = "upload_". Yii::$app->user->identity->id  ."_". Yii::$app->security->generateRandomString();

        if ($this->file_expediente->saveAs('alumnos/' . $name . '.' . $this->file_expediente->extension)) {

            $FileUpload = new FileUpload();
            $FileUpload->alumno_id         = $this->id;
            $FileUpload->url_file       = $name;
            $FileUpload->title_original = $this->file_expediente->name;
            $FileUpload->type_file      = $this->file_expediente->type;
            $FileUpload->pertenece_id   = $pertenece_id;
            $FileUpload->tipo           = FileUpload::TIPO_ALUMNO;
            $FileUpload->expira         = $is_expira ? $fecha : null;
            $FileUpload->save();

        }
        return true;
    }


//------------------------------------------------------------------------------------------------//
// ACTIVE RECORD
//------------------------------------------------------------------------------------------------//
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->fecha_nacimiento = $this->fecha_nacimiento && !is_numeric($this->fecha_nacimiento) ? strtotime($this->fecha_nacimiento) : $this->fecha_nacimiento;
            if ($insert) {
                $this->created_at = time();
                $this->created_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;

            }else{

                // Quién y cuando
                $this->updated_at = time();
                $this->updated_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;
            }
            return true;

        } else
            return false;
    }
}
