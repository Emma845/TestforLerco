<?php

namespace app\models\ciclo;

use Yii;
use app\models\user\User;
use app\models\Esys;
use app\models\esys\EsysCambioLog;
use app\models\esys\EsysCambiosLog;
use yii\helpers\ArrayHelper;



/**
 * This is the model class for table "articulo".
 *
 * @property int $id ID
 * @property string $notas notas
 * @property double $precio Precio
 * @property int $rango_a rango_a
 * @property int $rango_b rango_b
 * @property int $year year
 * @property int $year_fin year_fin
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Ciclo extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE   = 10;
    const STATUS_INACTIVE = 1;

    public static $statusList = [
        self::STATUS_ACTIVE   => 'Habilitado',
        self::STATUS_INACTIVE => 'Inhabilitado',
    ];

    public $image;

    private $CambiosLog;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ciclo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year','year_fin'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by','year','year_fin'], 'integer'],
            [['notas'], 'string', 'max' => 350],
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
            'rango_a' => 'Inicio del ciclo',
            'rango_b' => 'Fin del ciclo',
            'year' => 'Año inicio',
            'year_fin' => 'Año fin',
            'notas' => 'Notas',
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

        } 
        else
            
        return false;
    }
}
