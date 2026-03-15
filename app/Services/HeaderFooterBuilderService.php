<?php

namespace App\Services;

use App\Models\Footer;
use App\Models\Header;
use App\Models\Website;

class HeaderFooterBuilderService
{
    public function seedDefaultsForWebsite(Website $website): void
    {
        $header = Header::where('website_id', $website->id)->first();
        $footer = Footer::where('website_id', $website->id)->first();

        if ($header) {
            $headerState = $header->builder_state;
            if (empty($headerState) || !is_array($headerState)) {
                $header->builder_state = $this->defaultHeaderState();
            }
            $header->use_builder = true;
            $header->save();
        }

        if ($footer) {
            $footerState = $footer->builder_state;
            if (empty($footerState) || !is_array($footerState)) {
                $footer->builder_state = $this->defaultFooterState();
            }
            $footer->use_builder = true;
            $footer->save();
        }
    }

    public function defaultHeaderState(): array
    {
        return [
            'components' => [
                $this->singleComponentInnerSection('header-contact-topbar'),
                $this->singleComponentInnerSection('header-logo'),
                $this->singleComponentInnerSection('header-menu-links'),
                $this->singleComponentInnerSection('header-auth-button'),
                $this->singleComponentInnerSection('header-invest-button'),
                $this->singleComponentInnerSection('header-investor-bar'),
                // Keep full legacy nav block for exact parity fallback/editability.
                $this->singleComponentInnerSection('header-nav'),
            ],
            'pageSettings' => [
                'backgroundColor' => '#ffffff',
            ],
        ];
    }

    public function defaultFooterState(): array
    {
        return [
            'components' => [
                $this->singleComponentInnerSection('footer-logo'),
                $this->singleComponentInnerSection('footer-description'),
                $this->singleComponentInnerSection('footer-social-links'),
                $this->singleComponentInnerSection('footer-contact-block'),
                $this->singleComponentInnerSection('footer-policy-links'),
                $this->singleComponentInnerSection('footer-disclaimer'),
                $this->singleComponentInnerSection('footer-investment-disclaimer'),
                $this->singleComponentInnerSection('footer-background-images'),
                // Keep full legacy footer block for exact parity fallback/editability.
                $this->singleComponentInnerSection('footer-legacy-main'),
            ],
            'pageSettings' => [
                'backgroundColor' => '#ffffff',
            ],
        ];
    }

    private function singleComponentInnerSection(string $componentType): array
    {
        return [
            'type' => 'inner-section',
            'nestedComponents' => [
                [
                    [
                        'type' => $componentType,
                        'style' => [],
                        'wrapperStyle' => [],
                    ],
                ],
            ],
            'innerSectionData' => [
                'columns' => 1,
                'fullWidth' => false,
                'contentWidth' => 'boxed',
            ],
            'style' => [],
            'wrapperStyle' => [],
        ];
    }
}
