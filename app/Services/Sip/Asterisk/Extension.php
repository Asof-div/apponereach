<?php

namespace App\Services\Sip\Asterisk;

class Extension
{
	private $extension = 'conf';
	private $template_file = 'sip_extension/asterisk/template/config.txt';
	private $config_file_path = 'sip_extension/asterisk/';

	public function generate($country_code)
	{
		$extension = $this->generateExtension();
		$password = $this->generatePassword();

		$config_template = file_get_contents($this->getTemplateFile());
		$config = str_replace(['[extension]', '[password]'], [$extension, $password], $config_template);

		$filename = strtolower($country_code) . '.' . $this->extension;
		$file_path = $this->getConfigFilePath() . $filename;

		$fh = fopen($file_path, 'a+');
		fwrite($fh, $config);
		fwrite($fh, "\r\n");
		fclose($fh);

		return ['extension' => $extension, 'password' => $password];
	}

	public function getTemplateFile()
	{
		return base_path($this->template_file);
	}

	public function getConfigFilePath()
	{
		return base_path($this->config_file_path);
	}

	private function generateExtension()
	{
		return '12345';
	}

	private function generatePassword()
	{
		return '67890';
	}
}