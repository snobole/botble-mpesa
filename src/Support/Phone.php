<?php

namespace Snobole\Mpesa\Support;

class Phone
{
    private $phone;

    public function __construct(string $phoneNumber)
    {
        $this->phone = $phoneNumber;
    }

    /**
     * @return string
     */
    public function formatKe(){
        if ($number = $this->formatPhoneNumber($this->phone)) {
            return $number;
        }
        return $this->phone;
    }

    /**
     * @param string $phoneNumber
     * @param string $countryCode
     * @return array|false|string|string[]|null
     */
    private function formatPhoneNumber(string $phoneNumber, string $countryCode = '254') {
        $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

        if(strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-9);
            $number = substr($phoneNumber, -9, 9);
            $phoneNumber = $countryCode.$number;
        }
        else if(strlen($phoneNumber) == 10) {
            $phoneNumber = $countryCode.substr($phoneNumber, 1);
        }
        else if(strlen($phoneNumber) == 9) {
            $phoneNumber = $countryCode.$phoneNumber;
        }

        return $phoneNumber;
    }
}