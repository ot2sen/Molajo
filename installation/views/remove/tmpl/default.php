<?php
/**
 * @package     Molajo
 * @subpackage  Installation
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2011 Amy Stephen. All rights reserved.
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined('MOLAJO') or die;
?>
<div id="step">
	<div class="far-right">
<?php if ($this->document->direction == 'ltr') : ?>
		<div class="button1-left"><div class="site"><a href="<?php echo JURI::root(); ?>" title="<?php echo JText::_('JSITE'); ?>"><?php echo JText::_('JSITE'); ?></a></div></div>
		<div class="button1-left"><div class="admin"><a href="<?php echo JURI::root(); ?>administrator/" title="<?php echo JText::_('JADMINISTRATOR'); ?>"><?php echo JText::_('JADMINISTRATOR'); ?></a></div></div>
<?php elseif ($this->document->direction == 'rtl') : ?>
		<div class="button1-left"><div class="admin"><a href="<?php echo JURI::root(); ?>administrator/" title="<?php echo JText::_('JADMINISTRATOR'); ?>"><?php echo JText::_('JADMINISTRATOR'); ?></a></div></div>
		<div class="button1-left"><div class="site"><a href="<?php echo JURI::root(); ?>" title="<?php echo JText::_('JSITE'); ?>"><?php echo JText::_('JSITE'); ?></a></div></div>
<?php endif; ?>
	</div>
	<span class="steptitle"><?php echo JText::_('INSTL_COMPLETE_REMOVE_FOLDER'); ?></span>
</div>
<div id="installer">
	<p class="error remove"><?php echo JText::_('INSTL_COMPLETE_REMOVE_INSTALLATION'); ?></p>
</div>
