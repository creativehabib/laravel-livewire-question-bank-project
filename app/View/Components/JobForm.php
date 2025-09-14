<?php

namespace App\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class JobForm extends Component
{
    /**
     * The categories for the form select dropdown.
     *
     * @var mixed
     */
    public $categories; // 👈 এখানে একটি public property ডিক্লেয়ার করুন
    public $companies;

    /**
     * The submit action for the form.
     *
     * @var string
     */
    public mixed $submitAction;

    /**
     * The text for the submitted button.
     *
     * @var string
     */
    public mixed $buttonText;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    // 👇 কনস্ট্রাক্টরে $categories গ্রহণ করুন
    public function __construct($categories, $companies, $submitAction = 'save', $buttonText = 'Save')
    {
        $this->categories = $categories; // 👈 এটিকে property-তে সেট করুন
        $this->companies = $companies;
        $this->submitAction = $submitAction;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Factory|View
     */
    public function render(): Factory|View
    {
        return view('components.job-form');
    }
}
