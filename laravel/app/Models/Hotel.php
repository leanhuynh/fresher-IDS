<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\City;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'city_id',
        'name_en',
        'name_jp',
        'telephone',
        'fax',
        'company_name',
        'tax_code',
        'hotel_code',
        'email',
        'address_1',
        'address_2'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }
}
