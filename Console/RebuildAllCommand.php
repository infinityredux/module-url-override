<?php
namespace InfinityRedux\UrlOverride\Console;
use Exception;
use InfinityRedux\UrlOverride\Operations\RemovePathSuffixOperation;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RebuildAllCommand extends AbstractRebuildCommand
{
    protected function configure()
    {
        $this->setName('infinityredux:rebuild:all');
        $this->setAliases(['ir:rebuild:all']);
        $this->setDescription('Rebuild the url rewrites for products and categories.');
        $this->setHelp(
            'Performs multiple (low level) database operations to ensure ' .
            'that all existing url rewrites are correct (either altering or ' .
            'removing any that are not) and then create any that are missing. ' .
            'This includes carrying out suffix modifications according to the ' .
            'current module configuration.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->generalConfig->isEnabled())
        {
            $message = 'Url override is not currently enabled.';
            $this->handleError($message, $output);
            return 25;
        }

        if (    !$this->generalConfig->isProductEnabled()
            or  !$this->generalConfig->isCategoryEnabled())
        {
            $message = 'Updating all urls requires both product and category to be enabled.';
            $this->handleError($message, $output);
            return 25;
        }

        if (    $this->seoConfig->isCategorySuffixActive()
            or  $this->seoConfig->isProductSuffixActive())
        {
            $message = 'Magento suffix is not empty.';
            $this->handleError($message, $output);
            return 25;
        }

        try {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
            $connection = $this->getConnection();
        }
        catch (LocalizedException $e) {
            $message = 'Unable to update state, before processing begins.';
            $this->handleError($message, $output, $e);
            return 50;
        }
        catch (RuntimeException $e) {
            $message = 'Unable to obtain database connection.';
            $this->handleError($message, $output, $e);
            return 50;
        }

        $this->writeTitle('Rebuilding All Url Rewrites...', $output);
        $connection->beginTransaction();

        try {
            $pathSuffix = RemovePathSuffixOperation::execute($this->context, $connection);

            $connection->commit();
            $this->writeBlock([
                'Processing existing rewrites to remove suffixes.',
                "Updated $pathSuffix path records",
                "Updated 0 autogenerated category rewrite records.",
                "Updated 0 autogenerated product rewrite records.",
                "Updated 0 category-prefix product redirect records.",
                "Updated 0 duplicated category rewrite records.",
                "Updated 0 duplicated product rewrite records.",
            ], $output);
        }
        catch (Exception $e) {
            $connection->rollBack();
            $message = 'Error while processing existing rewrites.';
            $this->handleError($message, $output, $e);
            return 99;
        }

        return 0;
    }
}
