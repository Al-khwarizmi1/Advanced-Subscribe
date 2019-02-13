<?php
class Apptha_Advsubscribe_Model_Observer extends Mage_Core_Model_Abstract{

	 private $stockNotifiTable;
	 private $read;
	 private $bcc;
	 private $productName;
	 private $productUrl;
	 private $prodcutImg;
	 private $siteLink;
	 private $productDescr;
	 private $storeName;
	 
	 
	public function __construct(){
			
		parent::_construct();
        $this->_init('advsubscribe/advsubscribe');
       
        $resource     = Mage::getSingleton('core/resource');
        $this->read   = $resource->getConnection('write');
        $tPrefix      = (string) Mage::getConfig()->getTablePrefix();
        $this->advNotifyTableName = $tPrefix.'advsubscribe';
        $this->siteLink         = Mage::getBaseUrl();
	
	}
	public function newProductAddedMailSendingToSubs($observer){

		$product = $observer->getProduct();
		$catIdArray = $product->_data['category_ids'];
		$numOfChar = count($catIdArray);
		$newCatId = $catIdArray[$numOfChar-1];
		$productNewUrl = Mage::getModel("catalog/category")->load($newCatId)->getUrl();
		$productNewUrl = substr($productNewUrl,0,(strlen($productNewUrl)-5) );
	
		$quantity  = $product->_data['stock_data']['qty'];
		$is_in_stock = $product->_data['stock_data']['is_in_stock'];
 	    $typeOfProduct =  $product->_data['type_id'];
	    if(!$product->_isObjectNew) //if not new product
    	{
    		return false;
    	} 
	    if($typeOfProduct == trim('downloadable')) //is it is not a simple product it is downloadable produt
	    {
			    $quantity = 1;
			    $is_in_stock = 1;
			 
	    }  
    	if(!$is_in_stock && !$quantity)
        {  
        	return false;
        }    
                  
 	   $_categories = $product->getCategoryIds();
     
            if ($product->getStatus() == 1)
             {
                $_categories = $product->getCategoryIds();
                if (count($_categories) > 0)
                {
                    $_category = Mage::getModel('catalog/category')->load($_categories[0]);
                    $url_key = $product->getUrlKey();
                    if (empty($url_key))
                    {
                        $product_url = $product->getName() . '.html';
                    }
                    else
                    {
                        $product_url = $url_key . '.html';
                    }
                }
                else
                {
                    $url_key = $product->getUrlKey();
                    if (empty($url_key))
                    {
                        $product_url = $product->getName() . '.html';
                    }
                    else
                    {
                        $product_url = $url_key . '.html';
                    }
                }
             }
        $sukUrl = explode(" ",$product_url);
     	$sukUrl = array_filter($sukUrl, 'strlen');
        $product_url = implode('-',$sukUrl);
        $this->productName = $product->getname();
        $product_description = $product->getdescription();
        $model = Mage::getModel('catalog/product');
        $productUrl        = $product->getUrlInStore();
        
        $this->productUrl = Mage::getBaseUrl().$product_url;
   	     
		$this->storeName = Mage::getStoreConfig("general/store_information/name");
		$catIdArray = $product->_data['category_ids'];
		$catIdArray = array_unique($catIdArray); //get uniquey categors id values
		$hi = json_decode($product->_data['media_gallery']['images']); //get images
		for($i = 0 ; $i < count($hi) ; $i++ )
		{
			if(!$hi[$i]->disabled && $hi[$i]->position == 1  && !$hi[$i]->removed){
				$prodcutImageIs = $hi[$i]->url;
				break;
			} 
			else if(!$hi[$i]->disabled  &&  !$hi[$i]->removed){
				$prodcutImageIs = $hi[$i]->url;
			}
		}
	 $this->prodcutImg = $prodcutImageIs;
	 $dir = Mage::getBaseDir();
 	 $path = $dir.DS.'media'.DS.'catalog'.DS.'advsub'.DS;
   
	if(is_dir($structure))
    {  
    	
    }
    else
    {
        mkdir($path,0777);
    }   
    $imageName = date("YmdHms").'.jpg';
    	
    	$src =  $this->prodcutImg;
        $uploadDirPath = $path.$imageName;
    	$desiredWidth = 200;
		$this->makeThumbImage($src,$uploadDirPath,$desiredWidth);
		$realPath = Mage::getBaseUrl("media").'catalog/advsub/'.$imageName;
		$this->prodcutImg = $realPath;
   
    	
   	$this->productDescr = substr($product->getDescription() , 0 , 420);
    	
		if(strlen($this->productDescr) > 420)
		{
			$this->productDescr .= '...';
		}
	
		$emailTemplateVariables = array();
		$emailTemplateVariables['productName'] = $this->productName;
		$emailTemplateVariables['productUrl']  = $this->productUrl;
		$emailTemplateVariables['productImg' ] = $this->prodcutImg;
		$emailTemplateVariables['storeName']   = $this->storeName;
		$emailTemplateVariables['siteLink']    = $this->siteLink;
		$emailTemplateVariables['productDesc'] = $this->productDescr;
		
	
		$newCatIds = array();
		foreach($catIdArray as $k => $value)
		{
			$newCatIds[] = $value;
		}
		
    	 if($quantity && $is_in_stock)
    	 {
     	     	 	  
    	 	    for($i = 0 ; $i < count($newCatIds) ; $i++ )
    	 	    {  
    	 	     $catId = $newCatIds[$i];
    	 	  
        	  	  	 $mailFunCallOrNot = $this->isProductInNotifiyCategori($catId);  //find this product in notify list or not
         	  	 	 if(count($mailFunCallOrNot))
			    	 {    
			    	 	  $sendingMailObj = new Apptha_Advsubscribe_Model_Sendingmails();
			    	 	    	 	  
			    	      $sendingMailObj->newProductAddedMailSending($mailFunCallOrNot , $emailTemplateVariables);
			    	 }
    	 	    }	 
	  	 }
    	 else{
    	   		return false;  //product is out of stock
    	 }
	}
	private function isProductInNotifiyCategori($catId) //find is this category is in notify list or not
	{
			  $toMailIds = '';		    
		      $query =  "
    		    		SELECT DISTINCT email_id
						FROM $this->advNotifyTableName
						WHERE FIND_IN_SET($catId , 
						    (SELECT GROUP_CONCAT(`categori_id`)
						     FROM $this->advNotifyTableName
						     WHERE FIND_IN_SET($catId,categori_id) )
						
						) AND `follower` = 1    AND `status` ='Active'
    		
    					";
  				 $toMailIds =  Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
  				 return $toMailIds; 				 
				
			
	
	}
	private function makeThumbImage($src,$uploadDirPath,$desiredWidth)  //for create imageThumbnile 
	{
		  /* read the source image */
		  $src = str_replace('https', 'http', $src);  //it is for facebook imgs it have https so we chage to http
		
		  $sourceImage = imagecreatefromjpeg($src);
		  $width = imagesx($sourceImage);
		  $height = imagesy($sourceImage);
		  
		  /* find the "desired height" of this thumbnail, relative to the desired width  */
		  $desired_height = $desiredWidth; //floor($height*($desiredWidth/$width));
		  
		  /* create a new, "virtual" image */
		  $virtualImage = imagecreatetruecolor($desiredWidth,$desired_height);
		  
		  /* copy source image at a resized size */
		  imagecopyresized($virtualImage,$sourceImage,0,0,0,0,$desiredWidth,$desired_height,$width,$height);
		  
		  /* create the physical thumbnail image to its destination */
		  imagejpeg($virtualImage,$uploadDirPath);
		
	} 
	

}