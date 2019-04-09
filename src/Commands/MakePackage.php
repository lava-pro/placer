<?php

namespace Lava\Placer\Commands;

use Lava\Placer\Placer;
use Lava\Placer\Replacer;
use Lava\Placer\ProgressBar;
use Illuminate\Console\Command;

class MakePackage extends Command
{
    use ProgressBar;

    /**
     * Package signature
     *
     * @var string
     */
    protected $signature = 'placer:make-package {name}';

    /**
     * Package description
     *
     * @var string
     */
    protected $description = 'Create new Lava package';

    /**
     * Package placer
     *
     * @var object \Lava\Placer\Placer
     */
    protected $placer;

    /**
     * Replacer
     *
     * @var object \Lava\Placer\Replacer
     */
    protected $replacer;

    /**
     * Create a new command instance
     *
     * @return void
     */
    public function __construct(Placer $placer, Replacer $replacer)
    {
        parent::__construct();

        $this->placer   = $placer;
        $this->replacer = $replacer;
    }

    /**
     * Execute the console command
     *
     * @return mixed
     */
    public function handle()
    {
        $this->startProgressBar(4);
        $this->info('Preparation...');
        $vendor  = $this->placer->vendor();
        $package = $this->placer->package($this->argument('name'));
        $this->makeProgress();

        $this->info('Checking if exists package: ' . $vendor . '\\' . $package . '...');
        if ($this->placer->checkIfPackageExists())
            return $this->error('Package named "packages\\' . strtolower($package) . '"  already exists!');
        $this->makeProgress();

        $this->info('Generating package files...');
        $this->placer->copyStencilFiles();
        $this->placer->renameFiles();
        $this->makeProgress();

        $this->info('Population package files...');
        $options = $this->laravel['config']->get('placer');
        $this->replacer->setReplacements(
            [':uc:package', ':lc:package', ':title'],
            [$package, strtolower($package), $options['title']]
        );
        $this->replacer->fill($this->placer->getPackagePath());
        $this->makeProgress();
        $this->finishProgress('Package created successfully!');
    }

}
