# Technical Map

This document lists the Laravel backend surface and Flutter addon pieces for the Professional Network Utilities, Security & Analytics addon. Use it as a quick index when enabling features or integrating with Sociopro hosts.

## 1. Laravel (Backend)

### Connections / My Network
- **Domain Service:** `ProNetwork\Services\ConnectionService` – summaries, first/second/third degree, mutual connections.
- **Controllers:** `ConnectionsController@index|list|mutual`.
- **Routes:**
  - Web: `/pro-network/my-network`, `/pro-network/my-network/connections`, `/pro-network/my-network/mutual/{user}`.
  - API: `/api/pro-network/connections`, `/api/pro-network/connections/mutual/{user}`.
- **Views:** `pro_network::my_network.index`, `pro_network::my_network.connections`, `pro_network::my_network.mutual`.
- **Config flags:** `features.connections_graph`.

### Recommendations
- **Domain Service:** `RecommendationService` – people/companies/groups/content suggestions.
- **Controller:** `RecommendationsController@people|companies|groups|content`.
- **Routes:** API `/api/pro-network/recommendations/*` guarded by `pro-network.feature:recommendations`.
- **Config flag:** `features.recommendations`.

### Professional Profiles & Company Pages
- **Domain Service:** `ProfileEnhancementService` for professional profiles; `AccountTypeService` for account tiers; `Company` logic via `CompanyProfileController`.
- **Controllers:** `ProfessionalProfileController@show|edit|update`, `CompanyProfileController@show|update`.
- **Routes:**
  - Web: `/pro-network/profile/professional`, `/pro-network/profile/professional/{user}`, `/pro-network/profile/professional/edit`, `/pro-network/company/{company}`.
  - API: `/api/pro-network/profile/professional` (GET/POST), `/api/pro-network/company/{company}` (GET/POST, update guarded by policy).
- **Views:** `pro_network::profile.show`, `pro_network::profile.edit`, `pro_network::company.show`.
- **Models:** `ProfessionalProfile`, `ProfileSkill`, `ProfileCertification`, `ProfileEducationHistory`, `ProfileWorkHistory`, `ProfileInterest`, `ProfileBackgroundCheck`, `ProfileOpportunity`, `ProfileReference`, `CompanyProfile`, `CompanyEmployee`, `UserAccountType`, `UserFeatureFlag`.
- **Config flag:** `features.profile_professional_upgrades`, `features.account_types`.

### Marketplace Escrow & Disputes
- **Domain Service:** `MarketplaceEscrowDomain` – escrow lifecycle, milestones, refunds, dispute hooks.
- **Controllers:** `MarketplaceEscrowController@open|release|refund|showByOrder`, `MarketplaceDisputeController@create|store|show|reply|resolve`.
- **Routes:**
  - Web: `/pro-network/marketplace/orders/{order}/escrow`, `/pro-network/marketplace/orders/{order}/disputes/create`, `/pro-network/marketplace/disputes/{dispute}`.
  - API: `/api/pro-network/marketplace/orders/{order}/escrow/open`, `/api/pro-network/marketplace/escrow/{escrow}/release`, `/api/pro-network/escrow/{escrow}/refund`, `/api/pro-network/marketplace/orders/{order}/disputes`, `/api/pro-network/marketplace/disputes/{dispute}`, `/api/pro-network/marketplace/disputes/{dispute}/reply`, `/api/pro-network/marketplace/disputes/{dispute}/resolve`.
- **Views:** `pro_network::escrow.show`, `pro_network::disputes.create`, `pro_network::disputes.show`.
- **Models:** `MarketplaceEscrow`, `MarketplaceMilestone`, `MarketplaceTransaction`, `MarketplaceDispute`, `MarketplaceDisputeMessage`.
- **Config flag:** `features.marketplace_escrow`.

### Stories & Live Enhancements
- **Domain Services:** `StoryEnhancementService` (stories), `LiveStreamingWrapper` (participants/donations).
- **Controllers:** `StoryEnhancementController@store|viewers|viewer|creator`.
- **Routes:** Web `/pro-network/stories/viewer`, `/pro-network/stories/creator`; API `/api/pro-network/stories` (POST), `/api/pro-network/stories/{story}/viewers` (GET).
- **Views:** `pro_network::stories.viewer`, `pro_network::stories.creator`.
- **Models:** `StoryMetadata`, `LiveSession`, `LiveSessionParticipant`, `LiveSessionDonation` (participant/donation data referenced in wrapper), `StoryMusicTrack` via music library.
- **Config flags:** `features.stories_wrapper`, `features.live_streaming_enhanced`, `features.music_library`.

