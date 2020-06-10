<?php

namespace App\Services\Call;

use App\Repos\CallRepository;
use App\Repos\UserRepository;

class CallLogCreator {
	protected $userRepo;
	protected $callRepo;

	public function __construct(UserRepository $userRepo, CallRepository $callRepo) {
		$this->userRepo = $userRepo;
		$this->callRepo = $callRepo;
	}

	public function create($details) {

		\Log::log('info', $details);

		if (strtolower($details['direction']) == 'intercom' && strtolower($details['source']) == 'local') {
			$details['call_rate'] = (float) $details['call_rate'];
			$call                 = $this->callRepo->saveLog($details);
			$this->userRepo->bindCallByExtension($details, $call);

		} elseif (strtolower($details['direction']) == 'outbound' && strtolower($details['source']) == 'gateway') {
			$details['call_rate'] = (float) $details['call_rate'];

			$call = $this->callRepo->saveLog($details);
			$this->userRepo->bindCallByContact($details, $call);
		} elseif (strtolower($details['direction']) == 'inbound' && strtolower($details['source']) != 'gateway') {
			$details['call_rate'] = (float) $details['call_rate'];

			$call = $this->callRepo->saveLog($details);
			// $this->userRepo->bindCallByContact($details, $call);
		}

		return true;
	}

}