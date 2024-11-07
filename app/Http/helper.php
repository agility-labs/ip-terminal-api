<?php

function calculateChecksum(string $data): string {
    $calc = 0;

    for ($r = 0; $r < strlen($data); $r++) {
        if ($data[$r] === '*' && $r > 0 && $data[$r - 1] === ';') {
            break;
        }

        $calc ^= ord($data[$r]);
    }

    return strtoupper(str_pad(dechex($calc), 2, '0', STR_PAD_LEFT));
}
