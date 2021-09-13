<?php

namespace App\Services;

class NotificationService
{
    public function newOrder()
    {
        $name = auth()->user()->name;
        return "You have a new order from <b>{$name}.</b>";
    }

    public function cancelOrder()
    {
        $name = auth()->user()->name;
        return "<b>{$name}</b> has cancelled his order.</b>";
    }

    public function confirmOrder()
    {
        $name = auth()->user()->name;
        return "<b>{$name}</b> has confirmed receiving his order.</b>";
    }

    public function newUser()
    {
        return "a new user has been registered";
    }
}
