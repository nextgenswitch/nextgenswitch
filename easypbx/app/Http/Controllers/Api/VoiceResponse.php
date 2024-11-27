<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Http;
class VoiceResponse extends \SimpleXMLElement
{
    
   // private $verb;
    function __construct($tag = null)
    {
        //info("tag is " . $tag);
        if(empty($tag))
            parent::__construct("<response></response>");
        else
            parent::__construct($tag);
        
    }

    public function xml(){
        return $this->asXML();
    }

    /* public function __toString(): string {
        $dom_sxe = dom_import_simplexml($this);
        $dom = new \DOMDocument('1.0');
        $dom_sxe = $dom->importNode($dom_sxe, true);
       // $dom_sxe = $dom->appendChild($dom_sxe);

        return $dom->saveXML();
    } */

 
    /**
     * Add SimpleXMLElement code into a SimpleXMLElement
     *
     * @param MySimpleXMLElement $append
     */
    public function appendXML($append)
    {
        
        if ($append) {
            
            if (strlen(trim((string)$append)) == 0) {
               
                $xml = $this->addChild($append->getName());
            } else {
                $xml = $this->addChild($append->getName(), (string)$append);
            }

            foreach ($append->children() as $child) {
                $xml->appendXML($child);
            }

            foreach ($append->attributes() as $n => $v) {
                $xml->addAttribute($n, $v);
            }
        }else{
            $xml = $this->addChild($append->getName());
        }
        return $xml;
    }

    public function sms($text,$options = []){
        $this->appendXML(self::genXmlElelment('sms',$text,$options));
    }

    public function say($text,$options = []){       
       // $this->verb = 'say';
        $this->appendXML(self::genXmlElelment('say',$text,$options));
    }

    public function play($text,$options = []){
      //  $this->verb = 'play';
        $this->appendXML(self::genXmlElelment('play',$text,$options));
    }

    public function gather($options = []){
       // $this->verb = 'gather';
        $tag = self::genXmlElelment('gather',null,$options);
        return $this->appendXML($tag);        
    }
    public function record($options = []){        
         $tag = self::genXmlElelment('record',null,$options);
         $this->appendXML($tag);        
    }

    public function pause($len,$options = []){
        // $this->verb = 'gather';
         $tag = self::genXmlElelment('pause',$len,$options);
         $this->appendXML($tag);        
    }


    public function hangup(){
        // $this->verb = 'gather';
         $tag = self::genXmlElelment('hangup',null,[]);
         $this->appendXML($tag);        
    }
    
    public function leave(){
        // $this->verb = 'gather';
         $tag = self::genXmlElelment('leave',null,[]);
         $this->appendXML($tag);        
    }


    public function dial($text,$options = []){
       // $this->verb = 'dial';
        return $this->appendXML(self::genXmlElelment('dial',$text,$options));
    }

    public function bridge($text,$options = []){
       // $this->verb = 'dial';
        $this->appendXML(self::genXmlElelment('bridge',$text,$options));
    }
    
    public function queue($text,$options = []){
        $this->appendXML(self::genXmlElelment('queue',$text,$options));
    }

    public function enqueue($text,$options = []){
        $this->appendXML(self::genXmlElelment('enqueue',$text,$options));
    }

    public function redirect($text = null,$options = []){
        //dd($text);
        $this->appendXML(self::genXmlElelment('redirect',$text,$options));
    }
    
    

    public static function genXmlElelment($tag,$val,$attrs = []){
        $xml = new VoiceResponse("<$tag>$val</$tag>");
        foreach($attrs as $k => $v){
            if(is_bool($v)) $v = ($v)?'true':'false';
            $xml->addAttribute($k,$v);
        }
        
        return $xml;            
    }

    public static function getElementFromXml($xml = null,$url = null,$params = []){
        if(!$xml && $url){
            //info("voice resp param");
            //info($params);
            $response = Http::post($url,$params);
            if($response->failed()) return;
            $xml = $response->body();
        }
        
        if(!$xml && !$url) return false;
        $xml = trim($xml);
        $use_errors = libxml_use_internal_errors(true);
        $xmlelem = simplexml_load_string($xml,"SimpleXMLElement", LIBXML_NOCDATA); 
        libxml_clear_errors();
        libxml_use_internal_errors($use_errors);
        if($xmlelem === false){ info("error in xml parsing "); info($xml); return false; }
        
       
        if($url && !isset($xmlelem['url']))
            $xmlelem->addAttribute("url",$url);
        return   $xmlelem;  
    }
}