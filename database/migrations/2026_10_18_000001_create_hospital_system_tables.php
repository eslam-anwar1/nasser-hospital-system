<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Departments / Clinics Table (الأقسام والعيادات الطبية)
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. CLN-CARDIO
            $table->string('name_ar'); // اسم العيادة / القسم
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable(); // مبنى أ - الطابق الثاني
            $table->decimal('consultation_fee', 8, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Patients Table (ملفات المرضى)
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('file_number')->unique(); // رقم الملف الطبي
            $table->string('full_name'); // الاسم الرباعي
            $table->string('national_id')->unique(); // الهوية الوطنية / رقم الإقامة
            $table->string('phone'); // رقم الهاتف للتواصل
            $table->string('gender'); // ذكر / أنثى (Male, Female)
            $table->date('date_of_birth');
            $table->string('blood_group')->nullable(); // فصيلة الدم
            $table->text('address')->nullable(); // العنوان السكني
            $table->string('emergency_contact_name')->nullable(); // جهة الاتصال للطوارئ
            $table->string('emergency_contact_phone')->nullable();
            $table->text('known_allergies')->nullable(); // الحساسيات الدوائية والغذائية
            $table->text('chronic_diseases')->nullable(); // الأمراض المزمنة
            $table->timestamps();
        });

        // Appointments Table (جدول المواعيد الطبية)
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique(); // رقم الحجز
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->dateTime('appointment_date');
            $table->enum('status', ['Pending', 'Completed', 'Cancelled', 'Confirmed'])->default('Pending');
            $table->enum('priority', ['Normal', 'Urgent', 'Emergency'])->default('Normal');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Medical Records / Consultations Table (السجلات الطبية وتفاصيل الكشف)
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->text('chief_complaint'); // الشكوى الرئيسية
            $table->text('diagnosis'); // التشخيص الطبي
            $table->text('prescription')->nullable(); // الوصفة الطبية والعلاج
            $table->text('doctor_notes')->nullable(); // ملاحظات وتوصيات الطبيب
            $table->string('blood_pressure')->nullable(); // ضغط الدم
            $table->string('pulse_rate')->nullable(); // النبض
            $table->string('temperature')->nullable(); // درجة الحرارة
            $table->decimal('weight', 5, 2)->nullable(); // الوزن كجم
            $table->date('visit_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('clinics');
    }
};
