<?php
namespace InfinityRedux\UrlOverride\Console;
use Exception;
use InfinityRedux\UrlOverride\Helper\CatalogSeoConfig;
use InfinityRedux\UrlOverride\Helper\UrlOverrideGeneralConfig;
use InfinityRedux\UrlOverride\Helper\UrlOverrideSuffixConfig;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;


abstract class AbstractRebuildCommand extends Command
{
    protected CatalogSeoConfig $seoConfig;
    protected UrlOverrideGeneralConfig $generalConfig;
    protected UrlOverrideSuffixConfig $suffixConfig;
    protected State $state;

    public function __construct(CatalogSeoConfig         $seoConfig,
                                UrlOverrideGeneralConfig $generalConfig,
                                UrlOverrideSuffixConfig  $suffixConfig,
                                State                    $state,
                                string                   $name = null)
    {
        parent::__construct($name);
        $this->seoConfig = $seoConfig;
        $this->generalConfig = $generalConfig;
        $this->suffixConfig = $suffixConfig;
        $this->state = $state;
    }

    protected function handleError(string $message, OutputInterface $output, Exception $e = null) {
        $output->writeln("<error>$message</error>");
        if ($e !== null)
            $output->writeln($e->getMessage());
    }
}
