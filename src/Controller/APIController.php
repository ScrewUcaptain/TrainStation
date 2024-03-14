<?php

namespace App\Controller;

use App\Entity\Train;
use App\Form\TrainType;
use App\Entity\Destination;
use App\Form\DestinationType;
use Symfony\Component\Form\FormEvent;
use App\EventSubscriber\TrainSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIController extends AbstractController
{
	
	#[Route('/new/dest', name: 'new_destination', methods: ['GET', 'POST'])]
	public function addDestination(Request $request, EntityManagerInterface $em)
	{
		$dest = new Destination();
		$form = $this->createForm(DestinationType::class, $dest);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$dest = $form->getData();
			$name = $dest->getName();
			$code = $dest->getCodeStation();
			//sanitize user input
			$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
			$code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
			// put the code station in uppercase to uniformize data
			$code = strtoupper($code);
			$dest->setCodeStation($code);
			$dest->setName($name);
			// save into db
			$em->persist($dest);
			$em->flush();
			$this->addFlash('success', 'The destination has been added successfully!');
			return $this->redirectToRoute('home');
		}

		return $this->render('form/destination.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/update/dest/{dest}', name: 'update_destination', methods: ['GET', 'POST'])]
	public function updateDestination(Request $request, EntityManagerInterface $em, Destination $dest)
	{

		$dest = $em->getRepository(Destination::class)->find($dest);
		// return if there is already a train bound to this destination so this can't be modified
		if ($dest->getTrains()->count() > 0) {
			$this->addFlash('error', 'This destination is already bound to a train, it can\'t be modified');
			return $this->redirectToRoute('destinations');
		}
		$form = $this->createForm(DestinationType::class, $dest);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$dest = $form->getData();
			$name = $dest->getName();
			$code = $dest->getCodeStation();
			//sanitize user input
			$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
			$code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
			// put the code station in uppercase to uniformize data
			$code = strtoupper($code);
			$dest->setCodeStation($code);
			$dest->setName($name);
			// save into db
			$em->persist($dest);
			$em->flush();
			$this->addFlash('success', 'The destination has been modified successfully!');
			return $this->redirectToRoute('home');
		}

		return $this->render('form/destination.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/delete/dest/{dest}', name: 'delete_destination', methods: ['DELETE'])]
	public function deleteDestination(EntityManagerInterface $em, Destination $dest)
	{
		$dest = $em->getRepository(Destination::class)->find($dest);

		if ($dest->getTrains()->count() > 0) {
			$this->addFlash('error', 'This destination is already bound to a train, it can\'t be removed');
			return $this->redirectToRoute('destinations');
		}
		// delete from db
		$em->remove($dest);
		$em->flush();
		return new JsonResponse(
			[
				'status' => 'success',
				'message' => 'The destination has been removed successfully!'
			]
		);
	}

	#[Route('/new/train', name: 'new_train', methods: ['GET', 'POST'])]
	public function addTrain(Request $request, EntityManagerInterface $em, TrainSubscriber $ts)
	{
		$train = new Train();
		$form = $this->createForm(TrainType::class, $train);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$train = $form->getData();
			// test if the arrival date is before the departure date
			if ($train->getArrivalTime() >= $train->getDepartureTime()) {
				$this->addFlash('error', 'The arrival date can\'t be after the departure date');
				return $this->redirectToRoute('new_train');
			}

			$availableDock = $ts->onPostSubmit(new FormEvent($form, $train));
			if(!$availableDock) {
				$this->addFlash('error', 'No dock available for this train');
				return $this->redirectToRoute('new_train');
			}
			$train->setDock($availableDock);
			// save into db
			$em->persist($train);
			$em->flush();
			$this->addFlash('success', 'The train has been added successfully, and assign to dock ' . $availableDock);
			return $this->redirectToRoute('trains');
		}

		return $this->render('form/train.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/update/train/{train}', name: 'update_train', methods: ['GET', 'POST'])]
	public function updateTrain(Request $request, EntityManagerInterface $em, Train $train)
	{
		$train = $em->getRepository(Train::class)->find($train);
		$form = $this->createForm(TrainType::class, $train);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$train = $form->getData();
			// test if the arrival date is before the departure date
			if ($train->getArrivalTime() >= $train->getDepartureTime()) {
				$this->addFlash('error', 'The arrival date can\'t be after the departure date');
				return $this->redirectToRoute('new_train');
			}
			// save into db
			$em->persist($train);
			$em->flush();
			$this->addFlash('success', 'The train has been modified successfully!');
			return $this->redirectToRoute('home');
		}

		return $this->render('form/train.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/delete/train/{train}', name: 'delete_train', methods: ['DELETE'])]
	public function deleteTrain( EntityManagerInterface $em, Train $train)
	{
		$train = $em->getRepository(Train::class)->find($train);
		// delete from db
		$em->remove($train);
		$em->flush();

		return new JsonResponse(
			[
				'status' => 'success',
				'message' => 'The train has been removed successfully!'
			]
		);
	}
}


