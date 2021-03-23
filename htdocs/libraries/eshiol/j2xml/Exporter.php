<?php
/**
 * @package		J2XML
 * @subpackage	lib_j2xml
 *
 * @author		Helios Ciancio <info (at) eshiol (dot) it>
 * @link		http://www.eshiol.it
 * @copyright	Copyright (C) 2010 - 2020 Helios Ciancio. All Rights Reserved
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3
 * J2XML is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
namespace eshiol\J2xml;

// no direct access
defined('_JEXEC') or die('Restricted access.');

// Import filesystem libraries.
jimport('joomla.filesystem.file');
jimport('joomla.log.log');
jimport('eshiol.J2xml.Table');
jimport('eshiol.J2xml.Version');

use eshiol\J2xml\Table\Category;
use eshiol\J2xml\Table\Contact;
use eshiol\J2xml\Table\Content;
use eshiol\J2xml\Table\Field;
use eshiol\J2xml\Table\Image;
use eshiol\J2xml\Table\User;
use eshiol\J2xml\Table\Usernote;
use eshiol\J2xml\Table\Viewlevel;
use eshiol\J2xml\Table\Weblink;
use eshiol\J2xml\Version;
\JLoader::import('eshiol.J2xml.Table.Category');
\JLoader::import('eshiol.J2xml.Table.Contact');
\JLoader::import('eshiol.J2xml.Table.Content');
\JLoader::import('eshiol.J2xml.Table.Field');
\JLoader::import('eshiol.J2xml.Table.Image');
\JLoader::import('eshiol.J2xml.Table.User');
\JLoader::import('eshiol.J2xml.Table.Usernote');
\JLoader::import('eshiol.J2xml.Table.Viewlevel');
\JLoader::import('eshiol.J2xml.Table.Weblink');
\JLoader::import('eshiol.J2xml.Version');

/**
 * Exporter
 *
 * @version 20.5.348
 * @since 1.5.2.14
 */
class Exporter
{

	// images/stories is path of the images of the sections and categories hard
	// coded in the file \libraries\joomla\html\html\list.php at the line 52
	private $_image_path = "images";

	private $_admin = 'admin';

	private $_option = '';

	/**
	 * CONSTRUCTOR
	 *
	 * @since 1.5
	 */
	function __construct ()
	{
		$this->option = (PHP_SAPI != 'cli') ? \JFactory::getApplication()->input->getCmd('option') : 'cli_' .
				 strtolower(get_class(\JApplicationCli::getInstance()));
		$this->_db = \JFactory::getDbo();

		// Merge the default translation with the current translation
		$jlang = \JFactory::getLanguage();
		$jlang->load('lib_j2xml', JPATH_SITE, 'en-GB', true);
		$jlang->load('lib_j2xml', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('lib_j2xml', JPATH_SITE, null, true);
	}

	/**
	 * Init xml
	 *
	 * @return
	 * @since 18.8.309
	 */
	protected function _root ()
	{
		$data = '<?xml version="1.0" encoding="UTF-8" ?>';
		// $data .= Version::$DOCTYPE;
		$data .= '<j2xml version="' . Version::$DOCVERSION . '"/>';
		$xml = new \SimpleXMLElement($data);
		$xml->addChild('base', \JUri::root());
		return $xml;
	}

	function export ($xml, $options)
	{
		if ($options['debug'] > 0)
		{
			$app = \JFactory::getApplication();
			$data = ob_get_contents();
			if ($data)
			{
				$app->enqueueMessage(\JText::_('LIB_J2XML_MSG_ERROR_EXPORT'), 'error');
				$app->enqueueMessage($data, 'error');
				return false;
			}
		}
		ob_clean();

		$version = explode(".", Version::$DOCVERSION);
		$xmlVersionNumber = $version[0] . $version[1] . substr('0' . $version[2], strlen($version[2]) - 1);

		$dom = new \DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		$data = $dom->saveXML();

		// modify the MIME type
		$document = \JFactory::getDocument();
		if ($options['gzip'])
		{
			$document->setMimeEncoding('application/gzip-compressed', true);
			\JResponse::setHeader('Content-disposition', 'attachment; filename="j2xml' . $xmlVersionNumber . date('YmdHis') . '.gz"', true);
			$data = gzencode($data, 9);
		}
		else
		{
			$document->setMimeEncoding('application/xml', true);
			\JResponse::setHeader('Content-disposition', 'attachment; filename="j2xml' . $xmlVersionNumber . date('YmdHis') . '.xml"', true);
		}
		echo $data;
		return true;
	}

	/**
	 * Export content articles, images, section and categories
	 *
	 * @return xml string
	 * @since 1.5.2.14
	 */
	function content ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		foreach ($ids as $id)
		{
			Content::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}

	/**
	 * Export categories
	 *
	 * @return xml string
	 * @since 1.5.3beta5.43
	 */
	function categories ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		$options['content'] = 1;
		foreach ($ids as $id)
		{
			Category::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}

	/**
	 * Export users
	 *
	 * @return xml string
	 * @since 1.5.3beta4.39
	 */
	function users ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		foreach ($ids as $id)
		{
			User::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}

	/**
	 * Export weblinks
	 *
	 * @return xml string
	 * @since 1.5.3beta3.38
	 */
	function weblinks ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		foreach ($ids as $id)
		{
			Weblink::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}

	/**
	 * Export contacts
	 *
	 * @return xml string
	 * @since 16.12.289
	 */
	function contact ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		foreach ($ids as $id)
		{
			Contact::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}

	/**
	 * Export fields
	 *
	 * @param array $ids
	 * @param SimpleXMLElement $xml
	 * @param array $options
	 *
	 * @return SimpleXMLElement
	 *
	 * @since 17.6.299
	 */
	function fields ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		foreach ($ids as $id)
		{
			Field::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}

	/**
	 * Export viewlevels
	 *
	 * @return xml string
	 * @since 192.2.323
	 */
	function viewlevels ($ids, &$xml, $options)
	{
		if (! $xml)
		{
			$xml = self::_root();
		}

		if (is_scalar($ids))
		{
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}

		foreach ($ids as $id)
		{
			Viewlevel::export($id, $xml, $options);
		}

		$params = new \JRegistry($options);
		\JPluginHelper::importPlugin('j2xml');
		$dispatcher = \JEventDispatcher::getInstance();
		// Trigger the onAfterExport event.
		$dispatcher->trigger('onAfterExport', array(
				$this->option . '.' . __FUNCTION__,
				&$xml,
				$params
		));

		return $xml;
	}
}