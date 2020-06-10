<?php

namespace App\Exports;

use App\Models\Operator\PilotNumber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PilotNumberExport implements FromView
{
	use Exportable;

	public function __construct($pilot_numbers = null){
		$this->pilot_numbers = is_null($pilot_numbers) ? PilotNumber::all() : $pilot_numbers;
	}


    public function mapping(): array
    {
        return [
            'MSISDN'  => 'A1',
            'Serial_no' => 'B1',
            'Status' => 'C1',
        ];
    }



    public function view(): View
    {
        return view('exports.pilot_number', [
            'pilot_numbers' => $this->pilot_numbers
        ]);
    }

}
