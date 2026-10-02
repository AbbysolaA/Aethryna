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
                'name' => 'Software Development',
                'slug' => 'software-development-foundations',
                'category' => 'technical',
                'description' => 'Build working software and prove it works. Web fundamentals, Git, testing and AI-assisted development where you can explain every line you ship. Roles: Junior Developer, QA and Test Analyst, Freelance Web Developer.',
                'hero_promise' => 'Build working software. Be able to explain every line.',
                'learn_text' => 'How the web works, then HTML, CSS and JavaScript by building real pages from week one. Git and GitHub as daily habits. Testing, debugging and reading error messages calmly. Then AI-assisted development done properly: early on, AI tutors you and you write the code; later, AI pairs with you at full speed and you review, test and explain everything you ship. We are honest that this is the discipline employers now hire for, and it is the one thing a folder of tutorial projects cannot show.',
                'make_text' => 'A hand-built personal site, an interactive tool using live data, a deployed product of your own tested by real users, a documented repository and a recorded technical walkthrough.',
                'leads_text' => 'Junior Developer, QA and Test Analyst and trainee roles, freelance web work for small businesses, or the builder seat every venture team needs in the Project Period.',
                'recommended_for' => 'You want to make things that work, you enjoy solving problems and you are willing to learn the discipline of proving your code does what you claim. No prior coding needed.',
                'skills' => [
                    'How the Web Works',
                    'HTML, CSS & JavaScript',
                    'Git & GitHub',
                    'Testing & Debugging',
                    'Reading Error Messages',
                    'AI-Assisted Development',
                    'Code Review',
                    'Shipping & Deployment'
                ],
                'career_paths' => [
                    'Junior Developer',
                    'QA and Test Analyst',
                    'Freelance Web Developer'
                ],
                'difficulty_level' => 'beginner',
                'duration_months' => 8,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Data and AI Analytics',
                'slug' => 'data-analytics',
                'category' => 'technical',
                'description' => 'Work with numbers, evidence and insight. SQL, spreadsheets, visualisation and AI-assisted analysis with built-in verification. Roles: Data Analyst, Insight Analyst, AI Operations Analyst.',
                'hero_promise' => 'Turn numbers into decisions people trust.',
                'learn_text' => 'Data thinking from first principles. Spreadsheets properly, then SQL. Cleaning messy real-world data, visualisation that communicates, dashboards and an introduction to Python for data work. AI runs through all of it: you will use it to accelerate analysis and you will be graded on catching its mistakes, because an analyst who cannot verify is not an analyst.',
                'make_text' => 'A cleaned real dataset, a portfolio of SQL queries, a visualisation pack, an insight memo written for a decision-maker and a live dashboard.',
                'leads_text' => 'Data Analyst, Insight Analyst and AI Operations Analyst roles, freelance analytics for small businesses, or the evidence seat on a venture team in the Project Period.',
                'recommended_for' => 'You like evidence, patterns and getting to the truth of things. You want a skill set every organisation needs, and you are happy to let the data disagree with you.',
                'skills' => [
                    'Data Thinking',
                    'Spreadsheets',
                    'SQL',
                    'Data Cleaning',
                    'Visualisation',
                    'Dashboards',
                    'Python for Data',
                    'AI-Assisted Analysis'
                ],
                'career_paths' => [
                    'Data Analyst',
                    'Insight Analyst',
                    'AI Operations Analyst'
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
                'name' => 'Product Design and Marketing',
                'slug' => 'ui-ux-design',
                'category' => 'creative',
                'description' => 'Create products people understand and want. User research, interface design, brand, content and launch marketing, with AI-assisted creative work used responsibly. Roles: Junior Product Designer, Digital Marketer, Content Producer.',
                'hero_promise' => 'Make things people understand, want and talk about.',
                'learn_text' => 'User research that starts with real conversations, not guesses. Design principles, wireframing and interface craft in Figma, working from a professional design system. Brand, voice and content strategy. Social and email marketing with real tools, analytics you can read and explain, usability testing and launch planning. AI assists the creative work throughout, and you will be graded on judging what it produces against your brand and your evidence.',
                'make_text' => 'A persona pack from five real conversations, a wireframed product flow, an interactive prototype, a branded content pack including a working email template, usability test results and a go-to-market plan.',
                'leads_text' => 'Junior Product Designer, Digital Marketer and Content Producer roles, freelance design and marketing packages for small businesses, or the design and sales seat on a venture team in the Project Period.',
                'recommended_for' => 'You notice when things are well made. You like words, visuals or both, and you want your taste to become a profession. You are willing to test your work on real people and change it when they are confused.',
                'skills' => [
                    'User Research',
                    'Wireframing',
                    'Interface Craft in Figma',
                    'Design Systems',
                    'Brand & Content Strategy',
                    'Social & Email Marketing',
                    'Usability Testing',
                    'Launch Planning'
                ],
                'career_paths' => [
                    'Junior Product Designer',
                    'Digital Marketer',
                    'Content Producer'
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
                'name' => 'Project Management and Delivery',
                'slug' => 'project-management',
                'category' => 'business',
                'description' => 'Organise and deliver digital work. Planning, stakeholder communication, requirements, risk and AI-assisted delivery with built-in verification. Roles: Project Coordinator, Junior Project Manager, Business Analyst.',
                'hero_promise' => 'Be the person who gets digital work delivered.',
                'learn_text' => 'The delivery lifecycle from plan to retrospective. Stakeholder management, including how to work with people senior to you: asking for decisions, escalating without drama, saying no with options. Requirements and backlogs, agile in practice, risk and dependency management, and reporting that busy people actually read, including a one-page executive standard. AI runs through all of it: you will use it to draft, summarise and track, and you will be graded on verifying what it produces.',
                'make_text' => 'A real project run end to end, a stakeholder map, status reports for three different audiences, a decision log and a portfolio that shows an employer you can be trusted with delivery.',
                'leads_text' => 'Project Coordinator, Junior Project Manager, Business Analyst and delivery support roles, freelance delivery work, or the planning seat on a venture team in the Project Period.',
                'recommended_for' => 'You like organising people and work, you communicate clearly and you want a route into tech that does not require writing code. You finish what you start, or you want to become someone who does.',
                'skills' => [
                    'Delivery Lifecycle',
                    'Stakeholder Management',
                    'Requirements & Backlogs',
                    'Agile in Practice',
                    'Risk & Dependency Management',
                    'Executive Reporting',
                    'AI-Assisted Delivery',
                    'Verification'
                ],
                'career_paths' => [
                    'Project Coordinator',
                    'Junior Project Manager',
                    'Business Analyst'
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
                'description' => 'Decide what gets built and prove it worked. Customer evidence, prioritisation, roadmaps, a working prototype and a real launch story. Roles: Associate Product Manager, Product Analyst, Product Operations Assistant.',
                'hero_promise' => 'Decide what gets built. Prove it was worth building.',
                'learn_text' => 'How to find a problem worth solving and prove it with real customer conversations. Prioritisation with real trade-offs, user stories, the one-page product requirements document, roadmaps that link to evidence. Simple product economics in plain language. How to work with designers and developers, define the metrics that matter, build a working prototype with AI assistance and test it on real users. You will present your product to a decision-making audience and take questions, because that is the job.',
                'make_text' => 'An opportunity brief built on five real customer conversations, a product spec pack, a working prototype tested by real users, a go-to-market one-pager and a launch-ready case study.',
                'leads_text' => 'Associate Product Manager, Product Analyst and Product Operations roles, product-adjacent moves from support and sales, or the product seat every venture team needs in the Project Period.',
                'recommended_for' => 'You are curious about why products succeed, you like evidence and people in equal measure and you want to own outcomes, not just tasks. No technical background needed.',
                'skills' => [
                    'Customer Evidence',
                    'Prioritisation',
                    'User Stories',
                    'Product Requirements',
                    'Roadmaps',
                    'Product Economics',
                    'Prototyping with AI',
                    'Metrics that Matter'
                ],
                'career_paths' => [
                    'Associate Product Manager',
                    'Product Analyst',
                    'Product Operations Assistant'
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
            'product-management',
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
