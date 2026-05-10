<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\LegalCategory;
use App\Models\Keyword;
use App\Models\LegalProcedure;
use App\Models\ProcedureStep;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $arabic = Language::firstOrCreate(['code' => 'ar'], ['name' => 'العربية', 'is_default' => true]);

        LegalCategory::firstOrCreate(['name' => 'إجراءات حكومية'], ['description' => 'خطوات قانونية بسيطة للمواطنين']);

        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'مشرف النظام',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $category = LegalCategory::first();

        $procedure = LegalProcedure::firstOrCreate([
            'legal_category_id' => $category->id,
            'language_id' => $arabic->id,
            'title' => 'إصدار جواز سفر جديد',
        ], [
            'description' => 'يمكنك التقدم للحصول على جواز سفر جديد من خلال المقاعد الإدارية المختصة في مقاطعتك.',
            'summary' => 'توجه إلى مكتب الجوازات بحجز مسبق، قدم الوثائق المطلوبة، واستلم جواز السفر بعد الموافقة.',
        ]);

        ProcedureStep::firstOrCreate([
            'legal_procedure_id' => $procedure->id,
            'step_number' => 1,
        ], [
            'title' => 'جمع الوثائق',
            'description' => 'نسخة من بطاقة التعريف الوطنية وصور شخصية حديثة.',
            'requirements' => 'بطاقة التعريف الوطنية سارية المفعول.',
            'documents_needed' => 'نسختان من البطاقة، صورتان شخصيتان.',
        ]);

        ProcedureStep::firstOrCreate([
            'legal_procedure_id' => $procedure->id,
            'step_number' => 2,
        ], [
            'title' => 'تقديم الطلب',
            'description' => 'قدّم الملف إلى المكتب الحكومي المختص أو عبر البوابة الإلكترونية إذا كانت متاحة.',
            'requirements' => 'ملف مرتب ووثائق واضحة.',
            'documents_needed' => 'طلب مكتمل، إيصال الدفع.',
        ]);

        Keyword::firstOrCreate([
            'legal_procedure_id' => $procedure->id,
            'keyword' => 'جواز سفر',
        ], ['weight' => 3]);

        Keyword::firstOrCreate([
            'legal_procedure_id' => $procedure->id,
            'keyword' => 'اصدار',
        ], ['weight' => 2]);

        Keyword::firstOrCreate([
            'legal_procedure_id' => $procedure->id,
            'keyword' => 'جديد',
        ], ['weight' => 1]);
    }
}
