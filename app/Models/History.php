<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_date', 'currency_code', 'currency_name', 'amount', 'success', 'message',
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
	 * A history can have many values.
	 */
	public function values() {
		return $this->hasMany( 'App\Models\Values', 'history_id', 'id' );
	}
}
