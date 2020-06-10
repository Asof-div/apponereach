<?php

namespace App\Http\Controllers\Api\Call;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Call\CallLogCreator;

use App\Models\User;
use App\Models\Call;
use App\Http\Resources\CallResource;

class CallController extends Controller
{



    public function deductCallCredits(Request $request, CallCreditsDeducer $deducer)
    {
        $this->validate($request, [
            'caller' => 'required|exists:users,phonenumber',
            'callee' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

    	$deducer->deduct($request->caller, $request->callee, $request->start_time, $request->end_time);

    	return response()->json('ok', 200);
    }


    public function getCallLogs(Request $request)
    {
        $calls = (new Call)->newQuery();
        $calls->where('tenant_id', $request->user()->tenant_id);

        if ($request->last_call_time) {
            $calls->where('start_time', '>', $request->last_call_time);
        }

        if ($request->limit) {
            $calls->take($request->limit);
        }

        $calls = $calls->get();

        $last_call_time = $calls->count() > 0 ? $calls->last()->start_time->format('Y-m-d H:i:s') : null;

        // return response()->json($calls, 200);
        // return CallResource::collection($calls)->additional(['meta' => [
                // 'last_call_time' => $last_call_time,
            // ]]);
        return response()->json(CallResource::collection($calls)->additional(['meta' => [
                'last_call_time' => $last_call_time,
            ]]), 200);
    }

    public function storeCallLog(Request $request, CallLogCreator $callLog) {
        $this->validate($request, [
            'uuid' => 'required',
            'caller' => 'required',
            'callee' => 'required',
            'direction' => 'required',
            'duration' => 'required',
            'source' => 'required',
            'direction' => 'required',
            'tenant_id' => 'required|exists:tenants,id',
            'status' => 'required',
            'start_time' => 'required|date',
            'call_rate' => 'required',
            'end_time' => 'required|date',
        ]);

        $details = $request->all();

        $callLog->create($details);

        return response()->json('ok', 200);
    }

    public function logs(){
        return response()->json(['data' => User::get()], 200);
    }
}
