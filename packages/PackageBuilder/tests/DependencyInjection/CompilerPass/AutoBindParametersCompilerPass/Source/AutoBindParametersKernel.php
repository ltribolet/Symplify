<?php declare(strict_types=1);

namespace Symplify\PackageBuilder\Tests\DependencyInjection\CompilerPass\AutoBindParametersCompilerPass\Source;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symplify\AutoBindParameter\DependencyInjection\CompilerPass\AutoBindParameterCompilerPass;
use Symplify\PackageBuilder\HttpKernel\SimpleKernelTrait;

final class AutoBindParametersKernel extends Kernel
{
    use SimpleKernelTrait;

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config.yml');
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new AutoBindParameterCompilerPass());
    }
}
