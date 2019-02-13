<?php
class Apptha_Advsubscribe_Model_Sendingmails extends Mage_Core_Model_Abstract
{
		private $shopName;
		private $siteLink;
		private $createNewAccout;
		private $encryKeyId;
		private $encryKeyVal;
		private $baseUrl;
		private $date;
		private $read;
		private $advNotifyTableName;
   function  __construct(){
   	
   			$this->shopName  = Mage::getStoreConfig("general/store_information/name");
   			$this->siteLink  = Mage::getBaseUrl();
   			$this->createNewAccout = Mage::getBaseUrl().'customer/account/login/';
   			$this->baseUrl = Mage::getBaseUrl();
   			
   		$resource = Mage::getSingleton('core/resource');
        $this->date = date("M d, Y");
        $this->read = $resource->getConnection('write');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
	    $this->advNotifyTableName = $tPrefix.'advsubscribe';
   }		
   public function sendConfirmationMailToSubscriber($to , $key){
                   
  		if (! $to) return 0;
        $sendTo = array();       
      	$recipient = $to;
//get uniquey key id and encryptionkey
		$this->encryKeyId  = date('YmdHis');
	
	$this->encryKeyVal = $this->getRandUniqid($this->encryKeyId);
	
		if($key) //get key of this id
		{
			
				$this->encryKeyVal = $this->getThisEmailKeyFromDB($to);
				$this->encryKeyVal = $this->getRandUniqid($this->encryKeyVal);
		}
		else{
				
			$this->encryKeyVal = $this->insertThisEmailValue($to); //insert data in table and return key val
		}
		
		$ctrlName = 'advsubscribe/index/getconfirmmailcontent?key='.$this->encryKeyVal.'&emailid='.$to.'&action=update'; 
     	$emailTemplateVariables = array();
		$emailTemplateVariables['storeName']   = $this->shopName;
		$emailTemplateVariables['siteLink']   = $this->siteLink;
		$emailTemplateVariables['createNewAccount'] = $this->createNewAccout;
		 
		$emailTemplateVariables['confirmButtonUrl'] = $this->baseUrl.$ctrlName;
		
		$marchentNotificationMailId  = Mage::getStoreConfig('advsubscribe/advsubscribe_confirm_email_settings/confirm_email_sender');
    	$senderMailId                = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/email");
      	$senderName                  =  Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/name");
	    $templeId                    = (int)Mage::getStoreConfig('advsubscribe/advsubscribe_confirm_email_settings/confirm_email_template');
	    
        if($templeId)   //if it is user template then this process is continue
        {
        	$emailTemplate  = Mage::getModel('core/email_template')->load($templeId);
        }
        else{   //  we are calling default template 
        		$emailTemplate  = Mage::getModel('core/email_template')
	        	->loadDefault('apptha_confirm_email_template'); 
	     }   
	    
	        	$emailTemplate->setSenderName($senderName);     //mail sender name
	        	$emailTemplate->setSenderEmail($senderMailId);  //mail sender email id
	        	$emailTemplate->setTemplateSubject('Follow '.$this->storeName );
 				$emailTemplate->setDesignConfig(array('area' => 'frontend'));
	        	$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
	           $emailTemplate->send($recipient,$senderName, $emailTemplateVariables);  //send mail to customer email ids
	 
	    
	        	return 1;				
   }
   public function sendSuccessMailToSubscriber($to)
   {
   
   	  	if (! $to) return 0;

   	  	$sendTo = array();       
      	$recipient = $to;
		$encryKeyVal = $this->getThisEmailKeyFromDB($to);
		$encryKeyVal = $this->getRandUniqid($encryKeyVal);
		
		$ctrlName      = 'advsubscribe/index/getconfirmmailcontent?key='.$encryKeyVal.'&emailid='.$to.'&action=update';
		$unsubCtrlName = 'advsubscribe/index/deletesubscriptioin?key='.$encryKeyVal.'&emailid='.$to.'&action=update'; 
	 	$emailTemplateVariables = array();
		$emailTemplateVariables['storeName']        = $this->shopName;
		$emailTemplateVariables['siteLink']         = $this->siteLink;
		$emailTemplateVariables['createNewAccount'] = $this->createNewAccout;
		$emailTemplateVariables['unsubscribe']      = $this->baseUrl.$unsubCtrlName;
		
		
		$emailTemplateVariables['manageSubscriptionButtonUrl'] = $this->baseUrl.$ctrlName;
		
        $marchentNotificationMailId  = Mage::getStoreConfig('advsubscribe/advsubscribe_success_email_settings/success_email_sener');
        $senderMailId                = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/email");
        $senderName                  = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/name");
	    $templeId                    = (int)Mage::getStoreConfig('advsubscribe/advsubscribe_confirm_email_settings/success_email_template');
	    
        if($templeId)   //if it is user template then this process is continue
        {
        	$emailTemplate  = Mage::getModel('core/email_template')->load($templeId);
        }
        else{   //  we are calling default template 
        		$emailTemplate  = Mage::getModel('core/email_template')
	        	->loadDefault('apptha_success_email_template'); 
	     }   
	        
	        	$emailTemplate->setSenderName($senderName);     //mail sender name
	        	$emailTemplate->setSenderEmail($senderMailId);  //mail sender email id
	        	$emailTemplate->setTemplateSubject('Follow '.$this->storeName );
 				$emailTemplate->setDesignConfig(array('area' => 'frontend'));
	        	$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
 	
	        	$emailTemplate->send($recipient,$senderName, $emailTemplateVariables);  //send mail to customer email ids
   
   }
   public function newProductAddedMailSending($to = array(), $emailTemplateVariables = array())
   {  //if any new product is added then send mail to all followers
   
     if (! $to) return;
         
          $sendTo = array();       
        
        foreach ($to as $recipient)
        {
            if (is_array($recipient))
            {
                $sendTo[] = $recipient['email_id'];
            }
            else
            {
                $sendTo[] = array(
                    'email' => $recipient,
                    'name' => null,
                );
            }
        } 
   	//print_r($emailTemplateVariables);		print_r($sendTo);exit;		
    
  		$marchentNotificationMailId  =  Mage::getStoreConfig('advsubscribe/advsubscribe_addnewproduct_email_settings/product_email_sender');
        $senderMailId                =  Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/email");
        $senderName                  =  Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/name");
	    $templeId                    = (int)Mage::getStoreConfig('advsubscribe/advsubscribe_addnewproduct_email_settings/product_email_template');
		
	    
        if($templeId)   //if it is user template then this process is continue
        {
        	$emailTemplate  = Mage::getModel('core/email_template')->load($templeId);
        }
        else{   //  we are calling default template 
        		$emailTemplate  = Mage::getModel('core/email_template')
	        	->loadDefault('apptha_newporduct_email_template'); 
	     }   
	        
	        	$emailTemplate->setSenderName($senderName);     //mail sender name
	        	$emailTemplate->setSenderEmail($senderMailId);  //mail sender email id
	        	$emailTemplate->setTemplateSubject('New Product Added Notification from '.$this->storeName );
 				$emailTemplate->setDesignConfig(array('area' => 'frontend'));
	        	$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
	  	
	        	foreach ($sendTo as $recipient) {
	        	$emailTemplate->send($recipient,$senderName, $emailTemplateVariables);  //send mail to customer email ids
				}
			
   
   	}
   public function getThisEmailKeyFromDB($emailId = '',$deleteCtrl = 0){
   
  		
  		$emailId = trim($emailId);
  		$resource = Mage::getSingleton('core/resource');
        $this->date = date("M d, Y");
        $fgt = $resource->getConnection('read');
        
  		if(!is_null($emailId))
  		{   
  			 $query =  "SELECT encryption_key , follower FROM $this->advNotifyTableName WHERE email_id = '$emailId' LIMIT 1 ";
  			 $data =  Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
  			}
  		if($deleteCtrl)
  		{	
	  		if(!intval($data[0]['follower']))
	  		{
	  			return 0;
	  		}
  		}
  		return $data[0]['encryption_key'];
  }
  private function insertThisEmailValue($emailId)
  {
  	 $id = date('YmdHis');
     $key = $this->getRandUniqid($id);
  
  	$key = trim($key);
	  	if(!is_null($key))
	  	{
	  				  		
	  			
		$fbupdate = $this->read->query("insert into $this->advNotifyTableName ( email_id , encryption_key , created_time )	values ('$emailId' ,'$id', '$this->date' ) " );
		
		return $key;
	  	}
  
  }
	 public function getRandUniqid($in, $to_num = false, $pad_up = false, $passKey = null)
		{
			
		 $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		    if ($passKey !== null) {
		        // Although this function's purpose is to just make the
		        // ID short - and not so much secure,
		        // you can optionally supply a password to make it harder
		        // to calculate the corresponding numeric ID
		
		        for ($n = 0; $n<strlen($index); $n++) {
		            $i[] = substr( $index,$n ,1);
		        }
	
		      $passhash = hash('sha256',$passKey);
		    
		        $passhash = (strlen($passhash) < strlen($index))
		            ? hash('sha512',$passKey)
		            : $passhash;
		
		        for ($n=0; $n < strlen($index); $n++) {
		            $p[] =  substr($passhash, $n ,1);
		        }
		
		        array_multisort($p,  SORT_DESC, $i);
		        $index = implode($i);
		    }
		
		  $base  = strlen($index);
	
		    if ($to_num) {
		        // Digital number  <<--  alphabet letter code
		        $in  = strrev($in);
		        $out = 0;
		        $len = strlen($in) - 1;
		        for ($t = 0; $t <= $len; $t++) {
		            $bcpow = pow($base, $len - $t);
		            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
		        }
		
		        if (is_numeric($pad_up)) {
		            $pad_up--;
		            if ($pad_up > 0) {
		                $out -= pow($base, $pad_up);
		            }
		        }
		        $out = sprintf('%F', $out);
		        $out = substr($out, 0, strpos($out, '.'));
		    } else {
		        // Digital number  -->>  alphabet letter code
		        if (is_numeric($pad_up)) {
		            $pad_up--;
		            if ($pad_up > 0) {
		                $in += pow($base, $pad_up);
		            }
		        }
		       
	
		        $out = "";
		        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
		            $bcp = pow($base, $t);
		            $a   = floor($in / $bcp) % $base;
		            $out = $out . substr($index, $a, 1);
		            $in  = $in - ($a * $bcp);
		        }
		        $out = strrev($out); // reverse
		    }
		
		    return $out;
		}
}//class end hear