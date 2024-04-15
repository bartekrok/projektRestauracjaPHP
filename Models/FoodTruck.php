<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTruck extends Model
{
       // jeśli nie prowadzimy informacji timestamps
// to należy zadeklarować to w modelu
public $timestamps = false;
//Stałe opisujące dostępne kolumny:
public const FIELD_ID = 'id_foodtruck';
public const FIELD_CITY= 'city';
public const FIELD_POSTAL_CODE= 'postal_code';
public const FIELD_NAME='name';
public const FIELD_ID_SUPERVISOR = 'id_supervisor';
//Nazwa tabeli powiązanej z modułem
protected $table = 'foodtruck';
//Klucz główny
protected $primaryKey = 'id_foodtruck';
//Pola, które mogą być wypełniane masowo
protected $fillable = [
self::FIELD_CITY,
self::FIELD_POSTAL_CODE,
self::FIELD_NAME,
self::FIELD_ID_SUPERVISOR
];
public function foodTruckBurger()
 {
return $this->hasMany(FoodTruckBurger::class);
 }
 public function foodTruckBeverage()
 {
return $this->hasMany(FoodTruckBeverage::class);
 }
 public function foodTruckSides()
 {
return $this->hasMany(FoodTruckSides::class);
 }
}
