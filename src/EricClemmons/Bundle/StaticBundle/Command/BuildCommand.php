<?php

namespace EricClemmons\Bundle\StaticBundle\Command;

use AppKernel;
use EricClemmons\Bundle\StaticBundle\HttpCache\StaticHttpCache;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class BuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('static:build')
            ->setDescription('Build static version of the site')
            ->setDefinition(array(
                new InputOption('relative', '', InputOption::VALUE_NONE, 'Strip <comment>/</comment> prefix from relative <info>href</info> attributes')
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel         = $this->getContainer()->get('kernel');
        $cacheKernel    = new AppKernel($kernel->getEnvironment(), $kernel->isDebug());

        $this->client   = $this->getContainer()->get('static.client');
        $this->cache    = new StaticHttpCache($cacheKernel);

        $this->dumpAssetic($input, $output);
        $this->dumpAssets($input, $output);
        $this->dumpUrls($input, $output);
    }

    private function dumpAssetic(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->get('assetic:dump');

        $command->run(new ArrayInput(array(
            'command'   => $command->getName(),
            'write_to'  => $this->cache->getStore()->getRoot(),
        )), $output);
    }

    private function dumpAssets(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->get('assets:install');

        $command->run(new ArrayInput(array(
            'command'   => $command->getName(),
            'target'    => $this->cache->getStore()->getRoot(),
        )), $output);
    }

    private function dumpUrls(InputInterface $input, OutputInterface $output)
    {
        $route      = '/';
        $response   = $this->cache->handle(Request::create($route, 'GET'));
        $date       = $response->getLastModified() ?: $response->getDate();

        $output->writeln(sprintf(
            '<comment>%s</comment> <info>[file+]</info> %s',
            $date->format('H:i:s'),
            $route
        ));

        $crawler    = $this->client->request('GET', $route);
        // var_dump($crawler->filter('a')->extract('href'));
    }
}
