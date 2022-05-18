<?php

namespace App\Actions;

use Ericlagarda\NovaTextCard\TextCard;

class UserWelcomeCard
{
  public static function getCard()
  {
    $content = '<div class="flex justify-between bg-white rounded-lg items-center">
        <div class="flex flex-col items-start justify-center">
          <div class="px-5 py-3">
            <h1 class="text-start text-3xl text-80 font-medium">
              You are connected as : 
              <span class="font-semibold">' . Auth()->user()->name . '</span>
            </h1>
          </div>
          <div class="px-7 py-2 mb-2">
            <h1 class="text-start text-lg text-80 font-medium">
              <span class="font-semibold">' . Auth()->user()->role->name . '</span>';
    if (Auth()->user()->establishment) {
      $content .= ' at <span class="font-semibold">' . Auth()->user()->establishment->name_fr . '</span>';
    }
    $content .= '</h1>
        </div>
      </div>
      <div>
        <a href="/nova/logout">
          <svg
            class="w-12 h-12"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
            ></path>
          </svg>
        </a>
      </div>
    </div>';

    return (new TextCard())
      ->forceFullWidth()
      ->center(false)
      ->text($content)
      ->textAsHtml();
  }
}
