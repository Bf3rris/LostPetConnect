<?php

require('vendor/autoload.php');
use SignalWire\Rest\Client;

  $client = new Client('21a6a9d2-411f-43ca-aff7-8608e66f40e4', 'PT4e8830a4c68a351df61924691b0ac27632ded267ea819be7', array("signalwireSpaceUrl" => "bf3rris.signalwire.com"));


  $media = $client->messages("ce849501-2e77-4673-bde7-400682537eac")
	  ->media
                  ->read();

 
      print_r($media);

?>
