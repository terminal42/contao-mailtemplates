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
$GLOBALS['TL_LANG']['tl_mail_template_languages']['language']		= array('Sprache', 'Wählen Sie die Sprache des Inhalts.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['fallback']		= array('Fallback', 'Wählen Sie diese Option um diese Sprache als Standard-Sprache zu verwenden.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['subject']		= array('Betreff', 'Bitte geben Sie hier den Betreff der E-Mail ein.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['content_html']	= array('Inhalt - HTML', 'Bitte geben Sie den Inhalt der E-Mail ein. Hier können Sie die Nachricht im HTML Modus eingeben.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['content_text']	= array('Inhalt - Text', 'Bitte geben Sie den inhalt der E-Mail ein. Hier können Sie die Nachricht im Text Modus eingeben. Der Textmodul sollte das bevorzugte Format sein.');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['attachments']	= array('Anhänge', 'Bitte wählen Sie die Dateien welche als Anhang mitgesendet werden sollen. Wenn Sie globale Anhänge gewählt haben können Sie hier Anhänge ZUSÄTZLICH hinzufügen.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_mail_template_languages']['new']		= array('Sprache hinzufügen', 'Eine neue Sprache für diese E-Mail Template anlegen');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['edit']		= array('Sprache bearbeiten', 'Sprache ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['copy']		= array('Sprache duplizieren', 'Sprache ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['delete']		= array('Sprache löschen', 'Sprache ID %s löschen');
$GLOBALS['TL_LANG']['tl_mail_template_languages']['show']		= array('Sprachdetails', 'Details der Sprache ID %s anzeigen');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_mail_template_languages']['title_legend']	= 'Globale Einstellungen';
$GLOBALS['TL_LANG']['tl_mail_template_languages']['email_legend']	= 'E-Mail Optionen';

