<?php
namespace Acme\DemoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
//use Blameable\Fixture\Document\User;

/**
 * @author wendell.zheng <wxzheng@ustc.edu.cn>
 */
class ImportUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:import:user')->setDescription('import users');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $appPath = $container->get('kernel')->getRootDir();
        $dataPath = $appPath."/../data";
        $source = $dataPath."/users.csv";

        $codes = array();
        if (($handle = fopen($source, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $codes[] = $data;
            }
            fclose($handle);
        }

        $codeJson = $dataPath."/codes.json";
        file_put_contents($codeJson, json_encode($codes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
