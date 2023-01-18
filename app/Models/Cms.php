<?php
/*
    * Class name    : Cms
    * Purpose       : Table declaration
    * Author        :
    * Created Date  : 17/01/2023
    * Modified date :
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    use HasFactory;

    public $timestamps    = false;

    protected $guarded = ['id'];    // The field name inside the array is not mass-assignable
}
