<?php

namespace App\EventSubscriber;

use App\Entity\Train;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrainSubscriber implements EventSubscriberInterface
{

	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPostSubmit(FormEvent $event)
	{
		$newTrain = $event->getData();
		$dockChecked = [];
		//line 31 - 51 need improvement to avoid redundancy and/or to be more efficient
		// if there is no train in dock A, assign dock A to the train and end the algorithm else repeat for other docks
		if ($newTrain->getDestination()->getCategory() === 'TER') {
			if ($this->em->getRepository(Train::class)->findBy(['dock' => 'A']) === []) {
				return 'A';
			} else if (
				$this->em->getRepository(Train::class)->findBy(['dock' => 'B']) === []
			) {
				return 'B';
			} else if (
				$this->em->getRepository(Train::class)->findBy(['dock' => 'C']) === []
			) {
				return 'C';
			}
		}
		if ($this->em->getRepository(Train::class)->findBy(['dock' => 'D']) === []) {
			return 'D';
		} else if (
			$this->em->getRepository(Train::class)->findBy(['dock' => 'E']) === []
		) {
			return 'E';
		}
		if ($newTrain->getDestination()->getCategory() === 'TGV') {
			//if the train is a TGV we can consider only docks D and E
			$dockChecked = ['A', 'B', 'C'];
		}
		$trains = $this->em->getRepository(Train::class)->findBy([], ['dock' => 'ASC']);

		$checkingDock = null;
		$dockIsFree = false;
		
		foreach ($trains as $t) {
			//the first condition is needed to stop the loop if a dock is found
			//we have sorted train by alphabetical order of the dock so we can stop the loop if all trains have been successfully checked
			if ($dockIsFree && $checkingDock !== $t->getDock()) {
				break;
			}
			// if the dock has already been checked we can skip this train
			if (
				 in_array($t->getDock(), $dockChecked))
			{
				continue;
			}
			$checkingDock = $t->getDock();
			//if all previous conditions passed we can check if the present train conflicts with the train being created
			//time conflicts check and take 10 minutes margin for departure
			if (
				$newTrain->getDepartureTime()->modify('+10 minutes') < $t->getDepartureTime()||
				$newTrain->getArrivalTime() > $t->getDepartureTime()->modify('+10 minutes')
			) 
			{
				// if the train is not conflicting we can temporarily assign the dock to the train
				$dockIsFree = true;
				continue;
			} else {
				// if the train is conflicting we can't assign the dock to the train and can pass to the next dock
				$dockIsFree = false;
				array_push($dockChecked, $t->getDock());
				continue;
			}
		}
		if ($dockIsFree && $checkingDock !== null) {
			return $checkingDock;
		} else return null;
			
    }
}