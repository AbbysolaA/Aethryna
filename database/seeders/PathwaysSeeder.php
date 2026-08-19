<?php

namespace Database\Seeders;

use App\Models\Pathway;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PathwaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pathways = [
            // Technical Pathways (T)
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'category' => 'technical',
                'description' => 'Build modern websites and web applications using HTML, CSS, JavaScript, and popular frameworks.',
                'recommended_for' => 'You enjoy solving problems and figuring out how things work. You\'re motivated by building tools and systems people rely on.',
                'skills' => [
                    'HTML5 & CSS3',
                    'JavaScript (ES6+)',
                    'React/Vue.js Frameworks',
                    'Node.js & Express',
                    'Database Design',
                    'API Development',
                    'Version Control (Git)',
                    'Responsive Design'
                ],
                'career_paths' => [
                    'Frontend Developer',
                    'Full-Stack Developer',
                    'Web Application Developer',
                    'UI/UX Developer',
                    'Technical Lead'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 6,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Software Development Foundations',
                'slug' => 'software-development-foundations',
                'category' => 'technical',
                'description' => 'Learn core programming concepts, algorithms, and software development principles.',
                'recommended_for' => 'You enjoy solving problems and figuring out how things work. You\'re motivated by building tools and systems people rely on.',
                'skills' => [
                    'Programming Fundamentals',
                    'Data Structures & Algorithms',
                    'Object-Oriented Programming',
                    'Problem Solving',
                    'Code Optimization',
                    'Software Design Patterns',
                    'Testing & Debugging',
                    'Version Control'
                ],
                'career_paths' => [
                    'Software Developer',
                    'Application Developer',
                    'Systems Analyst',
                    'Technical Consultant',
                    'Software Engineer'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 8,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Data & Business Analytics',
                'slug' => 'data-analytics',
                'category' => 'technical',
                'description' => 'Master data analysis, visualization, and business intelligence tools.',
                'recommended_for' => 'You enjoy solving problems and figuring out how things work. You\'re motivated by building tools and systems people rely on.',
                'skills' => [
                    'SQL & Database Querying',
                    'Python/R for Data Analysis',
                    'Excel Advanced Functions',
                    'Data Visualization (Tableau/Power BI)',
                    'Statistical Analysis',
                    'Business Intelligence',
                    'Data Cleaning & Processing',
                    'Reporting & Dashboards'
                ],
                'career_paths' => [
                    'Data Analyst',
                    'Business Intelligence Analyst',
                    'Data Scientist',
                    'Analytics Consultant',
                    'Business Analyst'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 6,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'AI & Generative AI',
                'slug' => 'ai-generative-ai',
                'category' => 'technical',
                'description' => 'Explore artificial intelligence, machine learning, and generative AI technologies.',
                'recommended_for' => 'You enjoy solving problems and figuring out how things work. You\'re motivated by building tools and systems people rely on.',
                'skills' => [
                    'Python Programming',
                    'Machine Learning Fundamentals',
                    'Data Science Libraries',
                    'Generative AI Concepts',
                    'Prompt Engineering',
                    'AI Ethics & Bias',
                    'Model Training & Deployment',
                    'AI Tool Integration'
                ],
                'career_paths' => [
                    'AI Developer',
                    'Machine Learning Engineer',
                    'AI Consultant',
                    'Data Scientist',
                    'AI Product Manager'
                ],
                'difficulty_level' => 'advanced',
                'duration_months' => 9,
                'image_path' => null,
                'is_active' => true,
            ],

            // Creative Pathways (C)
            [
                'name' => 'UI/UX & Digital Design',
                'slug' => 'ui-ux-design',
                'category' => 'creative',
                'description' => 'Create beautiful, user-centered digital experiences and interfaces.',
                'recommended_for' => 'You care about how things look, feel, and connect with people. You\'re drawn to visuals, experiences, and stories.',
                'skills' => [
                    'User Research & Analysis',
                    'Wireframing & Prototyping',
                    'UI Design Principles',
                    'User Experience Design',
                    'Design Systems',
                    'Figma/Adobe XD',
                    'Usability Testing',
                    'Visual Design Theory'
                ],
                'career_paths' => [
                    'UI/UX Designer',
                    'Product Designer',
                    'Interaction Designer',
                    'User Experience Researcher',
                    'Design System Lead'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 6,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Product Design Foundations',
                'slug' => 'product-design-foundations',
                'category' => 'creative',
                'description' => 'Learn to design products from concept to launch with user-centered thinking.',
                'recommended_for' => 'You care about how things look, feel, and connect with people. You\'re drawn to visuals, experiences, and stories.',
                'skills' => [
                    'Product Strategy',
                    'User Journey Mapping',
                    'Prototyping Techniques',
                    'Design Thinking',
                    'Product Validation',
                    'Iterative Design',
                    'Stakeholder Communication',
                    'Design Tools & Software'
                ],
                'career_paths' => [
                    'Product Designer',
                    'Design Strategist',
                    'Product Manager',
                    'UX Strategist',
                    'Innovation Consultant'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 7,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'category' => 'creative',
                'description' => 'Master digital marketing strategies, content creation, and brand storytelling.',
                'recommended_for' => 'You care about how things look, feel, and connect with people. You\'re drawn to visuals, experiences, and stories.',
                'skills' => [
                    'Content Strategy',
                    'Social Media Marketing',
                    'Brand Storytelling',
                    'Digital Campaign Management',
                    'SEO & SEM',
                    'Analytics & Reporting',
                    'Content Creation',
                    'Marketing Automation'
                ],
                'career_paths' => [
                    'Digital Marketing Specialist',
                    'Content Strategist',
                    'Social Media Manager',
                    'Brand Manager',
                    'Marketing Coordinator'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 5,
                'image_path' => null,
                'is_active' => true,
            ],

            // Business Pathways (B)
            [
                'name' => 'Project Management',
                'slug' => 'project-management',
                'category' => 'business',
                'description' => 'Learn to plan, execute, and deliver successful projects on time and within budget.',
                'recommended_for' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'skills' => [
                    'Project Planning & Scheduling',
                    'Risk Management',
                    'Stakeholder Communication',
                    'Agile & Scrum Methodologies',
                    'Budget Management',
                    'Team Leadership',
                    'Quality Assurance',
                    'Change Management'
                ],
                'career_paths' => [
                    'Project Manager',
                    'Program Manager',
                    'Scrum Master',
                    'Project Coordinator',
                    'Operations Manager'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 6,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Scrum Master / Agile Practitioner',
                'slug' => 'scrum-master',
                'category' => 'business',
                'description' => 'Master agile methodologies and become a certified Scrum Master.',
                'recommended_for' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'skills' => [
                    'Scrum Framework',
                    'Agile Principles',
                    'Sprint Planning & Execution',
                    'Team Facilitation',
                    'Conflict Resolution',
                    'Continuous Improvement',
                    'Metrics & Reporting',
                    'Coaching & Mentoring'
                ],
                'career_paths' => [
                    'Scrum Master',
                    'Agile Coach',
                    'Team Lead',
                    'Process Improvement Specialist',
                    'Agile Consultant'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 4,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Business Analysis',
                'slug' => 'business-analysis',
                'category' => 'business',
                'description' => 'Bridge the gap between business needs and technical solutions.',
                'recommended_for' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'skills' => [
                    'Requirements Gathering',
                    'Business Process Modeling',
                    'Data Analysis',
                    'Stakeholder Management',
                    'Solution Design',
                    'Change Management',
                    'Documentation',
                    'Quality Assurance'
                ],
                'career_paths' => [
                    'Business Analyst',
                    'Requirements Analyst',
                    'Systems Analyst',
                    'Process Analyst',
                    'Product Owner'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 6,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Product Management',
                'slug' => 'product-management',
                'category' => 'business',
                'description' => 'Learn to define, build, and launch successful digital products.',
                'recommended_for' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'skills' => [
                    'Product Strategy',
                    'Market Research',
                    'Roadmap Planning',
                    'User Story Writing',
                    'Prioritization Techniques',
                    'Metrics & KPIs',
                    'Cross-functional Leadership',
                    'Product Launch Management'
                ],
                'career_paths' => [
                    'Product Manager',
                    'Associate Product Manager',
                    'Product Owner',
                    'Growth Manager',
                    'Product Marketing Manager'
                ],
                'difficulty_level' => 'advanced',
                'duration_months' => 8,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'IT Service & Architecture Foundations',
                'slug' => 'it-service-architecture',
                'category' => 'business',
                'description' => 'Understand IT service management and enterprise architecture principles.',
                'recommended_for' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'skills' => [
                    'IT Service Management',
                    'ITIL Framework',
                    'Enterprise Architecture',
                    'Service Design',
                    'Process Optimization',
                    'IT Governance',
                    'Vendor Management',
                    'Change Management'
                ],
                'career_paths' => [
                    'IT Service Manager',
                    'Enterprise Architect',
                    'IT Operations Manager',
                    'Service Delivery Manager',
                    'IT Consultant'
                ],
                'difficulty_level' => 'advanced',
                'duration_months' => 7,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Business & Leadership Essentials',
                'slug' => 'business-leadership',
                'category' => 'business',
                'description' => 'Develop essential business and leadership skills for digital transformation.',
                'recommended_for' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'skills' => [
                    'Leadership & Team Management',
                    'Strategic Thinking',
                    'Communication Skills',
                    'Change Management',
                    'Digital Transformation',
                    'Business Acumen',
                    'Decision Making',
                    'Conflict Resolution'
                ],
                'career_paths' => [
                    'Team Lead',
                    'Project Manager',
                    'Operations Manager',
                    'Business Consultant',
                    'Digital Transformation Lead'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 5,
                'image_path' => null,
                'is_active' => true,
            ],

            // Security Pathways (S)
            [
                'name' => 'Cybersecurity Foundations',
                'slug' => 'cybersecurity-foundations',
                'category' => 'security',
                'description' => 'Learn the fundamentals of cybersecurity and digital protection.',
                'recommended_for' => 'You notice details, think in risks and what ifs, and like understanding how systems work under the surface.',
                'skills' => [
                    'Network Security',
                    'Cryptography Basics',
                    'Risk Assessment',
                    'Security Policies',
                    'Incident Response',
                    'Ethical Hacking',
                    'Security Tools',
                    'Compliance & Regulations'
                ],
                'career_paths' => [
                    'Security Analyst',
                    'Cybersecurity Specialist',
                    'Information Security Officer',
                    'Security Consultant',
                    'Compliance Officer'
                ],
                'difficulty_level' => 'intermediate',
                'duration_months' => 7,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Cloud Computing & DevOps Intro',
                'slug' => 'cloud-devops-intro',
                'category' => 'security',
                'description' => 'Master cloud platforms and DevOps practices with a focus on security.',
                'recommended_for' => 'You notice details, think in risks and what ifs, and like understanding how systems work under the surface.',
                'skills' => [
                    'Cloud Platforms (AWS/Azure/GCP)',
                    'Infrastructure as Code',
                    'CI/CD Pipelines',
                    'Containerization (Docker)',
                    'Monitoring & Logging',
                    'Cloud Security',
                    'Automation',
                    'Performance Optimization'
                ],
                'career_paths' => [
                    'DevOps Engineer',
                    'Cloud Architect',
                    'Site Reliability Engineer',
                    'Cloud Security Engineer',
                    'Infrastructure Engineer'
                ],
                'difficulty_level' => 'advanced',
                'duration_months' => 8,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'IT Support & Operations',
                'slug' => 'it-support-operations',
                'category' => 'security',
                'description' => 'Provide technical support and manage IT infrastructure and operations.',
                'recommended_for' => 'You notice details, think in risks and what ifs, and like understanding how systems work under the surface.',
                'skills' => [
                    'Technical Support',
                    'System Administration',
                    'Network Troubleshooting',
                    'Hardware Maintenance',
                    'User Training',
                    'Documentation',
                    'Help Desk Management',
                    'Problem Solving'
                ],
                'career_paths' => [
                    'IT Support Specialist',
                    'System Administrator',
                    'Help Desk Manager',
                    'Technical Support Engineer',
                    'IT Operations Specialist'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 5,
                'image_path' => null,
                'is_active' => true,
            ],

            // Foundation Pathway (F)
            [
                'name' => 'Digital Literacy & Foundations',
                'slug' => 'digital-foundations',
                'category' => 'foundation',
                'description' => 'Build essential digital skills and computer literacy for beginners.',
                'recommended_for' => 'You\'re developing your digital confidence. This is where your rise begins.',
                'skills' => [
                    'Computer Basics',
                    'Internet & Email',
                    'Microsoft Office/Google Workspace',
                    'File Management',
                    'Online Safety',
                    'Basic Troubleshooting',
                    'Digital Communication',
                    'Productivity Tools'
                ],
                'career_paths' => [
                    'Administrative Assistant',
                    'Data Entry Specialist',
                    'Office Support',
                    'Digital Assistant',
                    'Entry-level IT Support'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 3,
                'image_path' => null,
                'is_active' => true,
            ],
        ];

        /*
         * The four tracks Cohort 1 actually runs, against the four the site
         * markets:
         *
         *   Project and Product Delivery  → project-management
         *   Data and AI Analytics         → data-analytics
         *   Product Design and Marketing  → ui-ux-design
         *   Software Development          → software-development-foundations
         *
         * The other thirteen stay: the assessment scores against all of them
         * and they are honest directions to point somebody in. They are just
         * described as directions rather than as courses we teach, here and on
         * their own pages.
         *
         * Change this list when the pilot set changes; nothing else needs to
         * know which is which.
         */
        $pilotSlugs = [
            'project-management',
            'data-analytics',
            'ui-ux-design',
            'software-development-foundations',
        ];

        foreach ($pathways as $pathway) {
            $pathway['is_pilot'] = in_array($pathway['slug'], $pilotSlugs, true);

            // updateOrCreate, not create: this used to insert unconditionally,
            // so a second run either duplicated all seventeen or died on the
            // unique slug. Keyed on the slug, which is also the public URL, so
            // re-seeding refreshes copy in place rather than orphaning a page.
            Pathway::updateOrCreate(['slug' => $pathway['slug']], $pathway);
        }

        $this->command->info(
            'Pathways seeded: ' . count($pathways) . ' total, '
            . count($pilotSlugs) . ' marked as pilot tracks.'
        );
    }
}
