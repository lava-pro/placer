<?php

namespace Lava\Placer;

use Illuminate\Support\Facades\File;

class Placer
{
    /**
     * Package vendor namespace
     *
     * @var string
     */
    protected $vendor = 'Lava';

    /**
     * Package name
     *
     * @var string
     */
    protected $package;

    /**
     * Get the package vendor namespace
     *
     * @return string
     */
    public function vendor()
    {
        return $this->vendor;
    }

    /**
     * Set or get the package name
     *
     * @param  string $package
     * @return string
     */
    public function package($package = null)
    {
        if ($package === null)
            return $this->package;

        return $this->package = $package;
    }

    /**
     * Get the path to the packages directory
     *
     * @return string
     */
    public function getBasePath()
    {
        return base_path('packages');
    }

    /**
     * Get the full package path
     *
     * @return string
     */
    public function getPackagePath()
    {
        return $this->getBasePath() . '/' . strtolower($this->package());
    }

    /**
     * Check if the package already exists
     *
     * @return string|null
     */
    public function checkIfPackageExists()
    {
        return realpath($this->getPackagePath());
    }

    /**
     * Copy stencil files
     *
     * @return void
     */
    public function copyStencilFiles()
    {

        $destination = $this->getPackagePath();
        $directory   = __DIR__ . '/../stencils';

        return File::copyDirectory($directory, $destination);
    }

    /**
     * Populate stencil files
     *
     * @return void
     **/
    public function renameFiles()
    {
        $bindings = [
            [':uc:package', ':lc:package'],
            [$this->package(), strtolower($this->package())],
        ];

        $requires = [
            'src/StencilPackage.php'                => 'src/:uc:package.php',
            'config/StencilPackage.php'             => 'config/:lc:package.php',
            'src/Facades/StencilPackage.php'        => 'src/Facades/:uc:package.php',
            'src/StencilPackageServiceProvider.php' => 'src/:uc:packageServiceProvider.php',
        ];

        foreach ($requires as $file => $name)
        {
            $filename = str_replace($bindings[0], $bindings[1], $name);
            rename($this->getPackagePath() . '/' . $file, $this->getPackagePath() . '/' . $filename);
        }
    }

}
