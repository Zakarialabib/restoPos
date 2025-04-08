<?php

declare(strict_types=1);

namespace App\Livewire\Installation;

use App\Models\Settings;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class StepManager extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;
    
    // Company details
    public $company_name;
    public $site_title;
    public $company_email_address;
    public $company_phone;
    public $company_address;
    public $company_tax;
    
    // Site settings
    public $site_logo;
    public $homepage_type = 'welcome';
    public $multi_language = true;
    
    // Admin user details
    public $admin_name;
    public $admin_email;
    public $admin_password;
    public $admin_password_confirmation;

    public function mount()
    {
        // Check if already installed
        // if (settings('is_installed', false)) {
        //     return redirect()->route('home');
        // }
        
        // Load current step from settings if available
        $this->currentStep = (int) settings('installation_step', 1);
        
        // Pre-fill values if they exist in settings
        $this->company_name = settings('company_name', '');
        $this->site_title = settings('site_title', '');
        $this->company_email_address = settings('company_email_address', '');
        $this->company_phone = settings('company_phone', '');
        $this->company_address = settings('company_address', '');
        $this->company_tax = settings('company_tax', '');
    }

    public function nextStep(): void
    {

        if ($this->currentStep === 1) {
            $this->validateCompanyDetails();
        } elseif ($this->currentStep === 2) {
            $this->validateSiteSettings();
        } elseif ($this->currentStep === 3) {
            $this->validateAdminDetails();
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->saveCurrentStep();
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->saveCurrentStep();
        }
    }

    public function saveCurrentStep(): void
    {
        $this->updateSetting('installation_step', $this->currentStep);
    }

    private function validateCompanyDetails(): void
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'site_title' => 'required|string|max:255',
            'company_email_address' => 'required|email',
            'company_phone' => 'required|string',
            'company_address' => 'required|string',
        ]);

        // Save company details to settings
        $this->updateSetting('company_name', $this->company_name);
        $this->updateSetting('site_title', $this->site_title);
        $this->updateSetting('company_email_address', $this->company_email_address);
        $this->updateSetting('company_phone', $this->company_phone);
        $this->updateSetting('company_address', $this->company_address);
        $this->updateSetting('company_tax', $this->company_tax);
    }

    private function validateSiteSettings(): void
    {
        $this->validate([
            'site_logo' => 'nullable|image|max:1024',
            'homepage_type' => 'required|in:welcome,shop',
        ]);

        // Handle logo upload if provided
        if ($this->site_logo) {
            $logoPath = $this->site_logo->store('logos', 'public');
            $this->updateSetting('site_logo', $logoPath);
        }

        // Save site settings
        $this->updateSetting('homepage_type', $this->homepage_type);
        $this->updateSetting('multi_language', $this->multi_language);
    }

    private function validateAdminDetails(): void
    {
        $this->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8|confirmed',
        ]);

        // Create admin user
        User::create([
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'password' => Hash::make($this->admin_password),
            'is_admin' => true,
        ]);
    }

    private function updateSetting(string $key, $value): void
    {
        Settings::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function completeInstallation()
    {
        // Final step - mark installation as complete
        $this->updateSetting('is_installed', true);
        
        // // Create default homepage based on type
        // if ($this->homepage_type === 'welcome') {
        //     Page::updateOrCreate(
        //         ['is_homepage' => true],
        //         [
        //             'title' => 'Welcome to ' . $this->site_title,
        //             'slug' => 'home',
        //             'content' => '<div class="container mx-auto px-4 py-12">
        //                 <h1 class="text-4xl font-bold text-center mb-8">Welcome to ' . $this->site_title . '</h1>
        //                 <div class="prose lg:prose-xl mx-auto">
        //                     <p>This is the default homepage content. You can edit this in the admin panel.</p>
        //                 </div>
        //             </div>',
        //             'meta_title' => $this->site_title,
        //             'meta_description' => 'Welcome to our website',
        //             'is_homepage' => true,
        //             'is_published' => true,
        //             'template' => 'default',
        //             'type' => \App\Enums\PageType::HOME,
        //             'settings' => json_encode([
        //                 'is_sliders' => true,
        //                 'is_title' => true,
        //                 'is_description' => true,
        //                 'is_about' => true,
        //                 'is_services' => true,
        //                 'is_contact' => true,
        //                 'is_gallery' => true,
        //             ]),
        //         ]
        //     );
        // }

        // Redirect to homepage
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.installation.step-manager');
    }
} 