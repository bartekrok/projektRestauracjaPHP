<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beverage extends Model
{
    // jeśli nie prowadzimy informacji timestamps
// to należy zadeklarować to w modelu
public $timestamps = false;
//Stałe opisujące dostępne kolumny:
public const FIELD_ID = 'id_beverage';
public const FIELD_DESCRIPTION= 'description';
public const FIELD_PRICE= 'price';
public const FIELD_PHOTO='photo';
//Nazwa tabeli powiązanej z modułem
protected $table = 'beverage';
//Klucz główny
protected $primaryKey = self::FIELD_ID;
//Pola, które mogą być wypełniane masowo
protected $fillable = [
self::FIELD_DESCRIPTION,
self::FIELD_PRICE,
self::FIELD_PHOTO,
];

public function foodTruckBeverage()
 {
return $this->hasMany(FoodTruckBeverage::class);
 }

}
