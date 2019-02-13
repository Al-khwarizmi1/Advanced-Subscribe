<?php

class Apptha_Advsubscribe_Block_Adminhtml_Advsubscribe_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('advsubscribe_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('advsubscribe')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('advsubscribe')->__('Item Information'),
          'title'     => Mage::helper('advsubscribe')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('advsubscribe/adminhtml_advsubscribe_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}