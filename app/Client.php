<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;


class Client extends Authenticatable  
{
 use Notifiable;
 
 protected $fillable = [
        'name', 'email', 'location','phone_number','address','street_name','house_number','password','gps_coordinates','marketer_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
   // protected $guard = 'client';


    
 



}
