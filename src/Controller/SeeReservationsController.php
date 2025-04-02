<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservation;
use App\Entity\Station;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SeeReservationsController extends AbstractController
{
    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    #[Route('/see/reservations', name: 'app_see_reservations')]
    public function index(): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Récupérer le repository des réservations
        $reservationRepository = $this->entityManager->getRepository(Reservation::class);

        // Récupérer le repository des stations
        /** @var StationRepository $stationRepository */
        $stationRepository = $this->entityManager->getRepository(Station::class);

        // Récupérer les réservations de l'utilisateur par son email
        $reservation = $reservationRepository->getReservationsByUserEmail($user->getEmail());

        // Initialiser un tableau pour stocker les réservations formatées
        $reservations = array();

        // Parcourir chaque réservation et formater les données
        foreach ($reservation as $res){
            /** @var Reservation $res */
            $reservations[] = [ //on ajoute les informations de la réservation dans le tableau
                "id" => $res->getId(),
                "date" => $res->getDate(),
                "heureDebut" => $res->getHeureDebut(),
                "heureFin" => $res->getHeureFin(),
                "idStationDepart" => $stationRepository->getStationNameById($res->getIdStationDepart())[0]["name"],
                "idStationArrivee" => $stationRepository->getStationNameById($res->getIdStationArrivee())[0]["name"],
                "type" => $res->getClearType($res->getType()),
                "distance_km" => $res->getDistanceKm(),
            ];
        }

        // Rendre la vue avec les réservations formatées
        return $this->render('see_reservations/index.html.twig', [
            'controller_name' => 'SeeReservationsController',
            "reservations" => $reservations
        ]);
    }

    #[Route('/see/reservations/cancel/{id}', name: 'app_cancel_reservation')]
    public function cancel(Request $request): RedirectResponse
    {
        // Vérifier si la requête est de type POST
        if ($request->isMethod("POST"))
        {
            // Récupérer l'ID de la réservation à annuler
            $idReservation = $request->get("id");

            // Récupérer le repository des réservations
            /** @var ReservationRepository $reservationRepository */
            $reservationRepository = $this->entityManager->getRepository(Reservation::class);

            // Supprimer la réservation par son ID
            $reservationRepository->deleteReservationById($idReservation);

            // Sauvegarder les changements en base de données
            $this->entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash("success","Votre réservation a été annulée avec succès.");
        }

        // Rediriger vers la route des réservations
        return $this->redirectToRoute("app_see_reservations");
    }
}