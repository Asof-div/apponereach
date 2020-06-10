<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $guarded = ['id'];

	public function getIcon(){

		$ext = $this->ext;

		switch (  $ext) {
			case 'pdf':
			return 'pdf';
			break;
			case '':
			case 'jpeg':
			case 'jpg' :
			case 'png':
			case 'gif':
			return 'image_icon';
			break;
			case 'doc':
			case 'docx':
			return 'doc';
			break;
			case 'xlsx':
			case 'xls':
			case 'XLS':
			return 'xls';
			break;
			case 'ppt':
			case 'pptx':
			return 'ppt';
			case 'mp4':
			case '':
			return 'video';
			case 'mp3':
			case '':
			return 'audio';
			break;
			default:
			return 'file_icon';
			
		}
	}

	public function user(){

		return $this->belongsTo(User::class, 'user_id')->withDefault();
	}

	public function admin(){

		return $this->belongsTo(Admin::class, 'admin_id')->withDefault();
	}

	public function operator(){

		return $this->belongsTo(Operator::class, 'operator_id')->withDefault();
	}

	public function owner(){

		switch (strtolower($this->owner_type)) {
			case 'user':
				return $this->user->name;
				break;
			case 'operator':
				return $this->operator->name;
				break;
			case 'admin':
				return $this->admin->name;
				break;
			
			default:
				return '';
				break;
		}

	}

	public function getSize(){

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

			return number_format($this->size/1000000000000, 2) ." TB";
		}
	}

}
