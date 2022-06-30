<?php


namespace Snobole\Mpesa\Transaction;


use Snobole\Mpesa\Transaction\Contracts\WithPayerName;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model implements WithPayerName
{
    public function getPayerNameAttribute()
    {
        return 'Unspecified';
    }
}
