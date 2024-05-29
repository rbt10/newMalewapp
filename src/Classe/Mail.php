<?php
namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;


class Mail
{
    public function send($to_email,$to_name,$subject,$template, $vars=null)
    {
        //Recuperation du template

        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template);
        $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);

// Define your request body

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "ronytula@gmail.com",
                        'Name' => "Malewapp"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID'=>6009104,
                    'TemplateLanguage'=> true,
                    'Subject' => $subject,
                    'Variables'=>[
                        'content'=>$content
                    ]
                ]
            ]
        ];

    }
}