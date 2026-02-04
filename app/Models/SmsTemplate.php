<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'message',
        'description',
        'variables',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Replace variables in message
     */
    public function render(array $data = []): string
    {
        $message = $this->message;
        
        foreach ($data as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        
        return $message;
    }
}






