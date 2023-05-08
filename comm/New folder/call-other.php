<?php

require('vendor/autoload.php');
use \SignalWire\LaML\VoiceResponse;
use Generator as Coroutine;

class CustomConsumer extends SignalWire\Relay\Consumer {
  public $project = '21a6a9d2-411f-43ca-aff7-8608e66f40e4';
  public $token = 'PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7';
  public $contexts = ['default'];


 public function ready(): Coroutine {
    $params = ['type' => 'phone', 'from' => '+19374539293', 'to' => '+19374431724'];
    $dialResult = yield $this->client->calling->dial($params);
    if (!$dialResult->isSuccessful()) {
      echo "Dial error or call not answered by the remote peer..";
      return;
    }
    $call = $dialResult->getCall();
    $promptParams = [
      'type' => 'digits',
      'digits_max' => 6,
      'digits_terminators' => '#',
      'text' => 'This is a call from Lost Pet Connect. Please enter your 6-digit code.'
    ];
    $promptResult = yield $call->promptTTS($promptParams);
    if ($promptResult->isSuccessful()) {
      $pin = $promptResult->getResult();

		
    }
  }
}

$consumer = new CustomConsumer();
$consumer->run();


	


?>