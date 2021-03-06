<?php
/**
 * @version		$Id: view.html.php 21655 2011-06-23 05:43:24Z chdemko $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 */
class UsersViewUser extends JView
{
	protected $form;
	protected $item;
	protected $grouplist;
	protected $groups;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form			= $this->get('Form');
		$this->item			= $this->get('Item');
		$this->grouplist	= $this->get('Groups');
		$this->groups		= $this->get('AssignedGroups');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->form->setValue('password',		null);
		$this->form->setValue('password2',	null);

		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.0
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', 1);

		$user		= MolajoFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= UsersHelper::getActions();


		$isNew	= ($this->item->id == 0);
		$isProfile = $this->item->id == $user->id;
		MolajoToolbarHelper::title(JText::_($isNew ? 'COM_USERS_VIEW_NEW_USER_TITLE' : ($isProfile ? 'COM_USERS_VIEW_EDIT_PROFILE_TITLE' : 'COM_USERS_VIEW_EDIT_USER_TITLE')), $isNew ? 'user-add' : ($isProfile ? 'user-profile' : 'user-edit'));
		if ($canDo->get('core.edit')||$canDo->get('core.edit.own')||$canDo->get('core.create')) {
			MolajoToolbarHelper::apply('user.apply');
			MolajoToolbarHelper::save('user.save');
		}
		if ($canDo->get('core.create')&&$canDo->get('core.manage')) {
			MolajoToolbarHelper::save2new('user.save2new');
		}
		if (empty($this->item->id))  {
			MolajoToolbarHelper::cancel('user.cancel');
		} else {
			MolajoToolbarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
		}
		MolajoToolbarHelper::divider();
		MolajoToolbarHelper::help('JHELP_USERS_USER_MANAGER_EDIT');
	}
}
