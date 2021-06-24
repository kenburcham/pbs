<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Values extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_history_id', 'currency_code', 'currency_name', 'amount',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'currency_date' => 'date',
    ];
	/**
	 * A values belongs to one history.
	 */
	public function history(){
		return $this->belongsTo( 'App\Models\History', 'id', 'history_id' );
	}
}
