<?php
class Apptha_Advsubscribe_Block_Deletesubscription extends Mage_Core_Block_Template
{
	private $encryptKeyval;
			public function _prepareLayout()
		    {  
				
		    	return parent::_prepareLayout();
		    	
		    }
		    public function getAdvsubscribe()     
		     { 
		        if (!$this->hasData('deletesubscription')) {
		            $this->setData('deletesubscription', Mage::registry('deletesubscription'));
		        }
		        return $this->getData('deletesubscription');
		        
		    }
			 public function getShopName(){
			    	return Mage::getStoreConfig("general/store_information/name");
			    }
			 public function getDeleteFormAction(){
			    	return Mage::getBaseUrl().'advsubscribe/index/deleteSubscriber'; 
			    }
			    
			 public function checkValidatefEmailAndKey(){
			 							   $confirmmailObj =new Apptha_Advsubscribe_Block_Confirmmail();
			 							   $giveKey = 1; //for identify the delete function
			 							   $key = $this->getKeyValue();
			 							   $emailId = $this->getEmailId();
			 							   
			 							   $this->encryptKeyval = $confirmmailObj->validateKeyAndEmailId($key,$emailId , $giveKey);
										   $this->encryptKeyval =  intval( $this->encryptKeyval);
											if(!$this->encryptKeyval)
											{
													return 0;
											}		 							   
			 							  return $confirmmailObj->isValideUserEmail();
			 }    
			 public function getKeyValue(){
			 
			 	return $key = $this->getRequest()->getParam('key');
			 }
			 public function getEmailId(){
			 			  
			 return $key = $this->getRequest()->getParam('emailid');
			 }
			 public function getEncryptionKeyValue(){
			 
					return $this->encryptKeyval;
			 }
}