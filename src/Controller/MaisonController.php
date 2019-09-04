<?php

namespace App\Controller;

use App\Entity\Maisons;
use App\Repository\MaisonsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class MaisonController extends AbstractController
{
    /**
     * @var MaisonsRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;
    
    public function __construct(MaisonsRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em; 
    }
    
    
    /**
     * @Route("/maison", name="maison")
     */
    // public function index() : Response
    // {
        /*
         * pour injecter les donner dans la bse 
         */
        // $property = new Maisons();
        // $property->setTitle('mon troisieme bien')
        //     ->setPrice(120000)
        //     ->setRooms(8)
        //     ->setBedrooms(5)
        //     ->setDescription('une petite description')
        //     ->setSurface(200)
        //     ->setFloor(6)
        //     ->setHeat(3)
        //     ->setCity('Paris')
        //     ->setAdresse('23 street boys')
        //     ->setPostalCode('75000');
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($property);
        // $em->flush();
      

        /**
         * avant de faire le contructeur on utiliser cette methodes pour appeler le repository
         *  
         * $property = $this->getDoctrine()->getRepository(Maisons::class);
         * dump($property);
         * 
         */

        /*
         * Recuperer les lignes de la base de donner -- trouver l'id num un 
         */
        // $property = $this->repository->find(1);

        /*
         * find all les lignes
         */
        // $property = $this->repository->findAll(); 

        /**
         * find avec parametre avec findOneBy
         */
        //$property = $this->repository->findOneBy(['floor' => 4]);
        //dump($property);

        /*
         * Si on veut retourner toutes les maison non vendu on doit utiliser le findAll mais
         * pour fonctionner il faut creer une methode et elle se fait dans ce cas (MaisonsRepository)
         * findByExampleField. a voir
         */

         /**
          * une fois on a creer notre findAllVisible on l'utilise
          */
        
        // $property = $this->repository->findAllVisible(); // depuis MaisonsRepository
        
        /* le flush va detecter que le sold a ete change a 'true' et il va apporter les modifs 
        * la base de donnee et on doit supprimer le setSold et flush car elle a deja ete changer definitivemnet
        */
        //$property[0]->setSold(true);
        //$this->em->flush();

        // recuperer tt les biens depuis la BDD
        // $properties = $this->repository->findAllVisible();

    /**
     * @Route("/maison", name="maison")
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        //faire la pagination ça change comme quit
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery(),
            $request->query->getInt('page', 1), /*page number par default c'est 1 ici*/
            // 12 /*limit par page 3x4*/
            9 /*limit par page 3x3*/
        );
        return $this->render('Maisons/Maison.html.twig', [
            'controller_name' => 'MaisonController',
            'current_menu' => 'maison',
            'properties' => $properties
        ]);
    }

    /**
     * @return Response
     * @Route("/maison/{slug}-{id}", name="maison.show", requirements={"slug" : "[a-z0-9\-]*"})
     */
    // public function show($slug, $id) : Response
    public function show(Maisons $property, string $slug) : Response
    {
        /**
         * si on fait pas les parametres slug et id et on fait maisons $property a la place
         * on peut supprimer la ligne de      find($id)
         */
        // $property = $this->repository->find($id);

        if ($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('maison.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug(),
            ], 301);
        }
        return $this->render('Maisons/show.html.twig', [
            'controller_name' => 'MaisonController',
            'current_menu' => 'maison',
            // envoyer les property a la vue
            'house' => $property,
        ]);
    }
}
