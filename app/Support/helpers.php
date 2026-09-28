<?php
// Global helper functions — mirrors includes/functions.php from the old app.
// Register this file in composer.json:
//   "autoload": { "files": ["app/Support/helpers.php"] }
// then run: composer dump-autoload

if (!function_exists('format_date')) {
    function format_date($date): string
    {
        if (!$date) return '—';
        return \Illuminate\Support\Carbon::parse($date)->format('F j, Y');
    }
}

if (!function_exists('qr_code_url')) {
    function qr_code_url(string $data, int $size = 200): string
    {
        // Same free QR Server API used by the original app — no local QR
        // library/dependency needed. Requires outbound internet access.
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($data);
    }
}

if (!function_exists('time_ago')) {
    function time_ago(string $datetime): string
    {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return date('M j', strtotime($datetime));
    }
}

if (!function_exists('generate_code')) {
    function generate_code(string $prefix = 'AGL'): string
    {
        return strtoupper($prefix . '-' . bin2hex(random_bytes(4)) . '-' . date('Y'));
    }
}

if (!function_exists('al_initials')) {
    function al_initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $initials .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $initials ?: '?';
    }
}
