<?php
namespace SafePHP;

/**
 * Sanitize value on input (safe method)
 */
class Sanitize {
    /**
     * Sanitize an input with a type
     * @param mixed $value value of the input once the form submitted
     * @param string $type type of the output excpected
     * @return mixed
     */
    public static function sanitize(mixed $value, string $type): mixed {
        if ($value === null) {
            return null;
        }

        return match ($type) {

            'email' => filter_var(
                trim($value),
                FILTER_SANITIZE_EMAIL
            ),

            'int' => filter_var(
                $value,
                FILTER_VALIDATE_INT
            ),

            'float' => filter_var(
                str_replace(',', '.', $value),
                FILTER_VALIDATE_FLOAT
            ),

            'string' => trim(
                strip_tags($value)
            ),

            'text' => htmlspecialchars(
                trim($value),
                ENT_QUOTES,
                'UTF-8'
            ),

            'bool' => filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN
            ),
            default => null,
        };
    }
}