<?php
namespace InfinityRedux\UrlOverride\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RebuildProductsCommand extends AbstractRebuildCommand
{
    protected function configure()
    {
        $this->setName('infinityredux:rebuild:products');
        $this->setAliases(['ir:rebuild:products']);
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
        $this->handleError('Not implemented yet', $output);
        return -1;
    }
}
