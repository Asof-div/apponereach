<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageCollection;
use App\Services\Response\ApiResponse;

use App\Models\Package;
use App\Models\Addon;

class PackageController extends Controller
{


    /**
     * @OA\Get(
     *      path="/api/app/packages",
     *      operationId="getPackages",
     *      tags={"Resources"},
     *      summary="Get Packages",
     *      description="Returns package list",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     */
    public function index(Request $request){
        $addons = Addon::get();

        $packages = new PackageCollection(Package::where('name', '<>', 'Free')->where('price', '<>', 0)->orderBy('price')->with(['addons'])->get() );

        return response()->json(['addons' => $addons, 'packages' => $packages ], 200);
    }

    public function show($id){
        $package = Package::find($id);

        if(!$package){

            $response = (new ApiResponse)->error(['package' => ['package does not exist']]);
            return response()->json($response, 422);
        }

        return response()->json($package, 200);
    }


}
