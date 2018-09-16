<?php

require_once('./line/line_class.php');
require_once('./config.php');

include "./Math/EvalMath.php";
include "./Math/Stack.php";


$client = new LINEBot($channelAccessToken, $channelSecret);

date_default_timezone_set('Asia/Jakarta');
$jam=date("H:i:s");

$userId         = $client->parseEvents()[0]['source']['userId'];
$replyToken     = $client->parseEvents()[0]['replyToken'];
$timestamp      = $client->parseEvents()[0]['timestamp'];
$message        = $client->parseEvents()[0]['message'];
$messageid      = $client->parseEvents()[0]['message']['id'];
$profil         = $client->profil($userId);
$msg_receive   = $message['text'];
$type 		= $client->parseEvents()[0]['type'];

$pesan_datang = explode(" ", $message['text']);
$options = $pesan_datang[1];

function img_search($keyword) {
    $uri = 'https://www.google.co.id/search?q=' . $keyword . '&safe=off&source=lnms&tbm=isch';
    $response = Unirest\Request::get("$uri");
    $hasil = str_replace(">", "&gt;", $response->raw_body);
    $arrays = explode("<", $hasil);
    return explode('"', $arrays[291])[3];
}

if ($type == 'join'){
	$balas = array(
		'replyToken' => $replyToken,                                                        
		'messages' => array(
			array(
				'type' => 'flex',
				'altText' => 'fanisa cantid',
				'contents' =>
					array(
						'type' => 'bubble',
						'body' =>
							array(
								'type' => 'box',
								'layout' => 'vertical',
								'contents' =>
									array(
										0 =>
											array(
												'type' => 'text',
												'text' => '[Jam: '. $jam.']'
											),
										1 =>
											array(
												'type' => 'text',
												'text' => 'Thx udh di invite ke grup ini kakak:)'
											)
									)
							)
					)
			)
		)
	);

	$client->replyMessage($balas);
}

if($message['type']=='text'){

	$msg_xpl = explode(" ", $msg_receive);
	$keyword = $msg_xpl[0];

	if($keyword=='/menu') {

		$balas = array(
			'replyToken' => $replyToken,                                                        
			'messages' => array(
				array(
					'type' => 'text',                   
					'text' => 'Perintah :
					/menu : menampilkan menu
					/hitung 3+3 : kalkulator'
				)
			)
		);

		$client->replyMessage($balas);

	}elseif($keyword=='/hitung'){

		$m = new EvalMath;
		$result = $m->evaluate($msg_xpl[1]);

		$balas = array(
			'replyToken' => $replyToken,                                                        
			'messages' => array(
				array(
					'type' => 'text',                   
					'text' => $result
				)
			)
		);

		$client->replyMessage($balas);
	}elseif($keyword=='/help'){

		$balas = array(
			'replyToken' => $replyToken,                                                        
			'messages' => array(
				array(
					'type' => 'flex',
					'altText' => 'fanisa cantid',
					'contents' =>
						array(
							'type' => 'bubble',
							'body' =>
								array(
									'type' => 'box',
									'layout' => 'vertical',
									'contents' =>
										array(
											0 =>
												array(
													'type' => 'text',
													'text' => '[Command List]'
												),
											1 =>
												array(
													'type' => 'text',
													'text' => '> /help'
												),
											2 =>
												array(
													'type' => 'text',
													'text' => '> /hitung'
												),
											3 =>
												array(
													'type' => 'text',
													'text' => '> /menu'
												),
										),
								),
						),
				),
			),
		);
		$client->replyMessage($balas);
	}elseif($keyword=='tes'){
		$balas = array(
			'replyToken' => $replyToken,                                                        
			'messages' => array(
				array(
					'type' => 'text',                   
					'text' => 'tis'
				),
			),
		);
		$client->replyMessage($balas);
	}elseif($keyword=='imgsearch'){
		$hasil = img_search($options);
		$balas = array(
			'replyToken' => $replyToken,
			'messages' => array(
				array(
					'type' => 'image',
					'originalContentUrl' => $hasil,
					'previewImageUrl' => $hasil
				)
			)
		);
		$client->replyMessage($balas);
	}
}
//*fitur auto send message*//

if (isset($msg)) {
    $result = json_encode($msg);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);

    $client->replyMessage($balas);
}
?>