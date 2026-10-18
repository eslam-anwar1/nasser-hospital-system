<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Default Hospital Administrator (حساب المدير العام)
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'employee_id' => 'EMP-100',
                'name' => 'د. محمد الكردي - الإدارة العامة',
                'email' => 'admin@nasser.hospital',
                'password' => Hash::make('12345678'),
                'role' => 'Admin',
                'phone' => '0599100200',
                'is_active' => true,
            ]
        );

        // 2. Hospital Doctors (الأطباء الاستشاريون)
        $doctor1 = User::updateOrCreate(
            ['username' => 'dr.ahmed'],
            [
                'employee_id' => 'DOC-201',
                'name' => 'د. أحمد النجار',
                'email' => 'ahmed.najjar@nasser.hospital',
                'password' => Hash::make('12345678'),
                'role' => 'Doctor',
                'specialization' => 'أمراض القلب والشرايين والقسطرة',
                'phone' => '0599201301',
                'is_active' => true,
            ]
        );

        $doctor2 = User::updateOrCreate(
            ['username' => 'dr.sarah'],
            [
                'employee_id' => 'DOC-202',
                'name' => 'د. سارة عثمان',
                'email' => 'sarah.othman@nasser.hospital',
                'password' => Hash::make('12345678'),
                'role' => 'Doctor',
                'specialization' => 'طب الأطفال والخدج',
                'phone' => '0599202302',
                'is_active' => true,
            ]
        );

        $doctor3 = User::updateOrCreate(
            ['username' => 'dr.khalid'],
            [
                'employee_id' => 'DOC-203',
                'name' => 'د. خالد رضوان',
                'email' => 'khalid.radwan@nasser.hospital',
                'password' => Hash::make('12345678'),
                'role' => 'Doctor',
                'specialization' => 'جراحة العظام والمفاصل والعمود الفقري',
                'phone' => '0599203303',
                'is_active' => true,
            ]
        );

        // 3. Receptionist & Accountant Staff (الاستقبال والمحاسبة)
        $receptionist = User::updateOrCreate(
            ['username' => 'reception'],
            [
                'employee_id' => 'REC-301',
                'name' => 'ياسمين خليل - موظفة الاستقبال',
                'email' => 'reception@nasser.hospital',
                'password' => Hash::make('12345678'),
                'role' => 'Receptionist',
                'phone' => '0599301401',
                'is_active' => true,
            ]
        );

        $accountant = User::updateOrCreate(
            ['username' => 'accountant'],
            [
                'employee_id' => 'ACC-401',
                'name' => 'عمر الصالح - الإدارة المالية',
                'email' => 'accountant@nasser.hospital',
                'password' => Hash::make('12345678'),
                'role' => 'Accountant',
                'phone' => '0599401501',
                'is_active' => true,
            ]
        );

        // 4. Clinics / Departments (عيادات وأقسام مجمع ناصر الطبي)
        $clinics = [
            [
                'code' => 'CLN-CARDIO',
                'name_ar' => 'قسم القلب والأوعية الدموية',
                'name_en' => 'Cardiology Department',
                'description' => 'تشخيص وعلاج أمراض القلب والشرايين، قسطرة وتخطيط القلب التداخلي.',
                'location' => 'المبنى الطبي التخصصي - الطابق الثاني',
                'consultation_fee' => 150.00,
                'is_active' => true,
            ],
            [
                'code' => 'CLN-PEDIATRIC',
                'name_ar' => 'قسم طب الأطفال والخدج',
                'name_en' => 'Pediatrics Department',
                'description' => 'رعاية ومتابعة حديثي الولادة والأطفال والأمراض المعدية والنمو السليم.',
                'location' => 'مبنى الأمومة والطفولة - الطابق الأول',
                'consultation_fee' => 100.00,
                'is_active' => true,
            ],
            [
                'code' => 'CLN-ORTHO',
                'name_ar' => 'عيادة جراحة العظام والمفاصل',
                'name_en' => 'Orthopedics & Joint Surgery',
                'description' => 'علاج الإصابات الرياضية، الكسور، واستبدال المفاصل ومناظير الركبة.',
                'location' => 'المبنى الرئيسي - الجناح الجراحي',
                'consultation_fee' => 130.00,
                'is_active' => true,
            ],
            [
                'code' => 'CLN-EMERGENCY',
                'name_ar' => 'قسم الطوارئ والإسعاف الميداني',
                'name_en' => 'Emergency & Trauma Care',
                'description' => 'استقبال الحالات الحرجة والإنعاش الرئوي على مدار 24 ساعة يومياً.',
                'location' => 'المدخل الجنوبي - الطابق الأرضي',
                'consultation_fee' => 50.00,
                'is_active' => true,
            ],
            [
                'code' => 'CLN-INTERNAL',
                'name_ar' => 'عيادة الباطنية العامة والغدد',
                'name_en' => 'Internal Medicine & Endocrinology',
                'description' => 'متابعة الأمراض المزمنة، داء السكري، وارتفاع ضغط الدم والغدد الصماء.',
                'location' => 'المبنى الطبي التخصصي - الطابق الثالث',
                'consultation_fee' => 120.00,
                'is_active' => true,
            ],
        ];

        $clinicModels = [];
        foreach ($clinics as $clinicData) {
            $clinicModels[$clinicData['code']] = Clinic::updateOrCreate(
                ['code' => $clinicData['code']],
                $clinicData
            );
        }

        // 5. Patients (سجلات المرضى)
        $patients = [
            [
                'file_number' => 'NAS-P-1001',
                'full_name' => 'محمود عبد الرحيم الأغا',
                'national_id' => '401928374',
                'phone' => '0599554433',
                'gender' => 'ذكر',
                'date_of_birth' => '1978-05-14',
                'blood_group' => 'A+',
                'address' => 'خانيونس - حي الأمل - شارع المستشفى',
                'emergency_contact_name' => 'طارق الأغا (ابنه)',
                'emergency_contact_phone' => '0599554434',
                'known_allergies' => 'حساسية من البنسلين ومشتقاته',
                'chronic_diseases' => 'ارتفاع ضغط الدم والسكري النوع الثاني',
            ],
            [
                'file_number' => 'NAS-P-1002',
                'full_name' => 'فاطمة إبراهيم بربخ',
                'national_id' => '402837465',
                'phone' => '0599665544',
                'gender' => 'أنثى',
                'date_of_birth' => '1992-11-20',
                'blood_group' => 'O+',
                'address' => 'خانيونس - وسط البلد - بالقرب من القلعة',
                'emergency_contact_name' => 'حسام بربخ (الزوج)',
                'emergency_contact_phone' => '0599665545',
                'known_allergies' => 'لا يوجد',
                'chronic_diseases' => 'قصور طفيف في الغدة الدرقية',
            ],
            [
                'file_number' => 'NAS-P-1003',
                'full_name' => 'يوسف كمال شعت',
                'national_id' => '403746582',
                'phone' => '0599778899',
                'gender' => 'ذكر',
                'date_of_birth' => '2016-08-04',
                'blood_group' => 'B+',
                'address' => 'خانيونس - جورة اللوت',
                'emergency_contact_name' => 'كمال شعت (الوالد)',
                'emergency_contact_phone' => '0599778890',
                'known_allergies' => 'حساسية موسمية (ربو أطفال)',
                'chronic_diseases' => 'لا يوجد',
            ],
            [
                'file_number' => 'NAS-P-1004',
                'full_name' => 'مريم خليل زعرب',
                'national_id' => '404658392',
                'phone' => '0599881122',
                'gender' => 'أنثى',
                'date_of_birth' => '1965-03-12',
                'blood_group' => 'AB+',
                'address' => 'خانيونس - معن - الشارع العام',
                'emergency_contact_name' => 'سالم زعرب (شقيقها)',
                'emergency_contact_phone' => '0599881123',
                'known_allergies' => 'حساسية من الأسبرين ومسكنات NSAID',
                'chronic_diseases' => 'هشاشة عظام واحتكاك مفصل الركبة',
            ],
            [
                'file_number' => 'NAS-P-1005',
                'full_name' => 'إسماعيل حسن الفرا',
                'national_id' => '405569483',
                'phone' => '0599112233',
                'gender' => 'ذكر',
                'date_of_birth' => '1985-09-30',
                'blood_group' => 'O-',
                'address' => 'خانيونس - الشيخ ناصر',
                'emergency_contact_name' => 'منى الفرا (الزوجة)',
                'emergency_contact_phone' => '0599112234',
                'known_allergies' => 'لا يوجد',
                'chronic_diseases' => 'لا يوجد',
            ],
        ];

        $patientModels = [];
        foreach ($patients as $pData) {
            $patientModels[] = Patient::updateOrCreate(
                ['file_number' => $pData['file_number']],
                $pData
            );
        }

        // 6. Appointments (المواعيد الطبية)
        $now = Carbon::now();
        $appointments = [
            [
                'appointment_number' => 'APT-2026-001',
                'patient_id' => $patientModels[0]->id,
                'doctor_id' => $doctor1->id,
                'clinic_id' => $clinicModels['CLN-CARDIO']->id,
                'appointment_date' => $now->copy()->subHours(2),
                'status' => 'Completed',
                'priority' => 'Urgent',
                'notes' => 'متابعة تسارع نبضات القلب بعد تخطيط الإجهاد.',
            ],
            [
                'appointment_number' => 'APT-2026-002',
                'patient_id' => $patientModels[1]->id,
                'doctor_id' => $doctor1->id,
                'clinic_id' => $clinicModels['CLN-CARDIO']->id,
                'appointment_date' => $now->copy()->addHours(2),
                'status' => 'Confirmed',
                'priority' => 'Normal',
                'notes' => 'فحص دوري لضغط الدم واستشارة سنوية.',
            ],
            [
                'appointment_number' => 'APT-2026-003',
                'patient_id' => $patientModels[2]->id,
                'doctor_id' => $doctor2->id,
                'clinic_id' => $clinicModels['CLN-PEDIATRIC']->id,
                'appointment_date' => $now->copy()->addHours(4),
                'status' => 'Pending',
                'priority' => 'Normal',
                'notes' => 'سعال حاد وارتفاع حرارة الطفل منذ يومين.',
            ],
            [
                'appointment_number' => 'APT-2026-004',
                'patient_id' => $patientModels[3]->id,
                'doctor_id' => $doctor3->id,
                'clinic_id' => $clinicModels['CLN-ORTHO']->id,
                'appointment_date' => $now->copy()->addDay()->setHour(10)->setMinute(0),
                'status' => 'Pending',
                'priority' => 'Normal',
                'notes' => 'ألم شديد في الركبة اليمنى وصعوبة في الحركة.',
            ],
            [
                'appointment_number' => 'APT-2026-005',
                'patient_id' => $patientModels[4]->id,
                'doctor_id' => $doctor1->id,
                'clinic_id' => $clinicModels['CLN-INTERNAL']->id,
                'appointment_date' => $now->copy()->subDay(),
                'status' => 'Completed',
                'priority' => 'Normal',
                'notes' => 'فحوصات طبية روتينية شاملة.',
            ],
        ];

        $appointmentModels = [];
        foreach ($appointments as $aptData) {
            $appointmentModels[] = Appointment::updateOrCreate(
                ['appointment_number' => $aptData['appointment_number']],
                $aptData
            );
        }

        // 7. Medical Records (السجلات الطبية وتفاصيل الكشف)
        MedicalRecord::updateOrCreate(
            ['record_number' => 'REC-2026-001'],
            [
                'patient_id' => $patientModels[0]->id,
                'doctor_id' => $doctor1->id,
                'appointment_id' => $appointmentModels[0]->id,
                'clinic_id' => $clinicModels['CLN-CARDIO']->id,
                'chief_complaint' => 'شعور بضيق في التنفس وخفقان مفاجئ عند بذل مجهود خفيف.',
                'diagnosis' => 'تسارع بطيني طفيف وارتفاع ضغط الدم غير المستقر (Hypertension Stage 2).',
                'prescription' => "1. Concor 5mg - قرص واحد صباحاً يومياً.\n2. Exforge 10/160mg - قرص مساءً بعد العشاء.\n3. Baby Aspirin 81mg - قرص ظهراً بعد الغداء.",
                'doctor_notes' => 'يوصى بتقليل الملح في الطعام، والمشي 30 دقيقة يومياً بعد استقرار الحالة، والعودة للمراجعة بعد أسبوعين مع جدول قياس الضغط اليومي.',
                'blood_pressure' => '150/95',
                'pulse_rate' => '88',
                'temperature' => '36.8',
                'weight' => 84.50,
                'visit_date' => $now->copy()->toDateString(),
            ]
        );

        MedicalRecord::updateOrCreate(
            ['record_number' => 'REC-2026-002'],
            [
                'patient_id' => $patientModels[4]->id,
                'doctor_id' => $doctor1->id,
                'appointment_id' => $appointmentModels[4]->id,
                'clinic_id' => $clinicModels['CLN-INTERNAL']->id,
                'chief_complaint' => 'إرهاق عام وصداع نصفي مستمر.',
                'diagnosis' => 'إجهاد بدني مع نقص طفيف في فيتامين د وفقر دم معتدل.',
                'prescription' => "1. Vitamin D3 50,000 IU - كبسولة واحدة أسبوعياً لمدة شهرين.\n2. Fefol (Iron + Folic acid) - كبسولة يومياً بعد الأكل.\n3. Panadol Extra عند اللزوم.",
                'doctor_notes' => 'الراحة التامة والتعرض لأشعة الصباح وتناول الأغذية الغنية بالحديد، مع إعادة فحص CBC بعد شهر.',
                'blood_pressure' => '120/80',
                'pulse_rate' => '72',
                'temperature' => '37.0',
                'weight' => 76.00,
                'visit_date' => $now->copy()->subDay()->toDateString(),
            ]
        );
    }
}
