<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UnverifiedUserCounter extends Component
{
    public $unverifiedCount;

    public function mount()
    {
        $this->unverifiedCount = User::where('verified', false)
                                     ->where('roles', 'resident')
                                     ->count();
    }

    public function render()
    {
        return view('livewire.unverified-user-counter');
    }
}
