<?php

namespace Tests\Feature;

use App\Models\CustomField;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OnboardingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingCustomFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
    }

    public function test_custom_field_can_be_created_for_onboarding_requests()
    {
        // Create super admin user
        $admin = User::where('email', 'admin@hrmanagement.com')->first();

        // Create a custom field for OnboardingRequest
        $this->actingAs($admin);
        $response = $this->post(route('custom-fields.store'), [
            'model_type' => 'OnboardingRequest',
            'field_name' => 'urgency_level',
            'label' => 'Urgency Level',
            'field_type' => 'select',
            'options' => 'Low, Medium, High, Critical',
            'help_text' => 'Select the urgency level for this onboarding',
            'is_required' => true,
            'is_active' => true,
            'order' => 1,
        ]);

        $response->assertRedirect(route('custom-fields.index'));
        
        $this->assertDatabaseHas('custom_fields', [
            'model_type' => 'OnboardingRequest',
            'field_name' => 'urgency_level',
            'field_type' => 'select',
            'is_active' => true,
        ]);
    }

    public function test_onboarding_request_can_be_created_with_custom_field_values()
    {
        // Create admin user
        $admin = User::where('email', 'admin@hrmanagement.com')->first();

        // Create a department and employee
        $department = Department::where('type', 'IT')->first();
        $employee = Employee::factory()->create([
            'department_id' => $department->id,
            'email' => 'newemployee@test.com',
            'status' => 'active',
        ]);

        // Create a custom field for OnboardingRequest
        $customField = CustomField::create([
            'model_type' => 'OnboardingRequest',
            'field_name' => 'start_date_preference',
            'label' => 'Preferred Start Date',
            'field_type' => 'date',
            'is_required' => false,
            'is_active' => true,
            'order' => 1,
        ]);

        // Create onboarding request with custom field value
        $this->actingAs($admin);
        $response = $this->post(route('onboarding-requests.store'), [
            'employee_id' => $employee->id,
            'expected_completion_date' => now()->addDays(14)->format('Y-m-d'),
            'notes' => 'New hire onboarding with custom field',
            'custom_fields' => [
                $customField->id => '2026-02-01',
            ],
        ]);

        // Assert onboarding request was created
        $this->assertDatabaseHas('onboarding_requests', [
            'employee_id' => $employee->id,
            'status' => 'pending',
        ]);

        // Get the created onboarding request
        $onboardingRequest = OnboardingRequest::where('employee_id', $employee->id)->first();

        // Assert custom field value was saved
        $this->assertDatabaseHas('custom_field_values', [
            'custom_field_id' => $customField->id,
            'model_type' => OnboardingRequest::class,
            'model_id' => $onboardingRequest->id,
            'value' => '2026-02-01',
        ]);
    }

    public function test_onboarding_request_shows_custom_field_values()
    {
        // Create admin user
        $admin = User::where('email', 'admin@hrmanagement.com')->first();

        // Create a department and employee
        $department = Department::where('type', 'IT')->first();
        $employee = Employee::factory()->create([
            'department_id' => $department->id,
            'status' => 'active',
        ]);

        // Create a custom field for OnboardingRequest
        $customField = CustomField::create([
            'model_type' => 'OnboardingRequest',
            'field_name' => 'special_requirements',
            'label' => 'Special Requirements',
            'field_type' => 'textarea',
            'is_required' => false,
            'is_active' => true,
            'order' => 1,
        ]);

        // Create onboarding request
        $onboardingRequest = OnboardingRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $admin->id,
            'status' => 'pending',
            'expected_completion_date' => now()->addDays(7),
        ]);

        // Add custom field value
        $onboardingRequest->customFieldValues()->create([
            'custom_field_id' => $customField->id,
            'value' => 'Needs standing desk and ergonomic chair',
        ]);

        // View the onboarding request
        $this->actingAs($admin);
        $response = $this->get(route('onboarding-requests.show', $onboardingRequest));

        $response->assertStatus(200);
        $response->assertSee('Special Requirements');
        $response->assertSee('Needs standing desk and ergonomic chair');
    }
}
