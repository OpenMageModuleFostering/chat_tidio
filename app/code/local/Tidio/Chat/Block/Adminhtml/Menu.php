<?php

class Tidio_Chat_Block_Adminhtml_Menu extends Mage_Adminhtml_Block_Page_Menu
{
    // paths to configuration
    const XML_PATH_EXTENSION_ENABLED = 'chat_section/chat_group/tidio_enable';
    const XML_PATH_EXTENSION_PUBLIC_KEY = 'chat_section/chat_group/publickey';
    const XML_PATH_EXTENSION_PRIVATE_KEY = 'chat_section/chat_group/privatekey';

    public function getMenuArray()
    {
        //Load standard menu
        $parentArr = parent::getMenuArray();

        $publicKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY);
        $privateKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PRIVATE_KEY);

        if (Mage::getSingleton('core/session')->getPrivateKey() != '') {
            $publicKey = Mage::getSingleton('core/session')->getPublicKey();
            $privateKey = Mage::getSingleton('core/session')->getPrivateKey();
        }

        if ((trim($publicKey) == '') || (trim($privateKey) == '')) {
            $keys = $this->_getKeys();
            $publicKey = $keys['public_key'];
            $privateKey = $keys['private_key'];

            $config = new Mage_Core_Model_Config();
            $config->saveConfig(self::XML_PATH_EXTENSION_PUBLIC_KEY, $publicKey);
            $config->saveConfig(self::XML_PATH_EXTENSION_PRIVATE_KEY, $privateKey);

            Mage::getSingleton('core/session')->setPrivateKey($privateKey);
            Mage::getSingleton('core/session')->setPublicKey($publicKey);
        }

        $extEnabled = true;//(int) Mage::getStoreConfig(self::XML_PATH_EXTENSION_ENABLED);
        if ($extEnabled && ($privateKey != '')) {
            $chatUrl = "'http://external.tidiochat.com/access?privateKey=$privateKey'";
            $parentArr['tidio_chat'] = array(
                'label' => 'Tidio Chat',
                'active' => false,
                'sort_order' => 16,
                'click' => "window.open($chatUrl)",
                'url'=> '#' ,
                'level'=> 0,
                'last'=> true,
            );
        }

        return $parentArr;
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
