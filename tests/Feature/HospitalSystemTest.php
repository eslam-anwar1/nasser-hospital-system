<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HospitalSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the testing database with roles, admin, clinics, patients
        $this->seed();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('مجمع ناصر الطبي');
        $response->assertSee('admin');
    }

    public function test_admin_can_login_with_default_credentials(): void
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => '12345678',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_authenticated_user_can_access_dashboard_and_patients(): void
    {
        $admin = User::where('username', 'admin')->first();
        $this->assertNotNull($admin);
        $this->actingAs($admin);

        // Dashboard
        $dashResponse = $this->get(route('dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('مجمع ناصر الطبي');
        $dashResponse->assertSee('إجمالي المرضى المسجلين');

        // Patients index
        $patientsResponse = $this->get(route('patients.index'));
        $patientsResponse->assertStatus(200);
        $patientsResponse->assertSee('سجلات المرضى');

        // Appointments index
        $aptsResponse = $this->get(route('appointments.index'));
        $aptsResponse->assertStatus(200);
        $aptsResponse->assertSee('جدول المواعيد');

        // Medical Records index
        $recordsResponse = $this->get(route('medical-records.index'));
        $recordsResponse->assertStatus(200);
        $recordsResponse->assertSee('السجلات والاستشارات الطبية');

        // Clinics index
        $clinicsResponse = $this->get(route('clinics.index'));
        $clinicsResponse->assertStatus(200);
        $clinicsResponse->assertSee('الأقسام والعيادات');

        // Staff / Users index
        $usersResponse = $this->get(route('users.index'));
        $usersResponse->assertStatus(200);
        $usersResponse->assertSee('الكادر الطبي والموظفين');
    }

    public function test_patient_can_be_created_and_viewed(): void
    {
        $admin = User::where('username', 'admin')->first();
        $this->actingAs($admin);

        $testFileNum = 'NAS-P-9999';

        $response = $this->post(route('patients.store'), [
            'file_number' => $testFileNum,
            'full_name' => 'سامي أحمد رضوان',
            'national_id' => '999888777',
            'phone' => '0599000111',
            'gender' => 'ذكر',
            'date_of_birth' => '1990-01-01',
            'blood_group' => 'O+',
            'address' => 'خانيونس - وسط البلد',
        ]);

        $createdPatient = Patient::where('file_number', $testFileNum)->first();
        $this->assertNotNull($createdPatient);
        $response->assertRedirect(route('patients.show', $createdPatient));

        // Test show page
        $showResponse = $this->get(route('patients.show', $createdPatient));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('سامي أحمد رضوان');
    }
}
