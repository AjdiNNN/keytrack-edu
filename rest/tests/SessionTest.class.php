<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testSessionStart()
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
      $start = date("Y-m-d H:i:s", strtotime("now"));
      $end = date("Y-m-d H:i:s", strtotime("now + 2 hours"));

      $ch = curl_init();
      curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://keytrackedu.com/rest/session',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "start": "'.$start.'",
          "end" : "'.$end.'"
      }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: '.$token.'',
          'Content-Type: application/json'
        ),
      ));
      $r = curl_exec($ch);
      curl_close($ch);
      $data = json_decode($r, true);
      $this->assertEquals($start, $data['start']);
      $this->assertEquals($end, $data['end']);
  }
}
