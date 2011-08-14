<?php
/**
 * @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
 * @package		Joomla.Administrator
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$shownew 	= (boolean)$this->params->get('shownew', 1);
$showhelp 	= $this->params->get('showhelp', 1);

//
// Site SubMenu
//
$menu->addChild(
new MolajoMenuNode(JText::_('JSITE'), '#'), true
);


$menu->addChild(
new MolajoMenuNode(JText::_('MOD_MENU_CONTROL_PANEL'), 'index.php', 'class:control-panel')
);

$menu->addSeparator();

$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_USER_PROFILE'), 'index.php?option=com_admin&task=profile.edit&id='.$this->user->id, 'class:profile'));
$menu->addSeparator();

if ($this->user->authorise('core.admin')) {
	$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_CONFIGURATION'), 'index.php?option=com_config', 'class:config'));
	$menu->addSeparator();
}

$chm = $this->user->authorise('core.admin', 'com_checkin');
$cam = $this->user->authorise('core.manage', 'com_cache');

if ($chm || $cam )
{
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_MAINTENANCE'), 'index.php?option=com_checkin', 'class:maintenance'), true
	);

	if ($chm)
	{
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_GLOBAL_CHECKIN'), 'index.php?option=com_checkin', 'class:checkin'));
		$menu->addSeparator();
	}
	if ($cam)
	{
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_CLEAR_CACHE'), 'index.php?option=com_cache', 'class:clear'));
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_PURGE_EXPIRED_CACHE'), 'index.php?option=com_cache&view=purge', 'class:purge'));
	}

	$menu->getParent();
}

$menu->addSeparator();
if ($this->user->authorise('core.admin')) {
	$menu->addChild(
		new MolajoMenuNode(JText::_('MOD_MENU_SYSTEM_INFORMATION'), 'index.php?option=com_admin&view=sysinfo', 'class:info')
	);
	$menu->addSeparator();
}

$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_LOGOUT'), JRoute::_('index.php?option=com_login&task=logout&'. JUtility::getToken() .'=1'), 'class:logout'));

$menu->getParent();


//
// Users Submenu
//
if ($this->user->authorise('core.manage', 'com_users'))
{
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_USERS'), '#'), true
	);
	$createUser = $shownew && $this->user->authorise('core.create', 'com_users');
	$createGrp	= $this->user->authorise('core.admin', 'com_users');

	$menu->addChild(
		new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_USER_MANAGER'), 'index.php?option=com_users&view=users', 'class:user'),
		$createUser
	);
	if ($createUser) {
		$menu->addChild(
			new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_USER'), 'index.php?option=com_users&task=user.add', 'class:newarticle')
		);
		$menu->getParent();
	}

	if ($createGrp) {
		$menu->addChild(
			new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_GROUPS'), 'index.php?option=com_users&view=groups', 'class:groups'),
			$createUser
		);
		if ($createUser) {
			$menu->addChild(
				new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_GROUP'), 'index.php?option=com_users&task=group.add', 'class:newarticle')
			);
			$menu->getParent();
		}
		$menu->addChild(
			new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_LEVELS'), 'index.php?option=com_users&view=levels', 'class:levels'),
			$createUser
		);
		if ($createUser) {
			$menu->addChild(
			new MolajoMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_LEVEL'), 'index.php?option=com_users&task=level.add', 'class:newarticle')
			);
			$menu->getParent();
		}
	}

	$menu->addSeparator();
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_MASS_MAIL_USERS'), 'index.php?option=com_users&view=mail', 'class:massmail')
	);

	$menu->getParent();
}

//
// Menus Submenu
//
if ($this->user->authorise('core.manage', 'com_menus'))
{
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_MENUS'), '#'), true
	);
	$createMenu = $shownew && $this->user->authorise('core.create', 'com_menus');

	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_MENU_MANAGER'), 'index.php?option=com_menus&view=menus', 'class:menumgr'), $createMenu
	);
	if ($createMenu) {
		$menu->addChild(
		new MolajoMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU'), 'index.php?option=com_menus&view=menu&layout=edit', 'class:newarticle')
		);
		$menu->getParent();
	}
	$menu->addSeparator();

	// Menu Types
	foreach (MolajoLaunchpadHelper::getMenus() as $menuType)
	{
		$alt = '*' .$menuType->sef. '*';
		if ($menuType->home == 0)
		{
			$titleicon = '';
		}
		elseif ($menuType->home == 1 && $menuType->language == '*')
		{
			$titleicon = ' <span>'.JHtml::_('image','menu/icon-16-default.png', '*', array('title' => JText::_('MOD_MENU_HOME_DEFAULT')), true).'</span>';
		}
		elseif ($menuType->home > 1)
		{
			$titleicon = ' <span>'.JHtml::_('image','menu/icon-16-language.png', $menuType->home, array('title' => JText::_('MOD_MENU_HOME_MULTIPLE')), true).'</span>';
		}
		else
		{
			$image = JHtml::_('image','mod_languages/'.$menuType->image.'.gif', NULL, NULL, true, true);
			if (!$image)
			{
				$titleicon = ' <span>'.JHtml::_('image','menu/icon-16-language.png', $alt, array('title' => $menuType->title_native), true).'</span>';
			}
			else
			{
				$titleicon = ' <span>'.JHtml::_('image', 'mod_languages/'.$menuType->image.'.gif', $alt, array('title'=>$menuType->title_native), true).'</span>';
			}
		}
		$menu->addChild(
		new MolajoMenuNode($menuType->title, 'index.php?option=com_menus&view=items&menutype='.$menuType->menutype, 'class:menu', null, null, $titleicon), $createMenu
				);

		if ($createMenu) {
				$menu->addChild(
				new MolajoMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU_ITEM'), 'index.php?option=com_menus&view=item&layout=edit&menutype='.$menuType->menutype, 'class:newarticle')
				);
				$menu->getParent();
		}
	}
	$menu->getParent();
}

//
// Content Submenu
//
if ($this->user->authorise('core.manage', 'com_content'))
{
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_COM_ARTICLES'), '#'), true
	);
	$createContent =  $shownew && $this->user->authorise('core.create', 'com_content');
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_COM_ARTICLES_ARTICLE_MANAGER'), 'index.php?option=com_content', 'class:article'), $createContent
	);
	if ($createContent) {
		$menu->addChild(
		new MolajoMenuNode(JText::_('MOD_MENU_COM_ARTICLES_NEW_ARTICLE'), 'index.php?option=com_content&task=article.add', 'class:newarticle')
		);
		$menu->getParent();
	}

	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_COM_ARTICLES_CATEGORY_MANAGER'), 'index.php?option=com_categories&extension=com_content', 'class:category'), $createContent
	);
	if ($createContent) {
		$menu->addChild(
		new MolajoMenuNode(JText::_('MOD_MENU_COM_ARTICLES_NEW_CATEGORY'), 'index.php?option=com_categories&task=category.add&extension=com_content', 'class:newarticle')
		);
		$menu->getParent();
	}
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_COM_ARTICLES_FEATURED'), 'index.php?option=com_content&view=featured', 'class:featured')
	);
	$menu->addSeparator();
	if ($this->user->authorise('core.manage', 'com_media')) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_MEDIA_MANAGER'), 'index.php?option=com_media', 'class:media'));
	}

	$menu->getParent();
}

//
// Components Submenu
//

// Get the authorised components and sub-menus.
$components = MolajoLaunchpadHelper::getComponents( true );

// Check if there are any components, otherwise, don't render the menu
if ($components) {
	$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_COMPONENTS'), '#'), true);

	foreach ($components as &$component) {
		if (!empty($component->submenu)) {
			// This component has a db driven submenu.
			$menu->addChild(new MolajoMenuNode($component->text, $component->link, $component->img), true);
			foreach ($component->submenu as $sub) {
				$menu->addChild(new MolajoMenuNode($sub->text, $sub->link, $sub->img));
			}
			$menu->getParent();
		}
		else {
			$menu->addChild(new MolajoMenuNode($component->text, $component->link, $component->img));
		}
	}
	$menu->getParent();
}

//
// Extensions Submenu
//
$im = $this->user->authorise('core.manage', 'com_installer');
$mm = $this->user->authorise('core.manage', 'com_modules');
$pm = $this->user->authorise('core.manage', 'com_plugins');
$tm = $this->user->authorise('core.manage', 'com_templates');
$lm = $this->user->authorise('core.manage', 'com_languages');

if ($im || $mm || $pm || $tm || $lm)
{
	$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSIONS'), '#'), true);

	if ($im) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSION_MANAGER'), 'index.php?option=com_installer', 'class:install'));
		$menu->addSeparator();
	}
	if ($mm) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_MODULE_MANAGER'), 'index.php?option=com_modules', 'class:module'));
	}
	if ($mm) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_LAYOUTS_MANAGER'), 'index.php?option=com_modules', 'class:module'));
	}
	if ($pm) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_PLUGIN_MANAGER'), 'index.php?option=com_plugins', 'class:plugin'));
	}
	if ($tm) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER'), 'index.php?option=com_templates', 'class:themes'));
	}
	if ($lm) {
		$menu->addChild(new MolajoMenuNode(JText::_('MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER'), 'index.php?option=com_languages', 'class:language'));
	}
	$menu->getParent();
}

//
// Help Submenu
//
if ($showhelp == 1) {
	$menu->addChild(
	new MolajoMenuNode(JText::_('MOD_MENU_HELP'), '#'), true
	);
}
