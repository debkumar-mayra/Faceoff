<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use HasSlug;


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $search) {
            $query->where('name','like', "%" . trim($search) . "%");
        });
    
        $query->when($filters['category'] ?? null, function ($query, $search) {
            $query->where('parent_id',$search);
        });

        $query->when(!isset($filters['category']), function ($query, $search) {
            $query->where('parent_id', 0);
        });
      

        $query->when(isset($filters['status']) ?? null, function ($query, $search) use($filters){
            $query->where('status',$filters['status']);
        });
    }

    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use($filters){
            $query->orderBy($search,$filters['shortBy']);
        });
    }

    
}
