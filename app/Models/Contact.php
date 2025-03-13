<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    public $table = "contacts";
    protected $fillable = ["email", "first_name", "last_name", "message"];
    public $timestamps = false;
}
