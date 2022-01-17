<?php declare(strict_types=1);

namespace NZTim\ORM\Laravel;

use Illuminate\Console\Command;
use ReflectionClass;

class AddPersistenceCommand extends Command
{
    protected $signature = 'orm:persistence {class}';
    protected $description = 'Add Persistence folder with boilerplate for specified class';

    public function handle()
    {
        $class = strval($this->argument('class'));
        $reflector = new ReflectionClass($class);
        $namespace = $reflector->getNamespaceName() . '\Persistence';
        $shortClassName = $reflector->getShortName();
        $dir = pathinfo($reflector->getFileName(), PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . 'Persistence';
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $hydrator = file_get_contents(__DIR__ . '/hydrator.stub');
        $hydrator = str_replace('%%NAMESPACE%%', $namespace, $hydrator);
        $hydrator = str_replace('%%CLASS%%', $class, $hydrator);
        $hydratorName = $shortClassName . 'Hydrator';
        $hydrator = str_replace('%%HNAME%%', $hydratorName, $hydrator);
        $hydrator = str_replace('%%SHORTCLASS%%', $shortClassName, $hydrator);
        $hydratorFullPath = $dir . DIRECTORY_SEPARATOR . $hydratorName . '.php';
        if (!file_exists($hydratorFullPath)) {
            file_put_contents($hydratorFullPath, $hydrator);
        }
        //
        $repoName = $shortClassName . 'Repo';
        $repo = file_get_contents(__DIR__ . '/repo.stub');
        $repo = str_replace('%%NAMESPACE%%', $namespace, $repo);
        $repo = str_replace('%%CLASS%%', $class, $repo);
        $repo = str_replace('%%RNAME%%', $repoName, $repo);
        $repo = str_replace('%%HNAME%%', $hydratorName, $repo);
        $repo = str_replace('%%TABLENAME%%', str_plural(strtolower($shortClassName)), $repo);
        $repo = str_replace('%%SHORTCLASS%%', $shortClassName, $repo);
        $repoFullPath = $dir . DIRECTORY_SEPARATOR . $repoName . '.php';
        if (!file_exists($repoFullPath)) {
            file_put_contents($repoFullPath, $repo);
        }
    }
}
