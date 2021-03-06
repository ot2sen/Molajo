<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Utility class working with menu select lists
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
abstract class JHtmlMenu
{
	/**
	 * @var    array  Cached array of the menus.
	 */
	protected static $menus = null;

	/**
	 * @var    array  Cached array of the menus items.
	 */
	protected static $items = null;

	/**
	 * Get a list of the available menus.
	 *
	 * @return  string
	 * @since   11.1
	 */
	public static function menus()
	{
		if (empty(self::$menus))
		{
			$db = JFactory::getDbo();
			$db->setQuery(
				'SELECT menu_id As value, title As text' .
				' FROM #__menus' .
				' ORDER BY title'
			);
			self::$menus = $db->loadObjectList();
		}

		return self::$menus;
	}

	/**
	 * Returns an array of menu items groups by menu.
	 *
	 * @param   array  An array of configuration options.
	 *
	 * @return  array
	 */
	public static function menuitems($config = array())
	{
		if (empty(self::$items))
		{
			$db = JFactory::getDbo();
			$db->setQuery(
				'SELECT menu_id AS value, title AS text' .
				' FROM #__menus' .
				' ORDER BY title'
			);
			$menus = $db->loadObjectList();

			$query	= $db->getQuery(true);
			$query->select('a.id AS value, a.title AS text, a.level, a.menu_id');
			$query->from('#__menu_items AS a');
			$query->where('a.parent_id > 0');
			$query->where('a.type <> '.$db->quote('url'));
			$query->where('a.client_id = 0');

			// Filter on the published state
			if (isset($config['published'])) {
				if (is_numeric($config['published'])) {
					$query->where('a.published = '.(int) $config['published']);
				} else if ($config['published'] === '') {
					$query->where('a.published IN (0,1)');
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();

			// Collate menu items based on menu_id
			$lookup = array();
			foreach ($items as &$item) {
				if (!isset($lookup[$item->menu_id])) {
					$lookup[$item->menu_id] = array();
				}
				$lookup[$item->menu_id][] = &$item;

				$item->text = str_repeat('- ',$item->level).$item->text;
			}
			self::$items = array();

			foreach ($menus as &$menu) {
				// Start group:
				self::$items[] = JHtml::_('select.optgroup',	$menu->text);

				// Special "Add to this Menu" option:
				self::$items[] = JHtml::_('select.option', $menu->value.'.1', JText::_('MOLAJO_HTML_ADD_TO_THIS_MENU'));

				// Menu items:
				if (isset($lookup[$menu->value])) {
					foreach ($lookup[$menu->value] as &$item) {
						self::$items[] = JHtml::_('select.option', $menu->value.'.'.$item->value, $item->text);
					}
				}

				// Finish group:
				self::$items[] = JHtml::_('select.optgroup',	$menu->text);
			}
		}

		return self::$items;
	}

	/**
	 * Displays an HTML select list of menu items.
	 *
	 * @param   string   The name of the control.
	 * @param   string   The value of the selected option.
	 * @param   string   Attributes for the control.
	 * @param   array    An array of options for the control.
	 *
	 * @return  string
	 */
	public static function menuitemlist($name, $selected = null, $attribs = null, $config = array())
	{
		static $count;

		$options = self::menuitems($config);

		return JHtml::_(
			'select.genericlist',
			$options,
			$name,
			array(
				'id' =>				isset($config['id']) ? $config['id'] : 'assetgroups_'.++$count,
				'list.attr' =>		(is_null($attribs) ? 'class="inputbox" size="1"' : $attribs),
				'list.select' =>	(int) $selected,
				'list.translate' => false
			)
		);
	}


	/**
	 * Build the select list for Menu Ordering
	 */
	public static function ordering(&$row, $id)
	{
		$db = JFactory::getDbo();

		if ($id)
		{
			$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__menu_items'
			. ' WHERE menu_id = '.$db->Quote($row->menu_id)
			. ' AND parent_id = '.(int) $row->parent_id
			. ' AND published != -2'
			. ' ORDER BY ordering';
			$order = JHtml::_('list.genericordering',  $query);
			$ordering = JHtml::_(
				'select.genericlist',
				$order,
				'ordering',
				array('list.attr' => 'class="inputbox" size="1"', 'list.select' => intval($row->ordering))
			);
		}
		else
		{
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. JText::_('JGLOBAL_NEWITEMSLAST_DESC');
		}
		return $ordering;
	}

	/**
	 * Build the multiple select list for Menu Links/Pages
	 */
	public static function linkoptions($all=false, $unassigned=false)
	{
		$db = JFactory::getDbo();

		// get a list of the menu items
		$query = 'SELECT m.id, m.parent_id, m.title, m.menu_id'
		. ' FROM #__menu_items AS m'
		. ' WHERE m.published = 1'
		. ' ORDER BY m.menu_id, m.parent_id, m.ordering'
		;
		$db->setQuery($query);

		$mitems = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
		}

		if (!$mitems) {
			$mitems = array();
		}

		$mitems_temp = $mitems;

		// Establish the hierarchy of the menu
		$children = array();
		// First pass - collect children
		foreach ($mitems as $v)
		{
			$id = $v->id;
			$pt = $v->parent_id;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push($list, $v);
			$children[$pt] = $list;
		}
		// Second pass - get an indent list of the items
		$list = JHtmlMenu::TreeRecurse(intval($mitems[0]->parent_id), '', array(), $children, 9999, 0, 0);

		// Code that adds menu name to Display of Page(s)
		$mitems_spacer	= $mitems_temp[0]->menu_id;

		$mitems = array();
		if ($all | $unassigned) {
			$mitems[] = JHtml::_('select.option',  '<OPTGROUP>', JText::_('JOPTION_MENUS'));

			if ($all) {
				$mitems[] = JHtml::_('select.option',  0, JText::_('JALL'));
			}
			if ($unassigned) {
				$mitems[] = JHtml::_('select.option',  -1, JText::_('JOPTION_UNASSIGNED'));
			}

			$mitems[] = JHtml::_('select.option',  '</OPTGROUP>');
		}

		$lastMenuType	= null;
		$tmpMenuType	= null;
		foreach ($list as $list_a)
		{
			if ($list_a->menu_id != $lastMenuType)
			{
				if ($tmpMenuType) {
					$mitems[] = JHtml::_('select.option',  '</OPTGROUP>');
				}
				$mitems[] = JHtml::_('select.option',  '<OPTGROUP>', $list_a->menu_id);
				$lastMenuType = $list_a->menu_id;
				$tmpMenuType  = $list_a->menu_id;
			}

			$mitems[] = JHtml::_('select.option',  $list_a->id, $list_a->title);
		}
		if ($lastMenuType !== null) {
			$mitems[] = JHtml::_('select.option',  '</OPTGROUP>');
		}

		return $mitems;
	}

	public static function treerecurse($id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1)
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->id;

				if ($type) {
					$pre	= '<sup>|_</sup>&#160;';
					$spacer = '.&#160;&#160;&#160;&#160;&#160;&#160;';
				} else {
					$pre	= '- ';
					$spacer = '&#160;&#160;';
				}

				if ($v->parent_id == 0) {
					$txt	= $v->title;
				} else {
					$txt	= $pre . $v->title;
				}
				$pt = $v->parent_id;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count(@$children[$id]);
				$list = JHtmlMenu::TreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type);
			}
		}
		return $list;
	}
}