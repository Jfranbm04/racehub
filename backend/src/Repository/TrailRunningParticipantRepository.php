<?php

namespace App\Repository;

use App\Entity\TrailRunning;
use App\Entity\TrailRunningParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrailRunningParticipant>
 */
class TrailRunningParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrailRunningParticipant::class);
    }

    /**
     * Get the next available dorsal number for a trail running event
     * while respecting the available slots limit
     * 
     * @param TrailRunning $trailRunning The trail running event
     * @return int|null The next available dorsal number or null if no slots available
     */
    public function getNextAvailableDorsal(TrailRunning $trailRunning): ?int
    {
        // Get the current count of participants
        $participantCount = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.trailRunning = :trailRunning')
            ->setParameter('trailRunning', $trailRunning)
            ->getQuery()
            ->getSingleScalarResult();
        
        // Check if there are available slots
        if ($participantCount >= $trailRunning->getAvailableSlots()) {
            return null; // No slots available
        }
        
        // Get the highest dorsal number currently assigned
        $result = $this->createQueryBuilder('p')
            ->select('MAX(p.dorsal) as maxDorsal')
            ->where('p.trailRunning = :trailRunning')
            ->setParameter('trailRunning', $trailRunning)
            ->getQuery()
            ->getOneOrNullResult();
        
        // Return the next available dorsal number
        return ($result['maxDorsal'] ?? 0) + 1;
    }
}
