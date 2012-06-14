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

        $this->clearCache($input, $output);
        // $this->dumpAssetic($input, $output);
        // $this->dumpAssets($input, $output);
        $this->dumpUrls($input, $output);
    }

    private function clearCache(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->get('cache:clear');

        $command->run(new ArrayInput(array(
            'command'        => $command->getName(),
            '--no-warmup'   => true,
        )), $output);
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
        $visited    = array();
        $routes     = array('/');

        do {
            // Get next route
            $route      = array_shift($routes);

            // Track cached route
            $visited[]  = $route;

            // Crawl cached route for more internal links
            $crawler    = $this->client->request('GET', $route);
            $hrefs      = array_filter($crawler->filter('a')->extract('href'));
            $internal   = array_filter($hrefs, function($href) {
                return substr($href, 0, 4) !== 'http';
            });

            // Add internal links onto stack of routes
            $routes     += array_diff($internal, $visited, $routes);
        } while ($routes);

        foreach ($visited as $route) {
            $response   = $this->cache->handle(Request::create($route, 'GET'));
            $date       = $response->getLastModified() ?: $response->getDate() ?: new \DateTime();

            $output->writeln(sprintf(
                '<comment>%s</comment> <info>[file+]</info> %s',
                $date->format('H:i:s'),
                $route
            ));
        }
    }
}
