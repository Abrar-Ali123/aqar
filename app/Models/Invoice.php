<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['order_id', 'customer_id', 'amount', 'vat', 'pdf_path'];
}
