<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/27/20
 * Time: 23:57
 */

namespace App\Command;

use App\Entity\Category;
use App\Entity\Person;
use App\Entity\Proposal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InitDBCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:initdb';
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Initialization of DB',
            '============',
            '',
        ]);

        $this->loadCategories($input, $output);
        $this->loadPersons($input, $output);

        return 0;
    }

    protected function loadCategories(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Creating categories',
            '============',
            '',
        ]);
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('category.json');;

        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        $categories = json_decode($contents, true);

        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('categories-childs.json');;

        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        $childCategories = json_decode($contents, true);

        foreach ($childCategories as $childCategory) {
            $output->writeln('create sub category ' . $childCategory['label']);

            $cat = new Category();
            $cat->setName($childCategory['label']);
            $cat->setSlug($childCategory['id']);

            $this->em->persist($cat);
            $this->em->flush();
        }

        foreach ($categories as $category) {
            $output->writeln('create category ' . $category['label']);

            $cat = new Category();
            $cat->setName($category['label']);
            $cat->setSlug($category['id']);

            foreach ($category['sous-thematiques'] as $sousthematique) {
                /** @var Category $subCat */
                $subCat = $this->em->getRepository(Category::class)
                    ->findOneBy(['slug' => $sousthematique]);
                $cat->addChild($subCat);
            }

            $this->em->persist($cat);
            $this->em->flush();
        }
    }

    protected function loadPersons(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            'Creating persons',
            '============',
            '',
        ]);
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('candidats.json');;

        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        $persons = json_decode($contents, true);

        foreach ($persons as $person) {
            $newP = new Person();
            $this->em->persist($newP);

            $newP->setName($person['tetes'][0]['prenom'] . ' ' . $person['tetes'][0]['nom']);

            foreach ($person['propositions'] as $proposition) {
                $proposal = new Proposal();
                $this->em->persist($proposal);

                $newP->addProposal($proposal);

                $proposal->setSlug($proposition['id']);
                $proposal->setDescription($proposition['label']);
                $proposal->setLikes(rand(0, 12));
                $proposal->setProgressionType(rand(0, 2));
                if ($proposal->getProgressionType() == Proposal::PROGRESSION_NUMBER)
                    $proposal->setProgressionMax(rand(3, 20));
                $proposal->setStatus(rand(0, 3));
                if ($proposal->getStatus() == Proposal::COMPLETED)
                    $proposal->setProgression($proposal->getProgressionMax());
                if ($proposal->getStatus() == Proposal::RUNNING && $proposal->getProgressionType() == Proposal::PROGRESSION_NUMBER)
                    $proposal->setProgression(rand(1, $proposal->getProgressionMax()-1));

                /** @var Category $cat */
                $cat = $this->em->getRepository(Category::class)
                    ->findOneBy(['slug' => $proposition['st']]);

                $proposal->addCategory($cat);
            }
        }

        $this->em->flush();
    }
}