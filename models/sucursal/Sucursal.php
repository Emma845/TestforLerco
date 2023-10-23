<?php

namespace app\models\sucursal;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\user\User;
use app\models\esys\EsysDireccion;
use app\models\pregunta\SucursalPregunta;

/**
 * This is the model class for table "Sucursal".
 *
 * @property int $id
 * @property string $nombre
 * @property string $encargado
 * @property string $email
 * @property string $direccion
 * @property string $colonia
 * @property string $cp
 * @property string $tels
 * @property int $estado_id
 * @property int $municipio_id
 * @property string $lat
 * @property string $lng
 * @property int $on_web
 * @property int $factura
 * @property string $facturacion_serie
 *
 * @property Clientes-vendedores[] $clientes-vendedores
 * @property Compras[] $compras
 * @property Existencias[] $existencias
 * @property Productos[] $productos
 * @property Pedidos[] $pedidos
 * @property RegistroDeMovimientos[] $registroDeMovimientos
 * @property Traspasos[] $traspasos
 */
class Sucursal extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 1;

    public static $statusList = [
        self::STATUS_ACTIVE   => 'Habilitado',
        self::STATUS_INACTIVE => 'Inhabilitado',
        //self::STATUS_DELETED  => 'Eliminado'
    ];



    public $dir_obj;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sucursal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],

            [['nombre'], 'string', 'max' => 200],
            [['telefono'], 'string', 'max' => 100],
            [['telefono_movil'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 50],
            [['centro_costo'], 'string', 'max' => 20],
            [['informacion', 'comentarios'], 'string'],
            [['nombre'], 'unique'],
            [['nombre'],'required'],
            [['encargado_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['encargado_id' => 'id']],
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
            'nombre' => 'Nombre de sucursal',
            'encargado_id' => 'Encargado',
            'email' => 'Email',
            'telefono' => 'Telefono',
            'centro_costo' => 'Centro de costo',
            'telefono_movil' => 'Telefono movil',
            'status' => 'Estado',

        ];
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getEncargadoSucursal()
    {
        return $this->hasOne(User::className(), ['id' => 'encargado_id']);
    }

    public static function getItems()
    {
        $model = self::find()
            ->select(['id', 'nombre'])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->orderBy('nombre');

        return ArrayHelper::map($model->all(), 'id', 'nombre');
    }

    public function getDireccion()
    {
        return $this->hasOne(EsysDireccion::className(), ['cuenta_id' => 'id'])
            ->where(['cuenta' => EsysDireccion::CUENTA_SUCURSAL, 'tipo' => EsysDireccion::TIPO_PERSONAL]);
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert)
            $this->dir_obj->cuenta_id = $this->id;
        // Guardar dirección
        $this->dir_obj->save();
    }
    public function afterDelete()
    {
        parent::afterDelete();

        $this->direccion->delete();
    }

}
