<?php

namespace App\Services\Sip\Freeswitch;

class Extension
{
	private $ext_folder = '/sip_extension/freeswitch/';

	public function generateNumber()
	{
		$i = 0;
	    $number = "";
	    while ($i < 10) {
	        $number .= mt_rand(0, 9);
	        $i++;
	    }
	    return $number;
	}

	public function generatePassword()
	{
		return substr(uniqid(), 5);
	}

	public function generateFile($exten, $phone)
    {
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
        $xmlout->writeAttribute('id', $exten->number);

        //start params
        $xmlout->startElement('params');


        $xmlout->startElement('param');
        $xmlout->writeAttribute('name', 'password');
        $xmlout->writeAttribute('value', $exten->password);

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
        $xmlout->writeAttribute('value', $exten->number);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'user_context');
        $xmlout->writeAttribute('value', 'default');
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'ucalltel_number');
        $xmlout->writeAttribute('value', $phone);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'effective_caller_id_name');
        // $xmlout->writeAttribute('value', $exten->username. "  ". $exten->number);
        $xmlout->writeAttribute('value', $phone);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'effective_caller_id_number');
        $xmlout->writeAttribute('value', $phone);
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

        $ext_path = $this->generateExtensionFilePath($exten->number);

        file_put_contents($ext_path, $xmlout->flush(true));

        shell_exec("chmod -f 777 {$ext_path}");

        $ssh = ssh2_connect('192.168.234.98', 22);
        ssh2_auth_password($ssh, 'root', 'Telvida123');
        ssh2_exec($ssh, 'bash /root/reloadxml.sh');
    }

    private function generateExtensionFilePath($number)
	{
		return base_path() . $this->ext_folder . $number . '.xml';
	}
}