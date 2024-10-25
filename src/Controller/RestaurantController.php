<?php


namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Serveur; 
use App\Form\AddEditRestaurantType; 
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    #[Route('/restaurant/list', name: 'app_restaurant_list')]
    public function restaurantsList(RestaurantRepository $restaurantRepository)
    {
        $restaurantsDB = $restaurantRepository->findAll();
        return $this->render('restaurant/list.html.twig', [
            'restaurants' => $restaurantsDB
        ]);
    }

    #[Route('/restaurant/add', name: 'app_restaurant_add')]
    public function addRestaurant(EntityManagerInterface $em)
    {
        $restaurant = new Restaurant();
        $restaurant->setNom('rrrr');
        $restaurant->setAdresse('1211 St.');
        // Optionally add servers or other relations here
        $em->persist($restaurant);
        $em->flush();
        dd($restaurant);
    }

    #[Route('/restaurant/new', name: 'app_restaurant_new')]
    public function newRestaurant(Request $request, EntityManagerInterface $em)
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(AddEditRestaurantType::class, $restaurant);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($restaurant);
            $em->flush();
            return $this->redirectToRoute('app_restaurant_list');
        }
        
        return $this->render('restaurant/form.html.twig', [
            'title' => 'Add Restaurant',
            'form' => $form->createView()
        ]);
    }

    #[Route('/restaurant/edit/{id}', name: 'app_restaurant_edit')]
    public function editRestaurant($id, RestaurantRepository $restaurantRepository, Request $request, EntityManagerInterface $em)
    {
        $restaurant = $restaurantRepository->find($id);
        
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }

        $form = $this->createForm(AddEditRestaurantType::class, $restaurant);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_restaurant_list');
        }
        
        return $this->render('restaurant/form.html.twig', [
            'title' => 'Update Restaurant',
            'form' => $form->createView()
        ]);
    }

    #[Route('/restaurant/delete/{id}', name: 'app_restaurant_delete')]
    public function deleteRestaurant($id, RestaurantRepository $restaurantRepository, EntityManagerInterface $em)
    {
        $restaurant = $restaurantRepository->find($id);
        
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }

        $em->remove($restaurant);
        $em->flush();
        return $this->redirectToRoute('app_restaurant_list');
    }

    #[Route('/restaurant/details/{id}', name: 'app_restaurant_details')]
    public function restaurantDetails($id, RestaurantRepository $restaurantRepository)
    {
        $restaurant = $restaurantRepository->find($id);
        
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }

        return $this->render('restaurant/details.html.twig', [
            'restaurant' => $restaurant
        ]);
    }
}
