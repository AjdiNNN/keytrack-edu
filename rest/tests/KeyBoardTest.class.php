<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class KeyboardTest extends TestCase
{
    public function testKeyPressAdd()
    {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://keytrackedu.com/rest/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "username": "testingUser",
          "password": "test123"
      }
      ',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      $responseData = json_decode($response, true);
      $token = $responseData['token'];
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://keytrackedu.com/rest/usersessions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.$token.''
        ),
      ));
      $response = curl_exec($curl);
      $dataResponse = json_decode($response, true);

      $pressed = "a";
      $pressedAt = date("Y-m-d H:i:s", strtotime("now"));
      $special = 0;

      $ch = curl_init();
      curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://keytrackedu.com/rest/keyboard',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "pressed": "'.$pressed.'",
          "pressedAt" : "'.$pressedAt.'",
          "special" : "'.$special.'",
          "session_id" : "'.$dataResponse[0]['id'].'"
      }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.$token.'',
          'Content-Type: application/json'
        ),
      ));
      $r = curl_exec($ch);
      curl_close($ch);
      $data = json_decode($r, true);
      $this->assertEquals($pressed, $data['pressed']);
      $this->assertEquals($pressedAt, $data['pressedAt']);
      $this->assertEquals($special, $data['special']);
  }
}
