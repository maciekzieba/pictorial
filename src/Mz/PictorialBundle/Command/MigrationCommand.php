<?php

namespace Mz\PictorialBundle\Command;

use Mz\McpBundle\Entity\Catalog;
use Mz\McpBundle\Entity\Contract;
use Mz\McpBundle\Entity\Partner;
use Mz\McpBundle\Entity\Todo;
use Mz\McpBundle\Entity\User;
use Mz\McpBundle\Service\CatalogService;
use Mz\McpBundle\Service\ContractService;
use Mz\McpBundle\Service\MailingService;
use Mz\McpBundle\Service\TodoService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\DomCrawler\Crawler;


class MigrationCommand extends ContainerAwareCommand
{
    /**
     * @var int
     */
    private $timeLimit = 92000;

    /**
     * @var string
     */
    private $memoryLimit = '28G';

    /** @var \Doctrine\DBAL\Connection $connection */
    private $connection;

    protected function configure()
    {
        $this
            ->setName('pictorial:migration')
            ->setDescription('Migration operations')
        ;

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit($this->timeLimit);
        ini_set('memory_limit', $this->memoryLimit);

        $container = $this->getContainer();
        /**
         * @var RegistryInterface
         */
        $doctrine = $container->get('doctrine');
        /**
         * @var \Doctrine\DBAL\Connection
         */
        $this->connection = $doctrine->getConnection();

        $data = $this->connection->fetchAll("SELECT *  FROM visit");
        foreach ($data as $row) {


            if ($row['scounting_owner']) {
                $price = $this->getPrice($row['scounting_owner'], 2);
                $this->createVisitCost($row['id'], $row['scounting_owner'], 2, $price);
            }
            if ($row['photo_owner']) {
                $price = $this->getPrice($row['photo_owner'], 3);
                $this->createVisitCost($row['id'], $row['photo_owner'], 3, $price);
            }
            if ($row['interview_owner']) {
                $price = $this->getPrice($row['interview_owner'], 4);
                $this->createVisitCost($row['id'], $row['interview_owner'], 4, $price);
            }
            if ($row['postproduction_owner']) {
                $price = $this->getPrice($row['postproduction_owner'], 5);
                $this->createVisitCost($row['id'], $row['postproduction_owner'], 5, $price);
            }
            if ($row['editing_owner']) {
                $price = $this->getPrice($row['editing_owner'], 6);
                $this->createVisitCost($row['id'], $row['editing_owner'], 6, $price);
            }
            if ($row['provision_owner']) {
                $price = $this->getPrice($row['provision_owner'], 7);
                $this->createVisitCost($row['id'], $row['provision_owner'], 7, $price);
            }

        }

    }

    protected function getPrice($userId, $roleId)
    {
        $row = $this->connection->fetchAssoc("SELECT price  FROM pricelist WHERE user = $userId AND visit_role = $roleId LIMIT 1");
        if (is_array($row)) {
            return $row['price'];
        }
        return 0;
    }


    protected function createVisitCost($visitId, $userId, $roleId, $price)
    {
        $insertArr = array(
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'user' => $userId,
            'visit_role' => $roleId,
            'visit' => $visitId,
            'price' => $price
        );
        $this->connection->insert('visit_cost', $insertArr);
        return $this->connection->lastInsertId();
    }




}