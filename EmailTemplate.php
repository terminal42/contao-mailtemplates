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
     * the id of the mail template
     * @var int
     */
    protected $intId;

    /**
     * The unterlying Contao Email object
     * @var object
     */
    protected $objEmail;

    /**
     * The email template database result
     * @var Database_Result
     */
    protected $objTemplate;

    /**
     * The active language database result
     * @var Database_Result
     */
    protected $objLanguage;

    /**
     * Contain the simple tokens for this email
     * @var array
     */
    protected $arrTokens = array();

    /**
     * Array of attachments
     * @var array
     */
    protected $arrAttachments = array();

    /**
     * The target language
     */
    protected $strLanguage;

    /**
     * Flag if template is ready to be sent
     * @var bool
     */
    protected $blnReady = false;

    /**
     * Flag if attachments have been added to object
     * @var bool
     */
    protected $blnAttachmentsDone = false;

    /**
     * @param int $intId
     */
    public function __construct($intId, $strLanguage=null)
    {
        parent::__construct();

        $this->import('Database');
        $this->import('String');

        $this->intId = (int) $intId;
        $this->initializeTemplate();
        $this->initializeLanguage($strLanguage);
    }

    /**
     * Return an object property
     * @param string
     * @return mixed
     */
    public function __get($strKey)
    {
        return $this->objEmail->__get($strKey);
    }

    /**
     * Set an object property
     * @param  string
     * @param  mixed
     * @throws Exception
     */
    public function __set($strKey, $varValue)
    {
        if (is_object($varValue))
        {
            $this->$strKey = $varValue;
        }
        else
        {
            $this->objEmail->__set($strKey, $varValue);
        }
    }

    /**
     * Get the raw Contao Email object
     * @return Email
     */
    public function getEmailObject()
    {
        return $this->objEmail;
    }

    /**
     * Return language for this template
     * @return string
     */
    public function getLanguage()
    {
        return $this->strLanguage;
    }

    /**
     * Set the language for this template
     * @param string
     */
    public function setLanguage($varValue)
    {
        $strLanguage = strtolower(substr($varValue, 0, 2));

        if ($strLanguage != $this->strLanguage)
        {
            $this->initializeLanguage($strLanguage);
        }
    }

    /**
     * Return simple tokens
     * @return array
     */
    public function getTokens()
    {
        return $this->arrTokens;
    }

    /**
     * Set replacements tokens
     * @param array
     */
    public function setTokens(array $varValue)
    {
        $this->arrTokens = array();

        $arrValue = deserialize($varValue, true);

        foreach ($arrValue as $k => $v)
        {
            $this->addToken($k, $v);
        }
    }

    /**
     * Add a token to the list
     * @param string
     * @param mixed
     */
    public function addToken($strKey, $varValue)
    {
        if (is_array($varValue))
        {
            $varValue = $this->recursiveImplode(', ', $varValue);
        }

        $this->arrTokens[$strKey] = $varValue;

        // Must rebuild email content
        $this->blnReady = false;
    }

    /**
     * Remove a simple token
     * @param string
     */
    public function removeToken($strKey)
    {
        unset($this->arrTokens[$strKey]);
    }

    /**
     * Return attachments
     * @return array
     */
    public function getAttachments()
    {
        return $this->arrAttachments;
    }

    /**
     * Adds an attachment
     * @param string path without TL_ROOT
     */
    public function addAttachment($strPath)
    {
        $this->arrAttachments[] = $strPath;

        // Must reset email object when new attachments are added
        if ($this->blnReady) {
            $this->initializeTemplate();
        }
    }

    /**
     * Initialize email object data
     */
    public function prepareEmail()
    {
        // Nothing to do if the email object is ready
        if ($this->blnReady) {
            return;
        }

        // Load fallback language data
        if (!is_object($this->objLanguage)) {
            $this->initializeLanguage($this->strLanguage);
        }

        // Plain tokens are for subject, recipient etc.
        $arrData = $this->getTokens();
        $arrPlainData = array_map('strip_tags', $arrData);

        // Set optional sender name
        $strSenderName = $this->objTemplate->sender_name ? $this->objTemplate->sender_name : $GLOBALS['TL_ADMIN_NAME'];
        if ($strSenderName != '') {
            $strSenderName = $this->recursiveReplaceTokensAndTags($strSenderName, $arrPlainData);
            $strSenderName = $this->String->decodeEntities($strSenderName);
            $this->objEmail->fromName = $strSenderName;
        }

        // Set email sender address
        $strSenderAddress = $this->objTemplate->sender_address ? $this->objTemplate->sender_address : $GLOBALS['TL_ADMIN_EMAIL'];
        $strSenderAddress = $this->recursiveReplaceTokensAndTags($strSenderAddress, $arrPlainData);
        $strSenderAddress = $this->String->decodeEntities($strSenderAddress);
        $this->objEmail->from = $strSenderAddress;

        // Set email subject
        $strSubject = $this->objLanguage->subject;
        $strSubject = $this->recursiveReplaceTokensAndTags($strSubject, $arrPlainData);
        $strSubject = $this->String->decodeEntities($strSubject);
        $this->objEmail->subject = $strSubject;

        // Set email text content
        $strText = $this->objLanguage->content_text;
        $strText = $this->recursiveReplaceTokensAndTags($strText, $arrPlainData);
        $strText = $this->convertRelativeUrls($strText, '', true);
        $strText = $this->String->decodeEntities($strText);
        $this->objEmail->text = $strText;

        // Set optional email HTML content
        if ($this->objLanguage->content_html != '')
        {
            $arrData['head_css'] = '';

            // Add style sheet
            if ($this->objTemplate->css_internal != '' && is_file(TL_ROOT . '/' . $this->objTemplate->css_internal . '.css'))
            {
                $buffer = file_get_contents(TL_ROOT . '/' . $this->objTemplate->css_internal . '.css');
                $buffer = preg_replace('@/\*\*.*\*/@Us', '', $buffer);

                $css  = '<style type="text/css">' . "\n";
                $css .= trim($buffer) . "\n";
                $css .= '</style>' . "\n";
                $arrData['head_css'] = $css;
            }

            $objTemplate = new FrontendTemplate($this->objTemplate->template);
            $objTemplate->body = $this->objLanguage->content_html;
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

        // Add all attachments
        if ($this->blnAttachmentsDone === false) {
            foreach (array_merge($this->arrAttachments, deserialize($this->objTemplate->attachments, true), deserialize($this->objLanguage->attachments, true)) as $file) {
                if ($file != '' && is_file(TL_ROOT . '/' . $file)) {
                    $this->objEmail->attachFile(TL_ROOT . '/' . $file);
                }
            }

            $this->blnAttachmentsDone = true;
        }

        // Set CC recipients
        $arrCc = $this->compileRecipients($this->objTemplate->recipient_cc, $arrPlainData);
        if (!empty($arrCc)) {
            $this->objEmail->sendCc($arrCc);
        }

        // Set BCC recipients
        $arrBcc = $this->compileRecipients($this->objTemplate->recipient_bcc, $arrPlainData);
        if (!empty($arrBcc)) {
            $this->objEmail->sendBcc($arrBcc);
        }

        $this->blnReady = true;
    }

    /**
     * Set the data and send the email.
     * DON'T CALL THIS METHOD BEFORE YOU HAVE DONE ALL MODIFICATIONS ON THE MAIL TEMPLATE
     * @return boolean
     */
    public function sendTo()
    {
        $this->prepareEmail();

        return call_user_func_array(array($this->objEmail, 'sendTo'), func_get_args());
    }

    /**
     * Send to given address(es) with tokens
     * @param mixed
     * @param array|null
     * @param string|null
     */
    public function send($varRecipients, array $arrTokens=null, $strLanguage=null)
    {
        if (null !== $strLanguage)
        {
            $this->setLanguage($strLanguage);
        }

        if (!empty($arrTokens))
        {
            $this->setTokens($arrTokens);
        }

        return $this->sendTo($varRecipients);
    }

    /**
     * Initialize template and email object
     * @throws UnderflowException
     */
    protected function initializeTemplate()
    {
        $objTemplate = $this->Database->execute("SELECT * FROM tl_mail_templates WHERE id=" . $this->intId);

        if ($objTemplate->numRows < 1)
        {
            throw new UnderflowException('No mail template with ID "' . $this->intId . '" found.');
        }

        $this->blnReady = false;
        $this->blnAttachmentsDone = false;
        $this->objEmail = new Email();
        $this->objTemplate = $objTemplate;
        $this->arrAttachments = array();

        // Set some basic properties
        $this->objEmail->priority = $objTemplate->priority;
    }

    /**
     * Initialize language of the mail
     * @throws OutOfBoundsException
     */
    protected function initializeLanguage($strLanguage=null)
    {
        $this->blnReady = false;
        $this->strLanguage = $strLanguage;

        // Use current page language if none is given
        if (null === $this->strLanguage)
        {
            $this->strLanguage = $GLOBALS['TL_LANGUAGE'];
        }

        // get the data for the active language
        $objLanguage = $this->Database->prepare("SELECT * FROM tl_mail_template_languages WHERE pid=? AND (language=? OR fallback='1') ORDER BY fallback")
                                      ->limit(1)
                                      ->execute($this->intId, $this->strLanguage);

        if (!$objLanguage->numRows)
        {
            $this->log('No fallback language found for email template ID '.$this->intId, __METHOD__, TL_ERROR);
            throw new OutOfBoundsException('No fallback language found for email template ID '.$this->intId);
        }

        $this->objLanguage = $objLanguage;
    }

    /**
     * Generate CC or BCC recipients from comma separated string
     * @param string
     */
    protected function compileRecipients($strRecipients, $arrTokens)
    {
        $arrRecipients = array();

        foreach ((array) trimsplit(',', $strRecipients) as $strAddress)
        {
            if ($strAddress != '') {
                $strSubject = $this->recursiveReplaceTokensAndTags($strAddress, $arrTokens);
                $strSubject = $this->String->decodeEntities($strAddress);

                // Address could become empty through invalid inserttag
                if ($strAddress == '' || !$this->isValidEmailAddress($strAddress)) {
                    continue;
                }

                $arrRecipients[] = $strAddress;
            }
        }

        return $arrRecipients;
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

        foreach ($arrPieces as $varPiece)
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

