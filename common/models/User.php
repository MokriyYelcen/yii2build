<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Activerecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\helpers\Security;
/**
 * User model
 * 
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status_id
 * @property integer $role_id
 * @property integer $user_type_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 */


Class User extends Activerecord implements IdentityInterface{
    const STATUS_ACTIVE =10;
    
    
    public static function tableName(){
        return 'user';
    }
    
    /*
     * behaviors
     */
    
    public function behaviors(){
        return [
            'timestamp'=>[
                'class'=>'yii\behaviors\TimestampBehavior',
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression ('NOW()')
            ],
        ];
    }
    
    /** 
     * validation rules 
     */
    
    
    public function rules(){
        return [
            ['status_id','default','value'=> self::STATUS_ACTIVE],
            
            
            ['role_id','default','value' => 10],
            
            
            ['user_type_id','default','value' => 10],
            
            
            ['username','filter','filter'=> 'trim'],
            ['username', 'required'],
            ['username','unique'],
            ['username','string','min' => 2,'max' => 255],
            
            
            ['email','filter','filter' =>'trim'],
            ['email', 'required'],
            ['email','email'],
            ['email','unique']
            
        ];
    }
    
    public function attributeLabels(){
        return[
            /*
             * your other attribute labels
             */
        ];
    }
   
    /** Finds an identity by the given ID. *
     * @param string|integer $id the ID to be looked for *
     * @return IdentityInterface the identity object that *
     * matches the given ID. * 
     * Null should be returned if such an identity cannot be found * 
     * or the identity is not in an active state *
     * (disabled, deleted, etc.) 
     */
    public static function findIdentity ($id){
        
        return static::findOne(['id' => $id,'status_id' => self::STATUS_ACTIVE]);
        
    } 
    
    /**
      * @findIdentityByAccessToken
    */
    /** * Finds an identity by the given secrete token. * 
     * @param string $token the secrete token * 
     * @param mixed $type the type of the token. * 
     * The value of this parameter depends on the implementation. * 
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will * 
     * set this parameter to be `yii\filters\auth\HttpBearerAuth`. * 
     * @return IdentityInterface the identity object that matches *
     * the given token. * 
     * Null should be returned if such an identity cannot be found * 
     * or the identity is not in an active state * 
     * (disabled, deleted, etc.) 
     */

    public static function findIdentityByAccessToken ($token ,$type = null){
        return static::findOne(['auth_key' => $token]);
    }
    
    /**
     * Finds user by username 
     * @param string $username
     * @return static|null
     */

    public static function findByUsername ($username){
        
        return static::findOne(['username' => $username,'status_id' => self::STATUS_ACTIVE]);
        
    }
    
    
    /** 
     * Finds user by password reset token 
     * @param string $token password reset token
     * @return static|null
     */
    
    
    public static function findByPasswordResetToken ($token){
        $expire = Yii::$app->parms['user.passworRestTokenExpire'];
        $parts = explode('_',$token);
        $timestamp = (int) end($parts);
        
        if($timestamp + $expire < time() ){
            //token expired
            return null;
        }
        
        return static::findOne(['password_reset_token' => $token ,'status_id' => self::STATUS_ACTIVE]
                );
    }
    
    
    /*
     * @getId
     */
    /** * Returns an ID that can uniquely identify a user identity. * 
     * @return string|integer an ID that uniquely identifies a user identity. 
     */

    public function  getId(){
        return $this->getPrimaryKey();
    }
    
    /*
     * @getAuthKey
     * * Returns a key that can be used to check the validity of a given identity ID. *
     * *
     * The key should be unique for each individual user, and should be persistent * 
     * so that it can be used to check the validity of the user identity. *
     * * 
     * The space of such keys should be big enough to defeat potential identity atta\ cks. *
     * * 
     * This is required if [[User::enableAutoLogin]] is enabled. * 
     * @return string a key that is used to check the validity of a given identity I\ D. * 
     * @see validateAuthKey() 
     */

     
    
    public function getAuthKey(){
        return $this->auth_key;
    }
    
    /*
     * @validateAuthKey
     */
    /** * Validates the given auth key. *
     *  * 
     * This is required if [[User::enableAutoLogin]] is enabled. * 
     * @param string $authKey the given auth key * 
     * @return boolean whether the given auth key is valid. * 
     * @see getAuthKey() */

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }
    
    /*
     * Validates password
     * 
     * @param string $password password to validate 
     * @return boolean if password provided is valid for current user 
     */
    
    
    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password,$this->password_hash);
    }
    
    /*
     * Generates password hash from password and sets it to the model
     * 
     * @param string $password  
     */
    
    public function setPassword($password){
        
        $this->password_hash= Yii::$app->security->generatePasswordHash($password);
        
    }
    
    
     /*
     *  Generates "remember me" authentication key  
     */
    
    public function generateAuthKey(){
        $this->auth_key=Yii::$app->security->generateRandomString();
    } 
    
     /*
     *  Generates new password reset tokken 
     */
    
    public function generatePasswordResetToken(){
        $this->password_reset_token= Yii::$app->security->generateRandomString().'_'.time();
    }
    
    /*
     *  Removes password reset tokken 
     */
    
    public function removePasswordResetToken(){
        $this->password_reset_token = null;
    }
    
    
    
    
}