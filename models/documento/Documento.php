<?php
namespace app\models\documento;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\esys\EsysListaDesplegable;
use app\models\user\User;

/**
 * This is the model class for table "documento".
 *
 * @property int $id ID
 * @property string $nombre Nombre
 * @property int $compete Compete
 * @property int $documento_partida Documento Partida
 * @property int $periodicidad Periodicidad
 * @property int $plazo_adicional Plazo adicional
 * @property int $bloqueo Bloqueo
 * @property int $aplica Aplica
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property EsysListaDesplegable $aplica0
 * @property EsysListaDesplegable $compete0
 * @property User $createdBy
 * @property EsysListaDesplegable $documentoPartida
 * @property EsysListaDesplegable $periodicidad0
 * @property User $updatedBy
 */
class Documento extends \yii\db\ActiveRecord
{

    const BLOQUEO_SI = 10;
    const BLOQUEO_NO = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'update'], 'required'],
            [['tipo', 'update',  'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['nombre'], 'string', 'max' => 200],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['tipo' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['update'], 'exist', 'skipOnError' => true, 'targetClass' => EsysListaDesplegable::className(), 'targetAttribute' => ['update' => 'id']],
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
            'nombre' => 'Nombre',
            'tipo' => 'Tipo',
            'update' => 'Update',
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
    public function getTipoDocumento()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'tipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateDocumento()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'update']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public static function getItems()
    {
        $model = self::find()
            ->select(['id', 'nombre'])
            ->orderBy('nombre');

        return ArrayHelper::map($model->all(), 'id', 'nombre');
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
