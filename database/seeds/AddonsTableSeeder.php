<?php

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Addon;
use Illuminate\Database\Eloquent\Model;


class AddonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		// DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Model::unguard();
		DB::statement('TRUNCATE TABLE addons CASCADE;');
		// DB::statement('SET FOREIGN_KEY_CHECKS=1');

		$this->free = Package::where('name', "Free")->get()->first();
		$this->basic = Package::where('name', "Basic")->get()->first();
		$this->standard = Package::where('name', "Standard")->get()->first();
		$this->executive = Package::where('name', "Executive")->get()->first();

		$features = [
			['name' => 'Intercom', 'price' => '200', 'description' => 'The intercom feature of the CloudPBX solution allows users on a Closed User Group to make unlimited calls (FUC) amongst themselves. Intercom CUG/GSM calling is beyond the scope of the CloudPBX solution, as such, a pre-requisite is that the users must already belong to a CUG for them to use the intercom feature.', 'package' => 1, 'permission' => 'intercom'],

			['name' => 'Conference Call', 'price' => '0', 'description' => 'A conference call is a telephone call in which someone talks to several people at the same time. The conference calls may be designed to allow the called party to participate during the call, or the call may be set up so that the called party merely listens into the call and cannot speak.', 'package' => 1, 'permission' => 'local_conference'],
			['name' => 'Group Call', 'price' => '500', 'description' => 'Group call enables a user to dynamically call multiple members of a pre-defined group with one phone call. ', 'package' => 3, 'permission' => 'group_call'],
			['name' => 'Music On Hold', 'price' => '0', 'description' => 'Music plays when the PBX subscriber places a call on hold, so the callers will know that the subscriber is still there and the call has not accidentally dropped. Music on Hold provides callers with music or information while they are waiting for a call to be completed. Music on Hold is provided when a call is on Hold, transferred to a busy or ringing station. The music or information source is provided by the PBX customer and is pre-loaded at set up of the Cloud PBX solution for the subscriber.', 'package' => 1, 'permission' => 'music_on_hold'],
			['name' => 'Call Transfer', 'price' => '200', 'description' => 'Transfers calls to another person. Or, if the user needs to leave the office, but wants to continue the conversation, he/she can transfer the calls from the PC or IP phone, to the cell phone or tablet.', 'package' => 2, 'permission' => 'call_transfer'],
			['name' => 'Call Recording', 'price' => '500', 'description' => 'This service enables subscribers to save phone conversations and store it for later playback.  Once calls are recorded, the system saves them for access via the CloudPBX portal in playable media as it finally ends up in common audio format: wav, mp3, etc.', 'package' => 3, 'permission' => 'call_recording'],
			['name' => 'Voicemail To Email', 'price' => '500', 'description' => 'Here\'s how CloudPBX voicemail works: When a user receives a voicemail, it will be delivered to their mailbox as an email with the voicemail message as an attachment. They can also listen to their messages over their CloudPBX certified desktop phone, all CloudPBX applications', 'package' => 3, 'permission' => 'voicemail_to_email'],
			['name' => 'Pilot Line', 'price' => '0', 'description' => 'Each hunt group that is created on a CloudPBX account will have an associated pilot number. CloudPBX uses the pilot number to locate the hunt group and in turn to locate the telephone extension number on which the incoming call will be delivered.', 'package' => 1, 'permission' => 'pilot_line'],
			['name' => 'Automated Call Routing', 'price' => '0', 'description' => 'A pilot number is the address or location of the hunt group inside CloudPBX. A pilot number is generally defined as a "blank" extension number or one extension number from a hunt group of extension numbers that does not have a person or telephone associated with it.', 'package' => 1, 'permission' => 'automated_call_routing' ],
			['name' => 'Interactive Voice Response', 'price' => '0', 'description' => 'Interactive voice response (IVR) is a technology that allows a computer to interact with humans through the use of voice and DTMF tones input via keypad. IVR allows customers to interact with a company’s CloudPBX setup via a telephone keypad or by speech recognition, after which services can be inquired about through the IVR dialogue. IVR systems can respond with prerecorded or dynamically generated audio to further direct users on how to proceed', 'package' => 1, 'permission' => 'ivr'],
			['name' => 'Unlimited Extension', 'price' => '0', 'description' => 'Basic Plan Comes with Free 3 user, Standard Plan have 5 Free Users, Executive Plan have 10 Users Extension. Additional Extesion cost 200 NGN  ', 'package' => 1, 'permission' => 'extension'],
			['name' => 'CRM Integration', 'price' => '200', 'description' => 'Customer Relational Management - Account, Contact, Invoice, Leads, Opportunity, Quote, Task', 'package' => 2, 'permission' => 'crm'],
			['name' => 'Automated Company Greeting', 'price' => '0', 'description' => 'CloudPBX automated greetings plays a welcome message to all incoming caller. The message may be an announcement, message of the day, or customized time-based variable message based on current time.', 'package' => 1, 'permission' => 'automated_company_greeting'],
			['name' => 'Web Conferencing', 'price' => '200', 'description' => 'Web Conferencing', 'package' => 2, 'permission' => 'web_conferencing'],
			['name' => 'Public Meeting Room', 'price' => '200', 'description' => 'Public Meeting Room', 'package' => 2, 'permission' => 'public_meeting_room'],
			['name' => 'Private Meeting Room', 'price' => '500', 'description' => 'Private Meeting Room', 'package' => 3, 'permission' => 'private_meeting_room'],
			['name' => 'Automated Dial Up Of Participant', 'price' => '00', 'description' => 'Automated Dial Up of Participants', 'package' => 1, 'permission' => 'auto_dial_participant'],
			['name' => 'Business SMS', 'price' => '200', 'description' => 'Business SMS', 'package' => 2, 'permission' => 'sms_support' ],
			['name' => 'Email Support', 'price' => '0', 'description' => 'Email Support', 'package' => 1, 'permission' => 'email_support'],
			['name' => 'Instant Chat', 'price' => '0', 'description' => 'Instant Chat', 'package' => 1, 'permission' => 'instant_chat'],
			['name' => 'Online Ticket Submission', 'price' => '0', 'description' => 'Online Ticket Portal Support', 'package' => 1, 'permission' => 'ticketing_support'],
			['name' => 'Short Number Dial', 'price' => '500', 'description' => 'Voice short codes enables Cloud PBX mobile phone users to dial a short code (e.g. 615400) as an alternative to a standard 11-digit (e.g. 090 or 091 prefix long number. The CloudPBX subscriber will define a short code for each member of their PBX group. The short code is forwarded to a standard mobile number - typically an existing single mobile number, a hunt group or interactive voice response (IVR) system.' , 'package' => 3, 'permission' => 'short_code'],

		];


		foreach ($features as $key => $value) {
			$this->create($value, $key);
		}

        Model::reguard();

    }

	private function create($data, $index){

		$addon = Addon::create([
			'name' => str_replace(' ', '_', $data['name']),
			'price' => $data['price'],
			'currency' => '&#x20A6;',
			'label' => $data['name'],
			'description' => $data['description'],
			'binary_index' => $index,
			'permission_label' => $data['permission'],
		]);
		if($data['package'] == 1){

			$this->free->addons()->attach($addon->id);
			$this->basic->addons()->attach($addon->id);
			$this->standard->addons()->attach($addon->id);
			$this->executive->addons()->attach($addon->id);

		}elseif($data['package'] == 2){

			$this->executive->addons()->attach($addon->id);
			$this->standard->addons()->attach($addon->id);
		
		}else{

			$this->executive->addons()->attach($addon->id);

		}

	}

}
