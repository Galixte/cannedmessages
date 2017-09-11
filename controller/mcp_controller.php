<?php
/**
 *
 * Canned Messages. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\cannedmessages\controller;

class mcp_controller
{
	/** @var \phpbb\user */
	protected $user;

	/** @var  \phpbb\template\template */
	protected $template;

	/** @var  string Custom form action */
	protected $u_action;

	/** @var  string Current action */
	protected $action;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var  string Admin path for images */
	protected $phpbb_admin_path;

	/** @var array List of errors */
	protected $errors = array();

	/**
	 * Constructor
	 *
	 * @param \phpbb\user						 $user		   User object
	 * @param \phpbb\template\template           $template     Template object
	 * @param \phpbb\language\language           $language     Language object
	 * @param \phpbb\request\request             $request      Request object
 	 * @param \phpbb\log\log					 $log		   The phpBB log system
	 * @param \phpbb\cannedmessages\message\manager      $manager      Canned Messages manager object
	 * @param string                             $root_path    phpBB root path
	 * @param string							 $adm_relative_path  Admin relative path
	 * @param string                             $php_ext      PHP extension
	 */
	public function __construct(\phpbb\user $user, \phpbb\template\template $template, \phpbb\language\language $language, \phpbb\log\log $log, \phpbb\request\request $request, \phpbb\cannedmessages\message\manager $manager, $root_path, $adm_relative_path, $php_ext)
	{
		$this->user = $user;
		$this->template	= $template;
		$this->language = $language;
		$this->language->add_lang('mcp', 'phpbb/cannedmessages');
		$this->language->add_lang('acp/common');
		$this->log = $log;
		$this->manager = $manager;
		$this->request = $request;
		$this->phpbb_admin_path = $adm_relative_path;
	}

	/**
	 * Set page url
	 *
	 * @param	string	$u_action	Custom form action
	 * @return	void
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

	/**
	 * Get MCP page title for Canned Messages module
	 *
	 * @return	string	Language string for Canned Messages MCP module
	 */
	public function get_page_title()
	{
		return $this->language->lang('MCP_CANNEDMESSAGES_TITLE');
	}

	/**
	 * Process user request for manage mode
	 *
	 * @return	void
	 */
	public function mode_manage()
	{
		// Trigger specific action
		$this->action = $this->request->variable('action', '');

		if (in_array($this->action, array('add', 'edit', 'delete')))
		{
			$this->{'action_' . $this->action}($this->request->variable($this->action === 'add' ? 'parent_id' : 'cannedmessage_id', 0));
		}
		else
		{
			// Otherwise default to this
			$this->list_messages();
		}
	}

	/**
	 * Get list of messages
	 */
	protected function list_messages()
	{
		$parent_id = $this->request->variable('parent_id', 0);

		// Get the parent name(s)
		if ($this->request->is_set('parent_id'))
		{
			$parents = array();
			$parent_id_tracked = $parent_id;
			while ($parent_id_tracked > 0)
			{
				$cannedmessage = $this->manager->get_message($parent_id_tracked);
				if (count($cannedmessage))
				{
					$parents[] = array(
						'name'			=> $cannedmessage['cannedmessage_name'],
						'id'			=> $cannedmessage['cannedmessage_id'],
					);
					$parent_id_tracked = $cannedmessage['parent_id'];
				}
				else
				{
					break;
				}
			}

			$parents = array_reverse($parents);

			for ($i = 0; count($parents) > $i; $i++)
			{
				$this->template->assign_block_vars('parents', array(
					'PARENT_NAME'	=> $parents[$i]['name'],
					'U_PARENT'		=> $this->u_action . ($i > 0 ? "&amp;parent_id={$parents[$i]['id']}" : ''),
				));
			}
			unset($parents);
		}

		foreach($this->manager->get_messages(true, $parent_id) as $cannedmessage_id => $cannedmessage_row)
		{
			$this->template->assign_block_vars('cannedmessages', array(
				'CANNEDMESSAGE_ID'		=> $cannedmessage_id,
				'CANNEDMESSAGE_NAME'	=> $cannedmessage_row['cannedmessage_name'],
				'U_CANNEDMESSAGE'		=> $cannedmessage_row['is_cat'] ? "{$this->u_action}&amp;parent_id={$cannedmessage_id}" : false,
				'U_MOVE_UP'				=> "{$this->u_action}&amp;action=move_up&amp;cannedmessage_id={$cannedmessage_id}&amp;hash=" . generate_link_hash('up' . $cannedmessage_id),
				'U_MOVE_DOWN'			=> "{$this->u_action}&amp;action=move_down&amp;cannedmessage_id={$cannedmessage_id}&amp;hash=" . generate_link_hash('down' . $cannedmessage_id),
				'U_EDIT'				=> "{$this->u_action}&amp;action=edit&amp;cannedmessage_id={$cannedmessage_id}&amp;hash=" . generate_link_hash('edit' . $cannedmessage_id),
				'U_DELETE'				=> "{$this->u_action}&amp;action=delete&amp;cannedmessage_id={$cannedmessage_id}&amp;hash=" . generate_link_hash('delete' . $cannedmessage_id),
			));
		}

		$this->template->assign_vars(array(
			'U_ACTION_ADD'				=> "{$this->u_action}&amp;action=add" . ($parent_id > 0 ? "&amp;parent_id={$parent_id}" : ''),
			'ICON_MOVE_UP'				=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_up.gif" alt="' . $this->language->lang('MOVE_UP') . '" title="' . $this->language->lang('MOVE_UP') . '" />',
			'ICON_MOVE_UP_DISABLED'		=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_up_disabled.gif" alt="' . $this->language->lang('MOVE_UP') . '" title="' . $this->language->lang('MOVE_UP') . '" />',
			'ICON_MOVE_DOWN'			=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_down.gif" alt="' . $this->language->lang('MOVE_DOWN') . '" title="' . $this->language->lang('MOVE_DOWN') . '" />',
			'ICON_MOVE_DOWN_DISABLED'	=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_down_disabled.gif" alt="' . $this->language->lang('MOVE_DOWN') . '" title="' . $this->language->lang('MOVE_DOWN') . '" />',
			'ICON_EDIT'					=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_edit.gif" alt="' . $this->language->lang('EDIT') . '" title="' . $this->language->lang('EDIT') . '" />',
			'ICON_EDIT_DISABLED'		=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_edit_disabled.gif" alt="' . $this->language->lang('EDIT') . '" title="' . $this->language->lang('EDIT') . '" />',
			'ICON_DELETE'				=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_delete.gif" alt="' . $this->language->lang('DELETE') . '" title="' . $this->language->lang('DELETE') . '" />',
			'ICON_DELETE_DISABLED'		=> '<img src="' . htmlspecialchars($this->phpbb_admin_path) . 'images/icon_delete_disabled.gif" alt="' . $this->language->lang('DELETE') . '" title="' . $this->language->lang('DELETE') . '" />',
		));
	}

	/**
	 * Add a message
	 *
	 * @param $parent_id int  Optionally set what parent ID the canned message is being added for
	 */
	protected function action_add($parent_id)
	{
		if ($this->request->is_set_post('submit'))
		{
			$cannedmessage = $this->data_setup(array());

			if ($this->action_save($cannedmessage))
			{
				$this->log('ADD', $cannedmessage['cannedmessage_name']);
				$this->success('CANNEDMESSAGE_CREATED');
			}
		}
		else
		{
			$cannedmessage = $this->data_setup([
				'parent_id'	=> $parent_id,
			]);
		}

		$this->page_setup($cannedmessage);
	}

	/**
	 * Edit a message
	 *
	 * @param $cannedmessage_id	integer	The message ID to edit
	 */
	protected function action_edit($cannedmessage_id)
	{
		if ($this->request->is_set_post('submit'))
		{
			$cannedmessage = $this->data_setup([
				'cannedmessage_id'	=> $cannedmessage_id,
			]);

			if ($this->action_save($cannedmessage))
			{
				$this->log('EDIT', $cannedmessage['cannedmessage_name']);
				$this->success('CANNEDMESSAGE_UPDATED');
			}
		}
		else
		{
			$cannedmessage = $this->data_setup($this->manager->get_message($cannedmessage_id));
		}

		$this->page_setup($cannedmessage);
	}

	/**
	 * Saves canned message data
	 *
	 * @param $cannedmessage_data array The data to save
	 * @return bool  Save result
	 */
	protected function action_save($cannedmessage_data)
	{
		if (!check_form_key('phpbb_cannedmessages'))
		{
			$this->errors[] = $this->language->lang('FORM_INVALID');
			return false;
		}

		if (empty($cannedmessage_data['cannedmessage_name']))
		{
			$this->errors[] = $this->language->lang('MESSAGE_NAME_REQUIRED');
		}

		if (!$cannedmessage_data['is_cat'] && empty($cannedmessage_data['cannedmessage_content']))
		{
			$this->errors[] = $this->language->lang('MESSAGE_CONTENT_REQUIRED');
		}

		if (count($this->errors))
		{
			return false;
		}

		$result = $this->manager->save_message($cannedmessage_data);

		if (!$result['success'])
		{
			$this->errors[] = $result['errors'];
			return false;
		}

		return true;
	}

	/**
	 * Sets up the canned message data
	 *
	 * @param $cannedmessage array  Data of existing canned message data
	 * @return array  Information from either the sent in data or from the request object
	 */
	protected function data_setup($cannedmessage)
	{
		return [
			'cannedmessage_id'			=> isset($cannedmessage['cannedmessage_id']) ? $cannedmessage['cannedmessage_id'] : 0,
			'cannedmessage_name'		=> $this->request->variable('cannedmessage_name', isset($cannedmessage['cannedmessage_name']) ? $cannedmessage['cannedmessage_name'] : ''),
			'parent_id'					=> $this->request->variable('cannedmessage_parent', isset($cannedmessage['parent_id']) ? $cannedmessage['parent_id'] : 0),
			'is_cat'					=> $this->request->variable('is_cat', isset($cannedmessage['is_cat']) ? $cannedmessage['is_cat'] : 0),
			'cannedmessage_content'		=> $this->request->variable('cannedmessage_content', isset($cannedmessage['cannedmessage_content']) ? $cannedmessage['cannedmessage_content'] : ''),
		];
	}

	/**
	 * Sets up the page elements for canned messages
	 *
	 * @param $cannedmessage_data array  The canned message data with which to set up the page
	 */
	protected function page_setup($cannedmessage_data)
	{
		add_form_key('phpbb_cannedmessages');

		if ($this->action === 'edit')
		{
			$u_action = "{$this->u_action}&amp;action=edit&amp;cannedmessage_id={$cannedmessage_data['cannedmessage_id']}&amp;hash=" . generate_link_hash('edit' . $cannedmessage_data['cannedmessage_id']);
		}
		else
		{
			$u_action = "{$this->u_action}&amp;action=add" . ($cannedmessage_data['parent_id'] > 0 ? "&amp;parent_id={$cannedmessage_data['parent_id']}" : '');
		}

		$this->template->assign_vars(array(
			'S_ERROR'   => (bool) count($this->errors),
			'ERROR_MSG' => count($this->errors) ? implode('<br />', $this->errors) : '',

			'S_CANNEDMESSAGE_ADD_OR_EDIT'	=> true,
			'U_ACTION'						=> $u_action,
			'CANNESMESSAGE_NAME'			=> $cannedmessage_data['cannedmessage_name'],
			'S_CANNEDMESSAGE_PARENTS'		=> $this->manager->get_messages(false, null, true, $cannedmessage_data['parent_id']),
			'IS_CAT'						=> $cannedmessage_data['is_cat'],
			'CANNEDMESSAGE_CONTENT'			=> $cannedmessage_data['cannedmessage_content'],
		));
	}

	/**
	 * Log action
	 *
	 * @param	string	$action				Performed action in uppercase
	 * @param	string	$cannedmessage_name	Canned message name
	 * @return	void
	 */
	public function log($action, $cannedmessage_name)
	{
		$this->log->add('mod', $this->user->data['user_id'], $this->user->ip, "MCP_CANNEDMESSAGE_{$action}_LOG", time(), array($cannedmessage_name));
	}

	/**
	 * Handles success for the page
	 *
	 * @param $message string The lang key to use for the success message
	 */
	protected function success($message)
	{
		$redirect = $this->u_action;
		meta_refresh(3, $redirect);
		trigger_error($this->language->lang($message) . '<br /><br />' . sprintf($this->language->lang('RETURN_PAGE'), '<a href="' . $redirect . '">', '</a>'));
	}
}