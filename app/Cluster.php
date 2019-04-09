<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $connection = 'mysql';
    protected $table = 'cluster';
}
