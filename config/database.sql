-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_mail_templates`
-- 

CREATE TABLE `tl_mail_templates` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `category` varchar(255) NOT NULL default '',
  `sender_name` varchar(255) NOT NULL default '',
  `sender_address` varchar(255) NOT NULL default '',
  `recipient_cc` text NULL,
  `recipient_bcc` text NULL,
  `attachments` blob NULL,
  `priority` int(1) unsigned NOT NULL default '0',
  `template` varchar(255) NOT NULL default '',
  `css_internal` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_mail_template_languages`
-- 

CREATE TABLE `tl_mail_template_languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `language` varchar(2) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `content_html` text NULL,
  `content_text` text NULL,
  `attachments` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-------------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `mail_template` int(10) NOT NULL default '0',
  `admin_mail_template` int(10) NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

