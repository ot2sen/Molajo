<?php
/**
 * @version		$Id: menus.php 21471 2011-06-07 07:17:18Z chdemko $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Menu List Model for Menus.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * * * @since		1.0
 */
class MenusModelMenus extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'menu_id', 'a.menu_id',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.0.1
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items)) {
			return array();
		}

		// Getting the following metric by joins is WAY TOO SLOW.
		// Faster to do three queries for very large menu trees.

		// Get the menu types of menus in the list.
		$db = $this->getDbo();
		$menuTypes = JArrayHelper::getColumn($items, 'menu_id');

		// Quote the strings.
		$menuTypes = implode(
			',',
			array_map(array($db, 'quote'), $menuTypes)
		);

		// Get the published menu counts.
		$query = $db->getQuery(true)
			->select('m.menu_id, COUNT(DISTINCT m.id) AS count_published')
			->from('#__menu_items AS m')
			->where('m.published = 1')
			->where('m.menu_id IN ('.$menuTypes.')')
			->group('m.menu_id')
			;
		$db->setQuery($query);
		$countPublished = $db->loadAssocList('menu_id', 'count_published');

		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Get the unpublished menu counts.
		$query->clear('where')
			->where('m.published = 0')
			->where('m.menu_id IN ('.$menuTypes.')')
			;
		$db->setQuery($query);
		$countUnpublished = $db->loadAssocList('menu_id', 'count_published');

		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Get the trashed menu counts.
		$query->clear('where')
			->where('m.published = -2')
			->where('m.menu_id IN ('.$menuTypes.')')
			;
		$db->setQuery($query);
		$countTrashed = $db->loadAssocList('menu_id', 'count_published');

		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Inject the values back into the array.
		foreach ($items as $item)
		{
			$item->count_published		= isset($countPublished[$item->menu_id]) ? $countPublished[$item->menu_id] : 0;
			$item->count_unpublished	= isset($countUnpublished[$item->menu_id]) ? $countUnpublished[$item->menu_id] : 0;
			$item->count_trashed		= isset($countTrashed[$item->menu_id]) ? $countTrashed[$item->menu_id] : 0;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select all fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('`#__menus` AS a');

		$query->group('a.id');

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = MolajoFactory::getApplication('administrator');

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Gets a list of all mod_mainmenu modules and collates them by menu_id
	 *
	 * @return	array
	 */
	function &getModules()
	{
		$model	= JModel::getInstance('Menu', 'MenusModel', array('ignore_request' => true));
		$result	= &$model->getModules();

		return $result;
	}
}
