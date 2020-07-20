<?php

namespace Modules\Cocktail\Services;

use App\Services\Service;
use Exception;
use Modules\Cocktail\Entities\EventSpace;

class EventSpaceService extends Service {
//    public static function getInstance()
//    {
//        static $instance = NULL;
//        if (NULL === $instance) {
//            $instance = new static();
//        }
//        return $instance;
//    }
    /**
     * @param $param
     * @return mixed
     * @throws Exception
     */
    public function create($param,$userId,$spaceUuid) {
        $event = EventSpace::create($param);
        $this->addUserToSpace(array ($userId), $spaceUuid);
        if (!$event)
            throw new Exception();  // to throw the error instead of null so proper message can be shown// to throw exception so that proper msg can be shown
        return $event;
    }
    
    /**
     * @param $param
     * @param $space_uuid
     * @return mixed
     * @throws Exception
     */
    public function update($param, $space_uuid) {
        $updated = EventSpace::find($space_uuid)->update($param);
        if (!$updated)
            throw new Exception();  // to throw the error instead of null so proper message can be shown// to throw exception so that proper msg can be shown
        return EventSpace::find($space_uuid);
    }
    
    /**
     * @param $event_uuid
     * @return mixed
     * @throws Exception
     */
    public
    function getEventSpaces($event_uuid) {
        $spaces = EventSpace::where('event_uuid', $event_uuid)->get();
        if (!$spaces)
            throw new Exception();  // to throw the error instead of null so proper message can be shown// to throw exception so that proper msg can be shown
        return $spaces;
    }
    
    public
    function addUserToSpace($userId, $spaceUuid, $request) {
        $param=[
            'user_id'=>$userId,
            'space_uuid'=>$spaceUuid,
        ];
        $addUser = EventSpace::create($param);
        foreach($request->hosts as $host){
            $param=[
                $host=[
                    $user_id ='user_id'=>$request->user_id,
                    $spaceId ='space_uuid'=>$request->space_uuid,
                    $role    ='role'=> 1,
                    $hosts ='host' => $request->hosts,
                ],
            ];
            SpaceUser::insert(
                ['user_id' => $user_id, 'space_uuid' => $spaceId,'role'=> $role,'host'=>$hosts],
            );
        }
        if (!$addUser)
            throw new Exception();  // to throw the error instead of null so proper message can be shown// to throw exception so that proper msg can be shown
        return $addUser;
    }
    
    public
    function removeUserFromSpace($userId, $spaceUuid) {
        $showEvent=EventSpace::where([['user_id',$userId], ['space_uuid',$spaceUuid]]);
        $showEvent->delete();
        if (!$showEvent)
            throw new Exception();  // to throw the error instead of null so proper message can be shown// to throw exception so that proper msg can be shown
        return $showEvent;
    }
}