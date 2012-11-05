<?php
/**
 * Provides an interface to the PulseConnect(StormPost) SOAP API with debugging tools
 *
 * @category   PulseConnect
 * @package    PulseConnect_API
 * @copyright  Copyright (c) 2012 Joshua McGinnis (http://www.brooklab.com)
 * @license    MIT
 */
class PulseConnect {

    protected $_username = '';
    protected $_password = '';
    protected $_wsdl = 'http://api.stormpost.datranmedia.com/services/SoapRequestProcessor?wsdl';
    protected $_ns = 'http://skylist.com/services/SoapRequestProcessor';
    protected $_actor = 'http://schemas.xmlsoap.org/soap/actor/next';
    protected $_client;
    protected $_debug = false;

    public function __construct($username = false, $password = false, $options = null) {

        if($username)
            $this->_username = $username;

        if($password)
            $this->_password = $password;

        if(isset($options['debug']))
            $this->_debug = $options['debug'];

        $this->_client = new SoapClient($this->_wsdl,
                array('encoding' => 'ISO-8859-1',
                      'trace' => 1,
                      'exceptions' => 0));
        
        $this->addAuthHeaders();
    }

    private function addAuthHeaders()
    {
        $headers = array(
                      new SOAPHEADER($this->_ns,'username',$this->_username,false, $this->_actor),
                      new SOAPHEADER($this->_ns,'password',$this->_password,false, $this->_actor));
        
        $this->_client->__setSOAPHeaders($headers);
    }

    private function returnResult($result, $function)
    {
        if($this->_debug)
        {
            echo '<div class="qbDebug" style="border-bottom: 1px solid #ccc;">';
            echo '<h2>' . $function . '</h2>';
            echo '<h4>Header:</h4><pre>'  .htmlspecialchars($this->_client->__getLastRequestHeaders()) . '</pre>' . "\n";
            echo '<h4>Request:</h4><pre>' . htmlspecialchars($this->_client->__getLastRequest()) . '</pre>' . "\n";
            echo '<h4>Response:</h4><pre>' . htmlspecialchars($this->_client->__getLastResponse()) . '</pre>' . "\n";
            echo '<pre>'; var_dump($result); echo '</pre>';
            echo '</div>';

            if(is_soap_fault($result))
                echo 'FAULT DETECTED' . $result->faultstring;
        }

        return $result;
    }

    public function createEmailMailing($mailing, $emailContent)
    {
        return $this->returnResult($this->_client->createEmailMailing($mailing, $emailContent), __FUNCTION__);
    }

    public function createRecipient($recipientObject)
    {
        return $this->returnResult($this->_client->createRecipient($recipientObject), __FUNCTION__);
    }

    public function createSendTemplate($sendTemplateBean, $text = '', $html = '')
    {
        return $this->returnResult($this->_client->createSendTemplate($sendTemplateBean, $text, $html), __FUNCTION__);
    }

    public function doImportAndSendFromTemplate($importID, $sendTemplateID, $importData)
    {
        return $this->returnResult($this->_client->doImportAndSendFromTemplate($importID, $sendTemplateID, $importData), __FUNCTION__);
    }

    public function doImportFromTemplate($importID, $importData)
    {
        return $this->returnResult($this->_client->doImportFromTemplate($importID, $importData), __FUNCTION__);
    }

    public function enableMailing($mailingID)
    {
        return $this->returnResult($this->_client->enableMailing($mailingID), __FUNCTION__);
    }
    
    public function getDetailedMailingReport($mailingID)
    {
        return $this->returnResult($this->_client->getDetailedMailingReport($mailingID), __FUNCTION__);
    }

    public function getFunctions()
    {
        return $this->_client->__getFunctions();
    }

    public function getImportStatus($importID, $fileName = '')
    {
        return $this->returnResult($this->_client->getImportStatus($importID, $fileName), __FUNCTION__);
    }

    public function getImportTemplates()
    {
        return $this->returnResult($this->_client->getImportTemplates(), __FUNCTION__);
    }
    
    /**
     * Search all lists or if empty, returns all lists
     *
     * @param array $listObject
     * @return array
     */
    public function getLists($listObject = '')
    {
        return $this->returnResult($this->_client->getLists($listObject), __FUNCTION__);
    }

    public function getList($listID)
    {
        return $this->returnResult($this->_client->getLists($listID), __FUNCTION__);
    }

    public function getMailingReportSummaries($reportCriteria)
    {
        return $this->returnResult($this->_client->__soapCall('getMailingReportSummaries',$reportCriteria), __FUNCTION__);
    }
    
