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
		<div class="button1-right"><div class="prev"><a href="index.php?view=preinstall" onclick="return Install.goToPage('preinstall');" rel="prev" title="<?php echo JText::_('JPREVIOUS'); ?>"><?php echo JText::_('JPREVIOUS'); ?></a></div></div>
		<div class="button1-left"><div class="next"><a href="index.php?view=database" onclick="return Install.goToPage('database');" rel="next" title="<?php echo JText::_('JNEXT'); ?>"><?php echo JText::_('JNEXT'); ?></a></div></div>
<?php elseif ($this->document->direction == 'rtl') : ?>
		<div class="button1-right"><div class="prev"><a href="index.php?view=database" onclick="return Install.goToPage('database');" rel="next" title="<?php echo JText::_('JNEXT'); ?>"><?php echo JText::_('JNEXT'); ?></a></div></div>
		<div class="button1-left"><div class="next"><a href="index.php?view=preinstall" onclick="return Install.goToPage('preinstall');" rel="prev" title="<?php echo JText::_('JPREVIOUS'); ?>"><?php echo JText::_('JPREVIOUS'); ?></a></div></div>
<?php endif; ?>
	</div>
	<span class="steptitle"><?php echo JText::_('INSTL_LICENSE'); ?></span>
</div>
<form action="index.php" method="post" id="adminForm" class="form-validate">
	<div id="installer">
		<div class="m">
			<h2><?php echo JText::_('INSTL_GNU_GPL_LICENSE'); ?></h2>
			<iframe src="gpl.html" class="license" marginwidth="25" scrolling="auto"></iframe>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
