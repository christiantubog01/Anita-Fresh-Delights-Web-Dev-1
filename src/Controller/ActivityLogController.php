<?php

namespace App\Controller;

use App\Repository\ActivityLogRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[IsGranted('ROLE_ADMIN')]
final class ActivityLogController extends AbstractController
{
    #[Route('/activity/log', name: 'app_activity_log')]
    public function index(ActivityLogRepository $activityLogRepo): Response
    {
        return $this->render('activity_log/index.html.twig', [
            // ✅ SHOW ALL LOGS
            'activityLogs' => $activityLogRepo->findBy(
                [],
                ['createdAt' => 'DESC']
            ),
        ]);
    }
}