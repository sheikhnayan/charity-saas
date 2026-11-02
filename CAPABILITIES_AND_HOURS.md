# James Website Builder — Capabilities, Build Timeline & Itemized Hours

**Prepared:** 2026-06-17
**Project start:** May 2025
**Engagement span:** ~14 months (May 2025 → Jun 2026)
**Platform & integrations:** multi-tenant cloud architecture · drag-and-drop page & site builder · Stripe, Authorize.Net & Coinbase (crypto) payments · ACH / wire / mailed-check payouts · analytics, A/B testing, heatmaps & session recording · fraud detection · automated PDF & Excel reporting · push notifications · QR-code donations · live market-data integration
**Size:** 55+ feature modules · 70+ data entities · 180+ database schema updates · 20+ admin & analytics areas · 20 background service engines · multi-rail payment processing

> Each feature below is listed with the estimated engineering hours required to build it. Hours are derived from the functionality present in the delivered platform; build dates are taken from the system's internal records. Section 1 describes the method and the team behind the figures.

---

## 1. How the Hours and Dates Were Determined

This section describes how the hour figures were produced, how the build timeline was established, and how the work maps to the team that delivered it.

**What one "hour" represents.** Each hour covers the engineering work required to take a feature from concept to a finished, production-ready state — requirements and planning, design, building, testing, revisions, and issue-fixing. Hours measure build-and-delivery effort, not only the time to produce a first draft.

**How the hours were calculated.** The hours were estimated bottom-up. The delivered platform was reviewed feature by feature — every screen, workflow, integration, and data structure present in it — and each feature was assigned an effort figure based on its complexity. Each hour corresponds to a specific, identifiable part of the working product.

**How the timeline was established.** Build dates are taken from the platform's internal records — the timestamps created as each part of the system was added or changed. These provide a chronological record of when each part was built.

**Team & staffing.** Team size varied with the workload across the engagement:

| Period | Months | Developers |
|---|---:|---:|
| May–Aug 2025 | 4 | 3 |
| Sep–Dec 2025 | 4 | 4 |
| Jan–Jun 2026 | 6 | 1 |

---

## 2. Executive Summary

| Phase | Period | Module Group | Hours |
|---|---|---|---:|
| P0 | May 2025 | Template & builder foundation | 65 |
| P1 | May–Jun 2025 | Core platform & multi-tenant foundation | 210 |
| P2 | Jun–Aug 2025 | Page & site builder | 240 |
| P3 | Jun–Jul 2025 | Donations, tax receipts & multi-rail payments | 215 |
| P4 | Aug–Oct 2025 | Tickets & events | 125 |
| P5 | Jul–Aug 2025 | Auctions & live bidding | 105 |
| P6 | Sep–Nov 2025 | Investments / real-estate offerings | 185 |
| P7 | Aug–Oct 2025 | Sponsors, newsletter & QR donations | 80 |
| P8 | Sep–Oct 2025 | Analytics suite | 150 |
| P9 | Oct 2025 | Payment funnel analytics | 85 |
| P10 | Oct–Nov 2025 | Heatmaps & session recording | 135 |
| P11 | Sep–Nov 2025 | A/B testing | 110 |
| P12 | Nov 2025 | Cohort analysis | 90 |
| P13 | Nov–Dec 2025 | Fraud detection | 110 |
| P14 | Nov–Dec 2025 | Reporting & scheduled exports | 115 |
| P15 | Dec 2025 | Push notifications | 70 |
| P16 | Dec 2025 | Crypto payments | 65 |
| P17 | Dec 2025 | Roles, permissions & user management | 105 |
| P18 | Dec 2025–Jan 2026 | Teachers / students module | 80 |
| P19 | Jan–Mar 2026 | Dynamic email system & preferences | 95 |
| P20 | Feb–Mar 2026 | Menu builder & builder upgrades | 90 |
| P21 | Apr–Jun 2026 | SaaS multi-tenant hardening | 120 |
| CC | Ongoing | Cross-cutting (QA, bug-fixing, DevOps) | 140 |
| | | **TOTAL** | **2,785** |

---

## 3. Itemized Feature Inventory & Hours

### P0 — Template & Builder Foundation — *May 2025* — **65 hrs**
| Item | Hrs |
|---|---:|
| Reusable section-template engine | 25 |
| Header & footer foundation (fonts, backgrounds, layout) | 25 |
| Base project scaffolding & environment setup | 15 |

### P1 — Core Platform & Multi-Tenant Foundation — *May–Jun 2025* — **210 hrs**
| Item | Hrs |
|---|---:|
| Multi-tenant website architecture (per-organization sites, isolation) | 65 |
| User accounts, profiles & onboarding | 30 |
| Global settings & configuration system | 35 |
| Donation data core & lifecycle | 45 |
| Authentication (login, registration, password reset) | 35 |

