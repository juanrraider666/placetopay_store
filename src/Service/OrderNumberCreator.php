<?php

namespace App\Service;

class OrderNumberCreator
{

    /**
     * @var string Unique, random, cryptographically secure string
     */
    private $signingKey;


    public function __construct(string $signingKey)
    {
        $this->signingKey = $signingKey;
    }

    /**
     * Get a cryptographically secure token with it's non-hashed components.
     *
     * @param string $short Only required for token comparison
     * @return string
     */
    public function createToken(string $short = null)
    {
        if (null === $short) {
            return $this->getRandomAlphaNumStr();
        }

        $encodedData = \json_encode([uniqid(), $short]);

        return \str_replace(['/','+', '='], '', $this->getHashedToken($encodedData));
    }

    private function getHashedToken(string $data): string
    {
        return \base64_encode(\hash_hmac('sha256', $data, $this->signingKey, true));
    }


    /**
     *
     * String length is 20 characters
     */
    public function getRandomAlphaNumStr(): string
    {
        $string = '';

        while (($len = \strlen($string)) < 10) {
            $size = 10 - $len;

            $bytes = \random_bytes($size);

            $string .= \substr(
                \str_replace(['/','+', '='], '', \base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}