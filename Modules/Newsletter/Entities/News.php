<?php

namespace Modules\Newsletter\Entities;

use Brexis\LaravelWorkflow\Traits\WorkflowTrait;
//use Hyn\Tenancy\Abstracts\TenantModel as TenancyModel;
use Illuminate\Database\Eloquent\Model;

class News extends Model {
    use WorkflowTrait;

    protected $table = 'news_info';
    protected $fillable = [
        'title', 'header', 'description', 'status', 'created_by', 'media_url', 'media_thumbnail',
    ];

    public function reviews() {
        return $this->morphMany(NewsReview::class, 'reviewable');
    }

    public function reviewsCount() {
        return $this->morphMany(NewsReview::class, 'reviewable')
            ->selectRaw('review_reaction,COUNT(review_reaction) as reactions,reviewable_id')
            ->groupBy('review_reaction');
    }

}
