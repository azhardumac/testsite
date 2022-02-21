<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{

    public function user(){
        return $this->hasOne(User::class, 'id', 'auction_owner');
    }

    public function buyer(){
        return $this->hasOne(User::class, 'id', 'auction_buyer');
    }


}
