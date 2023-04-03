<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;



class Log extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates  = [ 'created_at' , 'updated_at'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeProcessID($q, $process_id)
    {
        if (!is_null($process_id))
            return $q->where('process_id', $process_id);
    }

    public function scopeProcessType($q, $process_type)
    {
        if (!is_null($process_type))
            return $q->where('process_type', 'LIKE', '%' . $process_type . '%');
    }

    public function scopeCreatedLog($q, $created_log)
    {
        if (!is_null($created_log))
        {
            $created_log = Carbon::parse($created_log)->format("Y-m-d H:i:s");
            return $q->where('created_at', $created_log);
        }

    }
    public function scopeUpdatedLog($q, $updated_log)
    {
        if (!is_null($updated_log))
        {
            $updated_log = Carbon::parse($updated_log)->format("Y-m-d H:i:s");
            return $q->where('updated_at', $updated_log);
        }

    }
    public function scopeUser($q, $user_id)
    {
        if (!is_null($user_id))
            return $q->where('user_id', $user_id);
    }
}
