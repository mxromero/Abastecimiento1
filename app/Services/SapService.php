<?php

namespace App\Services;

use SoapClient;

class SapService
{
    protected $client;
    protected $sapUser;
    protected $sapPassword;

    public function __construct()
    {
        $this->sapUser = env('SAP_USERNAME');
        $this->sapPassword = env('SAP_PASSWORD');
   }

    public function valida_ordenPrev(){

        $wsdl = env('WS_SAP_VALIDA_ORDENES_WSDL');

        $this->client = new SoapClient($wsdl, [
            'login' =>  $this->sapUser,
            'password' => $this->sapPassword,
            'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                ]
            ])
        ]);


    }

    public function obtenerDatos($parametros)
    {
        try {
            $this->valida_ordenPrev();

            $response = $this->client->__soapCall('ZppValidaOrdenes', [$parametros]);
            $responseArray = json_decode(json_encode($response), true);

            if (!empty($responseArray['LtMensaje']['Message'])) {
                $message = $responseArray['LtMensaje']['Message'];
                $messageType = $responseArray['LtMensaje']['Type'];

                if ($messageType == 'E') {
                    return [
                        'success' => false,
                        'message' => $message,
                        'data' => null
                    ];
                } elseif ($messageType == 'W') {
                    return [
                        'success' => true,
                        'message' => $message,
                        'data' => $responseArray
                    ];
                }
            }

            return [
                'success' => true,
                'message' => "Operación exitosa.",
                'data' => $responseArray['LtValido']
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al conectar con el servicio: revise los datos enviados',
                'data' => null
            ];
        }

    }
}
