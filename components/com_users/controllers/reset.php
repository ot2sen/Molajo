<?php
/**
 * @version		$Id: reset.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Reset controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @version		1.6
 */
class UsersControllerReset extends UsersController
{
	/**
	 * Method to request a password reset.
	 *
	 * @since	1.6
	 */
	public function request()
	{
		// Check the request token.
		JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

		$app	= MolajoFactory::getApplication();
		$model	= $this->getModel('Reset', 'UsersModel');
		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Submit the password reset request.
		$return	= $model->processResetRequest($data);

		// Check for a hard error.
		if (JError::isError($return)) {
			// Get the error message to display.
			if ($app->getCfg('error_reporting')) {
				$message = $return->getMessage();
			} else {
				$message = JText::_('COM_USERS_RESET_REQUEST_ERROR');
			}

			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset'.$itemid;

			// Go back to the request form.
			$this->setRedirect(MolajoRoute::_($route, false), $message, 'error');
			return false;
		} elseif ($return === false) {
			// The request failed.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset'.$itemid;

			// Go back to the request form.
			$message = JText::sprintf('COM_USERS_RESET_REQUEST_FAILED', $model->getError());
			$this->setRedirect(MolajoRoute::_($route, false), $message, 'notice');
			return false;
		} else {
			// The request succeeded.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset&layout=confirm'.$itemid;

			// Proceed to step two.
			$this->setRedirect(MolajoRoute::_($route, false), $message);
			return true;
		}
	}

	/**
	 * Method to confirm the password request.
	 *
	 * @access	public
	 * @since	1.0
	 */
	function confirm()
	{
		// Check the request token.
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$app	= MolajoFactory::getApplication();
		$model	= $this->getModel('Reset', 'UsersModel');
		$data	= JRequest::getVar('jform', array(), 'request', 'array');

		// Confirm the password reset request.
		$return	= $model->processResetConfirm($data);

		// Check for a hard error.
		if (JError::isError($return))
		{
			// Get the error message to display.
			if ($app->getCfg('error_reporting')) {
				$message = $return->getMessage();
			} else {
				$message = JText::_('COM_USERS_RESET_CONFIRM_ERROR');
			}

			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset&layout=confirm'.$itemid;

			// Go back to the confirm form.
			$this->setRedirect(MolajoRoute::_($route, false), $message, 'error');
			return false;
		} elseif ($return === false) {
			// Confirm failed.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset&layout=confirm'.$itemid;

			// Go back to the confirm form.
			$message = JText::sprintf('COM_USERS_RESET_CONFIRM_FAILED', $model->getError());
			$this->setRedirect(MolajoRoute::_($route, false), $message, 'notice');
			return false;
		} else {
			// Confirm succeeded.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset&layout=complete'.$itemid;

			// Proceed to step three.
			$this->setRedirect(MolajoRoute::_($route, false));
			return true;
		}
	}

	/**
	 * Method to complete the password reset process.
	 *
	 * @since	1.6
	 */
	public function complete()
	{
		// Check for request forgeries
		JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

		$app	= MolajoFactory::getApplication();
		$model	= $this->getModel('Reset', 'UsersModel');		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Complete the password reset request.
		$return	= $model->processResetComplete($data);

		// Check for a hard error.
		if (JError::isError($return)) {
			// Get the error message to display.
			if ($app->getCfg('error_reporting')) {
				$message = $return->getMessage();
			} else {
				$message = JText::_('COM_USERS_RESET_COMPLETE_ERROR');
			}

			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset&layout=complete'.$itemid;

			// Go back to the complete form.
			$this->setRedirect(MolajoRoute::_($route, false), $message, 'error');
			return false;
		} elseif ($return === false) {
			// Complete failed.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getResetRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=reset&layout=complete'.$itemid;

			// Go back to the complete form.
			$message = JText::sprintf('COM_USERS_RESET_COMPLETE_FAILED', $model->getError());
			$this->setRedirect(MolajoRoute::_($route, false), $message, 'notice');
			return false;
		} else {
			// Complete succeeded.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getLoginRoute();
			$itemid = $itemid !== null ? '&Itemid='.$itemid : '';
			$route	= 'index.php?option=com_users&view=login'.$itemid;

			// Proceed to the login form.
			$message = JText::_('COM_USERS_RESET_COMPLETE_SUCCESS');
			$this->setRedirect(MolajoRoute::_($route, false), $message);
			return true;
		}
	}
}