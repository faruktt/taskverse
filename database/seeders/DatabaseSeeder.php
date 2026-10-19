<?php

namespace Database\Seeders;

use App\Models\{User, AdminUser, Category, Gig, Order, Conversation, Message, Review};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account
        $admin = AdminUser::firstOrCreate(
            ['email' => 'admin@taskverse.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Default Seller
        $seller = User::firstOrCreate(
            ['email' => 'seller@taskverse.com'],
            [
                'name'      => 'Alex Rivera',
                'password'  => Hash::make('password'),
                'role'      => 'seller',
                'avatar'    => 'avatars/seller_alex.jpg',
                'country'   => 'United States',
                'bio'       => 'Full-stack software engineer & senior UI/UX designer with 8+ years building enterprise web apps and modern digital experiences.',
                'phone'     => '+1 (555) 234-5678',
                'is_banned' => false,
            ]
        );

        // Another Top Seller
        $seller2 = User::firstOrCreate(
            ['email' => 'sophia@taskverse.com'],
            [
                'name'      => 'Sophia Chen',
                'password'  => Hash::make('password'),
                'role'      => 'seller',
                'avatar'    => 'avatars/seller_sophia.jpg',
                'country'   => 'Canada',
                'bio'       => 'AI specialist and machine learning engineer helping startups deploy custom LLMs and automated intelligent agents.',
                'phone'     => '+1 (555) 876-5432',
                'is_banned' => false,
            ]
        );

        // 3. Default Buyer
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@taskverse.com'],
            [
                'name'      => 'Sarah Jenkins',
                'password'  => Hash::make('password'),
                'role'      => 'buyer',
                'avatar'    => 'avatars/buyer_sarah.jpg',
                'country'   => 'United Kingdom',
                'bio'       => 'Product Director at NovaTech labs looking for top freelance talent for scaling web products.',
                'phone'     => '+44 20 7946 0912',
                'is_banned' => false,
            ]
        );

        // Another Buyer
        $buyer2 = User::firstOrCreate(
            ['email' => 'marcus@taskverse.com'],
            [
                'name'      => 'Marcus Vance',
                'password'  => Hash::make('password'),
                'role'      => 'buyer',
                'country'   => 'Germany',
                'bio'       => 'Startup Founder & Venture Builder.',
                'phone'     => '+49 30 123456',
                'is_banned' => false,
            ]
        );

        // 4. Categories
        $categoriesData = [
            [
                'name'  => 'Web & App Development',
                'slug'  => 'web-app-development',
                'icon'  => 'code',
                'color' => '#6366f1',
            ],
            [
                'name'  => 'UI/UX & Graphic Design',
                'slug'  => 'ui-ux-design',
                'icon'  => 'palette',
                'color' => '#ec4899',
            ],
            [
                'name'  => 'AI & Machine Learning',
                'slug'  => 'ai-machine-learning',
                'icon'  => 'psychology',
                'color' => '#06b6d4',
            ],
            [
                'name'  => 'Digital Marketing & SEO',
                'slug'  => 'digital-marketing',
                'icon'  => 'campaign',
                'color' => '#f59e0b',
            ],
            [
                'name'  => 'Video & Animation',
                'slug'  => 'video-animation',
                'icon'  => 'movie_filter',
                'color' => '#8b5cf6',
            ],
            [
                'name'  => 'Content & Copywriting',
                'slug'  => 'content-writing',
                'icon'  => 'article',
                'color' => '#10b981',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 5. Sample Gigs
        $gigsData = [
            [
                'seller_id'      => $seller->id,
                'category_id'    => $categories['web-app-development']->id,
                'title'          => 'I will build a high-performance modern web application in Laravel & Vue',
                'description'    => "Are you looking for a rock-solid, scalable web application tailored to your business needs? With over 8 years in full-stack architecture, I build secure, fast, and beautifully designed web systems from the ground up.\n\nIncluded features:\n- Responsive sleek UI with modern glassmorphism or minimalist theme\n- Clean database architecture and RESTful APIs\n- Authentication, role-based permissions, and payment gateway integration\n- Speed optimization & security best practices.",
                'thumbnail'      => 'gigs/thumbnails/web_dev.jpg',
                'price_basic'    => 80,
                'price_standard' => 160,
                'price_premium'  => 320,
                'basic_desc'     => 'Single page interactive web app or landing page with dynamic backend',
                'standard_desc'  => 'Complete multi-page web platform with auth, dashboard, and database CRUD',
                'premium_desc'   => 'Full enterprise marketplace / SaaS application with payment gateway and messaging',
                'delivery_days'  => 5,
                'revisions'      => 3,
                'tags'           => 'laravel, php, web development, vue, fullstack',
                'status'         => 'active',
                'views'          => 284,
                'avg_rating'     => 5.0,
                'total_reviews'  => 12,
                'total_orders'   => 15,
            ],
            [
                'seller_id'      => $seller->id,
                'category_id'    => $categories['ui-ux-design']->id,
                'title'          => 'I will design premium modern UI UX for your mobile app or web platform in Figma',
                'description'    => "Transform your digital product with world-class user interface design. I craft aesthetic, intuitive, and conversion-focused UX experiences that captivate your users and stand out in the competitive market.\n\nDeliverables:\n- High-fidelity interactive Figma prototype\n- Complete design system with color palette, typography, and components\n- Mobile and desktop responsive layouts\n- Developer-ready handover files.",
                'thumbnail'      => 'gigs/thumbnails/ui_ux.jpg',
                'price_basic'    => 65,
                'price_standard' => 130,
                'price_premium'  => 240,
                'basic_desc'     => '3 customized mobile or web screens with complete vector design tokens',
                'standard_desc'  => 'Up to 8 screens with user flows, components system, and clickable prototype',
                'premium_desc'   => 'Full application design (up to 20 screens), design system, and micro-interactions',
                'delivery_days'  => 3,
                'revisions'      => 5,
                'tags'           => 'figma, ui ux, web design, app design, prototype',
                'status'         => 'active',
                'views'          => 410,
                'avg_rating'     => 4.9,
                'total_reviews'  => 24,
                'total_orders'   => 29,
            ],
            [
                'seller_id'      => $seller2->id,
                'category_id'    => $categories['ai-machine-learning']->id,
                'title'          => 'I will build custom AI agents, LLM integrations and automated workflows for your company',
                'description'    => "Supercharge your business operations with autonomous AI agents and smart LLM automations. I connect OpenAI GPT, Anthropic Claude, and local open-source models with your internal tools, databases, and APIs.\n\nCapabilities:\n- Intelligent conversational bots with memory and document retrieval (RAG)\n- Automated scraping and intelligent synthesis workflows\n- Function calling and multi-tool agentic setups\n- Enterprise privacy compliance and robust guardrails.",
                'thumbnail'      => 'gigs/thumbnails/ai_agent.jpg',
                'price_basic'    => 120,
                'price_standard' => 250,
                'price_premium'  => 500,
                'basic_desc'     => 'Custom prompt engine and single tool integration with LLM API',
                'standard_desc'  => 'Full RAG pipeline with vector database knowledge base and chat UI',
                'premium_desc'   => 'Autonomous multi-agent system with webhook triggers and database execution',
                'delivery_days'  => 4,
                'revisions'      => 4,
                'tags'           => 'ai, llm, openai, langchain, agent, python',
                'status'         => 'active',
                'views'          => 530,
                'avg_rating'     => 5.0,
                'total_reviews'  => 18,
                'total_orders'   => 21,
            ],
            [
                'seller_id'      => $seller->id,
                'category_id'    => $categories['digital-marketing']->id,
                'title'          => 'I will create high-ROI digital marketing strategies and full-funnel ad campaigns',
                'description'    => "Scale your customer acquisition with tailored digital marketing campaigns across Google Ads, Meta Ads, and LinkedIn. I optimize every stage of the funnel for maximum ROAS and lower CPA.",
                'thumbnail'      => 'gigs/thumbnails/marketing_seo.jpg',
                'price_basic'    => 50,
                'price_standard' => 110,
                'price_premium'  => 210,
                'basic_desc'     => 'Competitor audit & keyword research report with campaign setup roadmap',
                'standard_desc'  => 'Full ad campaign setup across 2 ad networks with audience targeting & copies',
                'premium_desc'   => 'Complete 30-day managed ad campaign with weekly A/B testing and CRO recommendations',
                'delivery_days'  => 3,
                'revisions'      => 2,
                'tags'           => 'marketing, ads, google ads, facebook ads, growth',
                'status'         => 'active',
                'views'          => 195,
                'avg_rating'     => 4.8,
                'total_reviews'  => 9,
                'total_orders'   => 11,
            ],
            [
                'seller_id'      => $seller2->id,
                'category_id'    => $categories['video-animation']->id,
                'title'          => 'I will produce 4K 3D product motion graphics and explainer animations',
                'description'    => "Bring your product concept to life with cinematic 3D motion graphics, fluid typography, and sound design that captures instant viewer attention and boosts conversions.",
                'thumbnail'      => 'gigs/thumbnails/animation_3d.jpg',
                'price_basic'    => 90,
                'price_standard' => 180,
                'price_premium'  => 350,
                'basic_desc'     => '15-second teaser animation with licensed audio track',
                'standard_desc'  => '30-second full product explainer with 3D model rendering and voiceover sync',
                'premium_desc'   => '60-second high-end commercial animation with realistic physics & full post-production',
                'delivery_days'  => 6,
                'revisions'      => 3,
                'tags'           => '3d, animation, blender, motion graphics, video',
                'status'         => 'active',
                'views'          => 340,
                'avg_rating'     => 4.9,
                'total_reviews'  => 15,
                'total_orders'   => 16,
            ],
            [
                'seller_id'      => $seller->id,
                'category_id'    => $categories['content-writing']->id,
                'title'          => 'I will write SEO-optimized authority articles and technical blog posts',
                'description'    => "Engage your audience and rank at the top of Google search with well-researched, deeply technical, and persuasive blog articles crafted for technology and SaaS audiences.",
                'thumbnail'      => 'gigs/thumbnails/seo_content.jpg',
                'price_basic'    => 40,
                'price_standard' => 75,
                'price_premium'  => 140,
                'basic_desc'     => '800 words SEO-optimized article with metadata and keyword integration',
                'standard_desc'  => '1,500 words in-depth technical guide with code examples or case studies',
                'premium_desc'   => '3,000 words pillar guide + 3 social media summary snippets',
                'delivery_days'  => 2,
                'revisions'      => 2,
                'tags'           => 'content writing, seo, blog, copywriting, tech writer',
                'status'         => 'active',
                'views'          => 220,
                'avg_rating'     => 5.0,
                'total_reviews'  => 8,
                'total_orders'   => 10,
            ],
            // Pending gig for admin moderation showcase
            [
                'seller_id'      => $seller2->id,
                'category_id'    => $categories['web-app-development']->id,
                'title'          => 'I will build a decentralized Web3 smart contract dApp on Ethereum',
                'description'    => 'Solidity smart contracts development, auditing, and frontend Web3 integration.',
                'price_basic'    => 150,
                'price_standard' => 300,
                'price_premium'  => 600,
                'basic_desc'     => 'ERC-20 token contract with test suite',
                'standard_desc'  => 'Custom NFT or staking contract with frontend mint portal',
                'premium_desc'   => 'Full decentralized protocol with multisig governance',
                'delivery_days'  => 7,
                'revisions'      => 3,
                'tags'           => 'web3, solidity, ethereum, blockchain',
                'status'         => 'pending', // Pending for admin review
                'views'          => 12,
                'avg_rating'     => 0,
                'total_reviews'  => 0,
                'total_orders'   => 0,
            ],
        ];

        $createdGigs = [];
        foreach ($gigsData as $gd) {
            $createdGigs[] = Gig::create($gd);
        }

        // 6. Sample Orders & Conversation
        $primaryGig = $createdGigs[0]; // Laravel web app

        // Completed Order with Review
        $order1 = Order::create([
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'gig_id'         => $primaryGig->id,
            'buyer_id'       => $buyer->id,
            'seller_id'      => $seller->id,
            'package'        => 'standard',
            'price'          => 160.00,
            'delivery_days'  => 5,
            'status'         => 'completed',
            'payment_status' => 'paid',
            'requirements'   => 'We need an analytics dashboard with charts and CSV export feature for our team.',
            'delivery_note'  => 'Here is the completed source code archive and live staging server link. Tested and optimized!',
            'due_at'         => now()->subDays(2),
        ]);

        $conv1 = Conversation::create([
            'order_id'  => $order1->id,
            'buyer_id'  => $buyer->id,
            'seller_id' => $seller->id,
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id'       => $buyer->id,
            'body'            => 'Hi Alex! I just submitted the order for our analytics dashboard. Looking forward to working with you!',
            'created_at'      => now()->subDays(6),
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id'       => $seller->id,
            'body'            => 'Thanks Sarah! I have received all your requirements and started working on the chart components. Will share progress tomorrow.',
            'created_at'      => now()->subDays(5),
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id'       => $seller->id,
            'body'            => 'Hi Sarah, the delivery is ready! All features are complete, tested and verified.',
            'created_at'      => now()->subDays(2),
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id'       => $buyer->id,
            'body'            => 'Outstanding work Alex! The speed and clean design exceeded our expectations. Accepted and 5 stars given!',
            'created_at'      => now()->subDays(1),
        ]);

        Review::create([
            'order_id' => $order1->id,
            'gig_id'   => $primaryGig->id,
            'buyer_id' => $buyer->id,
            'rating'   => 5,
            'comment'  => 'Alex is an exceptional developer! Delivered ahead of schedule with spotless code and great communication.',
        ]);

        // In-progress order
        $designGig = $createdGigs[1]; // UI/UX design
        $order2 = Order::create([
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'gig_id'         => $designGig->id,
            'buyer_id'       => $buyer->id,
            'seller_id'      => $seller->id,
            'package'        => 'basic',
            'price'          => 65.00,
            'delivery_days'  => 3,
            'status'         => 'in_progress',
            'payment_status' => 'paid',
            'requirements'   => 'Mobile app wireframes and high-fidelity mockups for onboarding screens.',
            'due_at'         => now()->addDays(2),
        ]);

        $conv2 = Conversation::create([
            'order_id'  => $order2->id,
            'buyer_id'  => $buyer->id,
            'seller_id' => $seller->id,
        ]);

        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id'       => $buyer->id,
            'body'            => 'Hello Alex, I placed an order for mobile app onboarding UI. The brand colors are dark indigo and cyan.',
            'created_at'      => now()->subHours(8),
        ]);

        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id'       => $seller->id,
            'body'            => 'Got it Sarah! Indigo and cyan make a fantastic palette. I will have the initial mockups ready for your review soon.',
            'created_at'      => now()->subHours(4),
        ]);

        // Pending order from Buyer 2
        $aiGig = $createdGigs[2];
        $order3 = Order::create([
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'gig_id'         => $aiGig->id,
            'buyer_id'       => $buyer2->id,
            'seller_id'      => $seller2->id,
            'package'        => 'standard',
            'price'          => 250.00,
            'delivery_days'  => 4,
            'status'         => 'pending',
            'payment_status' => 'paid',
            'requirements'   => 'Customer support AI agent hooked up to PostgreSQL database for order status inquiries.',
            'due_at'         => now()->addDays(4),
        ]);

        Conversation::create([
            'order_id'  => $order3->id,
            'buyer_id'  => $buyer2->id,
            'seller_id' => $seller2->id,
        ]);
    }
}
