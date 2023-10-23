<?php

namespace app\models\file;

use Yii;
use app\models\user\User;
use app\models\alumno\Alumno;
use app\models\cliente\Cliente;
use app\models\esys\EsysListaDesplegable;
use app\models\Esys;


/**
 * This is the model class for table "file_check".
 *
 * @property int $id ID
 * @property int $alumno_id Alumno
 * @property int $tutor_id Tutor
 * @property int $tipo Tipo
 * @property int $pertenece_id Pertenece
 * @property int $expira Expira
 * @property int $created_at Creado
 * @property int $created_by Creado por
 *
 * @property Alumno $alumno
 * @property User $createdBy
 * @property EsysListaDesplegable $pertenece
 * @property Cliente $tutor
 */
class FileCheck extends \yii\db\ActiveRecord
{
    const TIPO_ALUMNO    = 10;
    const TIPO_TUTOR     = 20;

    public static $tipoList = [
        self::TIPO_ALUMNO   => 'ALUMNO',
        self::TIPO_TUTOR    => 'TUTOR',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_check';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alumno_id', 'tutor_id', 'tipo', 'pertenece_id', 'expira', 'created_at', 'created_by'], 'integer'],
            [['tipo'], 'required'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['pertenece_id'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['pertenece_id' => 'id']],
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
            'tipo' => 'Tipo',
            'pertenece_id' => 'Documento',
            'expira' => 'Expira',
            'created_at' => 'Creado el',
            'created_by' => 'Creado por',
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

    public static function getFilesTutor($tutor_id, $pertenece_id)
    {
        return self::find()->andWhere([ "and",
            ["=", "tutor_id",  $tutor_id],
            ["=", "tipo",  FileCheck::TIPO_TUTOR],
            ["=", "pertenece_id",  $pertenece_id],
        ])->one();
    }

    public static function getFilesAlumno($alumno_id, $pertenece_id)
    {
        return self::find()->andWhere([ "and",
            ["=", "alumno_id",  $alumno_id],
            ["=", "tipo",  FileCheck::TIPO_ALUMNO],
            ["=", "pertenece_id",  $pertenece_id],
        ])->one();
    }

    public static function getAllFilesTutor($tutor_id)
    {
        return self::find()->andWhere([ "and",
            ["=", "tutor_id",  $tutor_id],
            ["=", "tipo",  FileCheck::TIPO_TUTOR],
        ])->all();
    }

    public static function getAllFilesAlumno($alumno_id)
    {
        return self::find()->andWhere([ "and",
            ["=", "alumno_id",  $alumno_id],
            ["=", "tipo",  FileCheck::TIPO_ALUMNO],
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
}