    public function getMailingStatus($mailingID)
    {
        return $this->returnResult($this->_client->getMailingStatus($mailingID), __FUNCTION__);
    }

    public function getRecipientByAddress($emailAddress)
    {
        return $this->returnResult($this->_client->getRecipientByAddress($emailAddress), __FUNCTION__);
    }

    public function getRecipientByExternalID($externalID)
    {
        return $this->returnResult($this->_client->getRecipientByExternalID($externalID), __FUNCTION__);
    }

    public function getRecipientSubscriptionInfo($siteID, $emailAddress)
    {
        return $this->returnResult($this->_client->getRecipientSubscriptionInfo($siteID, $emailAddress), __FUNCTION__);
    }

    public function getRecipientSubscriptions($recipID)
    {
        return $this->returnResult($this->_client->getRecipientSubscriptions($recipID), __FUNCTION__);
    }

    public function getSendTemplateContent($sendTemplateID, $part = 'TEXT')
    {
        return $this->returnResult($this->_client->getSendTemplateContent($sendTemplateID, $part), __FUNCTION__);
    }

    public function getSendTemplateDefinition($sendTemplateID)
    {
        return $this->returnResult($this->_client->getSendTemplateDefinition($sendTemplateID), __FUNCTION__);
    }

    public function getSendTemplates()
    {
        return $this->returnResult($this->_client->getSendTemplates(), __FUNCTION__);
    }

    public function getVersion()
    {
        return $this->returnResult($this->_client->getVersion(), __FUNCTION__);
    }

    public function globalUnsubscribe($recipID, $mailingID = '')
    {
        return $this->returnResult($this->_client->globalUnsubscribe($recipID, $mailingID), __FUNCTION__);
    }

    public function globalUnsubscribeAll($emailAddress, $mailingID = '')
    {
        return $this->returnResult($this->_client->globalUnsubscribeAll($emailAddress, $mailingID), __FUNCTION__);
    }

    public function sendEmailMessages($sendTemplateID, $recipientData, $mailingTitle = '', $emailContent = '')
    {
        return $this->returnResult($this->_client->sendEmailMessages($sendTemplateID, $mailingTitle, $emailContent, $recipientData), __FUNCTION__);
    }

    public function sendMessageFromTemplate($sendTemplateID, $emailAddress, $substitutionTagVals = null)
    {
        return $this->returnResult($this->_client->sendMessageFromTemplate($sendTemplateID, $emailAddress, $substitutionTagVals), __FUNCTION__);
    }

    public function sendMessageFromTemplatewithReferenceNumber($templateID, $externalID, $recipientData)
    {
        return $this->returnResult($this->_client->sendMessageFromTemplatewithReferenceNumber($templateID, $externalID, $recipientData), __FUNCTION__);
    }

    public function sendMessageFromTemplatewithReferenceNumberAndAddress($templateID, $externalID, $emailAddress, $recipientData)
    {
        return $this->returnResult(
                $this->_client->sendMessageFromTemplatewithReferenceNumberAndAddress
                                ($templateID, $externalID, $recipientData), __FUNCTION__);
    }

    public function sendTestMessage($mailingID, $emailAddresses, $messageType = 'HTML')
    {
        return $this->returnResult($this->_client->sendTestMessage($mailingID, $emailAddresses, $messageType), __FUNCTION__);
    }
    
    public function subscribeToList($listID, $recipID = '', $confirmed = '', $sourceID = '', $mailingID = '')
    {
        return $this->returnResult($this->_client->subscribeToList($listID, $recipID, $confirmed, $sourceID, $mailingID), __FUNCTION__);
    }

    public function unsubscribeFromList($recipID, $listID, $mailingID = '')
    {
        return $this->returnResult($this->_client->unsubscribeFromList($recipID, $listID, $mailingID), __FUNCTION__);
    }

    public function updateEmailMailing($mailingID, $emailAddresses, $messageType = 'HTML')
    {
        return $this->returnResult($this->_client->updateEmailMailing($mailingID, $emailAddresses, $messageType), __FUNCTION__);
    }

    public function updateRecipient($recipientObject)
    {
        return $this->returnResult($this->_client->updateRecipient($recipientObject), __FUNCTION__);
    }

    public function updateSendTemplateContents($sendTemplateID, $text = '', $html = '')
    {
        return $this->returnResult($this->_client->updateSendTemplateContents($sendTemplateID, $text, $html), __FUNCTION__);
    }

    public function updateSendTemplateDefinition($sendTemplateID, $sendTemplateBean)
    {
        return $this->returnResult($this->_client->updateSendTemplateContents($sendTemplateID, $sendTemplateBean), __FUNCTION__);
    }
}
