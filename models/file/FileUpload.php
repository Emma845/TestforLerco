<?php
namespace app\models\file;

use Yii;
use app\models\user\User;
use yii\helpers\FileHelper;
use app\models\alumno\Alumno;
use app\models\cliente\Cliente;
use app\models\esys\EsysListaDesplegable;

/**
 * This is the model class for table "file_upload".
 *
 * @property int $id ID
 * @property int $alumno_id Alumno
 * @property int $tutor_id Tutor
 * @property string $url_file Url file
 * @property string $title_original Titulo original
 * @property int $tipo Tipo
 * @property string $type_file Type
 * @property int $expira Expira
 * @property int $created_at Creado
 * @property int $created_by Creado por
 *
 * @property Alumno $alumno
 * @property User $createdBy
 * @property Cliente $tutor
 */
class FileUpload extends \yii\db\ActiveRecord
{
    const TIPO_ALUMNO    = 10;
    const TIPO_TUTOR     = 20;


     public static $tipoList = [
        self::TIPO_ALUMNO   => 'ALUMNO',
        self::TIPO_TUTOR    => 'TUTOR',
    ];



    public static $mimeType = [
         'image/png' => 'png',
         'image/jpeg' => 'jpe',
         'image/jpeg' => 'jpeg',
         'image/jpeg' => 'jpg',
         'image/gif' => 'gif',
         'application/pdf' => 'pdf',
         'application/vnd.ms-excel' => 'xls',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_upload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alumno_id', 'tutor_id', 'tipo', 'pertenece_id','expira', 'created_at', 'created_by'], 'integer'],
            [['url_file', 'tipo'], 'required'],
            [['title_original'], 'string'],
            [['url_file'], 'string', 'max' => 150],
            [['type_file'], 'string', 'max' => 50],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['pertenece_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['pertenece_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['tutor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['tutor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alumno_id' => 'Alumno ID',
            'tutor_id' => 'Tutor ID',
            'url_file' => 'Url File',
            'title_original' => 'Title Original',
            'tipo' => 'Tipo',
            'pertenece_id' => 'Pertenece ID',
            'type_file' => 'Type File',
            'expira' => 'Expira',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumno()
    {
        return $this->hasOne(Alumno::className(), ['id' => 'alumno_id']);
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
    public function getPertenece()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'pertenece_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTutor()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'tutor_id']);
    }


    public static function getFileAlumno($alumno_id, $pertenece_id)
    {
        return self::find()->andWhere([ "and",
            ["=", "alumno_id",  $alumno_id],
            ["=", "tipo",  FileUpload::TIPO_ALUMNO],
            ["=", "pertenece_id",  $pertenece_id],
        ])->all();
    }

    public static function getFileTutor($tutor_id, $pertenece_id)
    {
        return self::find()->andWhere([ "and",
            ["=", "tutor_id",  $tutor_id],
            ["=", "tipo",  FileUpload::TIPO_TUTOR],
            ["=", "pertenece_id",  $pertenece_id],
        ])->all();
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

            }

            return true;

        } else
            return false;
    }


    public function afterDelete()
    {
        parent::afterDelete();

        $fileRemove = $this->url_file .".". FileUpload::$mimeType[$this->type_file];

        $dirTemp = Yii::getAlias('@app') . '/web/alumnos/';
        $remove = FileHelper::findFiles($dirTemp,['only'=>[$fileRemove]]);
        foreach ($remove as $key => $item) {
            unlink($item);
        }
    }
}
