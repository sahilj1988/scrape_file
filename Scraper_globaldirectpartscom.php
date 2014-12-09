<?php
//Implement the ISearchByURL for parse the product page
class Scraper_globaldirectpartscom Implements ISearchByURL 
{
	protected $productsInformation=array();
    public function scrapeSpecificUrl($url){
		$url_obj = parse_url($url);
        if(stripos($url_obj['host'],'www.') === false)
            $url = $url_obj['scheme'] . '://www.' . $url_obj['host'] . $url_obj['path'];
		$this->extractProductInformation($url);
        return $this->productsInformation;
    }
   
    private function extractProductInformation($urlToParse,$count = 10){
	
		$page = file_get_contents($urlToParse);
		$page = phpQuery::newDocumentHTML($page);
		$productUrl="";$productTitle="";$productPrice="";$productImg="";
		for ($i = 0; $i < $count; $i++) {
            $productUrl = $page->find('a.productnamecolor')->eq($i)->attr('href');
			$productTitle = $page->find('a.productnamecolor')->eq($i)->text();
			$productPrice = $page->find('.product_productprice')->eq($i)->text();
			$productImg=  $page->find('.v65-productPhoto')->eq($i)->find('img')->attr('src');            
            if (empty($productUrl)) {
                unset($productUrl);
                break;
            }
		$this->productsInformation[]=array('title'=>$productTitle,'url'=>$productUrl,'price'=>$productPrice,'img'=>$productImg,'sellerName'=>'GlobalDirectParts');
        }
		$page->unloadDocument();  
	}}
