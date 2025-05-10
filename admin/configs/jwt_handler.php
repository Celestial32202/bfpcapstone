<?php
require_once realpath(__DIR__ . '/../../vendor/autoload.php');
require_once realpath(__DIR__ . '/../config-sec.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

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

    public static function decodeForgotResetToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return (array) $decoded; // Convert stdClass to array
        } catch (\Firebase\JWT\ExpiredException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }


    public static function decodeResetToken($token, $conn)
    {

        try {
            // Decode the JWT
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            $data = json_decode(json_encode($decoded), true); // Convert to an array

            if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            $stmt = $conn->prepare("SELECT verified FROM admin_creds WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $data['email']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return false; // Email not found
            }
            $user = $result->fetch_assoc();
            if ((int)$user['verified'] === 1) {
                return false; // Already verified, block reset
            }
            return $data;
        } catch (ExpiredException $e) {
            // 👇 Manually decode token WITHOUT verifying exp
            $payload = JWT::jsonDecode(JWT::urlsafeB64Decode(explode('.', $token)[1]));

            if (isset($payload->email)) {
                $email = $payload->email;

                // Update your DB to flag this email's token as expired
                $stmt = $conn->prepare("UPDATE admin_creds SET expiredToken = 1 WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->close();
            }

            return false;
        } catch (Exception $e) {

            return false;
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