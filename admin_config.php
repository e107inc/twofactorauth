<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

e107::lan('twofactorauth',true);

class twofactorauth_adminArea extends e_admin_dispatcher
{
	protected $modes = array(	
		'main'	=> array(
			'controller' 	=> 'twofactorauth_ui',
			'path' 			=> null,
			'ui' 			=> 'twofactorauth_form_ui',
			'uipath' 		=> null
		),
	);	
	
	
	protected $adminMenu = array(
		'main/list'			=> array('caption'=> LAN_MANAGE, 'perm' => 'P'),
		//'main/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		'main/prefs' 		=> array('caption'=> LAN_PREFS, 'perm' => 'P'),	

		// 'main/div0'      => array('divider'=> true),
		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P'),
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = 'TwoFactorAuth';
}

	
class twofactorauth_ui extends e_admin_ui
{
		protected $pluginTitle		= 'TwoFactorAuth';
		protected $pluginName		= 'twofactorauth';
	//	protected $eventName		= 'twofactorauth-twofactorauth'; // remove comment to enable event triggers in admin. 		
		protected $table			= 'twofactorauth';
		protected $pid				= 'user_id';
		protected $perPage			= 10; 
		protected $batchDelete		= false;
		protected $batchExport      = false;
		protected $batchCopy		= false;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent       = 'somefield_parent';
	//	protected $treePrefix       = 'somefield_title';

	//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
		protected $listQry      	= 
		"
			SELECT u.user_id, u.user_name 
			FROM #user AS u
	   		LEFT JOIN #user_extended AS ue ON u.user_id = ue.user_extended_id
	     	WHERE ue.user_plugin_twofactorauth_secret_key != ''
     	"; 
	
		protected $listOrder		= 'user_id ASC';
	
		protected $fields = array(
			'checkboxes' => array(  
				'title' 		=> '',  
				'type' 			=> null,  
				'data' 			=> null,  
				'width' 		=> '5%',  
				'thclass' 		=> 'center',  
				'forced' 		=> true,  
				'class' 		=> 'center',  
				'toggle' 		=> 'e-multiselect',  
				'readParms' 	=> array(),  
				'writeParms' 	=> array(),
			),
			'user_id' => array(
				'title' 		=> LAN_ID,  
				'type' 			=> '',  
				'data' 			=> 'int',  
				'width' 		=> '5%',  
				'readonly' 		=> true,  
				'help' 			=> '',  
				'readParms' 	=> array(),  
				'writeParms'	=> array(),  
				'class' 		=> 'left',  
				'thclass' 		=> 'left',
			),
			'user_name' => array( 
				'title' 		=> 'Username',  
				'type' 			=> 'text',  
				'noedit'		=> true,
				'data' 			=> 'str',  
				'width' 		=> 'auto',  
				'readonly' 		=> true,  
				'help' 			=> '',  
				'readParms' 	=> array(),  
				'writeParms' 	=> array(),  
				'class' 		=> 'left',  
				'thclass' 		=> 'left',
			),
			'options' => array(
				'title' 		=> LAN_OPTIONS,  
				'type' 			=> 'method',  
				'data' 			=> null,  
				'width' 		=> '10%',  
				'thclass'		=> 'center last',  
				'class' 		=> 'center last',  
				'forced' 		=> true,  
				'readParms' 	=> array(),  
				'writeParms' 	=> array(),
			),
		);		
		
		protected $fieldpref = array('user_id', 'user_name');
		
	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
			'tfa_debug' => array(
				'title'			=> 'Debug mode', 
				'tab'			=> 0, 
				'type'			=> 'boolean', 
				'data' 			=> 'str', 
				'help'			=> '', 
				'writeParms'	=> array()
			),
			'tfa_label' => array(
				'title'			=> 'Website label', 
				'tab'			=> 0,
				'type'			=> 'text', 
				'data' 			=> 'str', 
				'help'			=> 'Defaults to SITENAME', 
				'writeParms' 	=> array()
			),
		); 

	
		public function init()
		{
			// This code may be removed once plugin development is complete. 
			if(!e107::isInstalled('twofactorauth'))
			{
				e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail."); // DO NOT TRANSLATE
			}

		    // Check debug mode
	        if(e107::getPlugPref('twofactorauth', 'tfa_debug'))
	        {
	            e107::getMessage()->addWarning("TwoFactorAuth debug mode is <strong>enabled</strong>!"); // DO NOT TRANSLATE
	        }

	        if(vartrue($_POST['disable_tfa']))
			{
				$user_id = key($_POST['disable_tfa']);
				$this->disableTfa($user_id);
			}
		}

		function disableTfa($user_id)
		{
			if(!e107::getUserExt()->get($user_id, "user_plugin_twofactorauth_secret_key"))
			{
				e107::getMessage()->addError("Two Factor Authentication is already disabled for User ID ".$user_id."... Weird!");
				return; 
			}

			if(e107::getUserExt()->set($user_id, "user_plugin_twofactorauth_secret_key", $secret_key))
			{
				// Delete 
				e107::getMessage()->addSuccess("Two Factor Authentication has been disabled for User ID ".$user_id);
				return; 
			}
			else
			{
				e107::getMessage()->addError("Could not disable Two Factor Authentication for User ID ".$user_id);
			}			
		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data)
		{
			return $new_data;
		}
	
		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something		
		}		
		
		
		// ------- Customize Update --------
		
		public function beforeUpdate($new_data, $old_data, $id)
		{
			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something	
		}
		
		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something		
		}		
		
		// left-panel help menu area. (replaces e_help.php used in old plugins)
		public function renderHelp()
		{
			$caption = LAN_HELP;

			if($this->getAction() == "list")
			{
				$text = 'This overview shows which users have activated 2FA for their account';
			}

			if($this->getAction() == "prefs")
			{
				$text = 'Explain the prefs here';
			}
			

			return array('caption' => $caption,'text' => $text);
		}
			
		/*	
		// optional - a custom page.  
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;
			
		}
		*/			
}
				

class twofactorauth_form_ui extends e_admin_form_ui
{
	
	// Override the default Options field. 
	function options($parms, $value, $id, $attributes)
	{
		$action = $this->getController()->getAction();

		if($action == 'list')
		{
			//$options['icon'] = e107::getParser()->toIcon('fa-power-off.glyph'); // TODO Find suitable icon for 'disable'

			$text = "<div class='btn-group pull-right'>";
			//$text .= $this->renderValue('options', $value, $attributes, $id);
			$text .= $this->submit_image('disable_tfa['.$id.']', 1, 'delete', LAN_DISABLE, $options);
			$text .= "</div>";

			return $text;
		}
	}

}				
		
new twofactorauth_adminArea();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;