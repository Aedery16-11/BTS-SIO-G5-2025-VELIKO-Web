<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Station;
use App\Entity\StationUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        //c'est normal si c'est vide
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Appel de la méthode qui charge les utilisateurs
        $this->loadUsers($manager);
        $this->loadReservations($manager);
        $this->loadStationUser($manager);
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("user-admin-@gmail.dev");
        $user->setPassword($this->hasher->hashPassword($user, "Bonjour12345!"));
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setNom("nom-admin");
        $user->setPrenom("prenom-admin");
        $user->setDateNaissance(new \DateTime());
        $user->setAdresse("adresse-admin");
        $user->setCodePostal("92100");
        $user->setLastPasswordChanged(new \DateTime());
        $user->setVille("ville-admin");
        $user->setVerified(true);
        $user->setBlocked(false);
        $user->setMustChangePassword(false);
        $user->setResetToken(null);
        $user->setToken(null);

        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user-$i@gmail.dev");
            $user->setPassword($this->hasher->hashPassword($user, "Bonjour12345!"));
            $user->setRoles(["ROLE_USER"]);
            $user->setNom("nom-$i");
            $user->setPrenom("prenom-$i");
            $user->setDateNaissance((new \DateTime())->setTimestamp(mt_rand(strtotime("1980-01-01"), strtotime("2007-01-01"))));
            $user->setAdresse("adresse-$i");
            $user->setCodePostal("92100");
            $user->setLastPasswordChanged(new \DateTime());
            $user->setVille("ville-$i");
            $user->setVerified(true);
            $user->setBlocked(false);
            $user->setMustChangePassword(false);
            $user->setResetToken(null);
            $user->setToken(null);

            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function loadReservations(ObjectManager $manager): void
    {
        $stations_debut_ids = [
            213688169, 17278902806, 36255, 251039991, 85002689, 2515829865, 516709288, 120827885, 66491398, 66491398, 210565680
        ];
        $stations_arrivee_ids = [
            210403080, 210561800, 209063434, 94555589, 43195240, 501862076, 17486274358, 210566542, 85043758, 210397939
        ];

        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservation();
            $reservation->setEmailUser("user-$i@gmail.dev");
            $reservation->setDate((new \DateTime())->setTimestamp(mt_rand(strtotime('2010-01-01'), strtotime('2024-12-31'))));
            $reservation->setHeureDebut((new \DateTime())->setTimestamp(mt_rand(1, time())));
            $reservation->setHeureFin((new \DateTime())->setTimestamp(mt_rand(1, time())));
            $reservation->setIdStationDepart($stations_debut_ids[$i % count($stations_debut_ids)]);
            $reservation->setIdStationArrivee($stations_arrivee_ids[$i % count($stations_arrivee_ids)]);
            $reservation->setType($i % 2 === 0 ? "mechanical" : "ebike");
            $reservation->setDistanceKm(mt_rand(1, 20));
            $manager->persist($reservation);
        }
        $manager->flush();
    }
    private function loadStationUser(ObjectManager $manager): void
    {
        $stations_ids = [
            213688169, 17278902806, 36255, 251039991, 85002689, 2515829865, 516709288, 120827885, 66491398, 66491398, 210565680
        ];

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);
        for ($i = 1; $i <= 10; $i++) {
            $stationUser = new StationUser();
            $stationUser->setIdUser($userRepository->getAllIds()[$i % count($userRepository->getAllIds())]["id"]);
            $stationUser->setIdStation($stations_ids[$i % count($stations_ids)]);
            $manager->persist($stationUser);
        }
        $manager->flush();
    }
}