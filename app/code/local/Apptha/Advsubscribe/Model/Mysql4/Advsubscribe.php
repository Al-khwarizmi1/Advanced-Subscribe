<?php

class Apptha_Advsubscribe_Model_Mysql4_Advsubscribe extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the advsubscribe_id refers to the key field in your database table.
        $this->_init('advsubscribe/advsubscribe', 'advsubscribe_id');
    }
}