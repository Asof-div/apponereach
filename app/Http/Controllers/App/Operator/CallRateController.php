<?php

namespace App\Http\Controllers\App\Operator;

use App\Http\Controllers\Controller;
use App\Models\CallRate;

use App\Models\Country;
use App\Models\Currency;
use Auth;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Validator;
// use Excel;

class CallRateController extends Controller {
	public function __construct() {
		$this->middleware('auth:operator');

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$rates = CallRate::paginate(50);

		return view('app.operator.call_rate.index', compact('rates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$countries = Country::distinct('phonecode')->orderBy('phonecode', 'asc')->get();
		$rates     = CallRate::get()->pluck('phonecode');
		$countries = $countries->whereNotIn('phonecode', $rates)->unique('phonecode')->sortBy(function ($country, $key) {
				return (int) $country->phonecode;
			});
		$currencies = Currency::get();

		return view('app.operator.call_rate.create', compact('countries'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {

		$validator = Validator::make($request->all(), [

				'phonecode' => 'required|unique:call_rates,phonecode',
				'rate'      => 'required|numeric',

			]);

		if ($validator    ->fails()) {
			return response()->json(['error' => $validator->errors()->all()], 422);
		}

		$country = Country::where('phonecode', $request->phonecode)->first();

		if ($request->ajax()) {

			CallRate::create([
					'phonecode'  => $request->phonecode,
					'default'    => false,
					'country_id' => $country->id,
					'rate'       => $request->rate,
				]);

			return response()->json(['success' => 'Call Rate Successfully Saved.'], 200);

		}

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$rate = CallRate::find($id);
		if (!$rate) {abort(404);}
		$countries = Country::distinct('phonecode')->orderBy('phonecode', 'asc')->get();
		$rates     = CallRate::where('phonecode', '<>', $rate->phonecode)->get()->pluck('phonecode');
		$countries = $countries->whereNotIn('phonecode', $rates)->unique('phonecode')->sortBy(function ($country, $key) {
				return (int) $country->phonecode;
			});

		return view('app.operator.call_rate.edit', compact('rate', 'countries'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request) {

		$validator = Validator::make($request->all(), [

				'phonecode' => 'required|unique:call_rates,phonecode,'.$request->id,
				'rate'      => 'required|numeric',

			]);

		if ($validator    ->fails()) {
			return response()->json(['error' => $validator->errors()->all()], 422);
		}
		$rate = CallRate::find($request->id);
		if (!$rate) {

		}

		$country = Country::where('phonecode', $request->phonecode)->first();

		if ($request->ajax()) {

			$rate->update([
					'phonecode'  => $request->phonecode,
					'default'    => false,
					'country_id' => $country->id,
					'rate'       => $request->rate,
				]);

			return response()->json(['success' => 'Call Rate Successfully Updated.'], 200);

		}

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function export(Request $request) {

		return Excel::download(new PilotNumberExport, 'pilot_number.xlsx');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function template(Request $request) {

		$collections = (new PilotNumber)->newCollection();

		return Excel::download(new PilotNumberExport($collections), 'template.xlsx');
	}

	public function import(Request $request) {

		$validator = $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);

		$csvdata = [];

		ini_set('max_execution_time', 300);

		$dataStatus = [];

		$file = $request->file;

		$filename = $file->getClientOriginalName();
		$ext      = $file->getClientOriginalExtension();

		if ($request->hasFile('file')) {
			$pilotImporter = new PilotNumberImport;
			Excel::import($pilotImporter, $request->file);
			$dataStatus = $pilotImporter->getStatusMessage();
		}

		return redirect()->route('operator.pilot_number.create')->with(['flash_message' => 'Files Successfully Imported', 'import_status' => $dataStatus]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$this->validate($request, [
				'id' => 'required|exists:call_rates,id',
			]);

		$rate = CallRate::find($request->id);
		if (!$rate) {

			return redirect()->route('operator.call.rate.index')->withErrors(['This rate have be deleted.']);
		}
		$rate->delete();

		return redirect()->route('operator.call.rate.index')->with('flash_message', 'Call Rate successfully deleted');
	}

	public function importDataToDB($data, &$dataStatus) {

		if (!empty($data) && count($data)) {

			foreach ($data as $key => $row) {
				if (is_array($row) && array_key_exists('msisdn', array_values($row))) {

					$this->importDataToDB($row);

				} else {

					$key_state = (int) $key+1;

					// dd($row);

					if (isset($row['msisdn'])) {

						$pilot_number = PilotNumber::where('number', $row['msisdn'])->first();

						if (!$pilot_number) {

							PilotNumber::Create([
									'number'        => $row['msisdn'],
									'serial_no'     => array_key_exists('serial_no', $row)?$this->processString($row['serial_no']):null,
									'available'     => 1,
									'source'        => 'Operator',
									'operator_type' => 'App\Models\Operator',
									'operator_id'   => Auth::id(),
								]);

							$dataStatus[] = ['msg' => 'Row '.$key_state.' -  MSISDN - '.$row['msisdn'].' Successfully Imported.', 'status' => 'success'];
						}

					} else {

						$dataStatus[] = ['msg' => 'Row '.$key_state.' - MSISDN - '.$row['msisdn'].' Already existing.', 'status' => 'failed'];

					}

				}

			}

		} else {

			$dataStatus[] = ['msg' => "File Has No Data !!!", 'status' => 'failed'];
		}
		return $dataStatus;
	}

}
