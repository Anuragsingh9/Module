<?php

namespace Modules\Newsletter\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Newsletter\Entities\News;
use Modules\Newsletter\Entities\NewsReview;

class ReviewResource extends Resource {
    /**
     * @param Request
     * @return array
     */
    public function toArray($request) {
        return [
            'news'=>    $this->news,
            'review_id'       => $this->id,
            'review_reaction' => $this->reviewsCountByCategory,
        ];
    }
}
