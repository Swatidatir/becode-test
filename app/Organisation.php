<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Class Organisation
 *
 * @property int         id
 * @property string      name
 * @property int         owner_user_id
 * @property Carbon      trial_end
 * @property bool        subscribed
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property Carbon|null deleted_at
 *
 * @package App
 */
class Organisation extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name','owner_user_id','trial_end'];
    
    /**
     * @var array
     */
    protected $dates = [
        'deleted_at','trail_end'
    ];
    protected $casts = [
        'trial_end' => 'datetime',
    ];
    public function __construct($attributes = array())
    {
        $this->owner_user_id = Auth::user()->id;
        $this->trial_end = Carbon::now()->addDay('30')->toDateTimeString();
        $this->subscribed=1;
        parent::__construct($attributes);    
    }
    
    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'owner_user_id','id');
    }

    // public function setTrialEndAttribute($value)
    // {
    //     $this->attributes['trail_end'] = Carbon::now()->addDay('30')->toDateTimeString();
    // }
    // public function setOwnerUserIdAttribute($value)
    // {
    //     $this->attributes['owner_user_id'] = Auth::user()->id;;
    // }
}
