<?php

namespace App\Test\Controller;

use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecetteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/recette/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Recette::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recette index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'recette[libelle]' => 'Testing',
            'recette[description]' => 'Testing',
            'recette[slug]' => 'Testing',
            'recette[createdAt]' => 'Testing',
            'recette[updatedAt]' => 'Testing',
            'recette[photo]' => 'Testing',
            'recette[videos]' => 'Testing',
            'recette[isPublic]' => 'Testing',
            'recette[tempsPreparation]' => 'Testing',
            'recette[categorie]' => 'Testing',
            'recette[difficulte]' => 'Testing',
            'recette[province]' => 'Testing',
            'recette[auteur]' => 'Testing',
            'recette[ingredients]' => 'Testing',
            'recette[liked]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recette();
        $fixture->setLibelle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSlug('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setVideos('My Title');
        $fixture->setIsPublic('My Title');
        $fixture->setTempsPreparation('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setDifficulte('My Title');
        $fixture->setProvince('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setIngredients('My Title');
        $fixture->setLiked('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recette');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recette();
        $fixture->setLibelle('Value');
        $fixture->setDescription('Value');
        $fixture->setSlug('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setPhoto('Value');
        $fixture->setVideos('Value');
        $fixture->setIsPublic('Value');
        $fixture->setTempsPreparation('Value');
        $fixture->setCategorie('Value');
        $fixture->setDifficulte('Value');
        $fixture->setProvince('Value');
        $fixture->setAuteur('Value');
        $fixture->setIngredients('Value');
        $fixture->setLiked('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'recette[libelle]' => 'Something New',
            'recette[description]' => 'Something New',
            'recette[slug]' => 'Something New',
            'recette[createdAt]' => 'Something New',
            'recette[updatedAt]' => 'Something New',
            'recette[photo]' => 'Something New',
            'recette[videos]' => 'Something New',
            'recette[isPublic]' => 'Something New',
            'recette[tempsPreparation]' => 'Something New',
            'recette[categorie]' => 'Something New',
            'recette[difficulte]' => 'Something New',
            'recette[province]' => 'Something New',
            'recette[auteur]' => 'Something New',
            'recette[ingredients]' => 'Something New',
            'recette[liked]' => 'Something New',
        ]);

        self::assertResponseRedirects('/recette/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getPhoto());
        self::assertSame('Something New', $fixture[0]->getVideos());
        self::assertSame('Something New', $fixture[0]->getIsPublic());
        self::assertSame('Something New', $fixture[0]->getTempsPreparation());
        self::assertSame('Something New', $fixture[0]->getCategorie());
        self::assertSame('Something New', $fixture[0]->getDifficulte());
        self::assertSame('Something New', $fixture[0]->getProvince());
        self::assertSame('Something New', $fixture[0]->getAuteur());
        self::assertSame('Something New', $fixture[0]->getIngredients());
        self::assertSame('Something New', $fixture[0]->getLiked());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recette();
        $fixture->setLibelle('Value');
        $fixture->setDescription('Value');
        $fixture->setSlug('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setPhoto('Value');
        $fixture->setVideos('Value');
        $fixture->setIsPublic('Value');
        $fixture->setTempsPreparation('Value');
        $fixture->setCategorie('Value');
        $fixture->setDifficulte('Value');
        $fixture->setProvince('Value');
        $fixture->setAuteur('Value');
        $fixture->setIngredients('Value');
        $fixture->setLiked('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/recette/');
        self::assertSame(0, $this->repository->count([]));
    }
}
