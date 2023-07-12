<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class EncryptionController extends Controller
{
    //

    /**
     * Encrypts the data by communicating to ICT API.
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
                'amount' =>  $datos['amount'],
                'CustomerRef1' => $datos['CustomerRef1'],
                'ControlNumber' => $datos['CustomerRef1'],
                'BillTo_firstName' => $datos['BillTo_firstName'],
                'BillTo_lastName' => $datos['BillTo_lastName'],
                'BillTo_street' => $datos['BillTo_street'] ?? '',
                'BillTo_streetNumber' => $datos['BillTo_streetNumber'] ?? '',
                'BillTo_streetNumber2' => $datos['BillTo_streetNumber2'] ?? '',
                'BillTo_street2Col' => $datos['BillTo_street2Col'] ?? '',
                'BillTo_street2Del' => $datos['BillTo_street2Del'] ?? '',
                'BillTo_city' => $datos['BillTo_city'] ?? '',
                'BillTo_state' => $datos['BillTo_state'] ?? '',
                'BillTo_country' => $datos['BillTo_country'] ?? '',
                'BillTo_phoneNumber' => $datos['BillTo_phoneNumber'] ?? '',
                'BillTo_postalCode' => $datos['BillTo_postalCode'] ?? '',
                'BillTo_email' => $datos['BillTo_email'] ?? '',
                'MDDF_8' => $datos['MDDF_8'],
            ];


            $response = Http::post(env('ict_api') . '/cifrar-datos', $body);
            $apiStatus = $response->status();

            switch ($apiStatus) {
                case 401:
                case 500:
                    throw new \Exception("Error encriptando información: " . $response->json()['error'], $apiStatus);

                    break;

                case 400:
                    $errors = $response->json();
                    $errorMsg = "";

                    foreach ($errors as $key => $err) {
                        $errorMsg .= $err[0] . "<br> ";
                    }

                    throw new \Exception("Error encriptando información: " . $errorMsg, $apiStatus);
                    break;

                default:
                    return response()->json($response->json(), 200);
                    break;
            }
        } catch (\Throwable $th) {
            $errorCode = $th->getCode() == 0 ? 500 : $th->getCode();
            return response()->json(['error' => $th->getMessage()], $errorCode);
        }
    }


    /**
     * Dencrypts the data by communicating to ICT API.
     *
     * @return [
            'error' => bool,
            'message' => stringF
        ];
     *
     */
    public static function decrypt(Request $request)
    {
        try {

            $datos = $request->all();

            $body = [
                'usuario' => env('usuario', ''),
                'password' => env('password', ''),
                'token' => env('token', ''),
                'respuesta' =>  $datos['bank']
            ];


            $response = Http::post(env('ict_api') . '/descifrar-respuesta', $body);
            $apiStatus = $response->status();

            $json_response = $response->json();

            switch ($apiStatus) {
                case 401:
                case 500:
                    throw new \Exception("Error desencriptando información " . $json_response['error'], $apiStatus);

                    break;

                case 400:
                    $errors = $response->json();
                    $errorMsg = "";

                    foreach ($errors as $key => $err) {
                        $errorMsg .= $err[0] . "<br> ";
                    }

                    throw new \Exception("Error desencriptando información " . $errorMsg, $apiStatus);
                    break;
                case 200:

                    // valida que la llave no venga nula
                    if (is_null($json_response['datosDescifrados'])) {
                        throw new \Exception("Error desencriptando información. Datos nulos ", $apiStatus);
                        break;
                    }

                    $datos = $json_response['datosDescifrados'];

                    // verifica que la llave exista
                    if ((array_key_exists('resultadoPayw', $datos))) {

                        if ($datos['resultadoPayw'] == 'A') {
                            return response()->json([
                                'error' => false,
                                'message' => 'Operación realizada con éxito',
                            ], 200);
                        } else {
                            throw new \Exception($datos['texto'], 400);
                            break;
                        }
                    }

                    // verifica que la llave exista
                    if ((array_key_exists('resultadoAut', $datos))) {

                        if ($datos['resultadoAut'] == '00') {
                            return response()->json([
                                'error' => false,
                                'message' => 'Operación realizada con éxito',
                            ], 200);
                        } else {
                            throw new \Exception($datos['texto'], 400);
                            break;
                        }
                    }

                default:
                    throw new \Exception("Error de comunicación API: ", 500);
                    break;
            }
        } catch (\Throwable $th) {
            $errorCode = $th->getCode() == 0 ? 500 : $th->getCode();

            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], $errorCode);
        }
    }
}
