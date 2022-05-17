<?php

namespace App\Parsers;

use App\Parsers\ParserContract;

class ParserCurl implements ParserContract
{
    protected $lastResult = null;

    public function getLastResult()
    {
        return $this->lastResult;
    }
  
    public function parse(string $siteUrl)
    {
        $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";
        
        $ch = curl_init($siteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );
        
        $document = \phpQuery::newDocumentHTML($content);
        
        $title = $document->find('title')->html();
        $description = $document->find('meta[name=description]')->attr('content');
        $keywords = $document->find('meta[name=keywords]')->attr('content');
        
        return $this->lastResult = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords
        ];
    }
}
