<?php

namespace App\Services;

use SoapClient;

class SapService
{
    protected $client;

    public function __construct()
    {
        $wsdl = env('WS_SAP_VALIDA_ORDENES_WSDL');
        $sapUser = env('SAP_USERNAME');
        $sapPassword = env('SAP_PASSWORD');

        $this->client = new SoapClient($wsdl, [
            'login' =>  $sapUser,
            'password' => $sapPassword,
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
            $response = $this->client->__soapCall('ZppValidaOrdenes', [$parametros]);
            $responseArray = json_decode(json_encode($response), true);

            if (!empty($responseArray['LtMensaje']['Message'])) {
                $message = $responseArray['LtMensaje']['Message'];
                $messageType = $responseArray['LtMensaje']['Type'];

                if ($messageType == 'E') {
                    return [
                        'success' => false,
                        'message' => "Error en SAP: " . $message,
                        'data' => null
                    ];
                } elseif ($messageType == 'W') {
                    return [
                        'success' => true,
                        'message' => "Advertencia en SAP: " . $message,
                        'data' => $responseArray
                    ];
                }
            }

            return [
                'success' => true,
                'message' => "Operación exitosa.",
                'data' => $responseArray
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al conectar con el servicio: ' . $e->getMessage(),
                'data' => null
            ];
        }

    }
}
