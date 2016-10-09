<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    public function selectQuery($sql_stmt,$params) {
        //return DB::select(DB::raw($sql_stmt),$params);
    
        return DB::select($sql_stmt,$params);
    }

    public function sqlStatement($sql_stmt) {
        DB::statement($sql_stmt);
    }
}
