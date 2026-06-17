<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $sort = 0;
        foreach ($this->definitions() as $def) {
            $sort++;
            $category = Category::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'description' => $def['about'] ?? null,
                    'landing_meta' => [
                        'title' => $def['title'] ?? $def['name'],
                        'subtitle' => $def['subtitle'] ?? '',
                        'about' => $def['about'] ?? '',
                        'legacy_course_category' => $def['legacy_course_category'] ?? null,
                        'color' => $def['color'] ?? 'blue',
                        'icon_url' => $def['icon_url'] ?? null,
                        'hero_bg' => $def['hero_bg'] ?? 'from-blue-50 to-indigo-50',
                        'tags' => $def['tags'] ?? [],
                        'subjects' => $def['subjects'] ?? [],
                        'features' => $def['features'] ?? [],
                        'batches_types' => $def['batches_types'] ?? [],
                        'icon_bg' => $def['icon_bg'] ?? 'from-indigo-500 to-blue-500',
                        'icon_svg' => $def['icon_svg'] ?? null,
                    ],
                    'is_active' => true,
                    'sort_order' => $sort,
                ]
            );

            $subSort = 0;
            foreach ($def['subcategories'] ?? [] as $subName) {
                $subSort++;
                Subcategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($subName),
                    ],
                    [
                        'name' => $subName,
                        'is_active' => true,
                        'sort_order' => $subSort,
                    ]
                );
            }
        }
    }

    private function definitions(): array
    {
        return [
            [
                'slug' => 'iit-jee',
                'name' => 'IIT JEE',
                'title' => 'IIT JEE',
                'subtitle' => 'Joint Entrance Examination (Main + Advanced)',
                'legacy_course_category' => 'JEE',
                'color' => 'orange',
                'icon_url' => 'https://img.icons8.com/color/96/physics.png',
                'hero_bg' => 'from-orange-50 to-amber-50',
                'icon_bg' => 'from-indigo-500 to-blue-500',
                'icon_svg' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                'tags' => ['Class 11', 'Class 12', 'Dropper'],
                'subcategories' => ['Class 11', 'Class 12', 'Dropper'],
                'about' => 'The Joint Entrance Examination (JEE) is India\'s most prestigious engineering entrance exam.',
                'subjects' => ['Physics', 'Chemistry', 'Mathematics'],
                'features' => ['700+ hours of Live Classes', 'Daily Practice Papers (DPPs)', 'Chapter-wise Tests', '40 Full Mock Tests'],
            ],
            [
                'slug' => 'neet-ug',
                'name' => 'NEET',
                'title' => 'NEET UG',
                'subtitle' => 'National Eligibility cum Entrance Test (UG)',
                'legacy_course_category' => 'NEET',
                'color' => 'red',
                'icon_url' => 'https://img.icons8.com/color/96/medical-doctor.png',
                'hero_bg' => 'from-red-50 to-rose-50',
                'icon_bg' => 'from-emerald-500 to-teal-500',
                'icon_svg' => 'M19.428 15.341A8 8 0 118.659 4.572l1.839 1.839A3 3 0 0014.12 8.65l3.52-3.52A8.001 8.001 0 0119.428 15.34z',
                'subcategories' => ['Class 11', 'Class 12', 'Dropper'],
                'about' => 'NEET UG is the single gateway to MBBS, BDS, AYUSH, and all medical programs across India.',
                'subjects' => ['Physics', 'Chemistry', 'Biology (Botany + Zoology)'],
                'features' => ['600+ hours of Live Classes', 'NCERT Line-by-Line Coverage', '30 Full Mock Tests (NTA Pattern)'],
            ],
            [
                'slug' => 'pre-foundation',
                'name' => 'Pre Foundation',
                'legacy_course_category' => 'Foundation',
                'color' => 'yellow',
                'hero_bg' => 'from-yellow-50 to-amber-50',
                'icon_bg' => 'from-amber-500 to-orange-500',
                'icon_svg' => 'M12 14l9-5-9-5-9 5 9 5z',
                'subcategories' => ['Class 8', 'Class 9', 'Class 10'],
                'about' => 'Early excellence for Class 8, 9, and 10 students.',
            ],
            [
                'slug' => 'school-boards',
                'name' => 'School Boards',
                'legacy_course_category' => 'Foundation',
                'color' => 'blue',
                'hero_bg' => 'from-blue-50 to-indigo-50',
                'icon_bg' => 'from-sky-500 to-indigo-500',
                'icon_svg' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13',
                'subcategories' => ['CBSE', 'ICSE', 'State Boards'],
                'about' => 'CBSE, ICSE, and State Board exam excellence.',
            ],
            [
                'slug' => 'upsc',
                'name' => 'UPSC',
                'color' => 'indigo',
                'hero_bg' => 'from-indigo-50 to-blue-50',
                'icon_bg' => 'from-violet-500 to-fuchsia-500',
                'icon_svg' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'subcategories' => ['Civil Services', 'CDS', 'CAPF'],
                'about' => 'Union Public Service Commission civil services preparation.',
            ],
            [
                'slug' => 'nda',
                'name' => 'NDA & Defence',
                'color' => 'slate',
                'hero_bg' => 'from-slate-100 to-gray-50',
                'icon_bg' => 'from-indigo-500 to-violet-500',
                'icon_svg' => 'M12 3l8 4v6c0 5.25-3.438 10.5-8 12-4.562-1.5-8-6.75-8-12V7l8-4z',
                'subcategories' => ['NDA', 'Airforce X/Y', 'Navy AA/SSR'],
                'about' => 'NDA and defence entrance exam programs.',
            ],
            [
                'slug' => 'language-courses',
                'name' => 'Language Programs',
                'color' => 'yellow',
                'hero_bg' => 'from-yellow-50 to-amber-50',
                'icon_bg' => 'from-teal-500 to-emerald-500',
                'icon_svg' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10',
                'subcategories' => ['English Speaking', 'Communication Skills', 'Sanskrit'],
                'about' => 'Language and communication skill programs.',
            ],
            [
                'slug' => 'govt-job-exams',
                'name' => 'Govt Job Exams',
                'color' => 'purple',
                'hero_bg' => 'from-purple-50 to-fuchsia-50',
                'icon_bg' => 'from-rose-500 to-pink-500',
                'icon_svg' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H5m14 0v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5',
                'subcategories' => ['SSC CGL', 'Banking', 'Teaching'],
                'about' => 'Government job and teaching eligibility exams.',
            ],
            [
                'slug' => 'computer-skills',
                'name' => 'IT Courses',
                'color' => 'green',
                'hero_bg' => 'from-green-50 to-emerald-50',
                'icon_bg' => 'from-cyan-500 to-blue-500',
                'icon_svg' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'subcategories' => [
                    'Data Science',
                    'Data Analytics',
                    'Full Stack Development',
                    'UI/UX',
                ],
                'about' => 'IT, coding, and technology skill programs.',
            ],
        ];
    }
}
