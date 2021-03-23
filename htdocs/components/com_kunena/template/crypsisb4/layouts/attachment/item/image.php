<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsisb4
 * @subpackage      BBCode
 *
 * @copyright       Copyright (C) 2008 - 2021 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

$attachment = $this->attachment;

$name = preg_replace('/.html/', '', $attachment->getUrl());

if (!$attachment->isImage())
{
	return;
}

$config = KunenaFactory::getConfig();

// Load FancyBox library if enabled in configuration
if ($config->lightbox == 1)
{
	echo $this->subLayout('Widget/Lightbox');

	$config = KunenaConfig::getInstance();

	$attributesLink = $config->lightbox ? ' data-fancybox="gallery"' : '';
	$attributesImg  = ' style="max-height:' . (int) $config->imageheight . 'px;"';

	?>
	<a href="<?php echo $attachment->getUrl(); ?>"
	   title="<?php echo $attachment->getShortName($config->attach_start, $config->attach_end); ?>"<?php echo $attributesLink; ?>>
		<img src="<?php echo $attachment->getUrl(); ?>"<?php echo $attributesImg; ?>
			 width="<?php echo $attachment->width; ?>"
			 height="<?php echo $attachment->height; ?>"
			 alt="<?php echo $attachment->getFilename(); ?>"/>
	</a>
	<?php
}
else
{
	?>
	<a href="<?php echo $name; ?>"
	   title="<?php echo $attachment->getShortName($config->attach_start, $config->attach_end); ?>"<?php echo $attributesLink; ?>>
		<img class="kmsimage" src="<?php echo $name; ?>"<?php echo $attributesImg; ?>
			 width="<?php echo $config->thumbwidth; ?>"
			 height="<?php echo $config->thumbheight; ?>" alt="<?php echo $attachment->getFilename(); ?>"/>
	</a>
	<?php
}
