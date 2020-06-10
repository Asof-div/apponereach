<?php 

namespace App\Repos;

use App\Models\VoicemailInbox;
use App\Models\PlayMedia;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class VoicemailBoxRepo extends DestinationRepo
{

	public function __construct(){

	}

	public function store($data){

        $tenant = TenantManager::get();

        VoicemailInbox::create([
            'title' => $data['title'], 
            'user' => $data['user'], 
            'tenant_id' => $tenant->id,
            'context' => 'USER_'.$tenant->code,
            'number' => 'USER_'.$tenant->code .$data['user'], 
            'email' => $data['email'], 
            'sound_path' => $data['sound_path'],
            'pin' => $data['pin'], 
            'voicemail_prompt_id' => $data['voicemail_prompt'], 
            'leave_copy_on_portal' =>  isset($data['web_portal']) ? 1 : 0 ,
            'send_voicemail_to_mail' =>  isset($data['send_to_mail']) ? 1 : 0 ,
            'isGlobal' => 1,
            'params' => json_encode(['sound_path' => $data['sound_path'], 'number' => 'USER_'.$tenant->code .$data['user'] ]),

            ]);


	}

    public function update($data){

        $tenant = TenantManager::get();

        $inbox = VoicemailInbox::find($data['inbox_id']);
        if($inbox){
            $inbox->update([
                'title' => $data['title'], 
                'user' => $data['user'], 
                'number' => 'USER_'.$tenant->code .$data['user'], 
                'email' => $data['email'], 
                'sound_path' => $data['sound_path'],
                'pin' => $data['pin'], 
                'voicemail_prompt_id' => $data['voicemail_prompt'], 
                'leave_copy_on_portal' =>  isset($data['web_portal']) ? 1 : 0 ,
                'send_voicemail_to_mail' =>  isset($data['send_to_mail']) ? 1 : 0 ,
                'isGlobal' => 1,
                'params' => json_encode(['sound_path' => $data['sound_path'], 'number' => 'USER_'.$tenant->code .$data['user'] ]),

                ]);

        }


    }

    public function delete($data){

        $tenant = TenantManager::get();

        $inbox = VoicemailInbox::find($data['inbox_id']);
        if($inbox){
            
            $inbox->delete();

        }


    }


    public function generateFile(Extension $exten){

        $tenant = TenantManager::get();
        
        $xmlout = new \XMLWriter();
        // $xmlout->openURI('10001.xml');

        $xmlout->openMemory();
        $xmlout->setIndent(true);
        $xmlout->setIndentString('  ');
        // $xmlout->startDocument('1.0', 'UTF-8', 'no');

        //start include
        $xmlout->startElement('include');


        //start user
        $xmlout->startElement('user');
        $xmlout->writeAttribute('id', $exten->exten_reg);

        //start params
        $xmlout->startElement('params');


        $xmlout->startElement('param');
        $xmlout->writeAttribute('name', 'password');
        // $xmlout->writeAttribute('value', '$${default_password}');
        $xmlout->writeAttribute('value', $exten->password);

        //end param
        $xmlout->endElement();

        $xmlout->startElement('param');
        $xmlout->writeAttribute('name', 'vm-password');
        $xmlout->writeAttribute('value', $exten->voicemail_pin ? $exten->voicemail_pin : $exten->number);
        //end param
        $xmlout->endElement();


        //end params
        $xmlout->endElement();


        //start variables
        $xmlout->startElement('variables');

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'toll_allow');
        $xmlout->writeAttribute('value', 'domestic,international,local');
        //end variable
        $xmlout->endElement();



        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'accountcode');
        $xmlout->writeAttribute('value', $exten->exten_reg);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'user_context');
        // $xmlout->writeAttribute('value', $exten->context);
        $xmlout->writeAttribute('value', 'default');
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'effective_caller_id_name');
        $xmlout->writeAttribute('value', $exten->name. "  ". $exten->number);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'effective_caller_id_number');
        $xmlout->writeAttribute('value', $exten->number);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'outbound_caller_id_name');
        $xmlout->writeAttribute('value', $tenant->name);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'outbound_caller_id_number');
        $xmlout->writeAttribute('value', '$${outbound_caller_id}');
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'callgroup');
        $xmlout->writeAttribute('value', 'techsupport');
        //end variable
        $xmlout->endElement();


        // end variables
        $xmlout->endElement();

        // end user
        $xmlout->endElement();

        // end include
        $xmlout->endElement();


        // $xmlout->endDocument();
        //echo $xmlout->outputMemory();

        // $xmlout->flush();

        file_put_contents('/var/www/freeswitch/'. $exten->exten_reg .'.xml', $xmlout->flush(true));

        shell_exec('chmod -f 777 /var/www/freeswitch/'. $exten->exten_reg .'.xml');

    }


}