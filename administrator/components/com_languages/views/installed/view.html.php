<?php
/**
 * @version		$Id: view.html.php 21655 2011-06-23 05:43:24Z chdemko $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Molajo
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Displays a list of the installed languages.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * * * @since		1.0
 */
class LanguagesViewInstalled extends JView
{
	/**
	 * @var object application object
	 */
	protected $application = null;

	/**
	 * @var boolean|JExeption True, if FTP settings should be shown, or an exeption
	 */
	protected $ftp = null;

	/**
	 * @var string option name
	 */
	protected $option = null;

	/**
	 * @var object pagination information
	 */
	protected $pagination=null;

	/**
	 * @var array languages information
	 */
	protected $rows=null;

	/**
	 * @var object user object
	 */
	protected $user = null;

	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		// Get data from the model
		$this->ftp			= $this->get('Ftp');
		$this->option		= $this->get('Option');
		$this->pagination	= $this->get('Pagination');
		$this->rows			= $this->get('Data');
		$this->state		= $this->get('State');

		$document = MolajoFactory::getDocument();
		$document->setBuffer($this->loadTemplate('navigation'), 'modules', 'submenu');

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
		require_once JPATH_COMPONENT.'/helpers/languages.php';

		$canDo	= LanguagesHelper::getActions();

		MolajoToolbarHelper::title(JText::_('COM_LANGUAGES_VIEW_INSTALLED_TITLE'), 'langmanager.png');

		if ($canDo->get('core.edit.state')) {
			MolajoToolbarHelper::makeDefault('installed.setDefault');
			MolajoToolbarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			MolajoToolbarHelper::preferences('com_languages');
			MolajoToolbarHelper::divider();
		}

		MolajoToolbarHelper::help('JHELP_EXTENSIONS_LANGUAGE_MANAGER_INSTALLED');
	}
}
