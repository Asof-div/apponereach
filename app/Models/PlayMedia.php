<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Traits\NonGlobalTenantScopeTrait;

class PlayMedia extends Model
{
    use NonGlobalTenantScopeTrait;
    
    protected $guarded = ['id'];

    protected $table ='play_media';

    public function size(){
        
        if($this->size < 1000){
            return $this->size ." byte";
        }elseif ($this->size > 1000 && $this->size < 1000000) {
            
            return number_format($this->size/1000, 2) ." KB";
        }
        elseif ($this->size > 1000000 && $this->size < 1000000000) {

            return number_format($this->size/1000000, 2) ." MB";
        }
        elseif ($this->size > 1000000000 && $this->size < 1000000000000) {

            return number_format($this->size/1000000000, 2) ." GB";
        }
        else{

            return " N/A ";
        }
    }

    public function call_routes(){

        return $this->hasMany(CallRoute::class, 'greeting');
    }
    
    public function pilot_lines(){

        return $this->hasMany(PilotLine::class, 'greeting');
    }

    public function call_route_destinations()
    {
        return $this->morphMany(CallRoute::class, 'module');
    }

    public function ivrs(){

        return $this->hasMany(VirtualReceptionist::class, 'play_media_id');
    }

    public function ivr_destinations()
    {
        return $this->morphMany(VirtualReceptionistMenu::class, 'module');
    }
 
    public function deletable(){

        if(count($this->call_routes) < 1 && count($this->pilot_lines) < 1 && count($this->call_route_destinations) < 1 && count($this->ivrs) < 1 && count($this->ivr_destinations) < 1){

            return true;
        }

        // dd($this->call_route_destinations());

        return false;
    }


}
