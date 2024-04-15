<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
        // jeśli nie prowadzimy informacji timestamps
// to należy zadeklarować to w modelu
public $timestamps = false;
//Stałe opisujące dostępne kolumny:
public const FIELD_ID = 'id_employee';
public const FIELD_NAME= 'name';
public const FIELD_LAST_NAME= 'last_name';
//Nazwa tabeli powiązanej z modułem
protected $table = 'employee';
//Klucz główny
protected $primaryKey = self::FIELD_ID;
//Pola, które mogą być wypełniane masowo
protected $fillable = [
self::FIELD_NAME,
self::FIELD_LAST_NAME,
];
public function employee()
 {
return $this->hasMany(Employee::class);
 }
}
