<?php

namespace Modules\Newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsUpdateRequest extends FormRequest {
    /**
     * @return array
     */
    public function rules() {
        return [
            'news_id'     => [
                'required',
                Rule::exists('tenant.news_info', 'id')->whereNull('deleted_at')
            ],
            'title'       => 'required|string|max:1000', // todo change
            'header'      => 'required|string|max:1000', // todo change
            'description' => 'required|string|max:1000', // todo change
            'media_type'  => 'required|in:0,1,2', // 0 for video, 1 for system image, 2 image from adobe
            'media_url'   => 'required_if:media_type,0,2|url', // url need for video or adobe image
            'media_blob'  => 'required_if:media_type,0,1|image', // required for video thumbnail or image upload
        ];
    }
    
    /**
     * @return bool
     */
    public function authorize() {
        return TRUE;
    }
}
