<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\Response\ApiResponse;

use Auth;

use File;
use Illuminate\Http\Request;

use Storage;
use Validator;

class BusinessPageController extends Controller {

	public function index(Request $request) {

		$tenant   = $request->user()->tenant;
		$response = ['tenant' => new TenantResource($tenant)];

		return response()->json($response, 200);
	}

	public function update(Request $request) {

		$user      = $request->user();
		$validator = Validator::make($request->all(), [

				// 'id_type' => 'required|max:250',
				// 'customer_category' => 'required|max:250',
				// 'customer_type' => 'required|max:250',
				'corporation_name' => 'required|max:250',
				// 'corporation_type' => 'required|max:250',
				// 'corporation_short_name' => 'required|unique:tenants,domain|max:250',
				// 'industry' => 'required|max:250',
				// 'language' => 'required|max:250',
				// 'customer_sub_category' => 'required|max:250',
				// 'customer_grade' => 'required|max:250',

			]);

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse())->error($validator->errors()), 422);
		}

		$tenant       = $user->tenant;
		$tenant->name = $request->corporation_name;
		$tenant->info->update([
				'corporation_name' => $request->corporation_name,
				'url_link'         => $request->website,
				'state'            => $request->state,
				'address'          => $request->address,
				'nationality'      => $request->country,

			]);
		$tenant->update();

		$response = ['tenant' => new TenantResource($tenant)];

		return response()->json($response, 200);

	}

	function logo(Request $request) {
		$user      = $request->user();
		$validator = Validator::make($request->all(), [

				'logo' => 'required|image|max:2000',

			]);

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse())->error($validator->errors()), 422);
		}

		$tenant = $user->tenant;

		if ($request->hasFile('logo')) {

			$logo = $request->file('logo');

			$image_path = storage_path()."/app/public/".$tenant->info->logo;

			if (File::exists($image_path)) {
				File::delete($image_path);
				// unlink($image_path);
			}

			$tmpName            = "business_logo.".$logo->getClientOriginalExtension();
			$filePath           = 'tenants_logos/'.$tenant->tenant_no;
			$tenant->info->logo = $filePath."/".$tmpName;
			$pathName           = $filePath."/".$tmpName;

			Storage::disk('local')->put("public/".$pathName, File::get($logo));

		}

		$tenant->info->updated_by = Auth::user()->id;
		$tenant->info->update();

		return response()->json(['success' => 'Business Logo Successfully Uploaded.']);

	}

	function deleteLogo(Request $request) {

		$user = $request->user();

		$tenant = $user->tenant;

		if ($tenant->info->logo) {

			exec('rm -r '.public_path($tenant->info->logo));
			$tenant->info->update(['logo' => '']);

			return response()->json(['success' => 'Logo Successfully Deleted.']);
		}

	}

}
