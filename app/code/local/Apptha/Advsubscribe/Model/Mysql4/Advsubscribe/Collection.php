<?php

class Apptha_Advsubscribe_Model_Mysql4_Advsubscribe_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advsubscribe/advsubscribe');
    }
}