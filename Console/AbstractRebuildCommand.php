<?php
namespace InfinityRedux\UrlOverride\Console;
use Exception;
use InfinityRedux\UrlOverride\Helper\CatalogSeoConfig;
use InfinityRedux\UrlOverride\Helper\UrlOverrideGeneralConfig;
use InfinityRedux\UrlOverride\Helper\UrlOverrideSuffixConfig;
use InfinityRedux\UrlOverride\Model\RebuildContext;
use Magento\Framework\App\State;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;


abstract class AbstractRebuildCommand extends Command
{
    protected CatalogSeoConfig $seoConfig;
    protected UrlOverrideGeneralConfig $generalConfig;
    protected UrlOverrideSuffixConfig $suffixConfig;
    protected UrlRewriteResource $rewriteResource;
    protected RebuildContext $context;
    protected State $state;

    public function __construct(CatalogSeoConfig         $seoConfig,
                                UrlOverrideGeneralConfig $generalConfig,
                                UrlOverrideSuffixConfig  $suffixConfig,
                                UrlRewriteResource       $rewriteResource,
                                RebuildContext           $context,
                                State                    $state,
                                string                   $name = null)
    {
        parent::__construct($name);
        $this->seoConfig = $seoConfig;
        $this->generalConfig = $generalConfig;
        $this->suffixConfig = $suffixConfig;
        $this->rewriteResource = $rewriteResource;
        $this->context = $context;
        $this->state = $state;
    }

    protected function writeTitle(string $message, OutputInterface $output): void {
        $output->writeln("<fg=yellow;options=underscore>$message</>");
    }

    protected function writeBlock(array $messages, OutputInterface $output) {
        $block = [ '' ];
        foreach ($messages as $key => $message) {
            if ($key === 0)
                $block[] = "<comment>$message</comment>";
            else
                $block[] = "<info>$message</info>";
        }
        $block[] = '';
        $output->writeln($block);
    }

    protected function handleError(string $message, OutputInterface $output, Exception $e = null) {
        $output->writeln("<error>$message</error>");
        if ($e !== null)
            $output->writeln($e->getMessage());
    }

    protected function getConnection(): AdapterInterface  {
        $connection = $this->rewriteResource->getConnection();
        if (!$connection) {
            $message = 'Unable to obtain direct database connection';
            throw new RuntimeException($message);
        }
        return $connection;
    }
}
