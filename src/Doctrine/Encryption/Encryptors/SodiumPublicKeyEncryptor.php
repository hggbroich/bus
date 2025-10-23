<?php

namespace App\Doctrine\Encryption\Encryptors;

use Exception;
use Override;
use Random\RandomException;
use SensitiveParameter;
use SodiumException;

/**
 * Thanks to David Gebler - the encryption/decryption code was taken from here: https://github.com/dwgebler/php-encryption
 * Also see https://davegebler.com/post/php/php-encryption-the-right-way-with-libsodium
 */
readonly class SodiumPublicKeyEncryptor implements EncryptorInterface {

    public function __construct(
        #[SensitiveParameter] private string $selfKeyPairBase64,
        #[SensitiveParameter] private string $otherPublicKeyBase64
    ) {

    }

    /**
     * @throws RandomException
     * @throws SodiumException
     */
    #[Override]
    public function encrypt(?string $value): ?string {
        if(empty($value)) {
            return null;
        }

        $selfKeyPair = base64_decode($this->selfKeyPairBase64);
        $selfPrivateKey = sodium_crypto_box_secretkey($selfKeyPair);
        $otherPublicKey = base64_decode($this->otherPublicKeyBase64);

        $key = sodium_crypto_box_keypair_from_secretkey_and_publickey($selfPrivateKey, $otherPublicKey);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_box($value, $nonce, $key);

        sodium_memzero($key);
        sodium_memzero($otherPublicKey);
        sodium_memzero($selfPrivateKey);

        $result = sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
        sodium_memzero($nonce);

        return $result;
    }

    /**
     * @throws SodiumException
     */
    #[Override]
    public function decrypt(?string $value, #[SensitiveParameter] string $otherPrivateKeyBase64): ?string {
        if(empty($value)) {
            return null;
        }

        $message = sodium_base642bin($value, SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce = mb_substr($message, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($message, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $selfKeyPair = base64_decode($this->selfKeyPairBase64);
        $selfPublicKey = sodium_crypto_box_publickey($selfKeyPair);

        $key = sodium_crypto_box_keypair_from_secretkey_and_publickey(
            base64_decode($otherPrivateKeyBase64),
            $selfPublicKey,
        );

        $plaintext = sodium_crypto_box_open($ciphertext, $nonce, $key);
        sodium_memzero($key);
        sodium_memzero($ciphertext);
        sodium_memzero($nonce);
        sodium_memzero($otherPrivateKeyBase64);

        if($plaintext === false) {
            throw new Exception('Decryption failed.');
        }

        return $plaintext;
    }
}
