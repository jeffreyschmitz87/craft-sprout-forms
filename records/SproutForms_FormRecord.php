<?php
namespace Craft;

class SproutForms_FormRecord extends BaseRecord
{
	/**
	 * Return table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return 'sproutforms_forms';
	}
	
	/**
	 * Define attributes
	 *
	 * @return array
	 */
	public function defineAttributes()
	{
		return array(
			'groupId' => AttributeType::Number,
			'name' => array(
				AttributeType::String,
				'required' => true
			),
			'handle' => array(
				AttributeType::String,
				'required' => true
			),
			'titleFormat' => array(
				AttributeType::String,
				'required' => true
			),
			'redirectUri' => AttributeType::String,
			'submitAction' => AttributeType::String,
			'submitButtonText' => AttributeType::String,
			'notificationRecipients' => AttributeType::String,
			'notificationSubject' => AttributeType::String,
			'notificationSenderName' => AttributeType::String,
			'notificationSenderEmail' => AttributeType::String,
			'notificationReplyToEmail' => AttributeType::String,
		);
	}
	
	/**
	 * Define validation rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array(
				'name,handle',
				'required'
			),
			array(
				'name,handle',
				'unique',
				'on' => 'insert'
			),
			array(
				'notificationRecipients',
				'validateDistributionList'
			)
		);
	}

	/**
	 * Define relationships
	 * 
	 * @return array
	 */
	public function defineRelations()
	{
		return array(
			'element'     => array(static::BELONGS_TO, 'ElementRecord', 'id', 'required' => true, 'onDelete' => static::CASCADE),
			'fieldLayout' => array(static::BELONGS_TO, 'FieldLayoutRecord', 'onDelete' => static::SET_NULL),
		);
	}

	/**
	 * Custom validator for email distribution list
	 * 
	 * @param string $attribute
	 * @return boolean
	 */
	public function validateDistributionList($attribute)
	{
		if ($emails = explode(',', $this->notificationRecipients)) {
			foreach ($emails as $email) {
				$email = trim($email);
				if (!$email)
					continue;
				
				// allow twig syntax
				if(preg_match('/{{?(.*?)}?}/', $email))
				{
					continue; 
				}
				
				if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
					$this->addError($attribute, 'Please make sure all emails are valid.');
					return false;
				}
			}
		}
		return true;
	}
}
