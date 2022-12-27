<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class ValidPpdJson implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (! is_string($value)) {
            $fail('validation.json_ppd')->translate(['opt' => 'STRING']);

            return;
        }

        $json = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $fail('validation.json_ppd')->translate(['opt' => 'JSON']);

            return;
        }

        if (! is_array($json)) {
            $fail('validation.json_ppd')->translate(['opt' => 'ARRAY']);

            return;
        }

        foreach ($json as $option_name => $option) {
            if (! is_string($option_name)) {
                $fail('validation.json_ppd')->translate(['opt' => 'OPTION_KEY']);

                return;
            }

            $keys = array_combine(array_keys($option), array_keys($option));

            foreach (['key', 'name', 'values', 'default', 'enabled', 'order', 'group_key', 'group_name'] as $key) {
                if (! isset($keys[$key])) {
                    $fail('validation.json_ppd')->translate(['opt' => 'OPTION_MISSING_KEY']);

                    return;
                }

                unset($keys[$key]);
            }

            if (isset($keys['type'])) {
                unset($keys['type']);
            }

            if (count($keys) !== 0) {
                $fail('validation.json_ppd')->translate(['opt' => 'OPTION_EXTRA_KEY']);

                return;
            }

            if (
                ! is_string($option['key'])
                || ! is_string($option['name'])
                || ! is_string($option['default'])
                || ! is_string($option['group_key'])
                || ! is_string($option['group_name'])
                || ! is_int($option['order'])
                || ! is_array($option['values'])
                || ! is_bool($option['enabled'])
            ) {
                $fail('validation.json_ppd')->translate(['opt' => 'OPTION_TYPE']);

                return;
            }

            foreach ($option['values'] as $value_key => $o) {
                $fail('validation.json_ppd')->translate(['opt' => 'VALUE_KEY']);
                if (! is_string($value_key) && ! is_int($value_key)) {
                    return;
                }

                $val_keys = array_combine(array_keys($o), array_keys($o));

                foreach (['key', 'name', 'order', 'enabled'] as $key) {
                    if (! isset($val_keys[$key])) {
                        $fail('validation.json_ppd')->translate(['opt' => 'VALUE_MISSING_KEY']);

                        return;
                    }

                    unset($val_keys[$key]);
                }

                if (count($val_keys) !== 0) {
                    $fail('validation.json_ppd')->translate(['opt' => 'VALUE_EXTRA_KEY']);

                    return;
                }

                if (
                    ! is_string($o['key']) && ! is_int($o['key'])
                    || ! is_string($o['name'])
                    || ! is_int($o['order'])
                    || ! is_bool($o['enabled'])
                ) {
                    $fail('validation.json_ppd')->translate(['opt' => 'VALUE_TYPE']);

                    return;
                }
            }
        }
    }
}
