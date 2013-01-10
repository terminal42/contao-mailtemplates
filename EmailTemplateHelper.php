<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  terminal42 2013
 * @author     terminal42 <info@terminal42.ch>
 * @author     Leo Unglaub <leo@leo-unglaub.net>
 * @license    LGPL
 */
class EmailTemplateHelper
{
    /**
     * Returns an array of mail templates
     * @return array
     */
    public function getMailTemplates()
    {
        $arrTemplates = array();
        $objTemplates = Database::getInstance()->execute("SELECT id,name,category FROM tl_mail_templates ORDER BY category, name");

        while( $objTemplates->next() )
        {
            if ($objTemplates->category == '')
            {
                $arrTemplates[$objTemplates->id] = $objTemplates->name;
            }
            else
            {
                $arrTemplates[$objTemplates->category][$objTemplates->id] = $objTemplates->name;
            }
        }

        return $arrTemplates;
    }
}

