<?php 

namespace Ramyakant\ProductManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;


use Pimcore\Model\DataObject\Product;
use Ramyakant\ProductManagementBundle\Service\ProductManagementService;

#[AsCommand( name: 'update:product', description: 'Create or Update product through command!', aliases: ['manage:product'])]
class UpdateProductCommand extends Command
{
    private ProductManagementService $productManagementService;

    public function __construct(ProductManagementService $productManagementService)
    {
        parent::__construct();
        $this->productManagementService = $productManagementService;
    }

    /**
     * This method configures the command's name, description, and any arguments/options.
     */
    protected function configure(): void
    {
        // Arguments
        $this
            ->addArgument('sku', InputArgument::REQUIRED, 'Product SKU')
            ->addArgument('name', InputArgument::REQUIRED, 'Product name')
            ->addArgument('price', InputArgument::REQUIRED, 'Product price');
    }

    /**
     * This method is executed when the command is run.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        try {
            $sku = $input->getArgument('sku');
            $name = $input->getArgument('name');
            $price = (float) $input->getArgument('price');

            $result = $this->productManagementService->createOrUpdateProduct($sku, $name, $price);
            $product = $result['product'];
            $operation = $result['operation'];

            $io->success(sprintf( 'Product with SKU %s %s.', $sku, $operation ));

            $io->table(
                ['ID', 'SKU', 'Name', 'Price'],
                [[$product->getId(), $product->getSku(), $product->getName(), $product->getPrice()]]
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Please provice all arguments!');
            return Command::FAILURE;
        }
    }
}