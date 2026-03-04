<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealmakerConfig extends Model
{
    protected $table = 'dealmaker_config';

    protected $fillable = [
        // Hero Section
        'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
        'hero_background_video', 'hero_background_image',
        
        // Site Branding
        'site_logo', 'site_tagline', 'uploaded_logo',
        
        // Statistics
        'stat_1_number', 'stat_1_text', 'stat_2_number', 'stat_2_text', 'stat_3_number', 'stat_3_text',
        
        // Announcement
        'announcement_text', 'announcement_badge', 'announcement_url',
        
        // Navigation & CTA
        'signin_text', 'signin_url', 'main_cta_text', 'main_cta_url',
        'navigation_items',
        
        // Video Settings
        'bg_video_mp4', 'bg_video_webm', 'bg_video_poster', 
        'modal_video_desktop', 'modal_video_mobile',
        
        // About Section
        'about_title', 'about_description', 'about_image',
        
        // Services & Testimonials
        'services', 'testimonials',
        
        // Client Logos
        'client_logos',
        
        // Contact Info
        'phone_number', 'address', 'business_hours',
        
        // Social Media
        'linkedin_url', 'youtube_url', 'tiktok_url', 'facebook_url', 'instagram_url', 'twitter_url',
        'show_linkedin', 'show_twitter', 'show_facebook', 'show_instagram',
        
        // SEO Meta
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'uploaded_og_image',
        
        // Custom Code
        'custom_css', 'custom_js', 'custom_head_code',
        
        // Footer
        'footer_text', 'footer_copyright', 'footer_company_description', 'footer_address',
        'footer_menu_raise_capital', 'footer_menu_solutions', 'footer_menu_company', 'footer_menu_resources',
        'footer_menu_columns', 'footer_newsletter_title', 'footer_newsletter_description',
        'footer_award_image', 'footer_terms_url', 'footer_privacy_url', 'footer_cookies_url', 
        'footer_security_url', 'footer_accessibility_url', 'footer_copyright_text', 'footer_company_address',
        
        // Case Studies Section
        'case_studies_title', 'case_studies',
        
        // DealMaker Difference Section (Tabs)
        'difference_section_title', 'difference_eyebrow_text', 'difference_tabs',
        
        // Capital Raising Steps (Plan, Raise, Engage, Repeat)
        'plan_title', 'plan_description', 'plan_button_text', 'plan_button_url',
        'raise_title', 'raise_description', 'raise_button_text', 'raise_button_url',
        'engage_title', 'engage_description', 'engage_button_text', 'engage_button_url',
        'repeat_title', 'repeat_description', 'repeat_button_text', 'repeat_button_url',
        
        // Navigation Menu Items
        'nav_raise_capital_title', 'nav_products_title', 'nav_resources_title',
        
        // Platform Section Content 
        'platform_section_title', 'platform_section_description', 'platform_cta_text', 'platform_cta_url',
        
        // Slider Content (static slides)
        'slide_2_title', 'slide_2_description', 'slide_2_cta_text', 'slide_2_cta_url',
        'slide_3_title', 'slide_3_description', 'slide_3_cta_text', 'slide_3_cta_url',
        
        // Case Study Labels
        'case_study_capital_raised_label', 'case_study_investors_label', 'case_study_learn_more_text',
        
        // Tab Button Text
        'tab_plan_button_text', 'tab_raise_button_text', 'tab_engage_button_text', 'tab_repeat_button_text',
        
        // Additional Content
        'main_slider_default_description', 'testimonials_intro_text',
        
        // Testimonials Section
        'testimonials_section_title', 'testimonials_section_subtitle',
        
        // Capital Raising Section
        'capital_raising_title', 'capital_raising_subtitle', 'capital_raising_features',
        
        // Final CTA Section
        'final_cta_title', 'final_cta_subtitle', 'final_cta_button_text', 'final_cta_button_url',
        'final_cta_background_image',
        
        // Slider Images for Phone Section
        'slider_images',
        
        // Capital Revolutionized Section 
        'capital_revolutionized_title', 'capital_revolutionized_description',
        'reg_cf_title', 'reg_cf_subtitle', 'reg_cf_investor_text', 'reg_cf_url',
        'reg_a_title', 'reg_a_subtitle', 'reg_a_investor_text', 'reg_a_url',
        'reg_d_title', 'reg_d_subtitle', 'reg_d_investor_text', 'reg_d_url',
        
        // Final CTA Section (New)
        'final_cta_main_title', 'final_cta_main_description',
        'final_cta_primary_button_text', 'final_cta_primary_button_url',
        'final_cta_secondary_button_text', 'final_cta_secondary_button_url',
        'final_cta_growth_image', 'final_cta_sky_image', 'final_cta_city_image',
        
        // Section Toggles
        'show_hero', 'show_stats', 'show_about', 'show_services', 'show_testimonials', 'show_contact',
        'show_announcement', 'show_case_studies', 'show_difference_section', 'show_capital_raising', 'show_final_cta',
        
        // Section Background Colors (JSON field)
        'section_background_colors',
        
        // Button Colors
        'button_primary_color', 'button_hover_color', 'button_text_color',
        
        // Social Icon Colors
        'social_icon_bg_color', 'social_icon_hover_color', 'social_icon_color',
        
        // Menu Toggle Fields for Sections
        'menu_hero', 'menu_about', 'menu_services', 'menu_logos',
        'menu_cases', 'menu_difference', 'menu_testimonials', 'menu_solutions', 'menu_cta'
    ];

    protected $casts = [
        'navigation_items' => 'array',
        'services' => 'array',
        'testimonials' => 'array',
        'client_logos' => 'array',
        'case_studies' => 'array',
        'difference_tabs' => 'array',
        'capital_raising_features' => 'array',
        'slider_images' => 'array',
        'footer_menu_raise_capital' => 'array',
        'footer_menu_solutions' => 'array',
        'footer_menu_company' => 'array',
        'footer_menu_resources' => 'array',
        'footer_menu_columns' => 'array',
        'section_background_colors' => 'array',
        'show_linkedin' => 'boolean',
        'show_twitter' => 'boolean',
        'show_facebook' => 'boolean',
        'show_instagram' => 'boolean',
        'show_hero' => 'boolean',
        'show_stats' => 'boolean',
        'show_about' => 'boolean',
        'show_services' => 'boolean',
        'show_testimonials' => 'boolean',
        'show_contact' => 'boolean',
        'show_announcement' => 'boolean',
        'show_case_studies' => 'boolean',
        'show_difference_section' => 'boolean',
        'show_capital_raising' => 'boolean',
        'show_final_cta' => 'boolean',
        'menu_hero' => 'boolean',
        'menu_about' => 'boolean',
        'menu_services' => 'boolean',
        'menu_logos' => 'boolean',
        'menu_cases' => 'boolean',
        'menu_difference' => 'boolean',
        'menu_testimonials' => 'boolean',
        'menu_solutions' => 'boolean',
        'menu_cta' => 'boolean'
    ];

    /**
     * Get the singleton instance (there should only be one configuration)
     */
    public static function getInstance()
    {
        $config = self::first();
        
        if (!$config) {
            $config = self::create([
                // SEO Meta
                'meta_title' => 'DealMaker | Raise Capital Online',
                'meta_description' => 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.',
                'meta_keywords' => 'capital raising, investment, funding, dealmaker',
                'og_image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d988c9d3abae4ca097302_opengraphimage.png',
                
                // Hero Section
                'hero_title' => 'The Future Of Retail Capital',
                'hero_subtitle' => 'Raise Boldly',
                'hero_cta_text' => 'Get Started',
                'hero_cta_url' => '/connect',
                'hero_background_video' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997%2F686d6096b46c58223d7cc59b_homepage_loop5_1-transcode.mp4',
                
                // Site Branding
                'site_logo' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/68542f0e51eb3c2c8fac8fca_dm-icon.svg',
                'site_tagline' => 'Your trusted partner in wealth creation',
                
                // Statistics
                'stat_1_number' => '2',
                'stat_1_text' => 'Raised by customers',
                'stat_2_number' => '1.5',
                'stat_2_text' => 'Investments processed',
                'stat_3_number' => '900',
                'stat_3_text' => 'Offerings',
                
                // Announcement
                'announcement_text' => 'The Future Of Retail Capital. Raise Boldly.',
                'announcement_url' => '/connect',
                
                // About Section
                'about_title' => 'About DealMaker',
                'about_description' => 'We are a leading investment platform helping individuals and institutions build wealth through smart investment strategies.',
                
                // Case Studies
                'case_studies_title' => 'Success Stories',
                'case_studies' => [
                    [
                        'name' => 'EnergyX',
                        'description' => 'The Lithium Industry Transformed.',
                        'logo' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855710fa0a2d2ed60bd3663_energyx_logo_e7aea357.png',
                        'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561057298b4672c414ad9_section%203-01.webp',
                        'capital_raised' => '88',
                        'investors' => '31',
                        'learn_more_url' => '/content/energyx-case-study'
                    ],
                    [
                        'name' => 'Miso Robotics',
                        'description' => 'Serving Up The Future Of Robotics.',
                        'logo' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/68559dbcbf130ac8946b94b4_679016b0cde401ab818c9b8e_miso-logo-light-kitchen-ai-n-automation.webp',
                        'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561049c6049c0a8e8ee9e_section%203-02.webp',
                        'capital_raised' => '104',
                        'investors' => '40',
                        'learn_more_url' => '/content/miso-robotics-wasted-no-time-in-their-raise'
                    ],
                    [
                        'name' => 'Omni Gaming',
                        'description' => 'Pioneering Next Gen Gaming.',
                        'logo' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/68559dbce7931d745ff52bb9_omni.png',
                        'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/68556105c248125c55e16f0d_section%203-03.webp',
                        'capital_raised' => '22',
                        'investors' => '9',
                        'learn_more_url' => '/category/case-studies'
                    ]
                ],
                
                // DealMaker Difference (Tabs)
                'difference_section_title' => '#1 in capital raising',
                'difference_eyebrow_text' => 'DealMaker Difference',
                'difference_tabs' => [
                    [
                        'title' => 'Plan',
                        'heading' => 'Personalized Raise Strategy',
                        'description' => 'Successful capital raises start with the right strategy. DealMaker works with you to plan every aspect of your raise strategy - whether it\'s your first retail raise or you\'re a multiple raise professional.',
                        'cta_text' => 'Learn More',
                        'cta_url' => '/connect',
                        'svg_content' => '<svg width="auto" height="auto" viewBox="0 0 636 500" fill="none" xmlns="http://www.w3.org/2000/svg">...</svg>' // SVG content here
                    ],
                    [
                        'title' => 'Raise',
                        'heading' => 'End-to-End Technology',
                        'description' => 'From investor onboarding to payment processing to ongoing communication - everything you need to run a successful capital raise.',
                        'cta_text' => 'Learn More',
                        'cta_url' => '/connect'
                    ],
                    [
                        'title' => 'Engage',
                        'heading' => 'Build Your Community',
                        'description' => 'Turn investors into advocates. Our platform helps you build lasting relationships with your investor community.',
                        'cta_text' => 'Learn More',
                        'cta_url' => '/connect'
                    ],
                    [
                        'title' => 'Repeat',
                        'heading' => 'Capitalize On Multiple Raises',
                        'description' => 'Over 80% of DealMaker\'s customers do multiple raises. Create and execute a multi-raise strategy aligned to your growth trajectory - from seed and growth to IPO and beyond.',
                        'cta_text' => 'Learn More',
                        'cta_url' => '/connect'
                    ]
                ],
                
                // Social Media
                'linkedin_url' => 'https://www.linkedin.com/company/dealmakertech/',
                'twitter_url' => 'https://x.com/Dealmakertech',
                'facebook_url' => 'https://www.facebook.com/dealmakertechnology/',
                'instagram_url' => 'https://www.instagram.com/dealmakertech/',
                
                // Footer
                'footer_copyright' => '© 2025 DealMaker. All rights reserved.',
                'footer_company_description' => 'DealMaker provides comprehensive capital raising technology that transforms how companies raise funds, engage investors, and build community.',
                'footer_address' => '30 East 23rd St. Fl. 2, New York, NY 10010',
                'footer_newsletter_title' => 'STAY UPDATED',
                'footer_newsletter_description' => 'Subscribe to our newsletter for the latest updates and insights on capital raising.',
                
                'footer_menu_raise_capital' => [
                    ['name' => 'Why DealMaker', 'url' => '/raise-capital'],
                    ['name' => 'Offering types', 'url' => '/offering-types'],
                    ['name' => 'Sports', 'url' => '/sports'],
                    ['name' => 'Get started', 'url' => '/connect']
                ],
                'footer_menu_solutions' => [
                    ['name' => 'Capital raise tech', 'url' => '/capital-raise-tech'],
                    ['name' => 'Investor relations', 'url' => '/investor-relations'],
                    ['name' => 'Campaign marketing', 'url' => '/dealmaker-marketing-services'],
                    ['name' => 'Tech licensing', 'url' => '/platforms']
                ],
                'footer_menu_company' => [
                    ['name' => 'About us', 'url' => '/about-us'],
                    ['name' => 'Careers', 'url' => '/careers'],
                    ['name' => 'Press', 'url' => '/category/press']
                ],
                'footer_menu_resources' => [
                    ['name' => 'Investor Support', 'url' => 'https://support.dealmaker.tech/'],
                    ['name' => 'Blog', 'url' => '/blog'],
                    ['name' => 'Case studies', 'url' => '/category/case-studies'],
                    ['name' => 'Partner with us', 'url' => '/connect/partnerships'],
                    ['name' => 'Refer a deal', 'url' => '/refer-a-deal']
                ],
                
                // Section toggles (all enabled by default)
                'show_hero' => true,
                'show_stats' => true,
                'show_about' => true,
                'show_services' => true,
                'show_testimonials' => true,
                'show_contact' => true,
                'show_case_studies' => true,
                'show_difference_section' => true,
                'show_capital_raising' => true,
                'show_final_cta' => true
            ]);
        }
        
        return $config;
    }
    
    /**
     * Get section background color with default fallback
     */
    public function getSectionBackgroundColor($section, $default = '#ffffff')
    {
        $colors = $this->section_background_colors ?? [];
        return $colors[$section] ?? $default;
    }
    
    /**
     * Get regulation color with default fallback
     */
    public function getRegulationColor($regulation, $colorType, $default = '#ffffff')
    {
        $colors = $this->section_background_colors ?? [];
        return $colors[$regulation . '_' . $colorType] ?? $default;
    }
    
    /**
     * Get all section background colors with defaults
     */
    public function getAllSectionBackgroundColors()
    {
        return [
            'hero' => $this->getSectionBackgroundColor('hero', '#000000'),
            'announcement' => $this->getSectionBackgroundColor('announcement', '#f8f9fa'),
            'stats' => $this->getSectionBackgroundColor('stats', '#ffffff'),
            'client_logos' => $this->getSectionBackgroundColor('client_logos', '#ffffff'),
            'case_studies' => $this->getSectionBackgroundColor('case_studies', '#f8f9fa'),
            'difference_section' => $this->getSectionBackgroundColor('difference_section', '#ffffff'),
            'testimonials' => $this->getSectionBackgroundColor('testimonials', '#f8f9fa'),
            'capital_revolutionized' => $this->getSectionBackgroundColor('capital_revolutionized', '#ffffff'),
            'final_cta' => $this->getSectionBackgroundColor('final_cta', '#000000'),
            'footer' => $this->getSectionBackgroundColor('footer', '#000000')
        ];
    }
    
    /**
     * Get enabled menu items with their titles and anchor links
     */
    public function getEnabledMenuItems()
    {
        $menuItems = [];
        
        if ($this->menu_hero) {
            $menuItems[] = [
                'title' => 'Home',
                'anchor' => '#hero',
                'section' => 'hero'
            ];
        }
        
        if ($this->menu_difference) {
            $menuItems[] = [
                'title' => 'Why Us',
                'anchor' => '#why-us',
                'section' => 'difference_section'
            ];
        }

        if ($this->menu_about) {
            $menuItems[] = [
                'title' => 'About',
                'anchor' => '#about',
                'section' => 'about'
            ];
        }
        
        if ($this->menu_services) {
            $menuItems[] = [
                'title' => 'Services',
                'anchor' => '#services',
                'section' => 'services'
            ];
        }
        
        if ($this->menu_logos) {
            $menuItems[] = [
                'title' => 'Partners',
                'anchor' => '#partners',
                'section' => 'client_logos'
            ];
        }
        
        if ($this->menu_testimonials) {
            $menuItems[] = [
                'title' => 'Testimonials',
                'anchor' => '#testimonials',
                'section' => 'testimonials'
            ];
        }

        if ($this->menu_cases) {
            $menuItems[] = [
                'title' => 'Case Studies',
                'anchor' => '#case-studies',
                'section' => 'case_studies'
            ];
        }
        
        if ($this->menu_solutions) {
            $menuItems[] = [
                'title' => 'Solutions',
                'anchor' => '#solutions',
                'section' => 'capital_revolutionized'
            ];
        }
        
        if ($this->menu_cta) {
            $menuItems[] = [
                'title' => 'Get Started',
                'anchor' => '#get-started',
                'section' => 'final_cta'
            ];
        }
        
        return $menuItems;
    }
}
