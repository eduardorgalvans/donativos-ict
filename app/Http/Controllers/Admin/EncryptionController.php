<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class EncryptionController extends Controller
{
    //

    /**
     * Encrypts the data by communicating with ICT API.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function encrypt(Request $request)
    {
        try {

            $datos = $request->all();

            $body = [
                'usuario' => env('usuario', ''),
                'password' => env('password', ''),
                'token' => env('token', ''),
                'amount' =>  floatval($datos['amount']),
                'CustomerRef1' => $datos['CustomerRef1'],
                'ControlNumber' => $datos['CustomerRef1'],
                'BillTo_firstName' => $datos['BillTo_firstName'],
                'BillTo_lastName' => $datos['BillTo_lastName'],
                'BillTo_street' => $datos['BillTo_street'],
                'BillTo_streetNumber' => $datos['BillTo_streetNumber'],
                'BillTo_streetNumber2' => $datos['BillTo_streetNumber2'],
                'BillTo_street2Col' => $datos['BillTo_street2Col'],
                'BillTo_street2Del' => $datos['BillTo_street2Del'],
                'BillTo_city' => $datos['BillTo_city'],
                'BillTo_state' => $datos['BillTo_state'],
                'BillTo_country' => $datos['BillTo_country'],
                'BillTo_phoneNumber' => $datos['BillTo_phoneNumber'],
                'BillTo_postalCode' => $datos['BillTo_postalCode'],
                'BillTo_email' => $datos['BillTo_email'],
                'MDDF_8' => $datos['MDDF_8'],
            ];


            $response = Http::post(env('ict_api') . '/cifrar-datos', $body);
            $apiStatus = $response->status();

            switch ($apiStatus) {
                case 401:
                case 500:
                    throw new \Exception("Error encriptando información: " . $response->json()['error']);

                    break;

                case 400:
                    $errors = $response->json();
                    $errorMsg = "";

                    foreach ($errors as $key => $err) {
                        $errorMsg .= $err[0] . "<br> ";
                    }

                    throw new \Exception("Error encriptando información: " . $errorMsg);
                    break;

                default:
                    return response()->json($response->json(), 200);
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $apiStatus);
        }
    }
}
