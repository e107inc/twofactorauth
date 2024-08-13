<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

require_once(__DIR__.'/../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

e107::lan('twofactorauth', true, true);

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
				'type' 			=> 'number',  
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
				'title' 		=> LAN_NAME,  
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
			'tfa_active' => array(
				'title'			=> LAN_2FA_PREFS_ACTIVE, 
				'tab'			=> 0, 
				'type'			=> 'boolean', 
				'data' 			=> 'int', 
				'help'			=> LAN_2FA_PREFS_ACTIVE_HELP, 
				'writeParms'	=> array()
			),
			'tfa_recoverycodes' => array(
				'title'			=> LAN_2FA_PREFS_RECOVERY_CODES, 
				'tab'			=> 0, 
				'type'			=> 'boolean', 
				'data' 			=> 'int', 
				'help'			=> LAN_2FA_PREFS_RECOVERY_CODES_HELP, 
				'writeParms'	=> array()
			),
			'tfa_recoverycodesattempts' => array(
				'title'			=> LAN_2FA_PREFS_RECOVERY_CODES_ATTEMPTS, 
				'tab'			=> 0, 
				'type'			=> 'number', 
				'data' 			=> 'int', 
				'help'			=> LAN_2FA_PREFS_RECOVERY_CODES_ATTEMPTS_HELP, 
				'writeParms'	=> array()
			),
			'tfa_debug' => array(
				'title'			=> LAN_2FA_PREFS_DEBUG, 
				'tab'			=> 0, 
				'type'			=> 'boolean', 
				'data' 			=> 'int', 
				'help'			=> LAN_2FA_PREFS_DEBUG_HELP, 
				'writeParms'	=> array()
			),
			'tfa_label' => array(
				'title'			=> LAN_2FA_PREFS_WEBLABEL, 
				'tab'			=> 0,
				'type'			=> 'text', 
				'data' 			=> 'str', 
				'help'			=> LAN_2FA_PREFS_WEBLABEL_HELP, 
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

	        // Check old files
	        $old_files = array(
				'login.php',
			);

			foreach($old_files as $old_file)
			{
				if(file_exists($old_file))
				{
					@unlink($old_file);

					if(file_exists($old_file))
					{
						e107::getMessage()->addDebug("Please remove the following outdated file: ".$old_file); // DO NOT TRANSLATE
					}
					else
					{
						e107::getMessage()->addSuccess("Outdated file removed: ".$old_file);
						e107::getPlug()->clearCache()->buildAddonPrefLists();
					}
				}
			}

			// Process disabling 2FA for a specific user
	        if(vartrue($_POST['disable_tfa']))
			{
				$user_id = key($_POST['disable_tfa']);
				$this->disableTfa($user_id);
			}

			if($this->getAction() == "list") 
			{
				$this->batchOptions = array(
					'disabletfa'  => LAN_2FA_DISABLE_BATCH, 
				); 
			}
		}

		public function handleListDisabletfaBatch($arr)
		{
			if(empty($arr))
			{
				return null;
			}

			$arr = e107::getParser()->filter($arr, 'int');

			//print_a($arr);
			foreach($arr as $key => $userID)
			{
				$this->disableTfa($userID);
			}
		}

		function disableTfa($user_id)
		{
			// Just checking
			if(!e107::getUserExt()->get($user_id, "user_plugin_twofactorauth_secret_key"))
			{
				// Should not happen, but secret_key is already empty?
				$message = e107::getParser()->lanVars(LAN_2FA_DISABLE_ALREADY_DISABLED, $user_id);
				e107::getMessage()->addError($message); 
				return; 
			}

			// Delete the secret_key from the EUF
			if(e107::getUserExt()->set($user_id, "user_plugin_twofactorauth_secret_key", null))
			{
				$message = e107::getParser()->lanVars(LAN_2FA_DISABLE_SUCCESS, $user_id);
				e107::getMessage()->addSuccess($message);
				return; 
			}
			// Error deleting the secret key from EUF 
			else
			{
				$message = e107::getParser()->lanVars(LAN_2FA_DISABLE_ERROR, $user_id);
				e107::getMessage()->addError($message);
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
		
		// left-panel help menu area
		public function renderHelp()
		{
			$text = '';
			$caption = LAN_HELP;

			if($this->getAction() == "list")
			{
				$text .= '<strong>'.LAN_MANAGE.'</strong>'; 
				$text .= '<p>'.LAN_2FA_HELP_MANAGE.'</p>'; 

				$text .= '<strong>'.LAN_DISABLE.'</strong>'; 
				$text .= '<p>'.LAN_2FA_HELP_DISABLE1.'</p>';
				$text .= '<p>'.LAN_2FA_HELP_DISABLE2.'</p>';
			}

			if($this->getAction() == "prefs")
			{

				$text .= '<strong>'.LAN_2FA_PREFS_ACTIVE.'</strong>'; 
				$text .= '<p>'.LAN_2FA_PREFS_ACTIVE_HELP.'</p>';
				
				$text .= '<strong>'.LAN_2FA_PREFS_DEBUG.'</strong>'; 
				$text .= '<p>'.LAN_2FA_PREFS_DEBUG_HELP.'</p>';

				$text .= '<strong>'.LAN_2FA_PREFS_WEBLABEL.'</strong>'; 
				$text .= '<p>'.LAN_2FA_PREFS_WEBLABEL_HELP.'</p>';
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
	function options($parms, $value, $id, $options)
	{
		$action = $this->getController()->getAction();

		if($action == 'list')
		{
			//$options['icon'] = e107::getParser()->toIcon('fa-power-off.glyph'); // TODO Find suitable icon for 'disable'

			$text = "<div class='btn-group pull-right'>";
			//$text .= $this->renderValue('options', $value, $attributes, $id);
			$text .= $this->submit_image('disable_tfa['.$id.']', 1, 'delete', LAN_DISABLE);
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