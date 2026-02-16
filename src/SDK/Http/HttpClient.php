<?php
namespace Vp\VaultsPay\SDK\Http;

class HttpClient
{
    public static function postJson(string $url, array $payload, ?string $token = null): array
    {
        return self::request($url, json_encode($payload), 'application/json', $token);
    }

    public static function postForm(string $url, array $payload): array
    {
        return self::request($url, http_build_query($payload), 'application/x-www-form-urlencoded');
    }

    private static function request(string $url, string $body, string $contentType, ?string $token = null): array
    {
        $headers = ['Content-Type: ' . $contentType];

        if ($token) {
            $headers[] = 'accessToken: ' . $token;
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            throw new \Exception("VaultsPay connection error: " . $error);
        }

        if ($status < 200 || $status >= 300) {
            throw new \Exception("VaultsPay HTTP $status: " . $response);
        }

        $decoded = json_decode($response, true);

        if (!$decoded) {
            throw new \Exception("Invalid response: " . $response);
        }

        return $decoded;
    }
}
