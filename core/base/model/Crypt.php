<?php

namespace core\base\model;


use core\base\controller\Singletone;

class Crypt
{

    use Singletone;

    private $cryptMethod = 'AES-128-CBC';
    private $hashAlgorithm = 'sha256';
    private $hashLength = 32;

    public function encrypt($str)
    {

        $ivlen = openssl_cipher_iv_length($this->cryptMethod);

        $iv = openssl_random_pseudo_bytes($ivlen);

        $cipherText = openssl_encrypt($str, $this->cryptMethod, CRYPT_KEY, OPENSSL_RAW_DATA, $iv);

        $hmac = hash_hmac($this->hashAlgorithm, $cipherText, CRYPT_KEY, true);

        /*return base64_encode($iv . $hmac . $cipherText);*/

        $cipherText_comb = '112233445566778899';
        $iv_comb = 'abcdefghijklmnop';
        $hmac_comb = '00000000000000000000000000000000';

        $res = $this->cryptCombine($cipherText_comb, $iv_comb, $hmac_comb);

        $crypt_data = $this->cryptUnCombine($res, $ivlen);

        exit();
    }

    public function decrypt($str)
    {
        $crypt_str = base64_decode($str);

        $ivlen = openssl_cipher_iv_length($this->cryptMethod);

        $iv = substr($crypt_str, 0, $ivlen);

        $hmac = substr($crypt_str, $ivlen, $this->hashLength);

        $cipherText = substr($crypt_str, $ivlen + $this->hashLength);

        $original_plaintext = openssl_decrypt($cipherText, $this->cryptMethod, CRYPT_KEY, OPENSSL_RAW_DATA, $iv);

        $calcmac = hash_hmac($this->hashAlgorithm, $cipherText, CRYPT_KEY, true);

        if (hash_equals($hmac, $calcmac)) return $original_plaintext;// с PHP 5.6+ сравнение, не подверженное атаке по времени

        return false;

    }

    public function cryptCombine($str, $iv, $hmac)
    {
        $new_str = '';

        $str_len = strlen($str);

        $counter = (int)ceil(strlen(CRYPT_KEY) / ($str_len + $this->hashLength));

        $progress = 1;

        if ($counter >= $str_len) $counter = 1;

        for ($i = 0; $i < $str_len; $i++) {

            if ($counter < $str_len) {

                if ($counter === $i) {

                    $new_str .= substr($iv, $progress - 1, 1);
                    $progress++;
                    $counter += $progress;

                }

            } else {
                break;
            }

            $new_str .= substr($str, $i, 1);

        }

        $new_str .= substr($str, $i);
        $new_str .= substr($iv, $progress - 1);

        $new_str_half = (int)ceil(strlen($new_str) / 2);

        $new_str = substr($new_str, 0, $new_str_half) . $hmac . substr($new_str, $new_str_half);


        return base64_encode($new_str);
    }

    protected function cryptUnCombine($str, $ivlen)
    {
        $crypt_data = [];

        $str = base64_decode($str);

        $hash_position = (int)ceil(strlen($str) / 2 - $this->hashLength / 2);

        $crypt_data['hmac'] = substr($str, $hash_position, $this->hashLength);

        $str = str_replace($crypt_data['hmac'], '', $str);

        return $crypt_data;

    }

}