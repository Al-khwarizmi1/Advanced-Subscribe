<?php
class Apptha_Advsubscribe_Block_Advsubscribe extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAdvsubscribe()     
     { 
        if (!$this->hasData('advsubscribe')) {
            $this->setData('advsubscribe', Mage::registry('advsubscribe'));
        }
        return $this->getData('advsubscribe');
        
    }
}