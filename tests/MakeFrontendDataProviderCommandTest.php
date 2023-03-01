<?php

namespace HiHaHo\LaravelJsStore\Tests;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeFrontendDataProviderCommandTest extends TestCase
{
    public function test_it_creates_a_data_provider(): void
    {
        $provider = app_path('Http/FrontendDataProviders/Test.php');

        $key = AbstractFrontendDataProvider::convertClassnameToKey('Test');

        if (File::exists($provider)) {
            unlink($provider);
        }

        $this->assertFalse(File::exists($provider));

        $expectedOutput = version_compare($this->app->version(), '9.32.0', '>=')
            ? 'FrontendDataProvider [app/Http/FrontendDataProviders/Test.php] created successfully.'
            : 'FrontendDataProvider created successfully.';

        $this->artisan('make:frontend-data-provider', ['name' => 'Test'])
            ->expectsOutputToContain($expectedOutput)
            ->expectsConfirmation("Generated key: $key, would you like to use a custom key?")
            ->assertExitCode(0)
            ->run();

        $this->assertTrue(File::exists($provider));

        $expectedContents = <<<CLASS
<?php

namespace App\Http\FrontendDataProviders;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class Test extends AbstractFrontendDataProvider
{
    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data()
    {
        // return 'my-data';
    }
}

CLASS;

        $this->assertSame($expectedContents, file_get_contents($provider));
    }

    public function test_it_should_set_key_when_custom_key_is_empty(): void
    {
        $provider = app_path('Http/FrontendDataProviders/Test.php');

        $key = AbstractFrontendDataProvider::convertClassnameToKey('Test');

        if (File::exists($provider)) {
            unlink($provider);
        }

        $this->assertFalse(File::exists($provider));

        $expectedOutput = version_compare($this->app->version(), '9.32.0', '>=')
            ? 'FrontendDataProvider [app/Http/FrontendDataProviders/Test.php] created successfully.'
            : 'FrontendDataProvider created successfully.';

        $this->artisan('make:frontend-data-provider', ['name' => 'Test'])
            ->expectsOutputToContain($expectedOutput)
            ->expectsConfirmation("Generated key: $key, would you like to use a custom key?", 'yes')
            ->expectsQuestion('Custom key', '')
            ->assertExitCode(0)
            ->run();

        $this->assertTrue(File::exists($provider));

        $expectedContents = <<<CLASS
<?php

namespace App\Http\FrontendDataProviders;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class Test extends AbstractFrontendDataProvider
{
    protected string \$key;

    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data()
    {
        // return 'my-data';
    }
}

CLASS;

        $this->assertSame($expectedContents, file_get_contents($provider));
    }

    public function test_it_creates_a_data_provider_with_custom_key(): void
    {
        $provider = app_path('Http/FrontendDataProviders/Test.php');

        $key = AbstractFrontendDataProvider::convertClassnameToKey('Test');

        if (File::exists($provider)) {
            unlink($provider);
        }

        $this->assertFalse(File::exists($provider));

        $expectedOutput = version_compare($this->app->version(), '9.32.0', '>=')
            ? 'FrontendDataProvider [app/Http/FrontendDataProviders/Test.php] created successfully.'
            : 'FrontendDataProvider created successfully.';

        $this->artisan('make:frontend-data-provider', ['name' => 'Test'])
            ->expectsOutputToContain($expectedOutput)
            ->expectsConfirmation("Generated key: $key, would you like to use a custom key?", 'yes')
            ->expectsQuestion('Custom key', 'custom-key')
            ->assertExitCode(0)
            ->run();

        $this->assertTrue(File::exists($provider));

        $expectedContents = <<<CLASS
<?php

namespace App\Http\FrontendDataProviders;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class Test extends AbstractFrontendDataProvider
{
    protected string \$key = 'custom-key';

    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data()
    {
        // return 'my-data';
    }
}

CLASS;

        $this->assertSame($expectedContents, file_get_contents($provider));
    }
}
