<?php

/**
 * Observer class file.
 *
 * @category    TB
 * @package     Tidio_Chat
 * @author      tb
 * @copyright   Copyright (c) 2014
 */

class Tidio_Chat_Model_Observer extends Mage_Core_Model_Abstract
{
    // paths to configuration
    const XML_PATH_EXTENSION_ENABLED = 'chat_section/chat_group/tidio_enable';
    const XML_PATH_EXTENSION_PUBLIC_KEY = 'chat_section/chat_group/publickey';
    const XML_PATH_EXTENSION_PRIVATE_KEY = 'chat_section/chat_group/privatekey';

    private $extEnabled = null;
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->extEnabled = (int) Mage::getStoreConfig(self::XML_PATH_EXTENSION_ENABLED);

        // if extension not enabled, then return
        if (!$this->extEnabled) {
            return;
        }

        try {
        } catch (Exception $e) {
        }
    }
    /**
     * Event dispatched after backoffice loaded
     *
     * @param array $observer
     *
     */
    public function hookToAdminLoad($observer)
    {
        if ($this->extEnabled) {
            $publicKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY);
            $privateKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY);
            if ((trim($publicKey) == '') || (trim($privateKey) == '')) {
                $keys = $this->_getKeys();

                $config = new Mage_Core_Model_Config();
                $config->saveConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY, $keys['public_key']);
                $config->saveConfig(self::XML_PATH_EXTENSION_PRIVATE_KEY, $keys['private_key']);
            }
        }
    }

    private function _getKeys()
    {
        $domainName = $_SERVER['HTTP_HOST'];
        $adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');
        $remoteAddr = $_SERVER['REMOTE_ADDR'];

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, "http://www.tidiochat.com/access/create?url=" . urlencode($domainName) . "&platform=magento&email=$adminEmail&_ip=$remoteAddr");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($output);

        return array('public_key' => $json->value->public_key, 'private_key' => $json->value->private_key);
    }




}