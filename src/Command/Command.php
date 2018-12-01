<?php

namespace Project\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Project\Entities\Category;

class Telegram extends \Knp\Command\Command {

    protected function configure() {
        $this
                ->setName('command')
                ->setDescription('Example console command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $app = $this->getSilexApplication();

        $category = new Category();
        $category->setName('category_name');

        $app['orm.em']->persist($category);
        $app['orm.em']->flush();
    }
}