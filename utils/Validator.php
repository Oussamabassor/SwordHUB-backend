<?php

class Validator {
    public static function validateRequired($data, $fields) {
        $errors = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }
        return $errors;
    }

    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        return null;
    }

    public static function validateNumber($value, $min = null, $max = null) {
        if (!is_numeric($value)) {
            return "Must be a number";
        }
        
        if ($min !== null && $value < $min) {
            return "Must be at least " . $min;
        }
        
        if ($max !== null && $value > $max) {
            return "Must be at most " . $max;
        }
        
        return null;
    }

    public static function validateEnum($value, $allowedValues) {
        if (!in_array($value, $allowedValues)) {
            return "Invalid value. Allowed values: " . implode(', ', $allowedValues);
        }
        return null;
    }

    public static function sanitizeString($string) {
        return htmlspecialchars(strip_tags(trim($string)));
    }

    public static function sanitizeArray($data, $fields) {
        $sanitized = [];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $sanitized[$field] = self::sanitizeString($data[$field]);
            }
        }
        return $sanitized;
    }
}
