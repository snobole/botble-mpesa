<?php


namespace Snobole\Mpesa;

class TokenGenerator extends Validator
{
    protected $default_endpoints = [
        'sandbox' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
        'live' => 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
    ];

    /**
     * @param string $env
     * @return mixed
     * @throws \ErrorException
     */
    public function generateToken(array $config) {

        $this->validateEndpoints($config['env']);
        $consumer_key = $config['consumer_key'];
        $consumer_secret = $config['consumer_secret'];

        if(!$consumer_key){
            throw new \ErrorException('Consumer key missing');
        }

        if(!$consumer_secret){
            throw new \ErrorException('Consumer secret missing');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->endpoint);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;
    }

}
