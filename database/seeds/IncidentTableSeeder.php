<?php

use Illuminate\Database\Seeder;
use App\Models\Incident;
use Illuminate\Database\Eloquent\Model;

class IncidentTableSeeder extends Seeder
{
    
    private $tables = ['incidents', 'admin_teams', 'operator_teams', 'tickets', 'ticket_user', 'ticket_operator', 'ticket_admin'];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

    	foreach ($this->tables as $table) {

            DB::statement('TRUNCATE TABLE '.$table.' CASCADE;');

    	}

        $this->generateIncidents();


        Model::reguard();
    }

    private function create($name, $label, $initial_response_time, $initial_response_unit, $expected_resolution_time, $expected_resolution_unit, $escalation_interval_time, $escalation_interval_unit, $priority, $severity ){
    	
    	Incident::create([
    		'name' => $name,
            'label' => $label,
            'initial_response' => $initial_response_time,
            'initial_response_unit' => $initial_response_unit,
            'expected_resolution' => $expected_resolution_time,
            'expected_resolution_unit' => $expected_resolution_unit,
            'escalation_interval' => $escalation_interval_time,
            'escalation_interval_unit' => $escalation_interval_unit,
            'priority' => $priority,
            'severity' => $severity,
            'apply_to_user' => true,
        ]);
    }

    private function generateIncidents(){

    	$this->create('Pilotline routing', 'Unable to configure pilotline destination', 1, 'hour', 3, 'hour', 2, 'hour', 'Urgent', 'Emergency');
    
    	$this->create('IVR', 'Unable to configure Interactive Voice Response', 1, 'hour', 5, 'hour', 4, 'hour', 'High', 'Normal');

    	$this->create('Conference', 'Unable to join conference call', 1, 'hour', 3, 'hour', 2, 'hour', 'Urgent', 'Emergency');
    
    	$this->create('Extension', 'Unable to register an User Extension', 1, 'hour', 3, 'hour', 2, 'hour', 'Urgent', 'Emergency');

    	$this->create('Support', 'General Support', 1, 'hour', 22, 'hour', 10, 'hour', 'Medium', 'Normal');

    }

}
