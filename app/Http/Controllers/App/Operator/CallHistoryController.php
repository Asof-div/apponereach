<?php

namespace App\Http\Controllers\App\Operator;

use App\Http\Controllers\Controller;
use App\Models\Call;

use Auth;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

// use Excel;

class CallHistoryController extends Controller {
	public function __construct() {
		$this->middleware('auth:operator');

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$calls = Call::paginate(200);

		return view('app.operator.call_history.index', compact('calls'));
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
