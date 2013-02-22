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
	 * The data of the active language mail template
	 * @var array
	 */
	protected $arrActiveLanguageData;

	/**
	 * @param int $intId
	 */
	public function __construct($intId, $strLanguage=null)
	{
		parent::__construct();

		$this->import('Database');
		$this->import('String');

		$this->intId = (int) $intId;
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
	 * Initialize language of the mail
	 * @return boolean
	 */
	public function initializeLanguage()
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

		$this->arrActiveLanguageData = $objLanguage->row();
		$this->strLanguage = $objLanguage->language;
		return true;
	}


	/**
	 * Initialize email object data
	 */
	public function initializeEMailObject()
	{
		$arrData = $this->arrSimpleTokens;
		$arrPlainData = array_map('strip_tags', $this->arrSimpleTokens);

		$strSubject = $this->arrActiveLanguageData['subject'];
		$strSubject = $this->recursiveReplaceTokensAndTags($strSubject, $arrPlainData);
		$strSubject = $this->String->decodeEntities($strSubject);
		$this->objEmail->subject = $strSubject;

		$strText = $this->arrActiveLanguageData['content_text'];
		$strText = $this->recursiveReplaceTokensAndTags($strText, $arrPlainData);
		$strText = $this->convertRelativeUrls($strText, '', true);
		$strText = $this->String->decodeEntities($strText);
		$this->objEmail->text = $strText;

		// html
		if ($this->arrActiveLanguageData['content_html'] != '')
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
			$objTemplate->body = $this->arrActiveLanguageData['content_html'];
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
			foreach (array_merge($this->arrAttachments, deserialize($this->arrActiveLanguageData['attachments'], true)) as $file)
			{
				if (is_file(TL_ROOT . '/' . $file))
				{
					$this->objEmail->attachFile(TL_ROOT . '/' . $file);
				}
			}

			$this->attachmentsDone = true;
		}
	}


	/**
	 * Set the data and send the email.
	 * DON'T CALL THIS METHOD BEFORE YOU HAVE DONE ALL MODIFICATIONS ON THE MAIL TEMPLATE
	 * @return boolean
	 */
	public function sendTo()
	{
		if (!$this->initializeLanguage())
		{
			return false;
		}

		$this->initializeEMailObject();

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
		}

		if (!empty($arrCc))
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

