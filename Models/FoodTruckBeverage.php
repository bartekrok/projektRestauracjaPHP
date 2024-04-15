<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTruckBeverage extends Model
{
       // jeśli nie prowadzimy informacji timestamps
// to należy zadeklarować to w modelu
public $timestamps = false;
//Stałe opisujące dostępne kolumny:
public const FIELD_ID = 'id';
public const FIELD_ID_FOODTRUCK = 'id_foodtruck';
public const FIELD_ID_BEVERAGE = 'id_beverage';
//Nazwa tabeli powiązanej z modułem
protected $table = 'foodtruckbeverage';
//Klucz główny
protected $primaryKey = self::FIELD_ID;
//Pola, które mogą być wypełniane masowo
protected $fillable = [
self::FIELD_ID_BEVERAGE,
self::FIELD_ID_FOODTRUCK,
];
public function foodTruck()
 {
return $this->belongsTo(FoodTruck::class, self::FIELD_ID_FOODTRUCK);
 }
 public function beverage()
 {
return $this->belongsTo(Beverage::class, self::FIELD_ID_BEVERAGE);
 }
}
