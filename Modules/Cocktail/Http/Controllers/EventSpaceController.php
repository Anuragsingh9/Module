<?php

namespace Modules\Cocktail\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Exception;
use Modules\Cocktail\Http\Requests\AddUserSpaceRequest;
use Modules\Cocktail\Http\Requests\EventSpaceRequest;
use Modules\Cocktail\Http\Requests\EventSpaceUpdateRequest;
use Modules\Cocktail\Http\Requests\EventSpaceUserRequest;
use Modules\Cocktail\Services\DataService;
use Modules\Cocktail\Services\EventSpaceService;
use Modules\Cocktail\Transformers\EventSpaceResource;
use Modules\Cocktail\Transformers\EventSpaceUserResource;
use Nwidart\Modules\Module;

class EventSpaceController extends Controller {
    
    protected $service;
    
    public function __construct() {
        $this->service = EventSpaceService::getInstance();;
    }
    
    
    /**
     * @param EventSpaceRequest $request
     * @return JsonResponse|EventSpaceResource
     */
    public function store(EventSpaceRequest $request) {
        DB::connection('tenant')->beginTransaction();
        try {
            $dataService = DataService::getInstance();
            $param = $dataService->prepareSpaceCreateParam($request);
            $event = $this->service->create($param);
            DB::connection('tenant')->commit();
            return (new EventSpaceResource($event))->additional(['status' => TRUE]);
        } catch (Exception $e) {
            DB::connection('tenant')->rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error ', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function update(EventSpaceUpdateRequest $request) {
        try {
            DB::connection('tenant')->beginTransaction();
            $dataService = DataService::getInstance();
            $param = $dataService->prepareSpaceUpdateParam($request);
            
            $update = $this->service->update($param, $request->space_uuid);
            DB::connection('tenant')->commit();
            return new EventSpaceResource($update);
        } catch (Exception $e) {
            DB::connection('tenant')->rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error ', 'error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * @param $event_uuid
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getEventSpaces($event_uuid) {
        try {
            $event = $this->service->getEventSpaces($event_uuid);
            return EventSpaceResource::collection($event)->additional(['status' => TRUE]);
        } catch (Exception $e) {
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error '], 500);
        }
    }
    
    public function addUserToSpace(AddUserSpaceRequest $request) {
        try {
            DB::connection('tenant')->beginTransaction();
            $eventSpaceUser = $this->service->addUserToSpace($request->user_id, $request->space_uuid);
            DB::connection('tenant')->commit();
            return (new EventSpaceUserResource($eventSpaceUser))->additional(['status' => TRUE]);
        } catch (Exception $e) {
            DB::connection('tenant')->rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error ', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function removeUserFromSpace(AddUserSpaceRequest $request) {
        try {
            DB::connection('tenant')->beginTransaction();
            $eventSpaceUsers = $this->service->removeUserFromSpace($request->user_id, $request->space_uuid);
            DB::connection('tenant')->commit();
            return (EventSpaceUserResource::collection($eventSpaceUsers))->additional(['status' => TRUE]);
        } catch (Exception $e) {
            DB::connection('tenant')->rollback();
            return response()->json(['status' => FALSE, 'msg' => 'Internal Server Error ', 'error' => $e->getMessage()], 500);
        }
    }
}
