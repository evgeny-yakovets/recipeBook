<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    const DEFAULT_IMG_URL = '/images/default-recipe.png';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'cuisine_type',
        'ingredients',
        'steps',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serializeRecipe()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name,
            'name' => $this->name,
            'description' => $this->description,
            'cuisine_type' => $this->cuisine_type,
            'ingredients' => json_decode($this->ingredients, true),
            'steps' => json_decode($this->steps, true),
            'image' => $this->getImageUrl(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getImageUrl()
    {
        
        if(!$this->image){
            return self::DEFAULT_IMG_URL;
        }

        return "/storage/{$this->image}";
    }

    
}
