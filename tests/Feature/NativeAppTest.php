<?php

declare(strict_types=1);

use function Pest\Laravel\post;

describe('off the device', function () {
    it('does not expose the native bridge', function (string $uri) {
        post($uri)->assertNotFound();
    })->with(['_native/api/call', '_native/api/events']);
});
