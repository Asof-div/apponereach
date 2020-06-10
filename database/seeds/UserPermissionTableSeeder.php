<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class UserPermissionTableSeeder extends Seeder
{
    
    private $tables = ['permissions', 'permission_role', 'roles'];
    private $permissions;
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

    	$this->create('tenant.'.$area.'.access', 'Access '.ucfirst($area).' Area', 'User can access '.$area.' area' );

    }

    private function crud($module, $only = []){

    	 $crud = (! empty($only)) ? $only : ['create', 'read', 'update', 'delete'];

    	 foreach( $crud as $access){

    	 	$this->create('tenant.'.$module.'.'.$access, ucfirst($access).' a '. $module, str_plural($module).' '.$access);
    	 }

    }

    private function attach($perm, $role, $only = []){
        foreach ($only as $access) {
            
            $permission = $this->permissions->where('name', 'tenant.'.$perm .'.'. $access )->first();
            if($permission){

                $role->addPermission($permission);
            
            }

        }

    }



    //Permission
    //
    
    private function generateRoles(){

        $this->permissions = Permission::get();

        // Administrator;

        $admin = Role::create([
            'name' => 'administrator',
            'label' => 'System Administrator',
            'description' => 'System administrators, can perform all action on the service.',
            'system' =>  true,
        ]);


        // Technical 
        $technical = Role::create([
            'name' => 'technical',
            'label' => 'Technical Personel',
            'description' => 'User can configure pilot number, media services ...',
            'system' =>  true,
        ]);

        $technicalAdmin = Role::create([
            'name' => 'technical_admin',
            'label' => 'IT Manager',
            'description' => 'User can configure, delete pilot number, media services ...',
            'system' =>  true,
        ]);

        // Sales

        $sales = Role::create([
            'name' => 'sales',
            'label' => 'Sales Personel',
            'description' => 'User can create, edit, customer accounts, quotes, invoice, contacts etc ...',
            'system' =>  true,
        ]);

        $salesAdmin = Role::create([
            'name' => 'sales_manager',
            'label' => 'Sales Manager',
            'description' => 'User can create, edit, delete. customer accounts, quotes, invoice, contacts etc ...',
            'system' =>  true,
        ]);


        // Manager

        $service = Role::create([
            'name' => 'service_account',
            'label' => 'Service Account Manager',
            'description' => 'User can manage billing information for the service. Migration of packages ...',
            'system' =>  true,
        ]);


        // Conference Manager

        $conference = Role::create([
            'name' => 'conference',
            'label' => 'Conference Manager',
            'description' => 'User can manage conference details for the service. Migration of packages ...',
            'system' =>  true,
        ]);

        // Basic User

        $basicUser = Role::create([
            'name' => 'basic_user',
            'label' => 'Basic User',
            'description' => 'User can manager billing information for the service. Migration of packages ...',
            'system' =>  true,
        ]);


        $this->attach('admin', $admin, ['access']);

        $this->attach('permission', $admin, ['access', 'assign', 'withdraw']);
        
        $this->attach('role', $admin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('user', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('user', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('user', $service, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('subscription', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('subscription', $service, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('order', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('order', $service, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('transaction', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('transaction', $service, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('pilot_line', $admin, ['access', 'create', 'read', 'update', 'delete', 'route']);
        $this->attach('pilot_line', $technical, ['access', 'create', 'read', 'update']);
        $this->attach('pilot_line', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete', 'route']);
        
        $this->attach('extension', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('extension', $technical, ['access', 'create', 'read', 'update']);
        $this->attach('extension', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('sound', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('sound', $technical, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('sound', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('text_to_speech', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('text_to_speech', $technical, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('text_to_speech', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('number', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('number', $technical, ['access', 'create', 'read']);
        $this->attach('number', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('gallery', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('gallery', $technical, ['access', 'create', 'read', ]);
        $this->attach('gallery', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('group_ring', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('group_ring', $technical, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('group_ring', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('virtual_receptionist', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('virtual_receptionist', $technical, ['access', 'create', 'read', 'update']);
        $this->attach('virtual_receptionist', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('scheduler', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('scheduler', $technical, ['access', 'create', 'read', 'update']);
        $this->attach('scheduler', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('call_flow', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('call_flow', $technical, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('call_flow', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('ticket', $admin, ['access', 'create', 'read', 'update', 'delete', 'status']);
        $this->attach('ticket', $technical, ['access', 'create', 'read', 'update', 'status']);
        $this->attach('ticket', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete', 'status']);
        
        $this->attach('support', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('support', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('conference_audio', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('conference_audio', $conference, ['access', 'create', 'read']);
        $this->attach('conference_audio', $technical, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('conference_audio', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('crm_report', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_report', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('crm_contact', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_contact', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_contact', $sales, ['access', 'create', 'read', ]);
        
        $this->attach('crm_account', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_account', $sales, ['access', 'create', 'read', ]);
        $this->attach('crm_account', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('crm_opportunity', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_opportunity', $sales, ['access', 'create', 'read']);
        $this->attach('crm_opportunity', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('crm_quote', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_quote', $sales, ['access', 'create', 'read']);
        $this->attach('crm_quote', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('crm_invoice', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_invoice', $sales, ['access', 'create', 'read']);
        $this->attach('crm_invoice', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        
        $this->attach('crm_task', $admin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $salesAdmin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $technicalAdmin, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $service, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $conference, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $sales, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $technical, ['access', 'create', 'read', 'update', 'delete']);
        $this->attach('crm_task', $basicUser, ['access', 'create', 'read', 'update', 'delete']);



    }

    private function setupAccess(){

    	
    	$this->access('admin');
    	$this->access('permission');
    	$this->access('role');
    	$this->access('user');
        $this->access('subscription');
    	$this->access('order');
    	$this->access('transaction');
    	$this->access('pilot_line');
    	$this->access('extension');
    	$this->access('sound');
    	$this->access('text_to_speech');
    	$this->access('number');
    	$this->access('gallery');
    	$this->access('group_ring');
    	$this->access('virtual_receptionist');
    	$this->access('scheduler');
    	$this->access('call_flow');
    	$this->access('ticket');
    	$this->access('support');
    	$this->access('conference_audio');
        $this->access('crm_report');
    	$this->access('crm_contact');
    	$this->access('crm_account');
        $this->access('crm_opportunity');
        $this->access('crm_quote');
    	$this->access('crm_invoice');
    	$this->access('crm_task');



    	$this->crud('role');
    	$this->crud('user');
        $this->crud('subscription');
    	$this->crud('order');
    	$this->crud('transaction');
    	$this->crud('pilot_line', ['create', 'read', 'update', 'delete', 'route']);
    	$this->crud('extension');
    	$this->crud('sound');
    	$this->crud('text_to_speech');
    	$this->crud('number');
    	$this->crud('gallery');
    	$this->crud('group_ring');
    	$this->crud('virtual_receptionist');
    	$this->crud('scheduler');
        $this->crud('call_flow');
    	$this->crud('call_summary');
        $this->crud('ticket', ['create', 'read', 'update', 'delete', 'status']);
    	$this->crud('support');
    	$this->crud('conference_audio');
        $this->crud('crm_report');
    	$this->crud('crm_contact');
    	$this->crud('crm_account');
        $this->crud('crm_opportunity');
        $this->crud('crm_quote');
    	$this->crud('crm_invoice');
        $this->crud('crm_task');
        $this->crud('permission', ['assign', 'withdraw']);

    }

}
