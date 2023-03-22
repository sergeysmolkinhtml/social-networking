<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ExtendedProfile extends Component
{

    /**
     * The component's state.
     *
     * @var array
     */
    public array $state = [];
    /**
     * @var string[]
     */
    private array $birthdayArr;

    public function mount()
    {
        $this->state = Auth::user()->withoutRelations()->toArray();

        if(Auth::user()->date_of_birth){
            $this->birthdayArr = explode("-",Auth::user()->date_of_birth);
            $this->state['bday']   = $this->birthdayArr[2];
            $this->state['bmonth'] = $this->birthdayArr[1];
            $this->state['byear']  = $this->birthdayArr[0];
        } else {
            $this->state['bday']      = '';
            $this->state['bmonth']    = '';
            $this->state['byear']     = '';

        }
    }

    public function setStrBirthday()
    {
        if(($this->state['bday'] && $this->state['bmonth']
            && $this->state['byear']) != '')
        {
            return "{$this->state['byear']}-{$this->state['bmonth']}-{$this->state['bday']}";
        }
    }

    public function updateExtendedProfile()
    {
        Validator::make($this->state,[
            'byear' => ['digits:4','integer'],
            'job_title' => ['string','max:50']
        ])->validate();

        Auth::user()->update([
            'date_of_birth' => $this->setStrBirthday(),
            'job_title' => $this->state['job_title'],
        ]);

        $this->emit('saved');
    }


    public function render()
    {
        return view('livewire.extended-profile');
    }
}
