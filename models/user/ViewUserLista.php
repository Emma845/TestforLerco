<?php

namespace app\models\user;

use Yii;

/**
 * This is the model class for table "view_user_lista".
 *
 * @property int $pase_lista
 * @property string $username Nombre de usuario
 * @property string $nombre_completo
 * @property string $perfil
 * @property string $nombre Nombre
 * @property string $apellidos Apellidos
 */
class ViewUserLista extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_user_lista';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pase_lista' => 'Pase Lista',
            'username' => 'Username',
            'nombre_completo' => 'Nombre Completo',
            'perfil' => 'Perfil',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
        ];
    }
}
