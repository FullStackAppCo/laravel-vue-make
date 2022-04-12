<?php

use FullStackAppCo\VueMake\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VueComponentCommandTest extends TestCase {

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    public function test_it_requires_name_parameter()
    {
        $this->expectErrorMessage('missing: "name"');

        Artisan::call('make:vue');
    }

    public function test_it_checks_if_component_already_exists()
    {
        $this->mock(Filesystem::class, function(MockInterface $mock) {
            $filepath = resource_path('js/components/TestComponent.vue');

            // Stubs.
            $mock->allows([
                'isDirectory' => true,
                'get' => 'template content',
                'put' => 23,
            ]);

            // Behaviour under test.
            $mock->shouldReceive('exists')
                ->with($filepath)
                ->once()
                ->andReturn(false);

            // The method is subsequently called
            // but this is not the call under test.
            $mock->shouldReceive('exists')->once();
        });

        $result = Artisan::call('make:vue', ['name' => 'TestComponent']);
        $this->assertSame(0, $result);
    }

    public function test_it_creates_directories()
    {
        $this->mock(Filesystem::class, function(MockInterface $mock) {

            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => false,
                'get' => 'template content',
                'put' => 23,
            ]);

            $dirpath = resource_path('js/components/foo/bar');

            $mock->shouldReceive('makeDirectory')
                ->withArgs([$dirpath, 0777, true, true])
                ->once()
                ->andReturn(true);
        });

        $result = Artisan::call('make:vue', ['name' => 'foo/bar/TestComponent']);
        $this->assertSame(0, $result);
    }

    public function test_it_retrieves_stub()
    {
        $this->mock(Filesystem::class, function(MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'put' => 890,
            ]);

            $mock->shouldReceive('get')
                ->with(realpath(__DIR__ . '/../stubs/vue.stub'))
                ->once();
        });

        $result = Artisan::call('make:vue', ['name' => 'TestComponent']);
        $this->assertSame(0, $result);
    }

    public function test_it_writes_component()
    {
        $this->mock(Filesystem::class, function(MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'get' => 'template content',
            ]);

            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/TestComponent.vue'), 'template content'])
                ->once();
        });

        $result = Artisan::call('make:vue', ['name' => 'TestComponent']);
        $this->assertSame(0, $result);
    }


    public function test_it_uses_overridden_stubs()
    {
        File::deleteDirectory(base_path('stubs'));
        File::deleteDirectory(resource_path('js/components'));

        $stubPath = base_path('stubs/vue.stub');
        File::makeDirectory(dirname($stubPath));
        File::put($stubPath, 'Overridden stub');

        $result = Artisan::call('make:vue', ['name' => 'TestComponent']);

        $this->assertSame(0, $result);
        $this->assertSame('Overridden stub', File::get(resource_path('js/components/TestComponent.vue')));
    }

    public function test_it_publishes_stubs()
    {
        File::deleteDirectory(base_path('stubs'));
        File::deleteDirectory(resource_path('js/components'));

        $result = Artisan::call('vendor:publish', ['--tag' => 'vue-stub']);

        $this->assertSame(0, $result);

        foreach (ServiceProvider::stubs() as $stub) {
            $this->assertTrue(File::exists(base_path("stubs/{$stub}")), "Stub {$stub} was not published.");
        }
    }

    public function test_it_correctly_replaces_dummy_component()
    {
        $this->mock(Filesystem::class, function(MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'get' => 'DummyComponent',
            ]);

            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/sub/dir/TestComponent.vue'), 'TestComponent'])
                ->once();
        });

        $result = Artisan::call('make:vue', ['name' => 'sub/dir/TestComponent']);
        $this->assertSame(0, $result);
    }
}
