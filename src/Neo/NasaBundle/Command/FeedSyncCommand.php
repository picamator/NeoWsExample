<?php
namespace Neo\NasaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FeedSyncCommand extends ContainerAwareCommand
{
    /**
     * @see \Neo\NasaBundle\Model\Builder\SyncLogFactory
     * @var int max value is 7
     */
    private static $syncLastDay = 3;

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName('feed:sync')
            ->setDescription('Synchronize NEO Feed for last 3 Days');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        /** @var \Neo\NasaBundle\Service\SyncService $service */
        $service = $container->get('neo_nasa.service_sync');

        /** @var \Neo\NasaBundle\Model\Builder\SyncLogFactory $syncLogFactory */
        $syncLogFactory = $container->get('neo_nasa.model_builder_sync_log_factory');
        $syncLog = $syncLogFactory->create(self::$syncLastDay);

        $response = is_null($syncLog) ? 0 : $service->syncNeo($syncLog);

        // display result
        $data = [
            '===================================================',
            '       Synchronize NEO Feed for last 3 days        ',
            '===================================================',
            $response
                ? sprintf('<info>It was successfully synchronized "%d" NEOs<info>', $response)
                : '<comment>Nothing to synchronize. Probably application already has all necessary data.</comment>',
            ''
        ];

        $output->writeln($data);
    }
}
