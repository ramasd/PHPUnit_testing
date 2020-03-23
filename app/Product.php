<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\CurrencyService;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'photo'];

    // ACCESSORS

    public function getPriceEurAttribute()
    {
        return (new CurrencyService())->convert($this->price, 'usd', 'eur');
    }
}
