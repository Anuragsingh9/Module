<?php

namespace Modules\Newsletter\Http\Controllers;

use App\AccountSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Newsletter\Entities\A;
use Modules\Newsletter\Entities\News;
use Modules\Newsletter\Entities\NewsReview;
use Modules\Newsletter\Http\Requests\ReviewAddRequest;
use Modules\Newsletter\Http\Requests\ReviewDescriptionRequest;
use Modules\Newsletter\Http\Requests\ReviewSendRequest;
use Modules\Newsletter\Services\ReviewService;
use Modules\Newsletter\Transformers\ReviewResource;

class ReviewController extends Controller {
    protected $service;
    
    public function __construct() {
        $this->service = ReviewService::getInstance();
    }
    
    public function store(ReviewAddRequest $request) {
        try {
            DB::beginTransaction();

            $param =
                ['review_reaction' => $request->review_reaction,
                 'is_visible'      => 1, //  as requirement says send when click on send button
                 'reviewed_by'     => 1,
                 'reviewable_id'   => $request->news_id,
                 'reviewable_type' => News::class,
                ];
            $review = $this->service->create($param, $request->news_id);
            DB::commit();
            return (new ReviewResource($review))->additional(['status' => TRUE]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error','error' => $e->getMessage()], 500);
        }
    }

    public function newsReview($news){

        try {
            $news = News::with('reviews')->find($news);
            return $news;
        } catch (\Exception $e) {
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error','error' => $e->getMessage()], 500);
        }
    }
    
    public function addDescription(ReviewDescriptionRequest $request) {
        try {
            DB::connection('tenant')->beginTransaction();
            
            $param = ['review_text' => $request->description,];
            $review = $this->service->update($param, $request->newsId);
            
            DB::connection('tenant')->commit();
            return (new ReviewResource($review))->additional(['status' => TRUE]);
        } catch (\Exception $e) {
            DB::connection('tenant')->rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error'], 500);
        }
    }
    
    public function send(ReviewSendRequest $request) {
        try {
            DB::connection('tenant')->beginTransaction();
            $param = ['is_visible' => 1];
            $review = $this->service->update($param, $request->news_id);
            DB::connection('tenant')->commit();
            return (new ReviewResource($review))->additional(['status' => TRUE]);
        } catch (\Exception $e) {
            DB::connection('tenant')->rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error'], 200);
        }
    }
    
    public function getReviews(Request $request) {
        try {
            $news = News::with('reviews')->find($request->news_id);
            if ($news) {
                return ReviewResource::collection($news->reviews)->additional(['status' => TRUE]);
            }
            return response()->json(['status' => TRUE, 'data' => NULL], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error'], 500);
        }
    }
    
    public function getReviewsCount(Request $request) {
        try {
//            $news=News::get(['id']);
//            return $news;

//            dd("ok");
            $names = ['bad', 'good', 'excellent']; // so $result[$name[$query->review_reaction]] become $result['excellent'] or $result['good']
            $result = ['excellent' => 3, 'good' => 2, 'bad' => 1];
            $newss = News::with('reviewsCount')->find($request->news_id);
//            return $newss;
            if ($newss)
                $newss->reviewsCount->map(function ($var) use (&$result, $names) {
                    if (isset($names[$var->review_reaction])) {
                        $result[$names[$var->review_reaction]] = $var->reactions;
                    }
                });
//            $nr=array_merge($news,$newss);
//            return $nr;
            return response()->json(['status' => TRUE, 'data' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error','error' => $e->getMessage()], 500);
        }
    }
    
    public function getUserReview(Request $request) {
        try {
            $review = NewsReview::where([
                'reviewable_id'   => $request->news_id,
                'reviewable_type' => News::class,
                'reviewed_by'     => Auth::user()->id
            ]);
            return response()->json(['status' => TRUE, 'data' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error'], 500);
        }
    }
}
