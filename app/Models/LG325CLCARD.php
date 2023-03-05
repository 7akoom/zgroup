<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LG325CLCARD extends Model
{
    use HasFactory;

    protected $table = 'LG_325_CLCARD';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'LOGICALREF';

    public function __construct()
    {
        parent::__construct(['325']);
    }
}
