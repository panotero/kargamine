<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SafeText
{
    /**
     * Allowed characters:
     * - letters, numbers
     * - spaces
     * - , . ' " - @ &
     */
    protected string $pattern = '/^[a-zA-Z0-9][a-zA-Z0-9 ,.\'"-@&]*$/';

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldValidate($request)) {
            $invalidFields = [];

            foreach ($this->flatten($request->all()) as $key => $value) {
                if (is_string($value) && !$this->isSafe($value)) {
                    $invalidFields[] = $key;
                }
            }

            if (!empty($invalidFields)) {
                return response()->json([
                    'message' => 'Invalid characters detected in request.',
                    'fields'  => $invalidFields,
                ], 422);
            }
        }

        return $next($request);
    }

    protected function shouldValidate(Request $request): bool
    {
        return $request->isMethod('post')
            || $request->isMethod('put')
            || $request->isMethod('patch');
    }

    protected function isSafe(string $value): bool
    {
        return preg_match($this->pattern, $value) === 1;
    }

    protected function flatten(array $array, string $prefix = ''): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $results += $this->flatten($value, $fullKey);
            } else {
                $results[$fullKey] = $value;
            }
        }

        return $results;
    }
}
