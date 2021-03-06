<?php
/**
 * @package    Molajo
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('MOLAJO') or die;

/**
 * Renders a button separator
 *
 * @package    Molajo
 * @subpackage  HTML
 * @since       1.0
 */
class MolajoButtonSeparator extends MolajoButton
{
	/**
	 * Button type
	 *
	 * @var   string
	 */
	protected $_name = 'Separator';

	public function render(&$definition)
	{
		// Initialise variables.
		$class	= null;
		$style	= null;

		// Separator class name
		$class = (empty($definition[1])) ? 'spacer' : $definition[1];
		// Custom width
		$style = (empty($definition[2])) ? null : ' style="width:'. intval($definition[2]).'px;"';

		return '<li class="'.$class.'"'.$style.">\n</li>\n";
	}

	/**
	 * Empty implementation (not required)
	 */
	public function fetchButton()
	{
	}
}
