<?php

class Apptha_Advsubscribe_Block_Adminhtml_Advsubscribe_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('advsubscribe_form', array('legend'=>Mage::helper('advsubscribe')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('advsubscribe')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('advsubscribe')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('advsubscribe')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('advsubscribe')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('advsubscribe')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('advsubscribe')->__('Content'),
          'title'     => Mage::helper('advsubscribe')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getAdvsubscribeData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getAdvsubscribeData());
          Mage::getSingleton('adminhtml/session')->setAdvsubscribeData(null);
      } elseif ( Mage::registry('advsubscribe_data') ) {
          $form->setValues(Mage::registry('advsubscribe_data')->getData());
      }
      return parent::_prepareForm();
  }
}