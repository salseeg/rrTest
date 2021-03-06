<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 18.03.16
 * Time: 20:02
 */

namespace App\Domain;

/**
 * Class RocketRouteApi
 * @package App\Domain
 * 
 * 
 * 
 */
class RocketRouteApi
{

    /** @var  \SoapClient */
    protected $client = null;

    protected $userName = 'salseeg@gmail.com';

    protected $password = '1ef2a5022066ae4ef6387ab1977b1da8';

    /**
     * @throws \SoapFault
     */
    protected function initClient(){
        $client = new \SoapClient('https://apidev.rocketroute.com/notam/v1/service.wsdl');

        $this->client = $client;
    }

    protected function getCredentialsXML(){
        return <<< XML
            <USR>{$this->userName}</USR>
            <PASSWD>{$this->password}</PASSWD>
XML;
    }

    /**
     * @param $rawResponse
     * @throws RocketRouteException
     */
    protected function checkError($rawResponse){
        $xml = simplexml_load_string($rawResponse);
        if ($code = (int)$xml->RESULT){
            throw new RocketRouteException((string) $xml->MESSAGE, (int) $code);
        }
    }

    /**
     * @return \SoapClient
     */
    protected function getClient()
    {
        if (! $this->client){
            $this->initClient();
        }
        return $this->client;
    }

    /**
     * @param \SoapClient $client
     */
    public function setClient(\SoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    protected function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param $code
     * @return IcaoNotamCollection
     * @throws RocketRouteException
     */
    protected function getSingleNotam($code){
            $credentials = $this->getCredentialsXML();
            $requestXml = <<< XML
<?xml version="1.0" encoding="UTF-8" ?>
                <REQNOTAM>
                $credentials
                <ICAO>$code</ICAO>
                </REQNOTAM>
XML;
            $rawResponse = $this->getClient()->getNotam($requestXml);
        $this->checkError($rawResponse);

        return $this->parseNotamResponse($rawResponse);
    }

    /**
     * @param $codes
     * @return IcaoNotamCollection
     * @throws RocketRouteException
     */
    public function getNotam(IcaoCollection $codes){

        // workaround over bulk bug
        $result = new IcaoNotamCollection();
        foreach ($codes as $code){
            $result->absorb($this->getSingleNotam((string) $code));
        }
        return $result;
    }


    /**
     * @param $rawRequest
     * @return IcaoNotamCollection
     */
    protected function parseNotamResponse($rawRequest){
        $response = new IcaoNotamCollection();
        $xml = simplexml_load_string($rawRequest);

        if ($xml->NOTAMSET){
            $icao = (string) $xml->NOTAMSET['ICAO'];
//            print_r($icao);

            foreach ($xml->NOTAMSET->NOTAM as $notam){
                $response ->addNotam($icao, new Notam($notam));
            }
        }
        
        return $response;

    }



}