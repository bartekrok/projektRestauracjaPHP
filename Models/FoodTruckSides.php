<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTruckSides extends Model
{
          // jeśli nie prowadzimy informacji timestamps
// to należy zadeklarować to w modelu
public $timestamps = false;
//Stałe opisujące dostępne kolumny:
public const FIELD_ID = 'id';
public const FIELD_ID_FOODTRUCK = 'id_foodtruck';
public const FIELD_ID_SIDES = 'id_sides';
//Nazwa tabeli powiązanej z modułem
protected $table = 'foodtrucksides';
//Klucz główny
protected $primaryKey = self::FIELD_ID;
//Pola, które mogą być wypełniane masowo
protected $fillable = [
self::FIELD_ID_SIDES,
self::FIELD_ID_FOODTRUCK,
];
public function foodTruck()
 {
return $this->belongsTo(FoodTruck::class, self::FIELD_ID_FOODTRUCK);
 }
 public function sides()
 {
return $this->belongsTo(Beverage::class, self::FIELD_ID_SIDES);
 }
}
