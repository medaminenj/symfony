<?php

namespace App\Controller;

use App\Entity\Serveur; 
use App\Entity\Restaurant; 
use App\Form\AddEditServeurType; 
use App\Repository\ServeurRepository;
use App\Repository\RestaurantRepository; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServeurController extends AbstractController
{
    #[Route('/serveur/list', name: 'app_serveur_list')]
    public function serveursList(ServeurRepository $serveurRepository)
    {
        $serveursDB = $serveurRepository->findAll();
        return $this->render('serveur/list.html.twig', [
            'serveurs' => $serveursDB
        ]);
    }

    #[Route('/serveur/add/{restaurantId}', name: 'app_serveur_add')]
    public function addServeur(int $restaurantId, EntityManagerInterface $em, RestaurantRepository $restaurantRepository)
    {
        // Fetch the restaurant based on the provided ID
        $restaurant = $restaurantRepository->find($restaurantId);
        
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }
    
        $serveur = new Serveur();
        $serveur->setNom('Ali'); 
        $serveur->setDateNaissance(new \DateTime('2000-01-01'));
        $serveur->setRestaurant($restaurant); // Set the associated restaurant
    
        $em->persist($serveur);
        $em->flush();
    
        dd($serveur); // For debugging purposes, you can remove this in production
    }
    

    #[Route('/serveur/new', name: 'app_serveur_new')]
    public function newServeur(Request $request, EntityManagerInterface $em, RestaurantRepository $restaurantRepository)
    {
        $serveur = new Serveur();
        $form = $this->createForm(AddEditServeurType::class, $serveur);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $restaurant = $request->request->get('restaurant_id'); 
            if ($restaurant) {
                $restaurantEntity = $restaurantRepository->find($restaurant);
                if ($restaurantEntity) {
                    $serveur->setRestaurant($restaurantEntity);
                } else {
                    $this->addFlash('error', 'Restaurant not found.');
                }
            }

            $em->persist($serveur);
            $em->flush();
            return $this->redirectToRoute('app_serveur_list');
        }
        
        return $this->render('serveur/form.html.twig', [
            'title' => 'Add Serveur',
            'form' => $form->createView()
        ]);
    }

    #[Route('/serveur/edit/{id}', name: 'app_serveur_edit')]
    public function editServeur($id, ServeurRepository $serveurRepository, Request $request, EntityManagerInterface $em, RestaurantRepository $restaurantRepository)
    {
        $serveur = $serveurRepository->find($id);
        
        if (!$serveur) {
            throw $this->createNotFoundException('Serveur not found');
        }

        $form = $this->createForm(AddEditServeurType::class, $serveur);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Ensure a restaurant is selected before persisting
            $restaurant = $request->request->get('restaurant_id'); // Assuming your form includes a restaurant select field
            if ($restaurant) {
                $restaurantEntity = $restaurantRepository->find($restaurant);
                if ($restaurantEntity) {
                    $serveur->setRestaurant($restaurantEntity); // Set the restaurant
                } else {
                    $this->addFlash('error', 'Restaurant not found.');
                }
            }

            $em->flush();
            return $this->redirectToRoute('app_serveur_list');
        }
        
        return $this->render('serveur/form.html.twig', [
            'title' => 'Update Serveur',
            'form' => $form->createView()
        ]);
    }

    #[Route('/serveur/delete/{id}', name: 'app_serveur_delete')]
    public function deleteServeur($id, ServeurRepository $serveurRepository, EntityManagerInterface $em)
    {
        $serveur = $serveurRepository->find($id);
        
        if (!$serveur) {
            throw $this->createNotFoundException('Serveur not found');
        }

        $em->remove($serveur);
        $em->flush();
        return $this->redirectToRoute('app_serveur_list');
    }

    #[Route('/serveur/details/{id}', name: 'app_serveur_details')]
    public function serveurDetails($id, ServeurRepository $serveurRepository)
    {
        $serveur = $serveurRepository->find($id);
        
        if (!$serveur) {
            throw $this->createNotFoundException('Serveur not found');
        }

        return $this->render('serveur/details.html.twig', [
            'serveur' => $serveur
        ]);
    }



    #[Route('/serveur/search', name: 'app_serveur_search')]
    public function searchServeurByDate(Request $request, ServeurRepository $serveurRepository)
    {
        $date1 = $request->query->get('date1');
        $date2 = $request->query->get('date2');
        $serveurs = [];

        if ($date1 && $date2) {
            $date1 = new \DateTime($date1);
            $date2 = new \DateTime($date2);
            $serveurs = $serveurRepository->findServeursByDateRange($date1, $date2);
        }

        return $this->render('serveur/search_results.html.twig', [
            'serveurs' => $serveurs,
            'date1' => $date1 ? $date1->format('Y-m-d') : '',
            'date2' => $date2 ? $date2->format('Y-m-d') : ''
        ]);
    }



}