### P2 — Page & Site Builder — *Jun–Aug 2025* — **240 hrs**
| Item | Hrs |
|---|---:|
| Drag-and-drop page builder (sections, layouts) | 75 |
| Page templates library & cloning | 40 |
| Header builder (menus, top bar, logos, colors) | 45 |
| Footer builder (dynamic columns, social links, privacy) | 35 |
| Custom fonts management | 15 |
| Page meta, SEO & social-sharing fields | 30 |

### P3 — Donations, Tax Receipts & Multi-Rail Payments — *Jun–Jul 2025* — **215 hrs**
| Item | Hrs |
|---|---:|
| Transaction processing & order lifecycle | 40 |
| Stripe payment integration | 30 |
| Authorize.Net payment integration | 30 |
| Payout methods — direct deposit, mailed check, wire transfer | 45 |
| Tax & tax-receipt generation (PDF) | 35 |
| Tipping / contribution-fee handling | 20 |
| Refund & privacy controls | 15 |

### P4 — Tickets & Events — *Aug–Oct 2025* — **125 hrs**
| Item | Hrs |
|---|---:|
| Ticket products (pricing, options, documents) | 35 |
| Ticket categories (icons, status, ordering) | 25 |
| Ticket features & image galleries | 25 |
| Ticket sales & sale-detail records | 40 |

### P5 — Auctions & Live Bidding — *Jul–Aug 2025* — **105 hrs**
| Item | Hrs |
|---|---:|
| Auction items & image galleries | 30 |
| Live bidding engine (real-time bids, last-bid tracking) | 50 |
| Auction timezones & checkout integration | 25 |

### P6 — Investments / Real-Estate Offerings — *Sep–Nov 2025* — **185 hrs**
| Item | Hrs |
|---|---:|
| Investment offerings & investor flow | 50 |
| Dealmaker configuration & dynamic offering pages | 50 |
| Property financials & details | 35 |
| Investor profiles & investor-type handling | 30 |
| Live market-data integration | 20 |

### P7 — Sponsors, Newsletter & QR Donations — *Aug–Oct 2025* — **80 hrs**
| Item | Hrs |
|---|---:|
| Sponsor management & display | 25 |
| Newsletter subscriptions (name / phone capture) | 25 |
| QR-code donation generation & flow | 30 |

### P8 — Analytics Suite — *Sep–Oct 2025* — **150 hrs**
| Item | Hrs |
|---|---:|
| Event-tracking infrastructure (high-volume, indexed) | 45 |
| Unique-visitor & page-view tracking | 35 |
| Analytics dashboard & charts | 40 |
| Referrer & UTM campaign analytics | 30 |

### P9 — Payment Funnel Analytics — *Oct 2025* — **85 hrs**
| Item | Hrs |
|---|---:|
| Funnel event tracking (step-by-step) | 35 |
| Funnel visualization & drop-off analysis | 30 |
| Payment-method analytics | 20 |

### P10 — Heatmaps & Session Recording — *Oct–Nov 2025* — **135 hrs**
| Item | Hrs |
|---|---:|
| Click / scroll heatmap capture & rendering | 50 |
| Session recording & playback | 55 |
| Automated page-screenshot capture | 30 |

### P11 — A/B Testing — *Sep–Nov 2025* — **110 hrs**
| Item | Hrs |
|---|---:|
| Experiment & variant management | 40 |
| Visitor assignment & traffic splitting | 30 |
| Conversion & event tracking | 25 |
| Results & statistical reporting | 15 |

### P12 — Cohort Analysis — *Nov 2025* — **90 hrs**
| Item | Hrs |
|---|---:|
| Cohort definition & membership | 30 |
| Retention analysis | 35 |
| Cohort comparison | 25 |

### P13 — Fraud Detection — *Nov–Dec 2025* — **110 hrs**
| Item | Hrs |
|---|---:|
| Fraud rules engine (configurable rules) | 45 |
| Detection scoring & blacklist | 35 |
| Fraud statistics & dashboard | 30 |

### P14 — Reporting & Scheduled Exports — *Nov–Dec 2025* — **115 hrs**
| Item | Hrs |
|---|---:|
| Report templates & builder | 40 |
| Report execution & history | 25 |
| Scheduled & recurring reports | 25 |
| Excel & PDF export engine | 25 |

### P15 — Push Notifications — *Dec 2025* — **70 hrs**
| Item | Hrs |
|---|---:|
| Push-notification system & device tokens | 40 |
| Notification preferences & targeting | 30 |

