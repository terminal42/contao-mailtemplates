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
 * @version    $Id: tl_mail_template_languages.php 324 2011-07-26 16:07:53Z aschempp $
 */


$GLOBALS['TL_DCA']['tl_mail_template_languages'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		'enableVersioning'			=> true,
		'ptable'					=> 'tl_mail_templates',
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'					=> 4,
			'fields'				=> array('fallback DESC', 'language'),
			'headerFields'			=> array('name', 'sender_name', 'sender_address'),
			'panelLayout'			=> 'filter,search,limit',
			'disableGrouping'		=> true,
			'child_record_callback'	=> array('tl_mail_template_languages', 'listLanguages'),
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
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['edit'],
				'href'				=> 'act=edit',
				'icon'				=> 'edit.gif'
			),
			'copy' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['copy'],
				'href'				=> 'act=paste&amp;mode=copy',
				'icon'				=> 'copy.gif'
			),
			'cut' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['cut'],
				'href'				=> 'act=paste&amp;mode=cut',
				'icon'				=> 'cut.gif',
				'attributes'		=> 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['delete'],
				'href'				=> 'act=delete',
				'icon'				=> 'delete.gif',
				'attributes'		=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['show'],
				'href'				=> 'act=show',
				'icon'				=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'					=> '{title_legend},language,fallback;{email_legend},subject,content_html,content_text,attachments;',
	),

	// Fields
	'fields' => array
	(
		'language' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['language'],
			'exclude'				=> true,
			'filter'				=> true,
			'inputType'				=> 'select',
			'default'				=> $GLOBALS['TL_LANGUAGE'],
			'options'				=> $this->getLanguages(),
			'eval'					=> array('mandatory'=>true, 'doNotCopy'=>true, 'chosen'=>true, 'tl_class'=>'w50')
		),
		'fallback' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['fallback'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'eval'					=> array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
		),
		'subject' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['subject'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
		),
		'content_html' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['content_html'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('rte'=>'tinyMCE', 'decodeEntities'=>true),
		),
		'content_text' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['content_text'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'textarea',
			'eval'					=> array('mandatory'=>true, 'decodeEntities'=>true),
		),
		'attachments' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_mail_template_languages']['attachments'],
			'exclude'				=> true,
			'inputType'				=> 'fileTree',
			'eval'					=> array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'tl_class'=>'clr'),
		)
	)
);


class tl_mail_template_languages extends Backend
{

	protected $arrLanguages;

	public function __construct()
	{
		parent::__construct();
		$this->arrLanguages = $this->getLanguages();
	}

	/**
	 * Display a language entry in the parent view
	 * @param array $arrRow
	 * @return string
	 */
	public function listLanguages($arrRow)
	{
		return '
<div class="cte_type published"><strong>' . $arrRow['subject'] . '</strong> - ' . $this->arrLanguages[$arrRow['language']] . ($arrRow['fallback'] ? (' (' . $GLOBALS['TL_LANG']['tl_mail_template_languages']['fallback'][0] . ')') : '') . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">' . ($arrRow['content_html'] == '' ? '' : '
<h3>' . $GLOBALS['TL_DCA']['tl_mail_template_languages']['fields']['content_html']['label'][0] . '</h3>
' . $arrRow['content_html'] . '<hr />') .'
<h3>' . $GLOBALS['TL_DCA']['tl_mail_template_languages']['fields']['content_text']['label'][0] . '</h3>
' . nl2br($arrRow['content_text']) . '
</div>' . "\n";
	}
}

