<?php

namespace Modules\Newsletter\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ReviewResource extends Resource {
    /**
     * @param Request
     * @return array
     */
    public function toArray($request) {
        return [
            'review_id'       => $this->id,
            'review_text'     => $this->review_text,
            'review_reaction' => $this->review_reaction,
            'is_visible'      => $this->is_visible,
            'reviewed_by'     => new UserResource($this->reviewer),
            'news'            => new NewsResource($this->reviewable),
        ];
    }
}
