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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_mail_template_languages']['language']		= array('Language', 'Please select the language for this content.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['fallback']		= array('Fallback', 'Check here if this is the fallback language.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['subject']		= array('Subject', 'Please enter your email subject.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['content_html']	= array('HTML content', 'Please enter your mail content. HTML allows for rich formatting but is not supported on all clients.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['content_text']	= array('Plain-Text content', 'Please enter your mail content. Plain text content should always be supplied to support all mail clients.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['attachments']	= array('Attachments', 'Add language specific content. If you have selected attachements in the template, BOTH will be added!');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_mail_template_languages']['new']		= array('Add language', 'Add a new language to this email template');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['edit']		= array('Edit language', 'Edit language ID ID %s');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['copy']		= array('Duplicate language', 'Duplicate language ID %s');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['delete']		= array('Delete language', 'Delete language ID %s');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['show']		= array('Language details', 'Show details of language ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_mail_template_languages']['title_legend']	= 'Global settings';
$GLOBALS['TL_LANG']['tl_mail_template_languages']['email_legend']	= 'Email options';

