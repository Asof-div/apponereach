<?php 

namespace App\Services;

class HuntGroupMember {

    public $members = null;
    public $last = 1;

	public function __construct($old = null){

		if($old ){

            $data = json_decode($old, true);
            $this->members = $data['members'];
            $this->last = $data['last'];
            
		}
	}



    public function removeList($id){

    	unset($this->members[$id]);
    }

    public function addList($lines, $delay){

        $data = [ 'attempt'=> $this->last, 'lines' => $lines, 'delay' => $delay];
        $this->members[$this->last] = $data;
        $this->last++;
    }

    public function updateList($id, $lines, $delay){
        
        $data = $this->members[$id] ;
        $data['lines'] = $lines;
        $data['delay'] = $delay;
        $this->members[$id] = $data;
    }
    
    public function save(){

        return json_encode( ['members' =>$this->members, 'last' => $this->last ]);
    }

}