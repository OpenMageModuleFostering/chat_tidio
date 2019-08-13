<?php

class Tidio_Chat_Block_Adminhtml_Menu extends Mage_Adminhtml_Block_Page_Menu
{
    const XML_PATH_EXTENSION_PRIVATE_KEY = 'chat_section/chat_group/privatekey';
    public function getMenuArray()
    {
        //Load standard menu
        $parentArr = parent::getMenuArray();
        $privateKey = Mage::getStoreConfig(self::XML_PATH_EXTENSION_PRIVATE_KEY);


        $chatUrl = "'http://external.tidiochat.com/access?privateKey=$privateKey'";
        $parentArr['tidio_chat'] = array(
            'label' => 'Tidio Chat',
            'active' => false,
            'sort_order' => 16,
            'click' => 'return false;',
            'url'=> '#' ,
            'level'=> 0,
            'last'=> true,
            'children' => array(
                array(
                    'label' => 'Tidio Chat',
                    'active' => false,
                    'sort_order' => 1,
                    'click' => "window.open($chatUrl)",
                    'url'=> '#' ,
                    'level'=> 0,
                    'last'=> true,
                )
            )
        );

        return $parentArr;
    }

}
