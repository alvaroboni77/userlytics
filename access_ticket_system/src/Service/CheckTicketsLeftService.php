<?php

namespace App\Service;

use App\Repository\AssistantRepository;
use App\Repository\TicketRepository;

class CheckTicketsLeftService
{
    private $ticketRepository;
    private $assistantRepository;

    function __construct(TicketRepository $ticketRepository, AssistantRepository $assistantRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->assistantRepository = $assistantRepository;
    }

    public function isTicketCategoryAvailable($category): bool
    {
        $categoryCapacity = $this->ticketRepository->findOneBy(['category' => $category])->getCapacity();
        $ticketsBooked = $this->assistantRepository->count(['ticketCategory' => $category]);
        if( $ticketsBooked >= $categoryCapacity ) {
            return false;
        } else {
            return true;
        }
    }
}