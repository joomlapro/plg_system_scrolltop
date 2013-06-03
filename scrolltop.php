<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Scrolltop
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('JPATH_BASE') or die;

/**
 * Scrolltop plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  System.Scrolltop
 * @since       3.1
 */
class PlgSystemScrolltop extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An array that holds the plugin configuration.
	 *
	 * @access  protected
	 * @since   3.1
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();

		// Get the application.
		$app = JFactory::getApplication();

		// Save the syntax for later use.
		if ($app->isAdmin())
		{
			$app->setUserState('editor.source.syntax', 'css');
		}
	}

	/**
	 * Method to catch the onAfterDispatch event.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.1
	 */
	public function onAfterDispatch()
	{
		// Check that we are in the site application.
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}

		// Get the document object.
		$doc = JFactory::getDocument();

		// Add Stylesheet.
		if ($custom_css = trim($this->params->get('custom_css')))
		{
			$doc->addStyleDeclaration($custom_css);
		}
		else
		{
			JHtml::stylesheet('plg_system_scrolltop/template.css', false, true, false);
		}

		// Add JavaScript Frameworks.
		JHtml::_('jquery.framework');

		// Build the script.
		$script = array();
		$script[] = 'jQuery(document).ready(function() {';
		$script[] = '	jQuery(\'body\').append(\'<a href="#" class="scroll-top">' . $this->params->get('template', '<i class="icon-chevron-up"></i>') . '</a>\');';
		$script[] = '	jQuery(window).scroll(function() {';
		$script[] = '		if (jQuery(this).scrollTop() > 100) {';
		$script[] = '			jQuery(\'.scroll-top\').fadeIn();';
		$script[] = '		} else {';
		$script[] = '			jQuery(\'.scroll-top\').fadeOut();';
		$script[] = '		}';
		$script[] = '	});';
		$script[] = '	jQuery(\'.scroll-top\').click(function() {';
		$script[] = '		jQuery(\'html, body\').animate({';
		$script[] = '			scrollTop: 0';
		$script[] = '		}, 600);';
		$script[] = '		return false;';
		$script[] = '	});';
		$script[] = '});';

		// Add the script to the document head.
		$doc->addScriptDeclaration(implode("\n", $script));

		return true;
	}
}
