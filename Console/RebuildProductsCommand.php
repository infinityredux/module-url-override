<?php
namespace InfinityRedux\UrlOverride\Console;
use InfinityRedux\UrlOverride\Helper\CatalogSeoConfig;
use InfinityRedux\UrlOverride\Helper\UrlOverrideGeneralConfig;
use InfinityRedux\UrlOverride\Helper\UrlOverrideSuffixConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RebuildProductsCommand extends Command
{
    private CatalogSeoConfig $seoConfig;
    private UrlOverrideGeneralConfig $generalConfig;
    private UrlOverrideSuffixConfig $suffixConfig;

    public function __construct(CatalogSeoConfig         $seoConfig,
                                UrlOverrideGeneralConfig $generalConfig,
                                UrlOverrideSuffixConfig  $suffixConfig,
                                string                   $name = null)
    {
        parent::__construct($name);
        $this->seoConfig = $seoConfig;
        $this->generalConfig = $generalConfig;
        $this->suffixConfig = $suffixConfig;
    }

    protected function configure()
    {
        $this->setName('infinityredux:url-override:rebuild:product');
        $this->setAliases([
            'ir:url-override:rebuild:product',
            'infinityredux:rebuild:product',
            'ir:rebuild:product',
        ]);
        $this->setDescription('Rebuild the url rewrites for products only.');
        $this->setHelp(
            "Performs multiple (low level) database operations to ensure " .
            "that all existing url rewrites are correct (either altering or " .
            "removing any that are not) and then create any that are missing.\n" .
            "This includes carrying out suffix modifications according to the " .
            "current module configuration."
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}