### Post Enhancements (Polls, Threads, Reshare, Celebrate)
- **Domain Service:** `PostEnhancementService` – polls, threads, reshares, celebrate.
- **Controller:** `PostEnhancementController@createPoll|storePoll|votePoll|createThread|storeThread|reshare|createCelebrate|storeCelebrate`.
- **Routes:**
  - Web creators under `/pro-network/posts/polls/create`, `/pro-network/posts/threads/create`, `/pro-network/posts/celebrate/create`.
  - API `/api/pro-network/posts/polls`, `/api/pro-network/posts/polls/{poll}/vote`, `/api/pro-network/posts/threads`, `/api/pro-network/posts/reshare`, `/api/pro-network/posts/celebrate`.
- **Views:** `pro_network::posts.polls.create`, `pro_network::posts.threads.create`, `pro_network::posts.celebrate.create`.
- **Models:** `PostEnhancement` (poll/thread metadata), `Hashtag`, `HashtagAssignment` for tagging.
- **Config flag:** `features.post_enhancements`.

### Reactions, Dislikes & Scores
- **Domain Service:** `ReactionsService` – reactions/dislikes, scoring.
- **Controller:** `ReactionsController@react|unreact|dislike|undislike|profileScore`.
- **Routes:** API `/api/pro-network/reactions` (POST/DELETE), `/api/pro-network/reactions/dislike` (POST/DELETE), `/api/pro-network/profiles/{user}/reaction-score`.
- **Models:** `Reaction`, `ReactionAggregate`, `ProfileReactionScore`.
- **Config flag:** `features.reactions_dislikes_scores`.

### Hashtags & Search SEO
- **Domain Service:** `HashtagService`, `SearchTagsDomain`, `SearchUpgradeService` for richer search and tagging.
- **Controller:** `HashtagController@index|search|show`.
- **Routes:** Web `/pro-network/hashtags/{hashtag}`; API `/api/pro-network/hashtags` (GET), `/api/pro-network/hashtags/search` (POST).
- **Views:** `pro_network::hashtags.show`.
- **Models:** `Hashtag`, `HashtagAssignment`.
- **Config flags:** `features.hashtags`, `features.search_upgrade`.

### Music Library
- **Domain Service:** `MusicLibraryService` – royalty-free track metadata.
- **Controller:** `MusicLibraryController@index|search`.
- **Routes:** API `/api/pro-network/music-library` (GET/POST search).
- **Models:** `MusicTrack`.
- **Config flag:** `features.music_library`.

### Recommendations, Notifications, Invites
- **Domain Services:** `RecommendationService`, `NotificationsWrapper`, `InviteContributorsService`.
- **Controllers/Routes:** Recommendations covered above; invites and notifications are surfaced via services for host wiring.
- **Config flags:** `features.recommendations`, `features.notifications_wrapper`, `features.invite_contributors`.

### Analytics Hub
- **Domain Service:** `AnalyticsService` – `metrics(array $filters)` and `series(array $filters)` plus tracking hooks.
- **Controller:** `AnalyticsController@overview|metrics|series`.
- **Routes:** Web `/pro-network/analytics`; API `/api/pro-network/analytics/metrics`, `/api/pro-network/analytics/series` (both `can:viewAnalytics`).
- **Views:** `pro_network::analytics.overview`.
- **Models:** `AnalyticsMetric`, `AnalyticsEvent`.
- **Config flag:** `features.analytics_hub`; driver config under `analytics.*`.

### Security & Moderation
- **Domain Services:** `SecurityEventService`, `ModerationService`, `BadWord`/`BadWordRule` utilities, `FileScan` hooks, `StorageService` encryption.
- **Controller:** `SecurityModerationController@securityLog|moderationQueue|events|queue|moderate`.
- **Routes:** Web `/pro-network/security/log` (`can:viewSecurity`), `/pro-network/moderation` (`can:moderate`); API `/api/pro-network/security/events`, `/api/pro-network/moderation/queue`, `/api/pro-network/moderation/action` with matching gates.
- **Views:** `pro_network::security.log`, `pro_network::moderation.queue`.
- **Models:** `SecurityEvent`, `ModerationQueue`, `BadWord`, `BadWordRule`, `FileScan`.
- **Config flags:** `features.security_hardening`, `features.moderation_tools`, `features.bad_word_checker`, `features.file_scan`, `security.*`.

### Storage & Encryption
- **Domain Service:** `StorageService` – maps disks, presigned URLs, optional encryption.
- **Models:** `BaseModel` provides encrypted casting for sensitive columns when enabled.
- **Config flags:** `features.storage_backends`, `features.db_encryption`, `storage.*`.

### Account Types & Feature Flags
- **Domain Service:** `AccountTypeService`.
- **Controllers/Routes:** Integrated within profile/company flows.
- **Models:** `AccountType`, `UserAccountType`, `UserFeatureFlag`.
- **Config flag:** `features.account_types`.

