<?php

namespace app\models\cliente;

use Yii;
use yii\db\Expression;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\models\user\User;
use app\models\documento\Documento;
use app\models\esys\EsysDireccion;
use app\models\esys\EsysListaDesplegable;
use app\models\esys\EsysDireccionCodigoPostal;
use app\models\esys\EsysCambiosLog;
use app\models\esys\EsysCambioLog;
use app\models\alumno\Alumno;
use app\models\alumno\AlumnoDocumento;
use app\models\file\FileUpload;

/**
 * This is the model class for table "cliente".
 *
 * @property int $id ID
 * @property string $nombre Nombre
 * @property string $apellidos Apellidos
 * @property string $email Email
 * @property int $sexo Sexo
 * @property string $telefono Telefono
 * @property string $movil Movil
 * @property int $status Estatus
 * @property string $notas Comentario / Observaciones
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Cliente extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE          = 'create';
    const SCENARIO_UPDATE          = 'update';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';

    const STATUS_ACTIVE   = 10;
    const STATUS_INACTIVE = 1;

    const ORIGEN_USA = 1;
    const ORIGEN_MX = 2;

    const SEXO_HOMBRE = 10;
    const SEXO_MUJER = 20;

    const SERVICIO_MEX = 1;
    const SERVICIO_LAX = 2;
    const SERVICIO_TIERRA = 3;

    public static $statusList = [
        self::STATUS_ACTIVE   => 'Habilitado',
        self::STATUS_INACTIVE => 'Inhabilitado',
        //self::STATUS_DELETED  => 'Eliminado'
    ];

    public static $sexoList = [
        self::SEXO_HOMBRE   => 'Hombre',
        self::SEXO_MUJER => 'Mujer',
    ];

    public static $origenList = [
        self::ORIGEN_MX   => 'México',
        self::ORIGEN_USA  => 'United States',
    ];

    public static $servicioList = [
        self::SERVICIO_MEX     => 'Servicio Méx',
        self::SERVICIO_LAX     => 'Servicio Lax',
        self::SERVICIO_TIERRA  => 'Servicio Tierra',
    ];


    public $dir_obj;
    public $cliente_call;

    public $csv_file;
    public $rows_details = [];

    private $num_rows = 0;
    private $csv_column_name = [];

    private $CambiosLog;

    public $cliente_documento  = [];

    public $file_expediente;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[], 'required', 'on' => self::SCENARIO_UPDATE],
            [[], 'required', 'on' => self::SCENARIO_CREATE],
            [['sexo', 'parentesco','atraves_de_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by','titulo_personal_id','asignado_id','tipo_cliente_id'], 'integer'],
            [['notas'], 'string'],
            [['nombre'],'required'],

            [['nombre', 'apellidos', 'rfc'], 'string', 'max' => 150],
            [['email'], 'string', 'max' => 50],
            [['cliente_documento'], 'each', 'rule' => ['string']],
            [['telefono', 'telefono_movil', 'whatsapp'], 'string', 'max' => 20],
            [['email'], 'unique'],

            [['asignado_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['asignado_id' => 'id']],
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
            'titulo_personal_id'=> 'Titulo personal',
            'atraves_de_id'=> 'Se entero a través de',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'email' => 'Email',
            'sexo' => 'Sexo',
            'parentesco' => 'Parentesco',
            'telefono' => 'Telefono Casa',
            'rfc' => 'RCF',
            'movil' => 'Movil',
            'whatsapp' => 'WhatsApp',
            'telefono_movil' => 'Movil Movil',
            'status' => 'Estatus',
            'notas' => 'Comentario / Observaciones',
            'tituloPersonal.singular' => 'Titulo personal',
            'asignado_id' => 'Asignado a :',
            'tipo_cliente_id' => 'Tipo de cliente',
            'created_at' => 'Creado',
            'documentoAsignarString' => 'Documentos asignado',
            'created_by' => 'Creado por',
            'updated_at' => 'Modificado',
            'updated_by' => 'Modificado por',
            'csv_file' => 'Examinar CSV',
        ];
    }

    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
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
    public function getAsignadoCliente()
    {
        return $this->hasOne(User::className(), ['id' => 'asignado_id']);
    }

    public function getTituloPersonal()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'titulo_personal_id']);
    }
    public function getAtravesDe()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'atraves_de_id']);
    }

    public function getAsignarDocumento()
    {
        return $this->hasMany(ClienteDocumento::className(), ['cliente_id' => 'id']);
    }

    public function getParentescoText()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'parentesco']);
    }

    public function getAlumno()
    {
        if (Yii::$app->user->identity->tipo == User::TIPO_MAESTRO) {

            $maestro = User::findOne(Yii::$app->user->id);
            return $this->hasMany(Alumno::className(), ['cliente_id' => 'id'])
                                ->andWhere(["nivel" => $maestro->nivel_id ])
                                ->andWhere([ "grado" => $maestro->grado_id ]);
        }else
            return $this->hasMany(Alumno::className(), ['cliente_id' => 'id']);
    }

    public function getDireccion()
    {
        return $this->hasOne(EsysDireccion::className(), ['cuenta_id' => 'id'])
            ->where(['cuenta' => EsysDireccion::CUENTA_CLIENTE, 'tipo' => EsysDireccion::TIPO_PERSONAL]);
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

    public function getDocumentoAsignar()
    {
        return $this->hasMany(Documento::className(), ['id' => 'documento_id'])
            ->viaTable('cliente_documento', ['cliente_id' => 'id']);
    }

    public function getTipo()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'tipo_cliente_id']);
    }

    public function getDocumentoAsignarString()
    {
        $documentos_string = [];

        foreach ($this->documentoAsignar as $key => $value) {
            $documentos_string[] = $value->nombre;
        }

        return implode(', ', $documentos_string);
    }

    public function setDocumentoAsignarNames()
    {
        $this->cliente_documento = [];
        foreach ($this->asignarDocumento as $key => $value) {
            $this->cliente_documento[] = $value->documento_id;
        }
    }
    /*
    public function saveAsignacionDocumentos()
    {
        switch ($this->scenario) {
            case self::SCENARIO_CREATE:
                // Agregamos sucursales que pudiera asiganar
                foreach ($this->cliente_documento as $key => $documento) {
                     $ClienteDocumento = new ClienteDocumento([
                            'cliente_id'      => $this->id,
                            'documento_id'  => $documento,
                        ]);
                    $ClienteDocumento->save();
                }
            break;
            case self::SCENARIO_UPDATE:
                // Recorremos todos las sucursales  del sistema
                foreach (User::getItemAll() as $user_id => $user_name) {
                    $ClienteDocumento = ClienteDocumento::find()->where(['cliente_id' => $this->id, 'documento_id' => $user_id])->one();

                    // Comprobamos si es necesario eliminar del usuario el perfil que pudiera asignar
                    if($ClienteDocumento && !in_array($user_id, $this->cliente_documento)) {
                        $ClienteDocumento->delete();

                        // Guardamos un registro de los cambios
                        EsysCambiosLog::createExpressLog(self::tableName(), ['documentoAsignarString' => ['old' => $user_name, 'dirty' => '**Removido**']], $this->id);
                    }

                    // Comprobamos si es necesario agregar el nuevo perfil que pudiera asignar el usuario
                    if(!$ClienteDocumento && in_array($user_id, $this->cliente_documento)) {
                        $ClienteDocumento = new ClienteDocumento([
                            'cliente_id' => $this->id,
                            'documento_id'  => $user_id,
                        ]);

                        $ClienteDocumento->save();

                        // Guardamos un registro de los cambios
                        EsysCambiosLog::createExpressLog(self::tableName(), ['documentoAsignarString' => ['old' => $user_name, 'dirty' => '**Agregado**']], $this->id);
                    }
                }
            break;
        }
    }
    */

    /**
     * @return JSON string
     */
    public static function getAsiganadoA()
    {
        $query = User::find()
            ->select('id,  nombre, apellidos')
            ->andWhere([
               'tipo' => User::TIPO_AGENTE
            ])
            ->orderBy('id asc');

        return ArrayHelper::map($query->all(), 'id', function($value){
            return '['.$value->id.'] '.$value->nombre .' '.$value->apellidos;
        });
    }

    public function uploadFiles($pertenece_id, $is_expira = false, $fecha = null)
    {
        /*************************************************************************************
                                GUARDAMOS LA IMAGEN CON SU EXTENSION
        /************************************************************************************/
        $name = "upload_". Yii::$app->user->identity->id  ."_". Yii::$app->security->generateRandomString();

        if ($this->file_expediente->saveAs('tutores/' . $name . '.' . $this->file_expediente->extension)) {

            $FileUpload = new FileUpload();
            $FileUpload->tutor_id         = $this->id;
            $FileUpload->url_file       = $name;
            $FileUpload->title_original = $this->file_expediente->name;
            $FileUpload->type_file      = $this->file_expediente->type;
            $FileUpload->pertenece_id   = $pertenece_id;
            $FileUpload->tipo           = FileUpload::TIPO_TUTOR;
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
                        case 'atraves_de_id':
                        case 'medio_contacto_id':
                        case 'status_venta_id':
                        case 'comportamiento_id':
                        case 'tipo_cliente_id':
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
                            $this->CambiosLog->updateValue($attribute, 'old', self::$statusList[$value['old']]);
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$statusList[$value['dirty']]);
                            break;

                        case 'sexo':
                            $this->CambiosLog->updateValue($attribute, 'old',  isset(self::$sexoList[$value['old']]) ? self::$sexoList[$value['old']]:'');

                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$sexoList[$value['dirty']]);
                            break;

                        case 'origen':
                            $this->CambiosLog->updateValue($attribute, 'old', isset(self::$origenList[$value['old']]) ? self::$origenList[$value['old']] :'');
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$origenList[$value['dirty']]);
                            break;

                        case 'servicio_preferente':
                            $this->CambiosLog->updateValue($attribute, 'old', isset(self::$servicioList[$value['old']]) ? self::$servicioList[$value['old']]:'');
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$servicioList[$value['dirty']]);
                            break;

                            case 'parentesco':
                                if($value['old'])
                                    $this->CambiosLog->updateValue($attribute, 'old', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['old']])->one()->singular);
    
                                if($value['dirty'])
                                    $this->CambiosLog->updateValue($attribute, 'dirty', EsysListaDesplegable::find()->select(['singular'])->where(['id' => $value['dirty']])->one()->singular);
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
        if($insert)
            $this->dir_obj->cuenta_id = $this->id;
        else
            // Guardamos un registro de los cambios
            $this->CambiosLog->createLog($this->id);

        if ($this->scenario == self::SCENARIO_UPDATE ) {
            if ($this->status == self::STATUS_INACTIVE) {
                $alumno = Alumno::find()->andWhere(["cliente_id" => $this->id])->all();
                foreach ($alumno as $key => $item) {
                    $item->status = Alumno::STATUS_INACTIVE;
                    $item->save();
                }
            }elseif ($this->status == self::STATUS_ACTIVE){
                $alumno = Alumno::find()->andWhere(["cliente_id" => $this->id])->all();
                foreach ($alumno as $key => $item) {
                    $item->status = Alumno::STATUS_ACTIVE;
                    $item->save();
                }
            }
        }

        //$this->saveAsignacionDocumentos();

            // Guardar dirección
        $this->dir_obj->save();
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
