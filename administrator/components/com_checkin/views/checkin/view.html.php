<?php
/**
 * @version		$Id: view.html.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	com_checkin
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Molajo
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Checkin component
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	com_checkin
 * @since 1.0
 */
class CheckinViewCheckin extends JView
{
	protected $tables;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.0
	 */
	protected function addToolbar()
	{
		MolajoToolbarHelper::title(JText::_('COM_CHECKIN_GLOBAL_CHECK_IN'), 'checkin.png');
		if (MolajoFactory::getUser()->authorise('core.admin', 'com_checkin')) {
			MolajoToolbarHelper::custom('checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			MolajoToolbarHelper::divider();
			MolajoToolbarHelper::preferences('com_checkin');
			MolajoToolbarHelper::divider();
		}
		MolajoToolbarHelper::help('JHELP_SITE_MAINTENANCE_GLOBAL_CHECK-IN');
	}
}
