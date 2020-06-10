<?php

namespace App\Imports;

use App\Models\Operator\PilotNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PilotNumberImport implements ToModel, WithHeadingRow
{
    // HeadingRowFormatter::default('none');

    private $statusMsg = [];
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $msisdn = isset($row['msisdn']) ? $row['msisdn'] : null; 
        $pilot_number = PilotNumber::where('number', $msisdn)->first();
        
        if (is_null($msisdn) || $pilot_number  ) {
            $this->statusMsg[] = ['msg' => $msisdn .' - already exist in the database.', 'status' => 'failed'];
            return null;
        }

        if(preg_match('%^(07)|(08)|(09)[0-9]{9}$%', $msisdn) && strlen($msisdn) == 11 && is_numeric($msisdn)){

            $this->statusMsg[] = ['msg' => $msisdn .' - Successfully added to the database.', 'status' => 'success'];
            return new PilotNumber([
                'number' => $row['msisdn'],
                'serial_no' => $row['serial_no'],
            ]);
        }
        $this->statusMsg[] = ['msg' => $msisdn .' - incorrect number format.', 'status' => 'failed'];

        return null;        
    }

    public function getStatusMessage(){

        return $this->statusMsg;
    }
}
