<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    use HasFactory;


    function user()  {
        return $this->belongsTo(User::class);
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $search) {
            $query->where('title','like', "%" . trim($search) . "%");
        });

        $query->when($filters['user'] ?? null, function ($query, $search) {
            // dd('ff');
            $query->whereHas('user',function($que) use($search){
                $que->WhereRaw("concat(first_name,' ', last_name) like '%" . trim($search) . "%' ");
            });
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
