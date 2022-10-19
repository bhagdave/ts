<?php
return [
  "driver" => env('MAIL_DRIVER'),
  "host" => env('MAIL_HOST'),
  "port" => env('MAIL_PORT'),
  "from" => array(
      "address" => env('MAIL_FROM_ADDRESS'),
      "name" => env('MAIL_FROM_NAME'),
  ),
  "username" => env('MAIL_USERNAME'),
  "password" => env('MAIL_PASSWORD'),
  "sendmail" => "/usr/sbin/sendmail -bs"
];