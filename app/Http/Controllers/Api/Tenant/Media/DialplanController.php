<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CallFlow;
use App\Traits\GetUserResourceTrait;
use Validator;
use App\Services\Response\ApiResponse;
use Illuminate\Validation\Rule;


class DialplanController extends Controller
{
    use GetUserResourceTrait;

    /**
     * @OA\Get(
     *      path="/api/app/media/dialplan",
     *      operationId="getDialplan",
     *      tags={"Media Dialplan"},
     *      summary="Get Dialplan",
     *      description="Return Dialplan List.",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function index(Request $request){

        $user = $request->user();

        $dialplans = CallFlow::company($user->tenant_id)->get();

        return response()->json(['dialplans' => $dialplans, ], 200);
    }


}
