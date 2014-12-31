<?php
	//scraper for otterbox to get the product information by implement searchby url interface
	class Scraper_otterboxcom Implements ISearchByURL
	{        
		public $carturl="https://www.otterbox.com/on/demandware.store/Sites-otterbox_us-Site/default/Cart-MiniAddProduct
		";
		public $productInformation=array('name'=>'','price'=>'','sku'=>'','shipping price'=>'','discount shipping'=>'');
		public function scrapeSpecificUrl($url){
				$this->fetchProuctInformation($url);
				return $this->productInformation;	
		}
		public function fetchProuctInformation($url="")
		{
			if(empty($url))
			{
				return false;
			}
			else
			{
				$page=file_get_contents($url);
				$result = phpQuery::newDocumentHTML($page);
				$this->productInformation['name']=$result->find(".productname")->text();
				$this->productInformation['price']=trim($result->find(".salesprice")->eq(0)->text());
				$this->productInformation['sku']=trim($result->find(".productID")->find(".bold")->text());
				$poststring="Quantity=1&masterPid=".$this->productInformation['sku']."&pid=".$this->productInformation['sku'];
				@unlink('cookie.txt');
				$ch=curl_init();
				curl_setopt($ch,CURLOPT_URL,'https://316105029.log.optimizely.com/event?a=316105029&d=316105029&y=false&src=js&x1274760017=1274650364&x1214330058=1218300024&s315760698=referral&s315760699=ff&s316122020=false&s315470813=none&n=pdp_-_add_to_cart_btn&u=oeu1419681485454r0.7824496926286224&wxhr=true&t='.time().'&f=1274760017,1214330058,2135000230&g=787725634');
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				$result=curl_exec($ch);
				curl_setopt($ch,CURLOPT_URL,$this->carturl);
				curl_setopt($ch,CURLOPT_POST,true);
				
				curl_setopt($ch,CURLOPT_POSTFIELDS,$poststring);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt ($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
				$result=curl_exec($ch);
				curl_setopt($ch,CURLOPT_URL,'https://www.otterbox.com/on/demandware.store/Sites-otterbox_us-Site/default/Cart-Show');
				$result=curl_exec($ch);
				curl_setopt($ch,CURLOPT_URL,'https://www.otterbox.com/on/demandware.store/Sites-otterbox_us-Site/default/Cart-Show?postalCode=10422&countryCode=US');
				$page=curl_exec($ch);
				$result = phpQuery::newDocumentHTML($page);
				curl_close($ch);
				$this->productInformation['shipping price']=trim($result->find(".cartordertotals")->find("span")->eq(1)->text());
				$this->productInformation['discount shipping']=trim($result->find(".cartordertotals")->find("span")->eq(3)->text());
				
			}
		}
	}
	
?>
