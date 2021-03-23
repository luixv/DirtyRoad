<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.Statistics
 *
 * @copyright       Copyright (C) 2008 - 2021 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;

?>

<div class="kfrontend">
	<div class="btn-toolbar pull-right">
		<div class="btn-group">
			<div class="btn btn-small" data-toggle="collapse" data-target="#kwho"></div>
		</div>
	</div>
	<h2 class="btn-link">
		<?php if ($this->usersUrl)
			:
			?>
			<a href="<?php echo $this->usersUrl; ?>">
				<?php echo Text::_('COM_KUNENA_MEMBERS'); ?>
			</a>
		<?php else

			:
			?>
			<?php echo Text::_('COM_KUNENA_MEMBERS'); ?>
		<?php endif; ?>
	</h2>

	<div class="row-fluid collapse in" id="kwho">
		<div class="well-small">
			<ul class="unstyled span1 btn-link">
				<?php echo KunenaIcons::members(); ?>
			</ul>
			<ul class="unstyled span11">
			<li>
				<?php echo Text::sprintf('COM_KUNENA_VIEW_COMMON_WHO_TOTAL', $this->membersOnline); ?>
			</li>
				<?php
				$template  = KunenaTemplate::getInstance();
				$direction = $template->params->get('whoisonlineName');

				if ($direction == 'both')
					:
					?>
					<li><?php echo $this->setLayout('both'); ?></li>
				<?php
				elseif ($direction == 'avatar')
					:
					?>
					<li><?php echo $this->setLayout('avatar'); ?></li>
				<?php else

					:
					?>
					<li><?php echo $this->setLayout('name'); ?></li>
				<?php
				endif;
				?>

				<?php if (!empty($this->onlineList))
					:
					?>
					<li>
						<span><?php echo Text::_('COM_KUNENA_LEGEND'); ?>:</span>
						<span class="kwho-admin">
						<?php echo KunenaIcons::user() . ' ' . Text::_('COM_KUNENA_COLOR_ADMINISTRATOR'); ?>
					</span>
						<span class="kwho-globalmoderator">
						<?php echo KunenaIcons::user() . ' ' . Text::_('COM_KUNENA_COLOR_GLOBAL_MODERATOR'); ?>
					</span>
						<span class="kwho-moderator">
						<?php echo KunenaIcons::user() . ' ' . Text::_('COM_KUNENA_COLOR_MODERATOR'); ?>
					</span>
						<span class="kwho-banned">
						<?php echo KunenaIcons::user() . ' ' . Text::_('COM_KUNENA_COLOR_BANNED'); ?>
					</span>
						<span class="kwho-user">
						<?php echo KunenaIcons::user() . ' ' . Text::_('COM_KUNENA_COLOR_USER'); ?>
					</span>
						<span class="kwho-guest">
						<?php echo KunenaIcons::user() . ' ' . Text::_('COM_KUNENA_COLOR_GUEST'); ?>
					</span>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
