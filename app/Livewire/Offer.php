<?php

namespace App\Livewire;

use App\Models\Offer as ModelsOffer;
use Auth;
use Livewire\Component;

class Offer extends Component
{
    private $offers;

    public function mount()
    {
        // dd(Auth::user()->roles()->pluck('id')->toArray());
        $this->offers = ModelsOffer::where('status', 1)->whereIn('for_user_role', Auth::user()->roles()->pluck('id')->toArray())->get();
    }
    public function render()
    {
        return view('livewire.offer', ['offers' => $this->offers]);
    }
}
