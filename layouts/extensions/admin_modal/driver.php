<?php
/**
 * @version     $id: driver.php
 * @package     Molajo
 * @subpackage  Layout Driver
 * @copyright   Copyright (C) 2011 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;

/** custom css/js **/
$document =& MolajoFactory::getDocument();
$document->addStyleSheet('../media/'.$this->request['option'].'/css/administrator.css');

/** component parameters **/
$this->state = JComponentHelper::getParams($this->request['option']);

/** output **/
include $this->layoutHelper->getPath ('modal_item.php');
