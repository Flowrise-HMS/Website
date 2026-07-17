<?php

namespace Modules\Website\Tests\Unit;

use Modules\Website\Classes\Support\SafeUrl;
use Tests\TestCase;

class SafeUrlTest extends TestCase
{
    public function test_href_allows_safe_schemes_and_relative_paths(): void
    {
        $this->assertSame('https://example.com/page', SafeUrl::href('https://example.com/page'));
        $this->assertSame('http://example.com', SafeUrl::href('http://example.com'));
        $this->assertSame('mailto:help@example.com', SafeUrl::href('mailto:help@example.com'));
        $this->assertSame('tel:+233200000000', SafeUrl::href('tel:+233200000000'));
        $this->assertSame('/booking', SafeUrl::href('/booking'));
        $this->assertSame('#', SafeUrl::href('#'));
    }

    public function test_href_rejects_dangerous_schemes(): void
    {
        $this->assertSame('#', SafeUrl::href('javascript:alert(1)'));
        $this->assertSame('#', SafeUrl::href('data:text/html,hi'));
        $this->assertSame('#', SafeUrl::href('//evil.example'));
        $this->assertSame('/safe', SafeUrl::href('javascript:alert(1)', '/safe'));
    }

    public function test_media_rejects_non_http_schemes(): void
    {
        $this->assertSame('/fallback.jpg', SafeUrl::media('javascript:alert(1)', '/fallback.jpg'));
        $this->assertSame('https://cdn.example/img.jpg', SafeUrl::media('https://cdn.example/img.jpg', '/fallback.jpg'));
        $this->assertSame('/storage/photo.jpg', SafeUrl::media('/storage/photo.jpg', '/fallback.jpg'));
    }
}
