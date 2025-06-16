<?php

$code = $params['code'] ?? null;

if (!$code || !is_numeric($code)) abort();

if (!in_array($code, [403, 404, 500])) abort(500);

view("StatusCodes/{$code}");