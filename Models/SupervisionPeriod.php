<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionPeriod extends Model
{
//Stałe opisujące dostępne kolumny:
public const FIELD_ID_SUPERVISION = 'id_supervision';
public const FIELD_ID_EMPLOYEE= 'id_employee';
public const FIELD_ID_FOODTRUCK= 'id_foodtruck';
public const FIELD_START_DATE='start_date';
public const FIELD_END_DATE='end_date';

//Nazwa tabeli powiązanej z modułem
protected $table = 'supervisionperiod';
//Klucz główny
protected $primaryKey = self::FIELD_ID_SUPERVISION;
//Pola, które mogą być wypełniane masowo
protected $fillable = [
self::FIELD_ID_EMPLOYEE,
self::FIELD_ID_FOODTRUCK,
self::FIELD_START_DATE,
self::FIELD_END_DATE,
];
public function supervisionPeriod()
 {
return $this->hasMany(SupervisionPeriod::class);
 }
}
