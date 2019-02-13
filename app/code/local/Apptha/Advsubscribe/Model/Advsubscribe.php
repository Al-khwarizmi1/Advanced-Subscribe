<?php
class Apptha_Advsubscribe_Model_Advsubscribe extends Mage_Core_Model_Abstract
{
	private $advNotifyTableName;
	private $read;
	private $date;
    public function _construct()
    {
        parent::_construct();
        $this->_init('advsubscribe/advsubscribe');
        $resource = Mage::getSingleton('core/resource');
        $this->date = date("M d, Y");
        $this->read = $resource->getConnection('write');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
	    $this->advNotifyTableName = $tPrefix.'advsubscribe';
	    
    }
    public function getNumberOfFollowers(){
    	
    				$query = "SELECT COUNT(*) as numberOfFollowers  FROM  $this->advNotifyTableName WHERE follower = 1 ";
	     			
		     	    $data =  Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
		     	    return  intval($data[0]['numberOfFollowers']);
    }
    public function subsriberEmailIsAlreadySend($paraMeters = array())
    {			
    	
    		  $isArray = count($paraMeters);
    		
      		  
      		  
	    		if($isArray)
	    		{
	    			$emailId= $paraMeters['email'];
	    			
	    			 $query = "SELECT COUNT(*) as isNotify  FROM  $this->advNotifyTableName WHERE email_id = '$emailId' AND follower = 1 ";
	     			
		     	    $data =  Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
		    		$isAlreadyNotify = intval($data[0]['isNotify']);  //check this user is already notify or not?
	    		 
			    		if(!$isAlreadyNotify)  //if he is not a follower then do this task
			    		{
			    			 $query = "SELECT COUNT(*) as isNotify  FROM  $this->advNotifyTableName WHERE email_id = '$emailId' ";
			    			
			    			 $data =  Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
			    			 $isAlreadyNotify = intval($data[0]['isNotify']); 
			    			 if($isAlreadyNotify)
			    			 {
			    			      //this mail alerday inserted in DB so dont insert agin and ask confirm to send mail again!
			    			      return 10;
			    			 }
			    			 else
			    			 {   //send mail and insert also
			    					
			  					     return 5;
			    			 }
		 				 	    		
			    		}
			    		else{ //he is follower so dont do any task
			    			
			    			return 20;
			    		}
		    			
    			}//if condition is end hear
    		
    		
       }//function is end hear
      
       public function conformFollowerEmailId($subsEmailId,$catIdAre)  //user selected categoris and clicked in SAVE CHANGES button then form submited then we are storing that mail,cateid,follower =1 
       {
       	      		
       		$query = " UPDATE $this->advNotifyTableName SET categori_id= '$catIdAre' , follower = 1, status = 'Active' ,  update_time = '$this->date' WHERE email_id = '$subsEmailId' " ;
       		return $this->read->query($query);
       }
       public function deleteSubscriberFromTable($emailId,$deleteKeyValu)
       {
       			$query = " UPDATE $this->advNotifyTableName SET  follower = 0, status = 'In Active' ,  update_time = '$this->date' , categori_id = '' WHERE email_id = '$emailId' AND encryption_key = '$deleteKeyValu' " ;
       			$this->read->query($query);
       			
       			
       }
       public function stopSendingMailsAndMaintainFollower($emailId,$deleteKeyValu){
       
      			$query = " UPDATE $this->advNotifyTableName SET   status = 'In Active' , categori_id = '' ,  update_time = '$this->date' WHERE email_id = '$emailId' AND encryption_key = '$deleteKeyValu' " ;
       			$this->read->query($query);
       		
       }
       public function exethisquery(){
	}   
}
?>