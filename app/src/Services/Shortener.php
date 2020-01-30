<?php

namespace App\Services;

class Shortener
{
    private $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";

    public function __construct()
    {
        $this->chars = str_split($this->chars);
    }

    /**
     * Validate the url
     *
     * @param string $url
     * @return boolean
     */
    public function validateUrlFormat($url): bool
    {
        return preg_match('(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})
        ',$url);
    }

    /**
     * Verify the URL is real and accessible
     *
     * @param string $url
     * @return boolean
     */
    public function verifyUrlExists($url): bool
    {
        self::validateUrlFormat($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    /**
     * Generate unique shortcode from integer value
     *
     * @param int $id
     * @return string
     */
    public function generateShortCode($id): string
    {
        if ($id == 0) return $this->chars[0];

        $result = [];

        $base = count($this->chars);

        while ($id > 0) {
            $result[] = $this->chars[($id % $base)];
            $id = floor($id / $base);
        }

        $result = array_reverse($result);

        return join("", $result);
    }
}
