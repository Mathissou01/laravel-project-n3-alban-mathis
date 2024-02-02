<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'name',
        'date',
        'heure',
        'category_id',
    ];

    public $sortable = [
        'name',
        'category_id',
        'date',
        'heure'
    ];

    protected $guarded = [
        'id',
    ];

    protected $with = [
        'category',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }
}
