<?php

namespace Dbws\Braintree\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransactionResponses extends Model
{
    
    protected $fillable = ['booking_id', 'response','booking_invoices_id','transaction_id','token' ];

	public $timestamps = false;
	public static function boot()
	{
		parent::boot();
		static::creating(function ($model)
		{
			$model->created_at = time();
			
		});
		
	}
}
