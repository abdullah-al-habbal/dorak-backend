<?php

declare(strict_types=1);

return [
    // Home page sections -------------------------------------------------
    [
        'page_slug' => 'home', 'type' => 'hero',
        'title' => null, 'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Manage Your Salon. Grow Your Business.',
                'subheading' => 'Dorak is the all-in-one platform for salons, barbershops, and freelance barbers. One dashboard, endless possibilities.',
                'cta_text' => 'Create Your Brand',
                'cta_url' => '/register',
                'image' => '/images/hero-neutral.jpg',
            ],
            'ar' => [
                'heading' => 'أدر صالونك. نمِّ أعمالك.',
                'subheading' => 'دورك هي المنصة المتكاملة لصالونات التجميل ومحلات الحلاقة والحلاقين المستقلين. لوحة تحكم واحدة، إمكانيات لا حصر لها.',
                'cta_text' => 'أنشئ علامتك التجارية',
                'cta_url' => '/register',
                'image' => '/images/hero-neutral.jpg',
            ],
        ],
        'media_url' => '/images/hero-neutral.jpg', 'sort_order' => 0, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'home', 'type' => 'feature_list',
        'title' => ['en' => 'Why Dorak?', 'ar' => 'لماذا دورك؟'],
        'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Why Dorak?',
                'features' => [
                    ['title' => 'Branch-First Architecture', 'description' => 'Manage one shop or a hundred. Your data grows with you.', 'icon' => 'building'],
                    ['title' => 'Standalone Barbers', 'description' => 'Barbers own their profiles. Freelancers and affiliates, all in one system.', 'icon' => 'user'],
                    ['title' => 'Backend-Driven UI', 'description' => 'The shop floor is described by the backend and drawn by the app. Dynamic, not static.', 'icon' => 'layout'],
                ],
            ],
            'ar' => [
                'heading' => 'لماذا دورك؟',
                'features' => [
                    ['title' => 'هندسة الفروع أولاً', 'description' => 'أدر محلاً واحداً أو مئة. بياناتك تنمو معك.', 'icon' => 'building'],
                    ['title' => 'حلاقون مستقلون', 'description' => 'الحلاقون يمتلكون ملفاتهم الشخصية. المستقلون والمنتسبون، كلهم في نظام واحد.', 'icon' => 'user'],
                    ['title' => 'واجهة مدعومة من الخلفية', 'description' => 'صالة المحل توصف من الخلفية وترسم من التطبيق. ديناميكية وليست ثابتة.', 'icon' => 'layout'],
                ],
            ],
        ],
        'media_url' => null, 'sort_order' => 1, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'home', 'type' => 'testimonials',
        'title' => ['en' => 'What Our Users Say', 'ar' => 'ماذا يقول مستخدمونا'],
        'subtitle' => null,
        'content' => [
            'en' => ['heading' => 'What Our Users Say'],
            'ar' => ['heading' => 'ماذا يقول مستخدمونا'],
        ],
        'media_url' => null, 'sort_order' => 2, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'home', 'type' => 'floor_plan_demo',
        'title' => ['en' => 'See It In Action', 'ar' => 'شاهدها في العمل'],
        'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'See It In Action',
                'description' => 'Click a chair to see how easy booking is. This is the exact same interface your clients will see.',
            ],
            'ar' => [
                'heading' => 'شاهدها في العمل',
                'description' => 'انقر على كرسي لترى كيف أصبح الحجز سهلاً. هذه هي نفس الواجهة التي سيراها عملاؤك.',
            ],
        ],
        'media_url' => null, 'sort_order' => 3, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'home', 'type' => 'pricing',
        'title' => ['en' => 'Simple Pricing', 'ar' => 'أسعار بسيطة'],
        'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Simple Pricing',
                'plans' => [
                    ['name' => 'Freemium', 'price' => 'Free', 'features' => ['1 Branch', 'Up to 5 Chairs', 'Basic Analytics'], 'cta' => 'Get Started'],
                    ['name' => 'Premium', 'price' => '$29/month', 'features' => ['Unlimited Branches', 'Unlimited Chairs', 'Job Board', 'Advanced Analytics'], 'cta' => 'Upgrade Now'],
                ],
            ],
            'ar' => [
                'heading' => 'أسعار بسيطة',
                'plans' => [
                    ['name' => 'مجاني', 'price' => 'مجاناً', 'features' => ['فرع واحد', 'حتى 5 كراسي', 'تحليلات أساسية'], 'cta' => 'ابدأ الآن'],
                    ['name' => 'مميز', 'price' => '29$/شهرياً', 'features' => ['فروع غير محدودة', 'كراسي غير محدودة', 'لوحة وظائف', 'تحليلات متقدمة'], 'cta' => 'قم بالترقية'],
                ],
            ],
        ],
        'media_url' => null, 'sort_order' => 4, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'home', 'type' => 'cta',
        'title' => null, 'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Ready to Transform Your Salon?',
                'subheading' => 'Join hundreds of salon owners who trust Dorak.',
                'cta_text' => 'Create Your Free Account',
                'cta_url' => '/register',
            ],
            'ar' => [
                'heading' => 'هل أنت مستعد لتغيير صالونك؟',
                'subheading' => 'انضم إلى مئات أصحاب الصالونات الذين يثقون بدورك.',
                'cta_text' => 'أنشئ حسابك المجاني',
                'cta_url' => '/register',
            ],
        ],
        'media_url' => null, 'sort_order' => 5, 'universe_visibility' => 'all',
    ],

    // Features page sections --------------------------------------------
    [
        'page_slug' => 'features', 'type' => 'hero',
        'title' => null, 'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Built for Salons, Barbershops, and Freelancers',
                'subheading' => 'Every feature is designed around how the grooming industry actually works.',
                'cta_text' => 'See Pricing',
                'cta_url' => '/pricing',
            ],
            'ar' => [
                'heading' => 'مبني للصالونات ومحلات الحلاقة والمستقلين',
                'subheading' => 'كل ميزة مصممة حول كيفية عمل صناعة العناية الشخصية فعلياً.',
                'cta_text' => 'شاهد الأسعار',
                'cta_url' => '/pricing',
            ],
        ],
        'media_url' => null, 'sort_order' => 0, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'features', 'type' => 'feature_list',
        'title' => ['en' => 'Everything You Need', 'ar' => 'كل ما تحتاجه'],
        'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Everything You Need',
                'features' => [
                    ['title' => 'Multi-Branch Management', 'description' => 'Add unlimited branches under one brand. Centralized dashboard, local control.', 'icon' => 'building'],
                    ['title' => 'Smart Scheduling', 'description' => 'Clients book their own chairs. Real-time availability. No more phone tag.', 'icon' => 'calendar'],
                    ['title' => 'Barber Profiles', 'description' => 'Every barber has a profile. Clients follow their favorite barber, not just a shop.', 'icon' => 'user'],
                    ['title' => 'Analytics & Insights', 'description' => 'Know your busiest hours, top services, and revenue trends at a glance.', 'icon' => 'chart'],
                ],
            ],
            'ar' => [
                'heading' => 'كل ما تحتاجه',
                'features' => [
                    ['title' => 'إدارة فروع متعددة', 'description' => 'أضف فروعاً غير محدودة تحت علامة تجارية واحدة. لوحة تحكم مركزية، تحكم محلي.', 'icon' => 'building'],
                    ['title' => 'جدولة ذكية', 'description' => 'يحجز العملاء كراسيهم بأنفسهم. توفر فوري. لا مزيد من المكالمات الهاتفية.', 'icon' => 'calendar'],
                    ['title' => 'ملفات الحلاقين', 'description' => 'كل حلاق لديه ملف شخصي. العملاء يتابعون حلاقهم المفضل، وليس مجرد محل.', 'icon' => 'user'],
                    ['title' => 'تحليلات ورؤى', 'description' => 'اعرف ساعات الذروة والخدمات الأكثر طلباً واتجاهات الإيرادات بنظرة واحدة.', 'icon' => 'chart'],
                ],
            ],
        ],
        'media_url' => null, 'sort_order' => 1, 'universe_visibility' => 'all',
    ],

    // Pricing page sections ---------------------------------------------
    [
        'page_slug' => 'pricing', 'type' => 'hero',
        'title' => null, 'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Start Free. Scale When You Need.',
                'subheading' => 'No hidden fees. No surprises. Your first branch is always free.',
            ],
            'ar' => [
                'heading' => 'ابدأ مجاناً. وسّع عندما تحتاج.',
                'subheading' => 'لا رسوم مخفية. لا مفاجآت. فرعك الأول مجاني دائماً.',
            ],
        ],
        'media_url' => null, 'sort_order' => 0, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'pricing', 'type' => 'pricing',
        'title' => ['en' => 'Choose Your Plan', 'ar' => 'اختر خطتك'],
        'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Choose Your Plan',
                'plans' => [
                    ['name' => 'Freemium', 'price' => 'Free', 'features' => ['1 Branch', 'Up to 5 Chairs', 'Basic Analytics', 'Standard Support'], 'cta' => 'Get Started', 'highlighted' => false],
                    ['name' => 'Premium', 'price' => '$29/month', 'features' => ['Unlimited Branches', 'Unlimited Chairs', 'Job Board Access', 'Advanced Analytics', 'Priority Support'], 'cta' => 'Upgrade Now', 'highlighted' => true],
                ],
            ],
            'ar' => [
                'heading' => 'اختر خطتك',
                'plans' => [
                    ['name' => 'مجاني', 'price' => 'مجاناً', 'features' => ['فرع واحد', 'حتى 5 كراسي', 'تحليلات أساسية', 'دعم عادي'], 'cta' => 'ابدأ الآن', 'highlighted' => false],
                    ['name' => 'مميز', 'price' => '29$/شهرياً', 'features' => ['فروع غير محدودة', 'كراسي غير محدودة', 'لوحة وظائف', 'تحليلات متقدمة', 'دعم أولوية'], 'cta' => 'قم بالترقية', 'highlighted' => true],
                ],
            ],
        ],
        'media_url' => null, 'sort_order' => 1, 'universe_visibility' => 'all',
    ],
    [
        'page_slug' => 'pricing', 'type' => 'cta',
        'title' => null, 'subtitle' => null,
        'content' => [
            'en' => [
                'heading' => 'Still Have Questions?',
                'subheading' => 'Contact our sales team for a personalized demo.',
                'cta_text' => 'Contact Sales',
                'cta_url' => '/contact',
            ],
            'ar' => [
                'heading' => 'لا تزال لديك أسئلة؟',
                'subheading' => 'اتصل بفريق المبيعات لدينا لجلسة تعريفية شخصية.',
                'cta_text' => 'اتصل بالمبيعات',
                'cta_url' => '/contact',
            ],
        ],
        'media_url' => null, 'sort_order' => 2, 'universe_visibility' => 'all',
    ],
];
