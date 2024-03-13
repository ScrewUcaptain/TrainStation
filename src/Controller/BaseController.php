<?php

namespace App\Controller;

use App\Entity\Train;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
	#[Route('/', name: 'home')]
	public function index(EntityManagerInterface $em): Response
	{
		$trains = $em->getRepository(Train::class)->findBy([], ['arrivalTime' => 'ASC'], 10);
		return $this->render('base/index.html.twig', [
			'controller_name' => 'BaseController',
			'trains' => $trains
		]);
	}
}
