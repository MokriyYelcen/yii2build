<?php
/*
 * here is second part of common/usermodel
 */
/*
     *  @GetRole
     */
    
    public function getRole(){
        return $this->hasOne(Role::className(),['role_value'=>'role_id']);
    }
    
    
     /*
     *  @GetRoleName
     */
    
    public function getRoleName(){
        return $this->role ? $this->role->role_name : '- no role -';
    }
    
    
    /*
     *  @GetRoleList
     */
    
    public static function getRoleList(){
        $droptions = Role::find()->asArray()->all();
        return Arrayhelper::map($droptions,'role_value','role_name');
    }
    
    
    /*
     *  @GetStatus
     */
    
    public function getStatus(){
        return $this->hasOne(Status::className(),['status_name' => 'status_id']);
    }
    
     /*
     *  @GetStatusName
     */
    
    public function getStatusName(){
        return $this->status ? $this->status->status_name : '- no status -';
    }
    
    
    public static function getStatusList(){
        $droptions = Status::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'status_value', 'status_name');
    }
    
    /*
     *  @GetProfile
     */
    
    public function getProfile(){
        return $this->hasOne(Profile::className(),['user_id' => 'id']);
    }
    
    /*
     *  @getProfileId
     */
    
    public function getProfileId (){
        return $this->profile ? $this->profil->id : 'none';
    }
    
    /*
     *  @getProfileLink
     */
    public function getProfileLink(){
        $url = Url::to(['profile/view','id'=>$this->profileid]);
        $options=[];
        return Html::a($this->profile ? 'profile':'none', $url, $options);
    }
    
    /*
     *  @getUserType
     */
    
    public function getUserType(){
        return $this->hasOne(UserType::className(),['user_type_value' => 'user_type_id']);
    }
    
    /*
     *  @getUserTypeName
     */
    
    public function getUserTypeName(){
        return $this->userType ? $this->userType->user_type_name :'name';
    }
    
    /*
     *  @getUserTypeList
     */
    
    public static function getUserTypeList(){
        $droptions = UserType::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'user_type_value', 'user_type_name');
    }
    
    /*
     *  @getUserTypeList
     */
    
    public function getUserTypeid(){
        return $this->userType ? $this->userType->id : 'none';
    }
    
    /*
     *  @getUserIdLink
     */
    
    public function getUserIdLink(){
        $url = Url::to(['user/update','id' => $this->id]);
        $options =[];
        return Html::a($this->id,$url, $options);
    }
    
    /*
     *  @getUserLink 
     */
    
    public function getUserLink(){
        $url= Url::to(['user/view','id' => $this->id]);
        $options =[];
        return Html::a($this->username, $url, $options);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    