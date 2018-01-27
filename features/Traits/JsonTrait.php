<?php declare(strict_types=1);

namespace BehatTests\Traits;

use RuntimeException;

trait JsonTrait
{
    /**
     * @param string $jsonString
     *
     * @return array
     */
    public function jsonDecode(string $jsonString): array
    {
        $array = json_decode($jsonString, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error();
            $message = json_last_error_msg();

            throw new RuntimeException('Error decode json data. Code: ' . $error . '; Message: ' . $message);
        }

        return $array;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public function prettyJsonEncode(array $array): string
    {
        return json_encode($array, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
    }
}
