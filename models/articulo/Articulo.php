<?php

namespace app\models\articulo;

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
 * @property string $nombre Nombre
 * @property string $image_src_filename Imagen Src Filename
 * @property string $image_web_filename Imagen Web Filename
 * @property double $precio Precio
 * @property int $inventario Inventario
 * @property int $status Estatus
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Articulo extends \yii\db\ActiveRecord
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
        return 'articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'precio', 'inventario'], 'required'],
            [['precio'], 'number'],
            [['inventario', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['nombre'], 'string', 'max' => 150],
            [['image_src_filename', 'image_web_filename'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions'=>'jpg, jpeg, gif, png'],
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
            'nombre' => 'Nombre',
            'image_src_filename' => 'Image Src Filename',
            'image_web_filename' => 'Image Web Filename',
            'precio' => 'Precio',
            'inventario' => 'Inventario',
            'status' => 'Estatus',
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

    public static function getItems()
    {
        $model = self::find()
            ->select(['id', 'nombre'])
            ->andWhere(['>', 'inventario', 0])
            ->andWhere(['status' => self::STATUS_ACTIVE])
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

                // Creamos objeto para log de cambios
                $this->CambiosLog = new EsysCambiosLog($this);

                // Remplazamos manualmente valores del log de cambios
                foreach($this->CambiosLog->getListArray() as $attribute => $value) {
                    switch ($attribute) {
                        case 'status':
                            $this->CambiosLog->updateValue($attribute, 'old', self::$statusList[$value['old']]);
                            $this->CambiosLog->updateValue($attribute, 'dirty', self::$statusList[$value['dirty']]);
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


    public function upload()
    {
        if ($this->validate()) {
            $this->image_src_filename = $this->image->name;
            $this->image_web_filename = Yii::$app->security->generateRandomString().".{$this->image->extension}";
            $this->image->saveAs(Yii::$app->basePath . '/web/img/articulos/' . $this->image_web_filename);
 
            return true;
        } else {
            return false;
        }
    }

    public function deleteImage() {
        $imagen = Yii::$app->basePath . '/web/img/articulos/' . $this->image_web_filename;
        if (unlink($imagen)) {
            $this->image_src_filename = null;
            $this->image_web_filename = null; 

            return true;
        }
        return false;
    }

    public function getCambiosLog()
    {
        return EsysCambioLog::find()
            ->andWhere(['or',
                ['modulo' => $this->tableName(), 'idx' => $this->id]
            ])
            ->all();
    }
}