### P16 — Crypto Payments — *Dec 2025* — **65 hrs**
| Item | Hrs |
|---|---:|
| Coinbase Commerce integration | 40 |
| Crypto payment tracking & reconciliation | 25 |

### P17 — Roles, Permissions & User Management — *Dec 2025* — **105 hrs**
| Item | Hrs |
|---|---:|
| Role-based access control (per-website roles) | 45 |
| Granular permissions system | 30 |
| Admin user management | 30 |

### P18 — Teachers / Students Module — *Dec 2025–Jan 2026* — **80 hrs**
| Item | Hrs |
|---|---:|
| Teacher & student management | 30 |
| Parent–child relationships | 25 |
| Student messaging | 25 |

### P19 — Dynamic Email System & Preferences — *Jan–Mar 2026* — **95 hrs**
| Item | Hrs |
|---|---:|
| Per-website email settings & branding | 35 |
| Dynamic transactional email system | 35 |
| Email notification preferences | 25 |

### P20 — Menu Builder & Builder Upgrades — *Feb–Mar 2026* — **90 hrs**
| Item | Hrs |
|---|---:|
| Visual menu builder (menus & items) | 45 |
| Header / footer builder upgrade (advanced fields) | 30 |
| Sub-menu styling & font controls | 15 |

### P21 — SaaS Multi-Tenant Hardening — *Apr–Jun 2026* — **120 hrs**
| Item | Hrs |
|---|---:|
| Per-website payment configuration & fees | 35 |
| Per-website email uniqueness & data isolation | 25 |
| Per-website roles & data separation | 30 |
| Tenant onboarding, tutorials & site-status controls | 30 |

### CC — Cross-Cutting (entire engagement) — **140 hrs**
| Item | Hrs |
|---|---:|
| Bug fixing, regressions & production hotfixes | 75 |
| QA / testing cycles | 30 |
| DevOps, deployments & database schema management | 35 |

---

## 4. Build Timeline (from schema & file dates)

| Period | What was built |
|---|---|
| **May–Jun 2025** | Foundation — template & builder engine, multi-tenant websites, user accounts, settings, donation core. |
| **Jun–Aug 2025** | Page & site builder, headers/footers, donations and multi-rail payments, tax receipts, auctions. |
| **Aug–Oct 2025** | Tickets & events, sponsors, newsletter, QR donations. |
| **Sep–Oct 2025** | Investment / real-estate offerings, analytics suite, payment-funnel analytics. |
| **Oct–Dec 2025** | Heatmaps & session recording, A/B testing, cohort analysis, fraud detection, reporting & scheduled exports, push notifications, crypto payments. |
| **Dec 2025–Jan 2026** | Roles & permissions, teacher/student module. |
| **Jan–Mar 2026** | Dynamic email system, menu builder, header/footer builder upgrades. |
| **Mar–Jun 2026** | SaaS multi-tenant hardening and refinements. |

---

## 5. Capability Summary

**Fundraising & commerce** — multi-tenant donation sites, one-time & recurring giving, tipping/contribution fees, ticketed events, live auctions with real-time bidding, sponsor management, QR-code donations, newsletter capture, tax-receipt generation.

**Site building** — drag-and-drop page builder, reusable section & page templates, header/footer builder, menu builder, custom fonts, SEO & social-sharing controls, per-site colors and layouts.

**Payments** — Stripe, Authorize.Net and Coinbase (crypto) processing, plus direct deposit, wire transfer and mailed-check payouts, configurable per-website fees and payment settings.

**Investments / real estate** — investment offerings with investor profiles, Dealmaker offering pages, property financials, and live market-data integration.

**Analytics** — visitors, page views, referrers and UTM campaigns, payment-funnel and drop-off analysis, click/scroll heatmaps, session recording & playback, A/B testing, and cohort/retention analysis.

**Operations & admin** — configurable fraud-detection engine with blacklisting, role-based access control with permissions, admin & user management, teacher/student module with messaging, per-website email system with preferences, push notifications, and a reporting suite with scheduled Excel/PDF exports.

---

## 6. Upcoming Phase — AI Capabilities *(projected, not included in the totals above)*

The next planned phase adds AI features to James Website Builder. The items below are estimates and are not part of the delivered hours above.

### AI Capabilities (projected: 420–560 hrs)
- AI donation & checkout assistant (conversational, guided giving)
- AI page & content generation built into the site builder
- Natural-language analytics — "ask your data" across the analytics suite
- AI-enhanced fraud & anomaly detection
- Smart donor segmentation & personalized recommendations
- Automated AI report summaries & plain-language insights
