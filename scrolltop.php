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

		// Define path.
		$path = JUri::root(true) . 'plugins/system/scrolltop';

		// Add Stylesheet.
		if ($custom_css = trim($this->params->get('custom_css')))
		{
			$doc->addStyleDeclaration($custom_css);
		}
		else
		{
			$doc->addStyleSheet($path . '/assets/css/template.css');
		}

		// Add JavaScript Frameworks.
		JHtml::_('jquery.framework');

		// Build the script.
		$script = array();
		$script[] = 'jQuery.noConflict();';
		$script[] = '(function($) {';
		$script[] = '	$(function() {';
		$script[] = '		$("body").append("<a href=\"#\" class=\"scroll-top\">' . $this->params->get('template', '<i class=\"icon-chevron-up\"></i>') . '</a>");';
		$script[] = '		$(document).ready(function() {';
		$script[] = '			$(window).scroll(function() {';
		$script[] = '				if ($(this).scrollTop() > 100) {';
		$script[] = '					$(".scroll-top").fadeIn();';
		$script[] = '				} else {';
		$script[] = '					$(".scroll-top").fadeOut();';
		$script[] = '				}';
		$script[] = '			});';
		$script[] = '			$(".scroll-top").click(function() {';
		$script[] = '				$("html, body").animate({';
		$script[] = '					scrollTop: 0';
		$script[] = '				}, 600);';
		$script[] = '				return false;';
		$script[] = '			});';
		$script[] = '		});';
		$script[] = '	});';
		$script[] = '})(jQuery);';

		// Add the script to the document head.
		$doc->addScriptDeclaration(implode("\n", $script));

		return true;
	}
}
