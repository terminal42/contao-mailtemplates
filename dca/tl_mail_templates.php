<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2011
 * @author     Leo Unglaub <leo@leo-unglaub.net>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: tl_mail_templates.php 324 2011-07-26 16:07:53Z aschempp $
 */


$GLOBALS['TL_DCA']['tl_mail_templates'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		'ctable'					=> array('tl_mail_template_languages'),
		'switchToEdit'				=> true,
		'enableVersioning'			=> true,
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'					=> 1,
			'fields'				=> array('category', 'name'),
			'flag'					=> 1,
			'panelLayout'			=> 'filter;search,limit',
		),
		'label' => array
		(
			'fields'				=> array('name'),
			'format'				=> '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'				=> 'act=select',
				'class'				=> 'header_edit_all',
				'attributes'		=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_templates']['edit'],
				'href'				=> 'table=tl_mail_template_languages',
				'icon'				=> 'edit.gif'
			),
			'copy' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_templates']['copy'],
				'href'				=> 'act=copy',
				'icon'				=> 'copy.gif'
			),
			'delete' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_templates']['delete'],
				'href'				=> 'act=delete',
				'icon'				=> 'delete.gif',
				'attributes'		=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_templates']['show'],
				'href'				=> 'act=show',
				'icon'				=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'					=> '{title_legend},name,category;{email_legend},sender_name,sender_address,recipient_cc,recipient_bcc,attachments;{expert_legend:hide},priority,template,css_internal',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['name'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'category' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['category'],
			'exclude'				=> true,
			'filter'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'sender_name' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['sender_name'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'sender_address' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['sender_address'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'recipient_cc' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['recipient_cc'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rgxp'=>'extnd', 'style'=>'height:40px; width:314px', 'tl_class'=>'w50" style="height:auto')
		),
		'recipient_bcc' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['recipient_bcc'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rgxp'=>'extnd', 'style'=>'height:40px; width:314px', 'tl_class'=>'w50" style="height:auto')
		),
		'attachments' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['attachments'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'fileTree',
			'eval'					=> array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'tl_class'=>'clr'),
		),
		'priority' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['priority'],
			'exclude'				=> true,
			'filter'				=> true,
			'inputType'				=> 'select',
			'options'				=> array(1,2,3,4,5),
			'default'				=> 3,
			'reference'				=> &$GLOBALS['TL_LANG']['tl_mail_templates'],
			'eval'					=> array('rgxp'=>'digit', 'tl_class'=>'w50'),
		),
		'template' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['template'],
			'exclude'				=> true,
			'filter'				=> true,
			'inputType'				=> 'select',
			'default'				=> 'mail_default',
			'options'				=> $this->getTemplateGroup('mail_'),
			'eval'					=> array('tl_class'=>'w50'),
		),
		'css_internal' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_templates']['css_internal'],
			'exclude'				=> true,
			'filter'				=> true,
			'inputType'				=> 'select',
			'options_callback'		=> array('tl_mail_templates', 'getCssFiles'),
			'eval'					=> array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
	)
);


class tl_mail_templates extends Backend
{
	
	/**
	 * Return all internal CSS Files for the select menu
	 * @return array
	 */
	public function getCssFiles()
	{
		$arrStyleSheets = array();
		$objStyleSheets = $this->Database->query("SELECT name, (SELECT name FROM tl_theme WHERE tl_style_sheet.pid=tl_theme.id) AS theme FROM tl_style_sheet ORDER BY theme, name");
		
		while ($objStyleSheets->next())
		{
			$arrStyleSheets[$objStyleSheets->theme][$objStyleSheets->name] = $objStyleSheets->name;
		}
		
		return $arrStyleSheets;
	}
}

