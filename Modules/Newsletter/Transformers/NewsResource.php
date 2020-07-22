<?php

namespace Modules\Newsletter\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class NewsResource extends Resource {
    /**
     * @param Request
     * @return array
     */
    public function toArray($request) {
        return [
            'news_id'                 => $this->id,
            'title'                   => $this->title,
            'header'                  => $this->header,
            'description'             => $this->description,
            'status'                  => $this->status,
            'media_url'               => $this->media_url,
            'media_thumbnail'         => $this->media_thumbnail,
        ];
    }
}
