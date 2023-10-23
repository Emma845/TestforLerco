<?php
namespace app\models\alumno;

use Yii;
use app\models\Esys;
use yii\helpers\FileHelper;

use app\models\cliente\Cliente;
use app\models\user\User;
use yii\helpers\ArrayHelper;
use app\models\esys\EsysListaDesplegable;
use app\models\esys\EsysCambiosLog;
use app\models\esys\EsysCambioLog;
use app\models\file\FileUpload;
use app\models\ciclo\CicloTarifa;
/**
 * This is the model class for table "alumno".
 *
 * @property int $id ID
 * @property int $cliente_id Cliente ID
 * @property string $nombre Nombre
 * @property string $nota Nota
 * @property int $nivel Nivel
 * @property int $grado Grado
 * @property int $fecha_nacimiento Fecha Nacimiento
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 * @property string $hobbies Hobbies
 * @property string $deporte Deporte
 * 
 *
 * @property Cliente $cliente
 * @property User $createdBy
 * @property User $updatedBy
 * @property AlumnoDocumento[] $alumnoDocumentos
 * @property Credito[] $creditos
 */
class Alumno extends \yii\db\ActiveRecord
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
    public $hobbies;
    public $deporte;
    
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

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id', 'nombre', 'nivel', 'grado'], 'required'],
            [['hobbies','deporte'], 'string'],
            [['cliente_id', 'factura','cuenta_equipo_internet', 'sexo', 'nivel', 'grado', 'tipo_sangre', 'vive_con', 'talla', 'created_at', 'created_by', 'updated_at', 'updated_by','status','is_especial','colegiaturas_especial','ciclo_escolar_id'], 'integer'],
            [['nota','enfermedades_lesiones','antecedentes_enfermedades','discapacidad'], 'string'],
            [['peso','costo_colegiatura','costo_colegiatura','costo_colegiatura_especial'], 'number'],
            [['nombre','nombre_vive_con','apellidos','lugar_nacimiento'], 'string', 'max' => 150],
            [["fecha_nacimiento"], 'safe'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
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
            'cliente_id' => 'Cliente ID',
            'nombre' => 'Nombre',
            'nota' => 'Nota',
            'nivel' => 'Nivel',
            'grado' => 'Grado',
            'lugar_nacimiento' => 'Lugar de nacimiento',
            'talla' => 'Talla',
            'sexo' => ' Genero',
            'factura' => 'Factura',
            'cuenta_equipo_internet' => '¿Cuenta con equipo e internet para trabajar?',
            'enfermedades_lesiones' => 'Lesiones',
            'antecedentes_enfermedades' => 'Enfermedades',
            'discapacidad' => 'Discapacidad',
            'ciclo_escolar_id' => 'Ciclo escolar',
            'costo_colegiatura' => 'Costo colegiatura',
            'is_especial' => '¿ Especial ?',
            'colegiaturas_especial' => 'Colegiaturas especial',
            'costo_colegiatura_especial' => 'Costo colegiatura especial',
            'status' => 'Estatus',
            'cicloEscolar.singular' => 'Ciclo escolar',
            'tipo_sangre' => 'Tipo de sangre',
            'vive_con' => 'Vive con',
            'nombre_vive_con' => 'Nombre',
            'peso' => 'Peso',
            'tipoSangreText.singular' => 'Tipo de sangre',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'hobbies' => 'Hobbies',
            'deporte'=>'Deporte',
            'parametro_nuevo1' => 'parametro_nuevo1',
        ];
    }

    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }
    public function getparametro_nuevo1()
    {
        return $this->parametro_nuevo1;
    }

    public function getHobbies()
    {
        return $this->hobbies;
    }
    public function getDeporte()
    {
        return $this->deporte;
    }
    

    public function getEdad()
    {
        list($Y,$m,$d) = explode("-",date('Y-m-d',$this->fecha_nacimiento));
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }

    public static function getDataAlumno($id_padre)
    {
        $array_h =[]; 
        $hijos = Alumno::find()->select(['id','ciclo_escolar_id','nivel', 'grado','nombre','apellidos','Hobbies','Deporte'])->where(['=', 'cliente_id',$id_padre])->all(); //obtengo los datos del alumno
       
        $response_array = []; 
        foreach($hijos as $key => $hijo)
        { 
            $array_h[$key] = [
                'id' => $hijo->id,
                'id_ciclo' => $hijo->ciclo_escolar_id, 
                'nombre' => $hijo->nombre, 
                'apellidos' => $hijo->apellidos, 
                'nivel' =>  Alumno::NIVEL_PREESCOLAR,
                'grado' => $hijo->grado,
            ];
            $response_array[$key] = $array_h[$key];
        }
        return $response_array;
    }
    public static function getDataAlumnoInformation($id_alumno,$ciclo = null)
    {   
        $array_h =[]; 
        $array_c =[];
        $response_array = [];

        $hijo = Alumno::find()->select(['id','ciclo_escolar_id','nivel', 'grado','nombre','apellidos','is_especial','colegiaturas_especial','costo_colegiatura_especial'])->where(['=', 'id',$id_alumno])->one(); //obtengo los datos del alumno
       
        if($ciclo != null){ 

            $ciclo_data = CicloTarifa::find()->select(['ciclo_id','nivel','inscripcion', 'colegiatura'])->where(['=', 'ciclo_id',$ciclo])->all(); //obtengo las tarifas asignadas en el ciclo seleccionado
            $count = 0;
            foreach($ciclo_data as $key2 => $ciclos)
            {
                $array_c[$count] = $ciclos;
                $count++;
                
            }
        }else {
            
            
            $ciclo_data = CicloTarifa::find()->select(['ciclo_id','nivel','inscripcion', 'colegiatura'])->where(['=', 'ciclo_id',$hijo->ciclo_escolar_id])->all(); //obtengo las tarifas asignadas en ciclos escolares
            $count = 0;
                foreach($ciclo_data as $key2 => $ciclo)
                {
                    if($ciclo->ciclo_id == $hijo->ciclo_escolar_id)
                    {
                    $array_c[$count] = $ciclo;
                    $count++;
                    }
                }
        }
           

            $array_h = [
                'id' => $hijo->id,
                'id_ciclo' => $hijo->ciclo_escolar_id, 
                'nombre' => $hijo->nombre, 
                'apellidos' => $hijo->apellidos,
                'es_especial' => $hijo->is_especial,
                'colegiaturas_especiales' => $hijo->colegiaturas_especial,
                'costo_especial' => $hijo->costo_colegiatura_especial, 
                'nivel' =>  Alumno::NIVEL_PREESCOLAR, 
                'grado' => $hijo->grado, 
                'tarifa' => $array_c,

            ];

            $response_array = $array_h;
        
        return $response_array;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    public function getNivelText()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'nivel']);
    }

    public function getCicloEscolar()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'ciclo_escolar_id']);
    }

    public function getGradoText()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'grado']);
    }

    public function getTipoSangreText()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'tipo_sangre']);
    }

    public function getViveConText()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'vive_con']);
    }

    public function getCambiosLog()
    {
        return EsysCambioLog::find()
            ->andWhere(['or',
                ['modulo' => $this->tableName(), 'idx' => $this->id],
            ])
            ->all();
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
    public function getAlumnoDocumentos()
    {
        return $this->hasMany(AlumnoDocumento::className(), ['alumno_id' => 'id']);
    }

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

                // Creamos objeto para log de cambios
                $this->CambiosLog = new EsysCambiosLog($this);

                // Remplazamos manualmente valores del log de cambios
                foreach($this->CambiosLog->getListArray() as $attribute => $value) {
                    switch ($attribute) {
                        case 'nivel':
                        case 'grado':
                        case 'tipo_sangre':
                            if($value['old'])
                                $this->CambiosLog->updateValue($attribute, 'old', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['old']])->one()->singular);

                            if($value['dirty'])
                                $this->CambiosLog->updateValue($attribute, 'dirty', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['dirty']])->one()->singular);
                            break;

                            case 'vive_con':
                                if($value['old'])
                                    $this->CambiosLog->updateValue($attribute, 'old', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['old']])->one()->singular);
    
                                if($value['dirty'])
                                    $this->CambiosLog->updateValue($attribute, 'dirty', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['dirty']])->one()->singular);
                                break;

                        case 'fecha_nacimiento':
                            if($value['old'])
                                $this->CambiosLog->updateValue($attribute, 'old', Esys::unixTimeToString($value['old']));

                            if($value['dirty'])
                                $this->CambiosLog->updateValue($attribute, 'dirty', Esys::unixTimeToString($value['dirty']));
                            break;

                        case 'sexo':
                            $this->CambiosLog->updateValue($attribute, 'old',  isset(self::$sexoList[$value['old']]) ? self::$sexoList[$value['old']]:'');

                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$sexoList[$value['dirty']]);
                            break;

                            case 'cuenta_equipo_internet':
                                $this->CambiosLog->updateValue($attribute, 'old',  isset(self::$equipoList[$value['old']]) ? self::$equipoList[$value['old']]:'');
    
                                $this->CambiosLog->updateValue($attribute, 'dirty', self::$equipoList[$value['dirty']]);
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

        if(!$insert)
            // Guardamos un registro de los cambios
            $this->CambiosLog->createLog($this->id);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->cambiosLog as $key => $value) {
           $value->delete();
        }
    }
}
