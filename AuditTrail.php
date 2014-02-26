<?php

namespace sammaye\audittrail;

use Yii;
use yii\db\ActiveRecord;

/**
 * The followings are the available columns in table 'tbl_audit_trail':
 * @var integer $id
 * @var string $new_value
 * @var string $old_value
 * @var string $action
 * @var string $model
 * @var string $field
 * @var string $stamp
 * @var integer $user_id
 * @var string $model_id
 */
class AuditTrail extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		if(isset(Yii::$app->params['audittrail.table']) && isset(Yii::$app->params['audittrail.table'])){
			return Yii::$app->params['audittrail.table'];
		}else{
			return '{{%audit_trail}}';
		}
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'old_value' => 'Old Value',
			'new_value' => 'New Value',
			'action' => 'Action',
			'model' => 'Type',
			'field' => 'Field',
			'stamp' => 'Stamp',
			'user_id' => 'User',
			'model_id' => 'ID',
		];
	}	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['action', 'model', 'stamp', 'model_id'], 'required'],
			['action', 'string', 'max' => 255],
			['model', 'string', 'max' => 255],
			['field', 'string', 'max' => 255],
			['model_id', 'string', 'max' => 255],
			['user_id', 'string', 'max' => 255],
			[['old_value', 'new_value'], 'safe']
		];
	}
	
	public static function recently($query)
	{
		$query->orderBy(['[[stamp]]' => SORT_DESC]);
	}
	
	public function getUser()
	{
		if(isset(Yii::$app->params['audittrail.model']) && isset(Yii::$app->params['audittrail.model'])){
			return $this->hasOne(Yii::$app->params['audittrail.model'], ['id' => 'user_id']);
		}else{
			return $this->hasOne('common\models\User', ['id' => 'user_id']);
		}
	}

	public function getParent(){
		$model_name = $this->model;
		return new $model_name;
	}
}