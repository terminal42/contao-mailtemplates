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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['name']			= array('Template name', 'Bitte geben Sie einen Namen für diese Vorlage ein. Dieser Name wird nur für die interne Kennzeichnung verwendet.');
$GLOBALS['TL_LANG']['tl_mail_templates']['category']		= array('Category', 'Gruppieren Sie verschiedene Vorlagen in Kategorien um eine bessere Übersicht zu behalten.');
$GLOBALS['TL_LANG']['tl_mail_templates']['sender_name']		= array('Sender name', 'Bitte geben Sie den Namen des Absenders ein.');
$GLOBALS['TL_LANG']['tl_mail_templates']['sender_address']	= array('Sender address', 'Bitte geben Sie die Absende-E-Mail Adresse ein.');
$GLOBALS['TL_LANG']['tl_mail_templates']['recipient_cc']	= array('Send a CC to', 'Recipients that should receive a carbon copy of the mail. Separate multiple addresses with a comma.');
$GLOBALS['TL_LANG']['tl_mail_templates']['recipient_bcc']	= array('Send a BCC to', 'Recipients that should receive a blind carbon copy of the mail. Separate multiple addresses with a comma.');
$GLOBALS['TL_LANG']['tl_mail_templates']['reply_to']	    = array('Reply to', 'Here you can enter the "reply to" e-mail address.');
$GLOBALS['TL_LANG']['tl_mail_templates']['attachments']		= array('Attachments', 'Select files you want to send in this email.');
$GLOBALS['TL_LANG']['tl_mail_templates']['priority']		= array('Priority', 'Please select a priority.');
$GLOBALS['TL_LANG']['tl_mail_templates']['template'] 		= array('Template file', 'Please choose a template file.');
$GLOBALS['TL_LANG']['tl_mail_templates']['css_internal']	= array('Style sheet', 'Select a CSS file from Contao\'s style sheet manager.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['new']         = array('New template', 'Create a new email template');
$GLOBALS['TL_LANG']['tl_mail_templates']['edit']        = array('Edit template', 'Edit email template ID %s');
$GLOBALS['TL_LANG']['tl_mail_templates']['editheader']  = array('Edit template settings', 'Edit the settings of email template ID %s');
$GLOBALS['TL_LANG']['tl_mail_templates']['copy']        = array('Duplicate template', 'Duplicate email template ID %s');
$GLOBALS['TL_LANG']['tl_mail_templates']['delete']      = array('Delete template', 'Delete email template ID %s');
$GLOBALS['TL_LANG']['tl_mail_templates']['show']        = array('Email template details', 'Show details of email template ID %s');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['1'] = 'very high';
$GLOBALS['TL_LANG']['tl_mail_templates']['2'] = 'high';
$GLOBALS['TL_LANG']['tl_mail_templates']['3'] = 'normal';
$GLOBALS['TL_LANG']['tl_mail_templates']['4'] = 'low';
$GLOBALS['TL_LANG']['tl_mail_templates']['5'] = 'very low';

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['title_legend']	= 'Globale Einstellungen';
$GLOBALS['TL_LANG']['tl_mail_templates']['email_legend']	= 'E-Mail Optionen';
$GLOBALS['TL_LANG']['tl_mail_templates']['expert_legend']	= 'Experteneinstellungen';

