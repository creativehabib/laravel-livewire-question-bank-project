<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'company_id',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(JobCompany::class, 'company_id');
    }
}
