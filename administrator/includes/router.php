<?php
/**
 * @version		$Id: router.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('MOLAJO') or die;

/**
 * Class to create and parse routes
 *
 * @package		Joomla.Administrator
 * @since		1.5
 */
class MolajoRouterAdministrator extends MolajoRouter
{
	/**
	 * Function to convert a route to an internal URI
	 */
	public function parse(&$uri)
	{
		return array();
	}

	/**
	 * Function to convert an internal URI to a route
	 *
	 * @param	string	$uri	The internal URL
	 *
	 * @return	string	The absolute search engine friendly URL
	 * @since	1.0
	 */
	function build($url)
	{
		// Create the URI object
		$uri = parent::build($url);

		// Get the path data
		$route = $uri->getPath();

		//Add basepath to the uri
		$uri->setPath(JURI::base(true).'/'.$route);

		return $uri;
	}
}
