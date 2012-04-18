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
 * @version    $Id: EmailTemplate.php 324 2011-07-26 16:07:53Z aschempp $
 */


class EmailTemplate extends Controller
{

	/**
	 * The unterlying Contao Email object
	 * @var object
	 */
	protected $objEmail;
	
	/**
	 * Contain the simple tokens for this email
	 * @var array
	 */
	protected $arrSimpleTokens = array();

	/**
	 * The current language for the email
	 * @var string
	 */
	protected $strLanguage;
	
	/**
	 * Email template file
	 * @var string
	 */
	protected $strTemplate;
	
	/**
	 * Contao CSS file to include
	 * @var string
	 */
	protected $strCssFile;
	
	/**
	 * Array of attachments
	 * @var array
	 */
	protected $arrAttachments;
	
	/**
	 * if attachments have been added (= reset $objEmail if language changes)
	 * @var bool
	 */
	protected $attachmentsDone = false;
	
	/**
	 * the id of the mail template 
	 * @var int
	 */
	protected $intId;

	/**
	 * @param int $intId
	 */
	public function __construct($intId, $strLanguage=null)
	{
		parent::__construct();

		$this->import('Database');
		$this->import('String');

		$this->intId = $intId;
		$this->initializeTemplate($strLanguage);
	}


	/**
	 * Set an object property
	 * @param  string
	 * @param  mixed
	 * @throws Exception
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			// Make sure tokens is an array but not multidimensional
			case 'simpleTokens':
				$arrTokens = array();
				$arrValue = deserialize($varValue, true);
				
				foreach( $arrValue as $k => $v )
				{
					if (is_array($v))
					{
						$arrTokens[$k] = $this->recursiveImplode(', ', $v);
						continue;
					}
					
					$arrTokens[$k] = $v;
				}
				
				$this->arrSimpleTokens = $arrTokens;
				break;
			
			case 'language':
				$strLanguage = substr($varValue, 0, 2);
				if ($strLanguage != $this->strLanguage)
				{
					$this->initializeTemplate($strLanguage);
				}
				break;

			default:
				if (is_object($varValue))
				{
					$this->$strKey = $varValue;
				}
				else
				{
					$this->objEmail->__set($strKey, $varValue);
				}
				break;
		}
	}

	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'language':
				return $this->strLanguage;
				break;
			
			default:
				return $this->objEmail->__get($strKey);
				break;
		}
	}
	
	
	/**
	 * Send to give address with tokens
	 */
	public function send($varRecipients, $arrTokens=null, $strLanguage=null)
	{
		if ($strLanguage)
		{
			$this->language = $strLanguage;
		}
		
		if (is_array($arrTokens))
		{
			$this->simpleTokens = $arrTokens;
		}
		
		return $this->sendTo($varRecipients);
	}


