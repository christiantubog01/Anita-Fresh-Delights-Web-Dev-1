<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SocketService
{
    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function emitOrderUpdate(
        int $orderId,
        string $status
    ): void {

        try {

            $this->client->request(
                'POST',
                'https://websocket-appdev.onrender.com/order-update',
                [
                    'json' => [
                        'orderId' => $orderId,
                        'status' => $status,
                    ],
                ]
            );

        } catch (\Throwable $e) {

            // optional logging
        }
    }
}