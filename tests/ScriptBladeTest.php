<?php

namespace HiHaHo\LaravelJsStore\Tests;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class ScriptBladeTest extends TestCase
{
    private const JSON_SCRIPT_PATTERN = '/<script[^>]*type="application\/json"[^>]*>(.*?)<\/script>/s';

    public function test_renders_application_json_script_tag(): void
    {
        $html = view('index')->render();

        $this->assertMatchesRegularExpression(self::JSON_SCRIPT_PATTERN, $html);
    }

    public function test_simple_payload_round_trips(): void
    {
        $this->store->put('user', ['id' => 1, 'name' => 'Alice']);

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame(['id' => 1, 'name' => 'Alice'], $decoded['user']);
    }

    public function test_preserves_literal_double_quotes_in_string_values(): void
    {
        $this->store->put('text', '<p style="text-align:center">"do not top load" pallet</p>');

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame('<p style="text-align:center">"do not top load" pallet</p>', $decoded['text']);
    }

    public function test_preserves_literal_single_quotes_in_string_values(): void
    {
        $this->store->put('text', "it's a 'test' value");

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame("it's a 'test' value", $decoded['text']);
    }

    public function test_escapes_closing_script_tag_in_payload(): void
    {
        $payload = 'user content containing </script><script>alert(1)</script>';

        $this->store->put('injection', $payload);

        $html = view('index')->render();

        // The HTML must not be terminated early: the raw payload must not appear verbatim
        // inside the JSON script block.
        $this->assertStringNotContainsString('</script><script>alert(1)', $html);

        $decoded = $this->extractStoreData($html);
        $this->assertSame($payload, $decoded['injection']);
    }

    public function test_preserves_unicode_characters(): void
    {
        $this->store->put('text', '💩 € naïve — 你好');

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame('💩 € naïve — 你好', $decoded['text']);
    }

    public function test_preserves_html_special_characters(): void
    {
        $this->store->put('html', '<div class="foo">a & b</div>');

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame('<div class="foo">a & b</div>', $decoded['html']);
    }

    public function test_preserves_backslashes_and_newlines(): void
    {
        $this->store->put('text', "line1\\u0022\nline2\\nline3");

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame("line1\\u0022\nline2\\nline3", $decoded['text']);
    }

    public function test_handles_nested_structures_with_mixed_quotes(): void
    {
        $payload = [
            'interactions' => [
                [
                    'id' => 98849,
                    'text' => '<p style="x">"quoted"</p>',
                    'style' => ['color' => '#FFF', 'label' => "it's fine"],
                ],
            ],
        ];

        $this->store->put('video', $payload);

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame($payload, $decoded['video']);
    }

    public function test_json_script_block_contains_no_raw_closing_tag(): void
    {
        $this->store->put('payload', '</script>');

        $html = view('index')->render();

        preg_match(self::JSON_SCRIPT_PATTERN, $html, $matches);
        $jsonBody = $matches[1] ?? '';

        $this->assertStringNotContainsString('</script>', $jsonBody);
        $this->assertStringContainsString('\u003C', $jsonBody);
    }

    public function test_remove_data_true_emits_cleanup_calls(): void
    {
        config()->set('js-store.remove-data', true);

        $html = view('index')->render();

        $this->assertStringContainsString('dataEl.remove()', $html);
    }

    public function test_remove_data_false_skips_cleanup_calls(): void
    {
        config()->set('js-store.remove-data', false);

        $html = view('index')->render();

        $this->assertStringNotContainsString('dataEl.remove()', $html);
    }

    public function test_window_element_from_config_is_used(): void
    {
        config()->set('js-store.window-element', '__MY_CUSTOM_STATE__');

        $html = view('index')->render();

        $this->assertStringContainsString('window.__MY_CUSTOM_STATE__ = JSON.parse', $html);
    }

    public function test_empty_store_renders_valid_json(): void
    {
        config()->set('js-store.data-providers', []);
        $this->store->flushShared();

        $html = view('index')->render();

        $decoded = $this->extractStoreData($html);
        $this->assertSame([], $decoded);
    }

    public function test_multiple_invocations_render_distinct_script_ids(): void
    {
        $this->store->put('foo', 'bar');

        $html = view('index')->render().view('index')->render();

        preg_match_all('/id="laravel-js-store-data-([A-Za-z0-9]+)"/', $html, $matches);

        $this->assertCount(2, $matches[1]);
        $this->assertNotSame($matches[1][0], $matches[1][1]);
    }

    public function test_arrayable_payload_is_serialized(): void
    {
        $this->store->put('arrayable', new class implements Arrayable
        {
            public function toArray(): array
            {
                return ['id' => 42, 'text' => '<p style="x">"quoted"</p>'];
            }
        });

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame(['id' => 42, 'text' => '<p style="x">"quoted"</p>'], $decoded['arrayable']);
    }

    public function test_jsonable_payload_is_serialized(): void
    {
        $this->store->put('jsonable', new class implements Jsonable
        {
            public function toJson($options = 0): string
            {
                return json_encode(['label' => "it's \"quoted\""]);
            }
        });

        $decoded = $this->extractStoreData(view('index')->render());

        $this->assertSame(['label' => "it's \"quoted\""], $decoded['jsonable']);
    }

    public function test_unicode_is_not_escaped_in_output(): void
    {
        $this->store->put('text', 'café 你好');

        $html = view('index')->render();

        preg_match(self::JSON_SCRIPT_PATTERN, $html, $matches);
        $this->assertStringContainsString('café 你好', $matches[1]);
        $this->assertStringNotContainsString('\u00e9', $matches[1]);
    }

    /**
     * Extracts the JSON payload from the rendered view and decodes it.
     *
     * @return array<string, mixed>
     */
    private function extractStoreData(string $html): array
    {
        $this->assertMatchesRegularExpression(self::JSON_SCRIPT_PATTERN, $html);

        preg_match(self::JSON_SCRIPT_PATTERN, $html, $matches);

        return json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
    }
}
