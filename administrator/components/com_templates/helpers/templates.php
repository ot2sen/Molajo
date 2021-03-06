<?php
/**
 * @version		$Id: templates.php 21650 2011-06-23 05:29:17Z chdemko $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Templates component helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * * * @since		1.0
 */
class TemplatesHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_TEMPLATES_SUBMENU_STYLES'),
			'index.php?option=com_templates&view=styles',
			$vName == 'styles'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_TEMPLATES_SUBMENU_TEMPLATES'),
			'index.php?option=com_templates&view=templates',
			$vName == 'templates'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 */
	public static function getActions()
	{
		$user	= MolajoFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, 'com_templates'));
		}

		return $result;
	}

	/**
	 * Get a list of filter options for the application applications.
	 *
	 * @return	array	An array of JHtmlOption elements.
	 */
	public static function getApplicationOptions()
	{
		// Build the filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option', '0', JText::_('JSITE'));
		$options[]	= JHtml::_('select.option', '1', JText::_('JADMINISTRATOR'));

		return $options;
	}

	/**
	 * Get a list of filter options for the templates with styles.
	 *
	 * @return	array	An array of JHtmlOption elements.
	 */
	public static function getTemplateOptions($applicationId = '*')
	{
		// Build the filter options.
		$db = MolajoFactory::getDbo();
		$query = $db->getQuery(true);

		if ($applicationId != '*') {
			$query->where('application_id='.(int) $applicationId);
		}

		$query->select('element as value, name as text');
		$query->from('#__extensions');
		$query->where('type='.$db->quote('template'));
		$query->where('enabled=1');
		$query->order('application_id');
		$query->order('name');
		$db->setQuery($query);
		$options = $db->loadObjectList();
		return $options;
	}

	public static function parseXMLTemplateFile($templateBaseDir, $templateDir)
	{
		$data = new JObject;

		// Check of the xml file exists
		$filePath = JPath::clean($templateBaseDir.'/templates/'.$templateDir.'/templateDetails.xml');
		if (is_file($filePath))
		{
			$xml = JApplicationHelper::parseXMLInstallFile($filePath);

			if ($xml['type'] != 'template') {
				return false;
			}

			foreach ($xml as $key => $value) {
				$data->set($key, $value);
			}
		}

		return $data;
	}
}
