<?php

namespace Jiannius\Scaffold\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'scaffold:install
                            {module?* : The module to install. Can be "auth}"
                            {--force : Force file overwrite}';

    protected $description = 'Install scaffolding';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modules = ['core', 'auth'];
        $force = $this->option('force');

        if ($this->argument('module')) $modules = array_merge(['core'], $this->argument('module'));

        foreach ($modules as $module) {
            $this->newLine();
            $this->comment("🚀 Installing \"$module\" module");
            $this->newLine();

            $args = ['--tag' => 'scaffold-' . $module];

            if ($force) $args['--force'] = true;

            $this->call('vendor:publish', $args);

            // publishing for dependencies
            if ($module === 'core') {
                $this->call('vendor:publish', [
                    '--provider' => 'Torann\GeoIP\GeoIPServiceProvider',
                    '--tag' => 'config',
                ]);
            }

            if ($module === 'auth') {
                $this->call('vendor:publish', [
                    '--provider' => 'Laravel\Sanctum\SanctumServiceProvider',
                ]);
            }

            $this->newLine(2);
        }
    }
}