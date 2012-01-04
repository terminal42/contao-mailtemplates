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
$GLOBALS['TL_LANG']['tl_mail_templates']['name']			= array('Name der Vorlage', 'Bitte geben Sie einen Namen für diese Vorlage ein. Dieser Name wird nur für die interne Kennzeichnung verwendet.');
$GLOBALS['TL_LANG']['tl_mail_templates']['category']		= array('Kategorie', 'Gruppieren Sie verschiedene Vorlagen in Kategorien um eine bessere Übersicht zu behalten.');
$GLOBALS['TL_LANG']['tl_mail_templates']['sender_name']		= array('Absendername', 'Geben Sie den Namen des Absenders ein.');
$GLOBALS['TL_LANG']['tl_mail_templates']['sender_address']	= array('Absenderadresse', 'Geben Sie die E-Mail Adresse des Absenders ein. Der Empfänger wird bei Antworten an diese Adresse senden.');
$GLOBALS['TL_LANG']['tl_mail_templates']['recipient_cc']	= array('Kopie senden an', 'Empfänger welche eine Kopie der Nachricht erhalten sollen. Trennen Sie mehrere E-Mail Adressen mit einem Komma.');
$GLOBALS['TL_LANG']['tl_mail_templates']['recipient_bcc']	= array('Blinkkopie senden an', 'Empfänger welche eine Blindkopie der Nachricht erhalten sollen. Trennen Sie mehrere E-Mail Adressen mit einem Komma.');
$GLOBALS['TL_LANG']['tl_mail_templates']['attachments']		= array('Anhänge', 'Bitte wählen Sie die Dateien aus welche als Anhang mitgesendet werden sollen.');
$GLOBALS['TL_LANG']['tl_mail_templates']['priority']		= array('Priorität', 'Bitte geben Sie die Priorität des E-Mails an.');
$GLOBALS['TL_LANG']['tl_mail_templates']['template'] 		= array('E-Mail Template', 'Bitte wählen Sie hier das E-Mail Template welches für den Verwand verwendet werden soll.');
$GLOBALS['TL_LANG']['tl_mail_templates']['css_internal']	= array('interne CSS Dateien', 'Bitte wählen Sie eine Datei aus dem Contao CSS Editor aus welche in HTML E-Mails eingefügt werden soll.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['new']		= array('Neue Vorlage', 'Ein neue E-Mail Vorlage anlegen');
$GLOBALS['TL_LANG']['tl_mail_templates']['edit']	= array('Vorlange bearbeiten', 'E-Mail Vorlange ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_mail_templates']['copy']	= array('Vorlange duplizieren', 'E-Mail Vorlange ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_mail_templates']['delete']	= array('Vorlange löschen', 'E-Mail Vorlange ID %s löschen');
$GLOBALS['TL_LANG']['tl_mail_templates']['show']	= array('Vorlagen details', 'Details der E-Mail Vorlage ID %s anzeigen');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['1'] = 'sehr hoch';
$GLOBALS['TL_LANG']['tl_mail_templates']['2'] = 'hoch';
$GLOBALS['TL_LANG']['tl_mail_templates']['3'] = 'normal';
$GLOBALS['TL_LANG']['tl_mail_templates']['4'] = 'niedrig';
$GLOBALS['TL_LANG']['tl_mail_templates']['5'] = 'sehr niedrig';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_mail_templates']['title_legend']	= 'Globale Einstellungen';
$GLOBALS['TL_LANG']['tl_mail_templates']['email_legend']	= 'E-Mail Optionen';
$GLOBALS['TL_LANG']['tl_mail_templates']['expert_legend']	= 'Experteneinstellungen';