	/**
	 * Set the data and send the email. 
	 * DON'T CALL THIS METHOD BEFORE YOU HAVE DONE ALL MODIFICATIONS ON THE MAIL TEMPLATE
	 */
	public function sendTo()
	{
		// Use current page language if none is set
		if (!$this->strLanguage)
		{
			$this->strLanguage = $GLOBALS['TL_LANGUAGE'];
		}
		
		// get the data for the active language
		$objLanguage = $this->Database->prepare("SELECT * FROM tl_mail_template_languages WHERE pid={$this->intId} AND (language='{$this->strLanguage}' OR fallback='1') ORDER BY fallback")
									  ->limit(1)
									  ->execute();

		if (!$objLanguage->numRows)
		{
			$this->log('No fallback language found for email template ID '.$this->intId, __METHOD__, TL_ERROR);
			return false;
		}
		
		$this->strLanguage = $objLanguage->language;
		
		$arrData = $this->arrSimpleTokens;
		$arrPlainData = array_map('strip_tags', $this->arrSimpleTokens);
		
		$strSubject = $objLanguage->subject;
		$strSubject = $this->recursiveReplaceTokensAndTags($strSubject, $arrPlainData);
		$strSubject = $this->String->decodeEntities($strSubject);
		$this->objEmail->subject = $strSubject;
		
		$strText = $objLanguage->content_text;
		$strText = $this->recursiveReplaceTokensAndTags($strText, $arrPlainData);
		$strText = $this->convertRelativeUrls($strText, '', true);
		$strText = $this->String->decodeEntities($strText);
		$this->objEmail->text = $strText;

		// html
		if ($objLanguage->content_html != '')
		{
			$arrData['head_css'] = '';

			// Add style sheet
			if (is_file(TL_ROOT . '/' . $this->strCssFile . '.css'))
			{
				$buffer = file_get_contents(TL_ROOT . '/' . $this->strCssFile . '.css');
				$buffer = preg_replace('@/\*\*.*\*/@Us', '', $buffer);
	
				$css  = '<style type="text/css">' . "\n";
				$css .= trim($buffer) . "\n";
				$css .= '</style>' . "\n";
				$arrData['head_css'] = $css;
			}
			
			$objTemplate = new FrontendTemplate($this->strTemplate);
			$objTemplate->body = $objLanguage->content_html;
			$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
			$objTemplate->css = '##head_css##';

			// Prevent parseSimpleTokens from stripping important HTML tags
			$GLOBALS['TL_CONFIG']['allowedTags'] .= '<doctype><html><head><meta><style><body>';
			$strHtml = str_replace('<!DOCTYPE', '<DOCTYPE', $objTemplate->parse());
			$strHtml = $this->recursiveReplaceTokensAndTags($strHtml, $arrData);
			$strHtml = $this->convertRelativeUrls($strHtml, '', true);
			$strHtml = str_replace('<DOCTYPE', '<!DOCTYPE', $strHtml);

			// Parse template
			$this->objEmail->html = $strHtml;
			$this->objEmail->imageDir = TL_ROOT . '/';
		}
		
		if (!$this->attachmentsDone)
		{
			foreach (array_merge($this->arrAttachments, deserialize($objLanguage->attachments, true)) as $file)
			{
				if (is_file(TL_ROOT . '/' . $file))
				{
					$this->objEmail->attachFile(TL_ROOT . '/' . $file);
				}
			}
			
			$this->attachmentsDone = true;
		}

		return $this->objEmail->sendTo(func_get_args());
	}
	
	
	/**
	 * Initialize from template and reset attachments if language changes
	 *
	 * @throws Exception
	 */
	protected function initializeTemplate($strLanguage)
	{
		$this->objEmail = new Email();
		$this->attachmentsDone = false;
		
		$objTemplate = $this->Database->execute("SELECT * FROM tl_mail_templates WHERE id=" . $this->intId);

		if ($objTemplate->numRows < 1)
		{
			throw new Exception('No mail template with ID "' . $this->intId . '" found.');
		}
		
		$this->strLanguage = $strLanguage;

		// set the options
		$this->objEmail->fromName = $objTemplate->sender_name ? $objTemplate->sender_name : $GLOBALS['TL_ADMIN_NAME'];
		$this->objEmail->from = $objTemplate->sender_address ? $objTemplate->sender_address : $GLOBALS['TL_ADMIN_EMAIL'];
		$this->objEmail->priority = $objTemplate->priority;

		// recipient_cc
		$arrCc = array();
		foreach ((array)trimsplit(',', $objTemplate->recipient_cc) as $email)
		{
			if ($email == '' || !$this->isValidEmailAddress($email))
				continue;

			$arrCc[] = $email;
			$this->objEmail->sendCc($email);
		}
		
		if (!empty($arrBcc))
		{
			$this->objEmail->sendCc($arrCc);
		}
		
		// recipient_bcc
		$arrBcc = array();
		foreach ((array)trimsplit(',', $objTemplate->recipient_bcc) as $email)
		{
			if ($email == '' || !$this->isValidEmailAddress($email))
				continue;

			$arrBcc[] = $email;
		}
		
		if (!empty($arrBcc))
		{
			$this->objEmail->sendBcc($arrBcc);
		}
		
		
		// template attachments
		$this->arrAttachments = deserialize($objTemplate->attachments, true);
		
		$this->strTemplate = $objTemplate->template;
		$this->strCssFile = $objTemplate->css_internal;
	}
	
	
	/**
	 * Recursively implode an array
	 * @param string
	 * @param array
	 * @return string
	 */
	protected function recursiveImplode($strGlue, $arrPieces)
	{
		$arrReturn = array();
		
		foreach( $arrPieces as $varPiece )
		{
			if (is_array($varPiece))
			{
				$arrReturn[] = $this->recursiveImplode($strGlue, $varPiece);
			}
			else
			{
				$arrReturn[] = $varPiece;
			}
		}

		return implode($strGlue, $arrReturn);
	}


	/**
	 * Recursively replace the simple tokens and the insert tags
	 * @param string
	 * @param array tokens
	 * @return string
	 */
	protected function recursiveReplaceTokensAndTags($strText, $arrTokens)
	{
		// first parse the tokens as they might have if-else clauses
		$strBuffer = $this->parseSimpleTokens($strText, $arrTokens);

		// then replace the insert tags
		$strBuffer = $this->replaceInsertTags($strBuffer);

		// check if the inserttags have returned a simple token or an insert tag to parse
		if (strpos($strBuffer, '##') !== false || strpos($strBuffer, '{{') !== false)
		{
			// Prevent infinite loop
			if ($strBuffer == $strText)
			{
				return $strBuffer;
			}

			$strBuffer = $this->recursiveReplaceTokensAndTags($strBuffer, $arrTokens);
		}

		return $strBuffer;
	}
}

