<?php

namespace App\Traits;

use Auth;
use ReflectionClass;
use Illuminate\Contracts\Auth\Guard;
use App\Models\AppLog;
trait ActivityLog
{
	protected  static function bootActivityLog()
	{
		
		foreach(static::getEventModels() as $event){
        
		
			static::$event( function($model) use ($event) {

				$model->addActivity($event, $model);
				
			});

		}
		
	}


	protected function addActivity($action, $model){
		if (Auth::check()) {
			$action = $action == 'updating' ? 'updated': $action;

			// Activity::create([

			// 	'user_id' => Auth::check()? Auth::user()->id: null,
			// 	'subject_id' => $model->id,
			// 	'subject_type' => $model->subjectType($model),
			// 	'action' => $model->action($model, $action),
			// 	'before' => $action != 'updated' ? '': json_encode(array_intersect_key($model->fresh()->toArray(), $model->getDirty())),
			// 	'after' => $action != 'updated' ? '': json_encode($model->getDirty())
			// ]);

	        // \Log::log('info', (new ReflectionClass(Auth::user()))->getName());
	        // \Log::log('info', array_intersect_key($model->fresh()->toArray(), $model->getDirty()));
	        // \Log::log('info', $model->getDirty());

	        // $this->composeMessage($this->subjectTypeShort(Auth::user()), Auth::user()->name, $action, array_keys($model->fresh()->toArray()) );
		}
	}



	public function subjectType($model){

		$subject = (new ReflectionClass($model))->getName();

		return $subject;
	}


	public function subjectTypeShort($model){

		$subject = (new ReflectionClass($model))->getShortName();

		return $subject;
	}

	public function action( $model, $action){

		$subject = strtolower((new ReflectionClass($model))->getShortName());
		if($action == "created"){
			$action = "Created new";
		}


		return ucfirst($action)." ". $subject;

	}

	protected static function getEventModels(){

		if(isset(static::$eventModels)){

			return static::$eventModels;
		}

		return [
			"created" , "updating", 'updated', 'deleting', 'deleted'
		];
	}

	protected function authenticatedUserType(){
		$guard = Auth::guard('operator')->check('operator');


	}

	private function composeMessage($user_type, $user, $action, $colums){

		\Log::log('info', $user .' '. $action . ' '. $this->logLabels($colums));
	}

    private function logLabels($colums){
        \Log::log('info',  static::$logs);
    	$labels = [];
    	foreach ($colums as $colum) {
    		
	    	if(isset(static::$logs[$colum])){

	    		$labels[] = isset(static::$logs[$colum]['label']) ? static::$logs[$colum]['label'] : '';
	    	}
	    }
        \Log::log('info', $labels);
	    return implode(', ', $labels);
   
    }
}