<?php
namespace App\Services\Task;

use PhpImap\Mailbox as ImapMailbox;

use App\Models\User;
use App\Models\TaskEmail;
use File;
use EmailReplyParser\Parser\EmailParser;

class MailFetcher
{
	private $server = 'smtp.telvida.com';
	private $mailbox;
    private $taskService;

	public function __construct(TaskService $taskService)
	{
		$this->taskService = $taskService;
	}

	public function fetch()
	{
        $emails = TaskEmail::all();

        foreach ($emails as $email) {
            $mails = $this->getMails($this->server, $email->email, $email->password);

            if (!$mails) {
                continue;
            }

            for ($i = 0; $i < count($mails); $i++) {
                $mail = $this->mailbox->getMail($mails[$i]);
                $this->processMail($email, $mail);
                $this->delete($mails[$i]);
            }

            File::cleanDirectory(storage_path('app/mail_attachments'));
        }
	}

	public function getMails($server, $email, $password, $port="993")
	{
		$attachment_path = storage_path('app/mail_attachments');

		$this->mailbox = new ImapMailbox('{'.$server.':'.$port.'/imap/ssl/novalidate-cert}INBOX', $email, $password, $attachment_path, 'utf-8');

        return $this->mailbox->searchMailbox('ALL');
    }

    private function processMail($task_email, $mail)
    {
        $subject = $this->getSubject($mail);
    	$sender_email = $this->getSenderEmail($mail);
        $sender_name = $this->getSenderName($mail);
    	$body = $this->getMailBody($mail);
        $cc = $this->getMailCC($mail);
        $recipients = $this->getMailRecipients($mail);

        if (starts_with($sender_email, 'postmaster') || starts_with($sender_name, 'postmaster')) {
            return;
        }

        $sender = User::whereHas('groups', function ($group) {
            $group->where('name', 'admin')->orWhere('name', 'agent');
        })->where('email', $sender_email)->first();

        if (is_null($sender)) return;

        $this->taskService->createTaskFromEmail($task_email, $subject, $sender, $recipients, $body);

        return;
    }

    private function getSenderEmail($mail)
    {
        $email = $mail->fromAddress;

        if (stripos($email, 'googlemail')) {
            $email = str_replace('googlemail', 'gmail', $email);
        }
        return trim($email);
    }

    private function getSenderName($mail)
    {
        $name = trim($mail->fromName);

        if (!empty($name)) {
            return $name;
        } else {
            return $this->getSenderEmail($mail);
        }
    }

    private function getSubject($mail)
    {
        return trim($mail->subject);
    }

    private function getMailBody($mail)
    {
        if ($mail->textPlain) {
            return $mail->textPlain;
        } else {
            $d = new \DOMDocument;
            $mock = new \DOMDocument;

            libxml_use_internal_errors(true);
            $d->loadHTML($mail->textHtml);
            $body = $d->getElementsByTagName('body')->item(0);
            foreach ($body->childNodes as $child){
                $mock->appendChild($mock->importNode($child, true));
            }

            $sig = $mock->getElementById('Signature');
            if ($sig) {
                $sig->parentNode->removeChild($sig);
            }

            $html = $mock->saveHTML();
            $html = preg_replace('/\n|\r\n?/', '', $html);
            $html = str_replace('<br>', '', $html);
            return $html;
        }
    }

    public function getMailRecipients($mail)
    {
        return array_keys($mail->to);
    }

    public function getMailCC($mail)
    {
        $cc = [];

        if (is_array($mail->cc) && !empty($mail->cc)) {
            foreach ($mail->cc as $email => $name) {
                array_push($cc, $email);
            }
        }

        return $cc;
    }

    public function delete($mail_id)
    {
    	$this->mailbox->deleteMail($mail_id);
    }
}