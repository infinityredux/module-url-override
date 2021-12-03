<?php
namespace InfinityRedux\UrlOverride\Console;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RebuildAllCommand extends AbstractRebuildCommand
{
    protected function configure()
    {
        $this->setName('infinityredux:url-override:rebuild:all');
        $this->setAliases([
            'ir:url-override:rebuild:all',
            'infinityredux:rebuild:all',
            'ir:rebuild:all',
            'infinityredux:rebuild',
            'ir:rebuild',
        ]);
        $this->setDescription('Rebuild the url rewrites for products and categories.');
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
        if (!$this->generalConfig->isEnabled())
        {
            $message = 'Url override is not currently enabled.';
            $this->handleError($message, $output);
            return 50;
        }

        if (    !$this->generalConfig->isProductEnabled()
            or  !$this->generalConfig->isCategoryEnabled())
        {
            $message = 'Updating all urls requires both product and category to be enabled.';
            $this->handleError($message, $output);
            return 50;
        }

        try {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
        }
        catch (LocalizedException $e) {
            $message = 'Unable to update state, before processing begins.';
            $this->handleError($message, $output, $e);
            return 99;
        }



        return 0;
    }
}
