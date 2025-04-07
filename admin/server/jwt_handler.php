<?php
require_once realpath(__DIR__ . '/../../vendor/autoload.php');
require_once realpath(__DIR__ . '/../config-sec.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private static $secretKey = JWT_SECRET;
    private static $algorithm = 'HS256';

    // Encode Data into JWT Token
    public static function encode($data, $expiry = 3600)
    {
        if (!is_array($data)) {
            $data = ["incident_id" => $data]; // ✅ Convert string to array
        }
        $payload = array_merge($data, ["exp" => time() + $expiry]);
        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }
    // Decode JWT Token
    public static function decode($token)
    {
        try {
            return JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
        } catch (Exception $e) {
            return null; // Invalid token
        }
    }
    public static function encodeResetToken($email, $expiry) // 1 hour expiration
    {
        $payload = [
            "email" => $email,
            "exp" => time() + $expiry
        ];
        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }


    public static function decodeResetToken($token, $conn)
    {

        try {
            // Check if the token exists in the blacklist (even if it's within an hour)
            $stmt = $conn->prepare("SELECT token FROM token_blacklist WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                return false; // Token is blacklisted
            }

            // Decode the JWT
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return json_decode(json_encode($decoded), true); // Convert to an array

        } catch (Exception $e) {
            return false; // Invalid or expired token
        }
    }

    public static function incident_encode($incident_id, $latitude, $longitude)
    {
        $payload = [
            'incident_data' => [
                'incident_id' => $incident_id,
                'latitude' => $latitude,
                'longitude' => $longitude
            ],
            'iat' => time(), // Issued at time
            'exp' => time() + (60 * 60 * 24 * 365) // Set expiration to 1 year (never expires)
        ];

        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }
    public static function incident_decode($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));

            if (!isset($decoded->incident_data)) {
                return null;
            }
            return [
                'incident_id' => $decoded->incident_data->incident_id ?? null,
                'latitude' => $decoded->incident_data->latitude ?? null,
                'longitude' => $decoded->incident_data->longitude ?? null
            ];
        } catch (Exception $e) {
            return null; // Return null if decoding fails
        }
    }
}