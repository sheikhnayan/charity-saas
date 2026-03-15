<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use App\Models\Header;
use App\Models\Website;
use App\Services\HeaderFooterBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeaderFooterBuilderController extends Controller
{
    public function __construct(private readonly HeaderFooterBuilderService $builderService)
    {
    }

    public function headerEditor(int $websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $header = Header::where('website_id', $websiteId)->firstOrFail();

        $data = $this->makeBuilderData($website, $header->id, $header->builder_state['pageSettings']['backgroundColor'] ?? '#ffffff');
        $customFonts = \App\Models\CustomFont::active()->get();

        return view('admin.page.page-builder', [
            'data' => $data,
            'customFonts' => $customFonts,
            'builderMode' => 'header',
            'builderTitle' => 'Header Builder',
            'builderSaveLabel' => 'Save Header',
            'builderBackUrl' => route('admin.menu', $header->id),
            'builderLoadUrl' => route('admin.header-builder.load', $websiteId),
            'builderSaveUrl' => route('admin.header-builder.save', $websiteId),
            'showTemplateActions' => false,
            'allowedComponentTypes' => [
                'inner-section',
                'custom-html',
                'header-contact-topbar',
                'header-nav',
                'header-investor-bar',
                'header-logo',
                'header-menu-links',
                'header-auth-button',
                'header-invest-button',
                'text',
                'image',
                'button',
                'divider',
                'section-title',
            ],
        ]);
    }

    public function footerEditor(int $websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $footer = Footer::where('website_id', $websiteId)->firstOrFail();

        $data = $this->makeBuilderData($website, $footer->id, $footer->builder_state['pageSettings']['backgroundColor'] ?? '#ffffff');
        $customFonts = \App\Models\CustomFont::active()->get();

        return view('admin.page.page-builder', [
            'data' => $data,
            'customFonts' => $customFonts,
            'builderMode' => 'footer',
            'builderTitle' => 'Footer Builder',
            'builderSaveLabel' => 'Save Footer',
            'builderBackUrl' => url('/admins/footer/' . $website->user_id),
            'builderLoadUrl' => route('admin.footer-builder.load', $websiteId),
            'builderSaveUrl' => route('admin.footer-builder.save', $websiteId),
            'showTemplateActions' => false,
            'allowedComponentTypes' => [
                'inner-section',
                'custom-html',
                'footer-legacy-main',
                'footer-logo',
                'footer-description',
                'footer-social-links',
                'footer-contact-block',
                'footer-policy-links',
                'footer-disclaimer',
                'footer-investment-disclaimer',
                'footer-background-images',
                'text',
                'image',
                'button',
                'divider',
                'section-title',
            ],
        ]);
    }

    public function loadHeader(int $websiteId): JsonResponse
    {
        $header = Header::where('website_id', $websiteId)->firstOrFail();

        if (empty($header->builder_state)) {
            $header->builder_state = $this->builderService->defaultHeaderState();
            $header->save();
        }

        return response()->json(['state' => $header->builder_state]);
    }

    public function saveHeader(Request $request, int $websiteId): JsonResponse
    {
        $header = Header::where('website_id', $websiteId)->firstOrFail();
        $state = $request->input('state');

        if (is_string($state)) {
            $decoded = json_decode($state, true);
            $state = is_array($decoded) ? $decoded : null;
        }

        if (!is_array($state)) {
            return response()->json(['success' => false, 'message' => 'Invalid builder state'], 422);
        }

        $header->builder_state = $state;
        $header->use_builder = true;
        $header->save();

        return response()->json(['success' => true]);
    }

    public function loadFooter(int $websiteId): JsonResponse
    {
        $footer = Footer::where('website_id', $websiteId)->firstOrFail();

        if (empty($footer->builder_state)) {
            $footer->builder_state = $this->builderService->defaultFooterState();
            $footer->save();
        }

        return response()->json(['state' => $footer->builder_state]);
    }

    public function saveFooter(Request $request, int $websiteId): JsonResponse
    {
        $footer = Footer::where('website_id', $websiteId)->firstOrFail();
        $state = $request->input('state');

        if (is_string($state)) {
            $decoded = json_decode($state, true);
            $state = is_array($decoded) ? $decoded : null;
        }

        if (!is_array($state)) {
            return response()->json(['success' => false, 'message' => 'Invalid builder state'], 422);
        }

        $footer->builder_state = $state;
        $footer->use_builder = true;
        $footer->save();

        return response()->json(['success' => true]);
    }

    private function makeBuilderData(Website $website, int $id, string $backgroundColor): object
    {
        $data = new \stdClass();
        $data->id = $id;
        $data->website_id = $website->id;
        $data->is_main_site = 0;
        $data->background_color = $backgroundColor;
        $data->show_in_menu = 1;
        $data->enable_confetti = 0;
        $data->website = $website;

        return $data;
    }
}
