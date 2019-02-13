<?php

class Apptha_Advsubscribe_Block_Adminhtml_Advsubscribe_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'advsubscribe';
        $this->_controller = 'adminhtml_advsubscribe';
        
        $this->_updateButton('save', 'label', Mage::helper('advsubscribe')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('advsubscribe')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('advsubscribe_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'advsubscribe_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'advsubscribe_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('advsubscribe_data') && Mage::registry('advsubscribe_data')->getId() ) {
            return Mage::helper('advsubscribe')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('advsubscribe_data')->getTitle()));
        } else {
            return Mage::helper('advsubscribe')->__('Add Item');
        }
    }
}