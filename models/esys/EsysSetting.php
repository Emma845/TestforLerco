<?php

namespace app\models\esys;

use Yii;
use app\models\user\User;
use app\models\envio\Envio;
/**
 * This is the model class for table "esys_setting".
 *
 * @property int $cliente_id Cliente
 * @property string $clave Clave
 * @property string $valor Valor
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class EsysSetting extends \yii\db\ActiveRecord
{

    const SITE_NAME     = "SITE_NAME";
    const SITE_EMAIL    = "SITE_EMAIL";
    const RFC_SITIO     = "RFC_SITIO";
    const CER_NAME     = "CER_NAME";
    const KEY_NAME     = "KEY_NAME";

    const FECHA_POLIZA  = "FECHA_POLIZA";
    const NUM_POLIZA    = "NUM_POLIZA";

    public $file_key;
    public $file_cer;







    public $esysSetting_list = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'esys_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],

            [['clave'], 'string', 'max' => 40],
            [['param1','param2'], 'string', 'max' => 20],
            [['valor'], 'string', 'max' => 250],
            [['clave'], 'unique'],
            [['file_key'], 'file'],
            [['file_cer'], 'file'],
            [['esysSetting_list'], 'safe'],
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
            'cliente_id' => 'Cliente ID',
            'clave' => 'Clave',
            'valor' => 'Valor',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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

    public  function getConfiguracionAll()
    {
        return  EsysSetting::find()->orderBy('orden asc')->all();
    }

    public static function getNumPoliza(){
        return EsysSetting::findOne(["clave" => self::NUM_POLIZA])->valor;
    }

    public static function getFechaPoliza(){
        return EsysSetting::findOne(["clave" => self::FECHA_POLIZA])->valor;
    }

    public static function getCer(){
        return EsysSetting::findOne(["clave" => self::CER_NAME])->valor;
    }

    public static function getRfc(){
        return EsysSetting::findOne(["clave" => self::RFC_SITIO])->valor;
    }

    public static function getKey(){
        return EsysSetting::findOne(["clave" => self::KEY_NAME])->valor;
    }

    public function saveConfiguracion($esysSetting_list)
    {
        foreach ($esysSetting_list["esysSetting_list"] as $key => $item) {
            $EsysSetting = EsysSetting::findOne(["clave" => $key]);
            $EsysSetting->valor = $item;
            $EsysSetting->update();
        }
    }

    public function uploadCer()
    {
        if ($this->validate()) {
            $this->file_cer->saveAs('cfdi/' . $this->file_cer->baseName . '.' . $this->file_cer->extension);
            return true;
        } else {
            return false;
        }
    }
    public function uploadKey()
    {
        if ($this->validate()) {
            $this->file_key->saveAs('cfdi/' . $this->file_key->baseName . '.' . $this->file_key->extension);
            return true;
        } else {
            return false;
        }
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

                // Quién y cuando
                $this->updated_at = time();
                $this->updated_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;
            }

            return true;

        } else
            return false;
    }

}
