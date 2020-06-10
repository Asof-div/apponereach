<?php

use Illuminate\Database\Seeder;
use App\Models\OperatorPermission as Permission;
use App\Models\OperatorRole as Role;
use Illuminate\Database\Eloquent\Model;

class OperatorPermissionTableSeeder extends Seeder
{
    
    private $tables = ['operator_permissions', 'operator_permission_role', 'operator_roles'];
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

        $this->setupAccess();
        $this->generateRoles();


        Model::reguard();
    }

    private function create($name, $label, $note){
    	
    	Permission::create([
    		'name' => $name,
    		'label' => $label,
    		'description' => $note
    	]);
    }

    private function access($area){

    	$this->create($area.'.access', 'Access '.ucfirst($area).' Area', 'User can access '.$area.' area' );

    }

    private function crud($module, $only = []){

    	 $crud = (! empty($only)) ? $only : ['create', 'read', 'update', 'delete'];

    	foreach( $crud as $access){

    	 	$this->create($module.'.'.$access, ucfirst($access).' a '. $module, str_plural($module).' '.$access);
    	}

    }

    private function attach($perm, $role, $only = []){
        foreach ($only as $access) {
            
            $permission = $this->permissions->where('name', $perm .'.'. $access )->first();
            if($permission){

                $role->addPermission($permission);
            
            }

        }

    }



    private function generateRoles(){

        $this->permissions = Permission::get();

        // Administrator;

        $admin = Role::create([
            'name' => 'administrator',
            'label' => 'Super Administrator',
            'description' => 'Super administrators, can perform all action on the service.',
            'system' =>  true,
        ]);


        // Technical 
        $ba = Role::create([
            'name' => 'business',
            'label' => 'Business Analyst',
            'description' => 'User can manage customer account ...',
            'system' =>  true,
        ]);

        $experience = Role::create([
            'name' => 'experience_center',
            'label' => 'Experience Center',
            'description' => 'User can register a customer ...',
            'system' =>  true,
        ]);

       

        $this->attach('admin', $admin, ['access']);

        $this->attach('permission', $admin, ['access', 'assign', 'withdraw']);
        
        $this->attach('role', $admin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('user', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('user', $ba, ['access', 'create', 'read', 'update']);
        
        $this->attach('subscription', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('subscription', $ba, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('subscription', $experience, ['access', 'create', 'read' ]);
        
        $this->attach('order', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('order', $ba, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('order', $experience, ['access', 'create', 'read', 'update']);
        
        $this->attach('transaction', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('transaction', $ba, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('transaction', $experience, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('pilot_line', $admin, ['access', 'create', 'read', 'update', 'delete', 'assign']);

        $this->attach('transaction', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('transaction', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('transaction', $admin, ['access', 'create', 'read', 'delete', 'pdf']);
        
        $this->attach('number', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('number', $ba, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('number', $ba, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('incident', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('incident', $ba, ['access', 'read' ]);
                
        $this->attach('report', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('report', $ba, ['access', 'create', 'read', 'update', 'delete']);
                
        $this->attach('ticket', $admin, ['access', 'create', 'read', 'update', 'delete', 'status']);
        $this->attach('ticket', $ba, ['access', 'create', 'read', 'update', 'status']);
        $this->attach('ticket', $experience, ['access', 'create', 'read', 'delete', 'status']);
        

    }
    
    private function setupAccess(){

    	
    	$this->access('admin');
    	$this->access('permission');
    	$this->access('role');
    	$this->access('user');
    	$this->access('customer');
        $this->access('incident');
        $this->access('ticket');
    	$this->access('order');
    	$this->access('transaction');
        $this->access('pilot_line');
    	$this->access('report');

    	
        $this->crud('ticket', ['create', 'read', 'update', 'delete', 'assign', 'status', 'escalate']);
    	$this->crud('incident');
    	$this->crud('role');
    	$this->crud('user');
        $this->crud('customer', ['create', 'read', 'update', 'delete', 'resume', 'suspend']);
    	$this->crud('number', ['create', 'read', 'update', 'delete']);
        $this->crud('subscription');
        $this->crud('order', ['create', 'read', 'update', 'delete', 'pdf']);
    	$this->crud('transaction', ['create', 'read', 'update', 'delete', 'pdf']);
    	$this->crud('pilot_line', ['create', 'read', 'update', 'delete', 'assign' ]);
        $this->crud('permission', ['assign', 'withdraw']);
        $this->crud('report');

    }
}
