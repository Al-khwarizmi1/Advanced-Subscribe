<?php
class Apptha_Advsubscribe_Block_Confirmmail extends Mage_Core_Block_Template
{
	private $emailIdIs;
	public function _prepareLayout()
    {

    	return parent::_prepareLayout();
    }
    
     public function getAdvsubscribe()     
     { 
        if (!$this->hasData('confirmmail')) {
            $this->setData('confirmmail', Mage::registry('confirmmail'));
        }
        return $this->getData('confirmmail');
        
    }
    public function getEmailId(){
    	return  $this->emailIdIs;
    }
    public function isValideUserEmail(){
    
    	 $validator = new Zend_Validate_EmailAddress();
    	 $key = $this->getRequest()->getParam('key');
         $this->emailIdIs = $email = $this->getRequest()->getParam('emailid');
       
         if($validator->isValid($email))
         {   
         	$isvalide =  $this->validateKeyAndEmailId($key,$this->emailIdIs);
         	if($isvalide)
         	{
         		return 1;
         	}
         	
         	
         	
         
         }
         else{
         	
		        if(isset($_REQUEST['categoriSelected'])) //if the form is submited then
				{   
					$selectedCate = $this->getRequest()->getParam('displayAllCategori');
					
					echo 'Your settings saved successfully';
					 
				}
				else{
         				return 0;
				}		
         }
         
    }
    public function getFormAction(){
    	return Mage::getBaseUrl().'advsubscribe/index/saveformdata';
    }
    public function getShopName(){
    	return Mage::getStoreConfig("general/store_information/name");
    }
    public function getDateTime(){
    	return date("F d , Y");
    }
    public function getdate(){
    	return date('Ymd');
    }
    public function test(){
    	echo 'krnathi';
    }
    public function get_categories(){
    	
		$AllCatDate = array();
		$category = Mage::getModel('catalog/category'); 
		$tree = $category->getTreeModel(); 
		$tree->load();
		$ids = $tree->getCollection()->getAllIds(); 
		$arr = array();
		if ($ids){ 
			
			foreach ($ids as $id){

				$catimg = 'http://localhost/magento_dev/magento/img/placeholder.gif';

				/* Load category by id*/
				$cat = Mage::getModel('catalog/category')->load($id);
				
				
				/*Returns comma separated ids*/
				$subcats = $cat->getChildren();


					foreach(explode(',',$subcats) as $subCatid)
					{
						  $_category = Mage::getModel('catalog/category')->load($subCatid);
							  if($_category->getIsActive())
							  { 
							  	$catId      = $_category->getId();
							    $caturl     = $_category->getURL();
							    $catname    = $_category->getName();
							    if($_category->getImageUrl())
							    {
							      echo $catimg   = $_category->getImageUrl();
							    }
							 
							   $AllCatDate[] = array('catId' =>$catId , 'catName' =>$catname , 'catUrl' => $caturl , 'catImg' => $catimg) ; 
							  }
					}
					
			} //foreach loop end
		}//if end hear

		return $AllCatDate;
		
    }//function end
    public function validateKeyAndEmailId($key,$emailId , $giveKey = 0){
            
           $id = Mage::getModel('advsubscribe/sendingmails')->getThisEmailKeyFromDB($emailId,$giveKey); //get the encryptkey_id from Db and send to func compare this key and new key
    		if(!$id)
    		{
    				// He already become unsubsriber.
    				return 0;
    		}
    		$newId = Mage::getModel('advsubscribe/sendingmails')->getRandUniqid($key , true);
    		if($giveKey)
    		{
    			return $newId;
    		}
    		if(!strcmp($id,$newId))
    		{
    			return 1;
    		}
    		else{
    			return 0;
    		}
    		
    }
    
}