<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }

    public function products()
    {
        return $this->hasMany(QuoteProduct::class, 'quote_id', 'id');
    }
    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

}
