<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Entity\Train;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{
	#[Route('/', name: 'home')]
	public function index(EntityManagerInterface $em): Response
	{
		$trains = $em->getRepository(Train::class)->findBy([], ['arrivalTime' => 'ASC'], 10);
		return $this->render('pages/index.html.twig', [
			'trains' => $trains
		]);
	}
	#[Route('/destinations', name: 'destinations')]
	public function destinations(EntityManagerInterface $em, Request $request): Response
	{
		$destinations = $em->getRepository(Destination::class)->findAll();
		$filter = $request->get('filter');
		if ($filter && $filter !== 'all') {
			$filteredDestinations = $em->getRepository(Destination::class)->findBy(['name' => $filter]);
			return $this->render('pages/destinations.html.twig', [
				'destinations' => $filteredDestinations,
				'filters' => $destinations
			]);
		}
		return $this->render('pages/destinations.html.twig', [
			'destinations' => $destinations,
			'filters' => $destinations
		]);
	}
	#[Route('/trains', name: 'trains')]
	public function trains(EntityManagerInterface $em, Request $request): Response
	{
		$destinations = $em->getRepository(Destination::class)->findAll();
		$dest = $request->get('destination');
		$hour = $request->get('datetime');
		if ($dest && $dest !== 'all') {
			$filteredTrains = $em->getRepository(Train::class)->findBy(['destination' => $dest], ['arrivalTime' => 'ASC']);
			if ($hour) {
				$filteredTrains = array_filter($filteredTrains, function ($train) use ($hour) {
					return $train->getArrivalTime() > $hour;
				});
			}
			return $this->render('pages/trains.html.twig', [
				'trains' => $filteredTrains,
				'destinations' => $destinations
			]);
		}
		$trains = $em->getRepository(Train::class)->findBy([], ['arrivalTime' => 'ASC']);
		if ($hour) {
			$trains = array_filter($trains, function ($train) use ($hour) {
				return $train->getArrivalTime() > $hour;
			});
		}
		return $this->render('pages/trains.html.twig', [
			'trains' => $trains,
			'destinations' => $destinations
		]);
	}
}
