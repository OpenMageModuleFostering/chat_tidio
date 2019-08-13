<?php

class Tidio_Chat_Block_Script extends Mage_Core_Block_Template
{
    const XML_PATH_EXTENSION_ENABLED = 'chat_section/chat_group/tidio_enable';
    const XML_PATH_EXTENSION_PUBLIC_KEY = 'chat_section/chat_group/publickey';

    protected function _construct()
    {
        parent::_construct();

    }

    public function canGenerate()
    {
        $extEnabled = (int) Mage::getStoreConfig(self::XML_PATH_EXTENSION_ENABLED);
        $publicKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY);

        if (!$publicKey && (Mage::getSingleton('core/session')->getPublicKey() != '')) {
            $publicKey = Mage::getSingleton('core/session')->getPublicKey();
        }

        if ($extEnabled && (trim($publicKey) != '')) {
            return true;
        }

        return false;
    }

    public function getPublicKey()
    {
        $publicKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY);
        if (!$publicKey && (Mage::getSingleton('core/session')->getPublicKey() != '')) {
            $publicKey = Mage::getSingleton('core/session')->getPublicKey();
        }

        return $publicKey;
    }

    public function getProtocol()
    {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
        return $isSecure ? 'https' : 'http';
    }
}
