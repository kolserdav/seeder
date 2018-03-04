<?php

namespace Avir\Seed;


class Seeder extends Seed
{
    protected $env;
    protected $reqUri;
    protected $hostName;
    protected $userAgent;
    protected $userIp;
    protected $dateForm;
    protected $date;
    protected $browsName;
    protected $isOldAndroid;
    protected $androidArr;

    public function __construct(){

        $this->hostName = $_SERVER['HTTP_HOST'];
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->userIp = $_SERVER['REMOTE_ADDR'];
        $this->dateForm = date('d.m.Y h:m:s');
        $this->date = $this->dateForm;
        $this->browsName = null;
    }
    public function test(){
        echo 'Hello';
    }

    public function seeder(callable $routeGet){
        $this->getInfo();
        $browser = $this->getBrowser();
        $bots = '%.*bot%';
        $this->androidArr = $this->getAndroidArr();
        $android = implode($this->androidArr,',');
        $this->getAndroid();
        if (is_array($browser)) {
            $strBrowser = implode($browser);
        }
        else {
            $strBrowser = 'parser';
        }
        $comment =  preg_match($bots,$browser['comment']);
        if ($comment){
            $this->browsName = 'BOT'.$browser['comment'];
        }
        if ($this->browsName !== null){
            $browserName = $this->browsName;
        }
        if (preg_match('%Android%',$browser['comment']) || preg_match('%Android%',$browser['platform'])){
            $strBrowser = $android;
            $browserName = $browser['comment'];
        }
        else {
            if (empty($browser)){
                $browserName = 'empty';
            }
            else {
                $browserName = implode([$browser['platform'], " ", $browser['comment']]);
            }
        }
        if ($this->hostName != parent::MY_HOST) {
            if ($this->userIp != parent::MY_IP && $this->requestUri != "/favicon.ico") {
                $method = $this->method;
                $file = tempnam(__DIR__.'/../private_logs', "$method '' $this->date '' $browserName '' $this->userIp ''");
                if ($file) {
                    $handler = fopen("$file", "r+");
                    $reqUri = $this->requestUri;
                    try {
                        $referer = $_SERVER['HTTP_REFERER'];
                    }
                    catch (Exception $e){
                        $referer = 'no referer';
                    }
                    fwrite($handler, "uri : $reqUri  info : $strBrowser referef : $referer ");
                    echo 'В разработке';
                }
            }
            else {
                $routeGet();
            }
        }
        else {
            $routeGet();
        }
        if (empty($strBrowser)){
            $strBrowser = "Parser";
        }
    }

    public function getAndroid(){

        $androidArr = $this->getAndroidArr();
        if(preg_match('%Google%', $androidArr['browsMaker']) && preg_match('%4.0%',$this->androidArr['version'])){
            return  1;
        }
        if(preg_match('%WebView%', $androidArr['andrVers'])){
            return 2;
        }
        if(preg_match('%Chrome 46.0%',$androidArr['andrVers'])){
            return 2;
        }
        else {
            return 0;
        }
    }

    public function getAndroidArr(){
        $browser = $this->getBrowser();
        return  [
            'andrVers' => " Android Version:: ".$browser['comment'],
            'devType' => " Device Type:: ".$browser['device_type'],
            'pointMeth' => " Point Method:: ".$browser['device_pointing_method'],
            'browsMaker' => " Browser Maker:: ".$browser['browser_maker'],
            'version' => " Version:: ".$browser['version']

        ];
    }

    public function getBrowser(){
        return get_browser(null, true);
    }

    public function getHostName(){
        return $this->hostName;
    }
}