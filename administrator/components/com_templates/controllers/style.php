<?php
/**
 * @version		$Id: style.php 21032 2011-03-29 16:38:31Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Template style controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * * * @since		1.0
 */
class TemplatesControllerStyle extends JControllerForm
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.0
	 */
	protected $text_prefix = 'COM_TEMPLATES_STYLE';

}