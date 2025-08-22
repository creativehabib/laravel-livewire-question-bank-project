<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\JobStatus;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'company_name',
        'summary',
        'description',
        'deadline',
        'posted_at',
        'status',
        'featured',
        'cover_image',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'deadline' => 'date',
        'posted_at' => 'datetime',
        'featured' => 'boolean',
        'status' => JobStatus::class,
    ];
}
