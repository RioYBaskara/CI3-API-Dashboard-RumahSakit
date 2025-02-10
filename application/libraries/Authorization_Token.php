<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Authorization_Token
 * ----------------------------------------------------------
 * API Token Generate/Validation
 * 
 * @author: Jeevan Lal (Improved by Rio)
 * @version: 0.0.2
 */

require_once APPPATH . 'third_party/php-jwt/JWT.php';
require_once APPPATH . 'third_party/php-jwt/BeforeValidException.php';
require_once APPPATH . 'third_party/php-jwt/ExpiredException.php';
require_once APPPATH . 'third_party/php-jwt/SignatureInvalidException.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class Authorization_Token
{
    /**
     * Token Key
     */
    protected $token_key;

    /**
     * Token algorithm
     */
    protected $token_algorithm;

    /**
     * Token Request Header Name
     */
    protected $token_header;

    /**
     * Token Expire Time
     */
    protected $token_expire_time;


    public function __construct()
    {
        $this->CI =& get_instance();

        /** 
         * jwt config file load
         */
        $this->CI->load->config('jwt');

        /**
         * Load Config Items Values 
         */
        $this->token_key = $this->CI->config->item('jwt_key');
        $this->token_algorithm = $this->CI->config->item('jwt_algorithm');
        $this->token_header = $this->CI->config->item('token_header');
        $this->token_expire_time = $this->CI->config->item('token_expire_time');
    }

    /**
     * Generate Token
     * @param: {array} data
     */
    public function generateToken($data = null)
    {
        if ($data && is_array($data)) {
            $data['API_TIME'] = time();

            try {
                return JWT::encode($data, $this->token_key, $this->token_algorithm);
            } catch (Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        } else {
            return ['status' => FALSE, 'message' => "Token Data Undefined!"];
        }
    }

    /**
     * Validate Token with Header
     * @return : user information or error message
     */
    public function validateToken()
    {
        /**
         * Get All Headers
         */
        $headers = $this->CI->input->request_headers();

        /**
         * Check If Authorization Header Exists
         */
        $token_data = $this->tokenIsExist($headers);
        if ($token_data['status'] === TRUE) {
            try {
                /**
                 * Decode Token
                 */
                try {
                    $token_decode = JWT::decode($token_data['token'], $this->token_key, [$this->token_algorithm]);
                } catch (ExpiredException $e) {
                    return ['status' => FALSE, 'message' => 'Token has expired.'];
                } catch (SignatureInvalidException $e) {
                    return ['status' => FALSE, 'message' => 'Invalid token signature.'];
                } catch (BeforeValidException $e) {
                    return ['status' => FALSE, 'message' => 'Token is not yet valid.'];
                } catch (Exception $e) {
                    return ['status' => FALSE, 'message' => 'Invalid token.'];
                }

                if (!empty($token_decode) && is_object($token_decode)) {
                    // Cek waktu API_TIME
                    if (empty($token_decode->API_TIME) || !is_numeric($token_decode->API_TIME)) {
                        return ['status' => FALSE, 'message' => 'Token Time Not Defined!'];
                    } else {
                        /**
                         * Check Token Expiry Time 
                         */
                        if (time() - $token_decode->API_TIME >= $this->token_expire_time) {
                            return ['status' => FALSE, 'message' => 'Token has expired.'];
                        } else {
                            /**
                             * Jika semua validasi berhasil, kembalikan data token
                             */
                            return ['status' => TRUE, 'data' => $token_decode];
                        }
                    }
                } else {
                    return ['status' => FALSE, 'message' => 'Invalid token format.'];
                }
            } catch (Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        } else {
            return ['status' => FALSE, 'message' => $token_data['message']];
        }
    }

    /**
     * Check If Token Exists in Headers
     * @param: request headers
     */
    private function tokenIsExist($headers)
    {
        if (!empty($headers) && is_array($headers)) {
            foreach ($headers as $header_name => $header_value) {
                if (strtolower(trim($header_name)) == strtolower(trim($this->token_header)))
                    return ['status' => TRUE, 'token' => $header_value];
            }
        }
        return ['status' => FALSE, 'message' => 'Authorization token not found.'];
    }

    /**
     * Get Token Data Without Validating Again
     * @return : token data or null
     */
    public function getTokenData()
    {
        $validation = $this->validateToken();
        return ($validation['status']) ? $validation['data'] : null;
    }
}