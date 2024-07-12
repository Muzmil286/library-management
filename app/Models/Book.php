<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{

    use HasFactory;
    protected  $fillable  = ['child_id', 'parent_id'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class);
    }
    public function versions()
    {
        return $this->hasMany(Book::class, 'parent_id');
    }
    public function orignal()
    {
        return $this->belongsTo(Book::class, 'parent_id');
    }
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