### Chat Enhancements
- **Domain Service:** `ChatEnhancementService` – conversation listing, clearing, message requests, settings.
- **Controller:** `ChatEnhancementController@listConversations|showConversation|deleteConversation|clearConversation|updateSettings|messageRequests|acceptRequest|declineRequest`.
- **Routes:** API `/api/pro-network/chat/*` guarded by `pro-network.feature:chat_enhancements`.
- **Models:** `NetworkMetric` (presence/status metrics leveraged in chat), conversation data handled via Sociopro core identifiers.
- **Config flag:** `features.chat_enhancements`.

### Newsletter & Invite to Contribute
- **Domain Service:** `NewsletterService` (subscribe/unsubscribe/admin listing); `InviteContributorsService` (invites).
- **Controller:** `NewsletterController@manage|adminIndex|subscribe|unsubscribe`.
- **Routes:** Web `/pro-network/newsletters/manage`; admin newsletters under `/pro-network/analytics` group; API `/api/pro-network/newsletters/subscribe`, `/api/pro-network/newsletters/unsubscribe`.
- **Views:** `pro_network::newsletters.manage`, `pro_network::newsletters.admin.index`.
- **Models:** `NewsletterSubscription`, `InviteContribution`.
- **Config flag:** `features.newsletters`, `features.invite_contributors`.

### Age Verification / KYC
- **Domain Service:** `AgeVerificationService` – status and start placeholders.
- **Controller:** `AgeVerificationController@status|start|callback`.
- **Routes:** Web `/pro-network/age-verification/callback`; API `/api/pro-network/age-verification/status`, `/api/pro-network/age-verification/start`.
- **Models:** `AgeVerification`, `AgeVerificationLog`.
- **Config flags:** `features.age_verification`, `age_verification.*`, `kyc.*`.

### Multi-language Wrapper
- **Domain Service:** `MultiLanguageService` for translating new feature surfaces.
- **Config flag:** `features.multi_language_wrapper`.

### Service Provider & Middleware
- **Provider:** `ProNetworkUtilitiesSecurityAnalyticsServiceProvider` merges config, loads migrations/routes/views/translations, registers policies, binds services, and registers `EnsureFeatureEnabled` middleware alias `pro-network.feature`.
- **Policies:** `MarketplaceEscrowPolicy`, `MarketplaceDisputePolicy`, `CompanyProfilePolicy` plus gates for analytics/security/moderation.

## 2. Flutter Addon

### Models
Located under `flutter_addon/lib/models/*` covering networking, profiles, companies, escrows/disputes, stories/live, posts (polls/threads/reshare/celebrate), reactions, recommendations, analytics metrics/series, security/age verification, newsletters, chat.

### API Clients
Under `flutter_addon/lib/services`:
- `ApiClient` (base), `ConnectionsApi`, `ProfileApi`, `CompanyApi`, `MarketplaceEscrowApi`, `StoriesApi`, `PostsApi`, `ChatApi`, `AnalyticsApi`, `SecurityApi`, `NewsletterApi`, `AgeVerificationApi`, `RecommendationsApi` (via network client), plus `services.dart` export barrel.

### State Management
`flutter_addon/lib/state/state.dart` exposes `ChangeNotifier` stores for network, profile, company, escrow/disputes, stories/posts, chat, analytics, moderation/security, newsletters, and age verification with loading/error/data handling.

### Screens & Widgets
Located under `flutter_addon/lib/ui`:
- Network (`network.dart`), Profile (`profile.dart`), Company (`company.dart`), Marketplace (`marketplace.dart`), Stories (`stories.dart`), Posts (`posts.dart`), Chat (`chat.dart`), Analytics (`analytics.dart`), Moderation (`moderation.dart`), Newsletters (`newsletters.dart`), Account/Security (`account_security.dart`), shared components in `common.dart` and `ui.dart`.

### Navigation Hooks
- `flutter_addon/lib/menu.dart` exports `proNetworkMenuItems` with route keys and builders.
- `flutter_addon/lib/menu/routes.dart` provides `ProNetworkRoutes` builders for router integration.

### Analytics & Security Hooks
- `flutter_addon/lib/analytics` contains `AnalyticsClient` to fire screen/action events to `/api/pro-network/analytics/*` endpoints; security metadata can be attached via `ApiClient` headers.

## 3. Integration Points
- **Feed & Posts:** Post enhancements (polls/threads/reshare/celebrate) and reactions integrate with Sociopro posts via `/api/pro-network/posts/*` and reaction endpoints; hashtags stored via `HashtagService` feed search.
- **Search:** Tag enrichment through `HashtagService`, `SearchTagsDomain`, and profile/company skill/employment metadata for richer queries.
- **Analytics:** Backend `AnalyticsService` records metrics/series; Flutter `AnalyticsClient` sends screen/action signals.
- **Security:** `SecurityEventService` logs events; moderation queue/actions available via API and exposed in Flutter moderation screens; file scan/bad word hooks rely on config toggles.
- **Storage:** `StorageService` maps to R2/Wasabi/local disks with signed URLs and optional encryption; mobile uploads should honor configured TTLs.
