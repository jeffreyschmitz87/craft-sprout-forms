<?php
namespace Craft;

class SproutFormsPlugin extends BasePlugin
{
	public function getName()
	{
		$pluginName	= Craft::t('Sprout Forms');
		$pluginNameOverride	= $this->getSettings()->pluginNameOverride;

		return ($pluginNameOverride) ? $pluginNameOverride : $pluginName;
	}

	public function getVersion()
	{
		return '0.8.02';
	}

	public function getDeveloper()
	{
		return 'Barrel Strength Design';
	}

	public function getDeveloperUrl()
	{
		return 'http://barrelstrengthdesign.com';
	}

	public function hasCpSection()
	{
		return true;
	}

	public function init()
	{
		// @TODO - Are these necessary?  Or is there a better way to do this?
		Craft::import('plugins.sproutforms.fields.ISproutFormsFieldType');
		Craft::import('plugins.sproutforms.fields.BaseSproutFormsFieldType');
	}

	/**
	 * Define plugin settings
	 * 
	 * @return array
	 */
	protected function defineSettings()
	{
		return array(
			'pluginNameOverride'     => AttributeType::String,
			'templateFolderOverride' => AttributeType::String
		);
	}

	public function registerCpRoutes()
	{
		return array(
			
			// Forms by Group
			'sproutforms/forms/(?P<groupId>\d+)' => 
			'sproutforms/forms',

			// New Form
			'sproutforms/forms/new' => array(
				'action' => 'sproutForms/forms/editFormTemplate'
			),

			// Edit Form
			'sproutforms/forms/edit/(?P<formId>\d+)' => array(
				'action' => 'sproutForms/forms/editFormTemplate'
			),

			// New Field
			'sproutforms/forms/(?P<formId>\d+)/fields/new' => array(
					'action' => 'sproutForms/fields/editFieldTemplate'
			),

			// Edit Form
			'sproutforms/forms/(?P<formId>\d+)/fields/edit/(?P<fieldId>\d+)' => array(
					'action' => 'sproutForms/fields/editFieldTemplate'
			),

			// Edit Entry
			'sproutforms/entries/edit/(?P<entryId>\d+)' => array(
				'action' => 'sproutForms/entries/editEntryTemplate'
			),

			/*
			* @controller SproutSeo_SettingsController
			* @template   sproutseo/templates/settings/index.html
			*/
			'sproutforms/settings' => array(
				'action' => 'sproutForms/settings/settingsIndexTemplate'
			),

			// Example Forms
			'sproutforms/examples' => 
			'sproutforms/_cp/examples',

		);
	}

	public function registerUserPermissions()
	{
		return array(
			'editSproutFormsSettings'	=> array(
				'label' => Craft::t('Edit Form Settings')
			)
		);
	}

	/**
	 * Event registrar
	 * 
	 * @param string $event
	 * @param \Closure $callback
	 */
	public function sproutformsAddEventListener($event, \Closure $callback)
	{		
		switch ($event) {
			case 'saveEntry': // only event supported at this time
				craft()->on( 'sproutForms.saveEntry' , $callback);
				break;
		}
	}
	
	/**
	 * Install examples after installation
	 * 
	 * @return void
	 */
	public function onAfterInstall()
	{
		craft()->request->redirect('../sproutforms/examples');
	}

	/**
	 * Perform any actions before the plugin gets uninstalled.
	 */
	public function onBeforeUninstall()
	{
		$forms = craft()->sproutForms_forms->getAllForms();

		foreach ($forms as $form) 
		{
			craft()->sproutForms_forms->deleteForm($form);
		}

		// remove example templates  
		// @TODO - deliberate whether this is a good idea
		// $fileHelper = new \CFileHelper();
		// $fileHelper->removeDirectory(craft()->path->getSiteTemplatesPath() . 'sproutforms');
	}
}
