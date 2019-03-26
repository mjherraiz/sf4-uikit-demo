<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Advertising;
use Doctrine\Common\Collections\ArrayCollection;
use Faker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class PopulateCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:populate';

    protected function configure()
    {
        $this
            ->setDescription('Populate application')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $io = new SymfonyStyle($input, $output);
        
        $admin=$this->createUser(true);
        $entityManager->persist($admin);
        for ($i=1; $i < 100; $i++) {
            $user=$this->createUser();
            $entityManager->persist($user);
        }
        for ($i=1; $i < 10; $i++) {
            $tag= new Tag();
            $tag->setName('tag-'.$i);
            $entityManager->persist($tag);
        }
        $entityManager->flush();
        $tags = $this->getContainer()->get('doctrine')->getRepository(Tag::class)->findAll();
        for ($i=1; $i < 100; $i++) {
            $advertising=$this->createAdvertising();
            $advertising->setPublished(false);
            shuffle($tags);
            $randomTags=array_slice($tags, 0, rand(1, 4));
            foreach ($randomTags as $tag) {
                $advertising->addTag($tag);
            }
            $entityManager->persist($advertising);
        }
        $entityManager->flush();
        $io->success('Finish');
    }
    private function createUser(bool $isAdmin = false)
    {
        $faker = Faker\Factory::create();
        $user =new User();
        if ($isAdmin) {
            $user->setEmail('admin@test.me');
            $user->setRoles(array_merge(['ROLE_ADMIN'], $user->getRoles()));
        } else {
            $user->setEmail($faker->email);
        }
        $user->setPassword(
            $this->getContainer()->get('security.password_encoder')->encodePassword(
                $user,
                '123456'
            )
        );
        return $user;
    }

    private function createAdvertising()
    {
        $faker = Faker\Factory::create();
        $advertising =new Advertising();
        $advertising->setTitle($faker->text);
        $advertising->setBody($faker->text);
        return $advertising;
    }
}
