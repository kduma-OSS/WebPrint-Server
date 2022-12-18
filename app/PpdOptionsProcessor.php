<?php

namespace App;

class PpdOptionsProcessor
{
    public function __construct()
    {
    }

    public function isValid(array $options): bool
    {
        foreach ($options as $option) {
            if (! isset($option['key'])) {
                return false;
            }

            if (! isset($option['name'])) {
                return false;
            }

            if (! isset($option['values'])) {
                return false;
            }

            if (! isset($option['default'])) {
                return false;
            }

            if (! isset($option['enabled'])) {
                return false;
            }

            if (! isset($option['order'])) {
                return false;
            }

            if (! isset($option['group_key'])) {
                return false;
            }

            if (! isset($option['group_name'])) {
                return false;
            }

            foreach ($option['values'] as $value) {
                if (! isset($value['key'])) {
                    return false;
                }

                if (! isset($value['name'])) {
                    return false;
                }

                if (! isset($value['enabled'])) {
                    return false;
                }

                if (! isset($value['order'])) {
                    return false;
                }
            }
        }

        return true;
    }

    public function upgrade(array $options): array
    {
        foreach ($options as $option_key => &$option) {
            if (! isset($option['key'])) {
                $option = ['key' => $option_key] + $option;
            }

            foreach ($option['values'] as $value_key => &$value) {
                if (! isset($value['key'])) {
                    $value = ['key' => $value_key] + $value;
                }
            }
        }

        return $options;
    }
}
