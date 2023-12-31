<?php
namespace app\models\cliente;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use app\models\cliente\Cliente;

/**
 * ClienteIdentity class for "cliente" table.
 * This is a base cliente class that is implementing IdentityInterface.
 * Cliente model should extend from this model, and other cliente related models should
 * extend from Cliente model.
 *
 * @property int $id Id
 * @property string $email Correo electrónico
 * @property string $auth_key
 * @property string $password_hash
 * @property string $account_activation_token
 * @property string $password_reset_token
 * @property string $api_username
 * @property string $api_password_hash
 * @property int $status Estatus
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 */
class ClienteIdentity extends ActiveRecord implements IdentityInterface
{
    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%cliente}}';
    }


//------------------------------------------------------------------------------------------------//
// IDENTITY INTERFACE IMPLEMENTATION
//------------------------------------------------------------------------------------------------//
    /**
     * Finds an identity by the given ID.
     *
     * @param  int|string $id The cliente id.
     * @return IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => Cliente::STATUS_ACTIVE]);
    }

    /**
     * Finds an identity by the given access token.
     *
     * @param  mixed $token
     * @param  null  $type
     * @return void|IdentityInterface
     *
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Returns an ID that can uniquely identify a cliente identity.
     *
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given
     * identity ID. The key should be unique for each individual cliente, and
     * should be persistent so that it can be used to check the validity of
     * the cliente identity. The space of such keys should be big enough to defeat
     * potential identity attacks.
     *
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * @param  string  $authKey The given auth key.
     * @return boolean          Whether the given auth key is valid.
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


//------------------------------------------------------------------------------------------------//
// USER FINDERS
//------------------------------------------------------------------------------------------------//
    /**
     * Finds cliente by api_username.
     *
     * @param  string $api_username
     * @return static|null
     */
    /*
    public static function findByUsername($api_username)
    {
        return static::findOne(['api_username' => $api_username]);
    }
    */

    /**
     * Finds cliente by email.
     *
     * @param  string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds cliente by password reset token.
     *
     * @param  string $token Password reset token.
     * @return null|static
     */
    /*
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => Cliente::STATUS_ACTIVE,
        ]);
    }
    */

    /**
     * Finds cliente by account activation token.
     *
     * @param  string $token Account activation token.
     * @return static|null
     */
    /*
    public static function findByAccountActivationToken($token)
    {
        return static::findOne([
            'account_activation_token' => $token,
            'status'                   => Cliente::STATUS_INACTIVE,
        ]);
    }
    */


//------------------------------------------------------------------------------------------------//
// IMPORTANT IDENTITY HELPERS
//------------------------------------------------------------------------------------------------//
    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Validates password.
     *
     * @param  string $password
     * @return bool
     *
     * @throws \yii\base\InvalidConfigException
     */
    /*
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    */

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param  string $password
     *
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Generates password hash from api_password and sets it to the model.
     *
     * @param  string $password
     *
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setApiPassword($password)
    {
        $this->api_password_hash = Yii::$app->security->generatePasswordHash($password);
    }



//------------------------------------------------------------------------------------------------//
// HELPERS
//------------------------------------------------------------------------------------------------//
    /**
     * Generates new password reset token.
     */
    /*
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    */

    /**
     * Removes password reset token.
     */
    /*
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    */

    /**
     * Finds out if password reset token is valid.
     *
     * @param  string $token Password reset token.
     * @return bool
     */
    /*
    public static function isPasswordResetTokenValid($token)
    {
        if(empty($token))
            return false;

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['cliente.passwordResetTokenExpire'];

        return true;
    }
    */

    /**
     * Generates new account activation token.
     */
    /*
    public function generateAccountActivationToken()
    {
        $this->account_activation_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    */

    /**
     * Removes account activation token.
     */
    /*
    public function removeAccountActivationToken()
    {
        $this->account_activation_token = null;
    }
    */

    /**
     * Returns the role name.
     * If cliente has any custom role associated with him we will return it's name,
     * else we return 'member' to indicate that cliente is just a member of the site with no special roles.
     *
     * @return string
     */
    /*
    public function getRoleName()
    {
        // if cliente has some role assigned, return it's name
        if($this->authAssignment)
            return $this->authAssignment->item_name;

        // cliente does not have role assigned, but if he is authenticated '@'
        return '@uthenticated';
    }
    */

}
