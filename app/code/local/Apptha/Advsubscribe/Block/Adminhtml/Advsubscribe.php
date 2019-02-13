<?php
class Apptha_Advsubscribe_Block_Adminhtml_Advsubscribe extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_advsubscribe';
    $this->_blockGroup = 'advsubscribe';
   $this->_headerText = Mage::helper('advsubscribe')->__('Followers Details');
    parent::__construct();
    $this->_removeButton('add');
  }
}