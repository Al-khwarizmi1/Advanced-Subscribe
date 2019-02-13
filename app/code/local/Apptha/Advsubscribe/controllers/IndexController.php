<?php
class Apptha_Advsubscribe_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/advsubscribe?id=15 
    	 *  or
    	 * http://site.com/advsubscribe/id/15 	
    	 */
    	/* 
		$advsubscribe_id = $this->getRequest()->getParam('id');

  		if($advsubscribe_id != null && $advsubscribe_id != '')	{
			$advsubscribe = Mage::getModel('advsubscribe/advsubscribe')->load($advsubscribe_id)->getData();
		} else {
			$advsubscribe = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($advsubscribe == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$advsubscribeTable = $resource->getTableName('advsubscribe');
			
			$select = $read->select()
			   ->from($advsubscribeTable,array('advsubscribe_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$advsubscribe = $read->fetchRow($select);
		}
		Mage::register('advsubscribe', $advsubscribe);
		*/
    	$this->loadLayout();
		$this->renderLayout();
    }
   public  function storingSubscriberEmailIdsInDBAction(){  //it is calling form ajax
    
    			 $email = $this->getRequest()->getParam('eMailId');
    			 $sendMailAgain = $this->getRequest()->getParam('sendMailAgain'); //echo $email.' -- '.$sendMailAgain; exit;
    			 $isInt = new Zend_Validate_Int();
    			  if($isInt->isValid($sendMailAgain))  //for send mail again bec this mail id already in DB
    			   {   
    			   		$key = 1; //for get key form dB and send link again 
						Mage::getModel('advsubscribe/sendingmails')->sendConfirmationMailToSubscriber($email,$key );
						echo 'inserted success'; 
				   }
				 else{ 
		    			
		    			 $validator = new Zend_Validate_EmailAddress();
		    			 if(!$validator->isValid($email))
		    			 {
		    			 	
		    			 }
		    			 $paraMeters = array('email' => $email);
		    	
		    			 $statusOfInsert = Mage::getModel('advsubscribe/advsubscribe')->subsriberEmailIsAlreadySend($paraMeters); //check already send or not
		    			 $statusOfInsert = intval($statusOfInsert);
		    			
   					    if($statusOfInsert == 10)
				    	{
				    		echo 'confirm to send mail again';
				    	}
				    	else if($statusOfInsert == 5){
				    		
				    		$key = 0;
				    		Mage::getModel('advsubscribe/sendingmails')->sendConfirmationMailToSubscriber($email,$key ); //send notify mail to custmer
				    		echo 'inserted success';    //return false; //if product is already notified then we ask to another id
				    	}
				    	else if($statusOfInsert == 20){  //he is already subscriber
				    		
				    		echo 'you are already subscriber';
				    		   
				    	}
				    	else{
				    			return 0;
				    	}
    			  
				 }  
    			   
					
	  
    }
    public  function getconfirmmailcontentAction(){   //if user click on confirm button in his mail then this funcion is calling

    		
    
 			 $this->loadLayout();
    		 $this->renderLayout();
    	   	
    }
    public function deletesubscriptioinAction(){  //when user click on unsubscriber button then we are going to delete the subscriber
    
    		 $this->loadLayout();
    		 $this->renderLayout();	
    	
    }
    public function deleteSubscriberAction(){  //after update deleted subscribtion then we store form values in Db
    
    		
    		$action = trim($this->getRequest()->getParam('action'));
    		$emailId = trim($this->getRequest()->getParam('deleteEmailId'));
    		$deleteKeyValu = trim($this->getRequest()->getParam('deleteKeyValu'));
    		//deleteKeyValu
    		$isValideEmailId = new Zend_Validate_EmailAddress();
    		   		
    		if($isValideEmailId->isValid($emailId))
    		{        
    				if($action == 'delete_blog')
    				{  
    					mage::getModel('advsubscribe/advsubscribe')->deleteSubscriberFromTable($emailId,$deleteKeyValu);  //subscri clikced CONFIRM DELETION BUTTON
    					Mage::getSingleton('core/session')->addSuccess($this->__('Unsubscribed successfully.')); 
    				
    				}
    				elseif($action == 'stopall') //update only status In Active but followe is 1 only bec he want only stop mails
    				{
    					
    					 mage::getModel('advsubscribe/advsubscribe')->stopSendingMailsAndMaintainFollower($emailId,$deleteKeyValu);
    					 Mage::getSingleton('core/session')->addSuccess($this->__('You are subscribtion will be stopped.'));
    				}
    		}
    		
    			Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl());
    }
    public function saveformdataAction(){
    	
    	   
    	
    	   $SelectedCategori = $this->getRequest()->getParam('AllcategoriSelected'); //selectd categories
    	   
    	   $SelectedCategori = array_unique($SelectedCategori);
    	   $AllCategoriesIdsList = $this->getRequest()->getParam('AllCategoriesIdsList');  //all categories list
    	  
    	   $formSubmited = $this->getRequest()->getParam('formSubmited');
    	   $subsEmailId = $this->getRequest()->getParam('subsEmailId');
    	   $isValideEmailId = new Zend_Validate_EmailAddress();
    	    
    	   
    	   if(strlen($formSubmited)>7 && $isValideEmailId->isValid($subsEmailId)){
    	   		$catIdAre = '';
    	   		if(count($SelectedCategori))
    	   		{
    	   			foreach($SelectedCategori as $k => $value)
    	   			{
    	   					$catIdAre .= $value.',';
    	   			}
    	   		}
    	   		else{
    	   			$catIdAre = $AllCategoriesIdsList;
    	   		}
    	   	    	   	
    	   		$catIdAre = substr($catIdAre , 0,strlen($catIdAre)-1);
    	   		$isupdated =  (int)mage::getModel('advsubscribe/advsubscribe')->conformFollowerEmailId($subsEmailId,$catIdAre);
    	   		
    	  		if($isupdated)
    	  		{
    	  			Mage::getModel('advsubscribe/sendingmails')->sendSuccessMailToSubscriber($subsEmailId);
    	  			$this->getconfirmmailcontentAction();
    	  			Mage::getSingleton('core/session')->addSuccess($this->__('Subscriber successfully added.'));
    	  			
    	  		}
    	  		else{
    	  			Mage::getSingleton('core/session')->addError($this->__('Invalid argument supplied.'));
    	  		}
    	  		 
    	   }
    	   else{
    	   		
    	   		$this->getconfirmmailcontentAction();
    	   		Mage::getSingleton('core/session')->addError($this->__('Invalid argument supplied.'));
    	   }	
           Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl());
    }
    
    
    
}