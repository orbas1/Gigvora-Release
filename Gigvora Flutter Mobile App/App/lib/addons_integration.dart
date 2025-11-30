import 'dart:async';

import 'package:advertisement_flutter_addon/advertisement_flutter_addon.dart' as ads_pkg;
import 'package:freelance_phone_addon/freelance_flutter_addon.dart' as freelance;
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart' as riverpod;
import 'package:jobs_flutter_addon/jobs_flutter_addon.dart' as jobs;
import 'package:provider/provider.dart' as legacy_provider;
import 'package:provider/single_child_widget.dart' as provider_widget;
import 'package:talent_ai_flutter_addon/pro_network_utilities_security_analytics.dart' as talent;
import 'package:webinar_networking_interview_podcast_flutter_addon/webinar_networking_interview_podcast_flutter_addon.dart'
    as live_addon;
import 'gigvora_theme.dart';

export 'gigvora_navigation.dart';
export 'feed_shell.dart';
export 'profile_shell.dart';
export 'stories_shell.dart';
export 'media_shell.dart';
export 'utilities_quick_tools.dart';
export 'inbox_shell.dart';

/// Shared token provider signature reused by both addons.
typedef AuthTokenProvider = Future<String?> Function();
typedef SyncTokenProvider = String Function();

class AdvertisementFeatureFlags {
  final bool enabled;

  const AdvertisementFeatureFlags({this.enabled = true});
}

class AddonMenuEntry {
  final String title;
  final IconData icon;
  final String route;

  const AddonMenuEntry({
    required this.title,
    required this.icon,
    required this.route,
  });
}

class TalentAiApis {
  final talent.AnalyticsApi analyticsApi;
  final talent.HeadhunterApi headhunterApi;
  final talent.LaunchpadApi launchpadApi;
  final talent.AiWorkspaceApi aiWorkspaceApi;
  final talent.VolunteeringApi volunteeringApi;

  const TalentAiApis({
    required this.analyticsApi,
    required this.headhunterApi,
    required this.launchpadApi,
    required this.aiWorkspaceApi,
    required this.volunteeringApi,
  });

  factory TalentAiApis.fromBaseUrl({
    required String baseUrl,
    required AuthTokenProvider tokenProvider,
  }) {
    return TalentAiApis(
      analyticsApi: talent.AnalyticsApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      headhunterApi: talent.HeadhunterApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      launchpadApi: talent.LaunchpadApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      aiWorkspaceApi: talent.AiWorkspaceApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      volunteeringApi: talent.VolunteeringApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
    );
  }
}

class GigvoraAddonProviders {
  /// Bloc providers for the advertisement addon.
  static List<BlocProvider> ads({required ads_pkg.AdvertisementRepository repository}) {
    return [
      BlocProvider(create: (_) => ads_pkg.CampaignBloc(repository)..load()),
      BlocProvider(create: (_) => ads_pkg.CreativeBloc(repository)),
      BlocProvider(create: (_) => ads_pkg.AnalyticsBloc(repository)),
      BlocProvider(create: (_) => ads_pkg.ForecastBloc(repository)),
      BlocProvider(create: (_) => ads_pkg.KeywordPlannerBloc(repository)),
      BlocProvider(create: (_) => ads_pkg.AffiliateBloc(repository)..refresh()),
    ];
  }

  /// ChangeNotifier providers for Talent & AI pillars.
  static List<provider_widget.SingleChildWidget> talentAi({
    required TalentAiApis apis,
    required talent.TalentAiFeatureFlags flags,
  }) {
    final analyticsClient = talent.TalentAiAnalyticsClient(talent.AnalyticsClient(apis.analyticsApi));
    return [
      if (flags.headhunters)
        legacy_provider.ChangeNotifierProvider(
          create: (_) => talent.HeadhunterState(apis.headhunterApi),
        ),
      if (flags.launchpad)
        legacy_provider.ChangeNotifierProvider(
          create: (_) => talent.LaunchpadState(apis.launchpadApi),
        ),
      if (flags.aiWorkspace)
        legacy_provider.ChangeNotifierProvider(
          create: (_) => talent.AiWorkspaceState(apis.aiWorkspaceApi),
        ),
      if (flags.volunteering)
        legacy_provider.ChangeNotifierProvider(
          create: (_) => talent.VolunteeringState(apis.volunteeringApi),
        ),
      legacy_provider.Provider.value(value: analyticsClient),
    ];
  }
}

/// Helper to expose the canonical Gigvora [ThemeData] so the host
/// application and addons can share a single source of truth for
/// colours and typography that mirror the web tokens.
///
/// See `resources/css/gigvora/tokens.css` and `docs/ui-audit.md#4-recommended-token-based-upgrade-path`.
class GigvoraThemeData {
  static ThemeData light() => GigvoraTheme.light();
}

class GigvoraAddonNavigation {
  /// Combined advertisement + Talent & AI routes using MaterialApp route maps.
  static Map<String, WidgetBuilder> routes({
    required AdvertisementFeatureFlags advertisementFlags,
    required talent.TalentAiFeatureFlags flags,
    required TalentAiApis apis,
    JobsIntegrationOptions? jobs,
    FreelanceIntegrationOptions? freelance,
    LiveEventsIntegrationOptions? live,
  }) {
    final analyticsClient = talent.TalentAiAnalyticsClient(talent.AnalyticsClient(apis.analyticsApi));
    final talentRoutes = <String, WidgetBuilder>{
      if (flags.headhunters)
        '/talent-ai/headhunters': (_) => talent.HeadhunterDashboardScreen(
              analytics: analyticsClient,
            ),
      if (flags.headhunters)
        '/talent-ai/headhunters/mandates': (_) => talent.MandateListScreen(
              analytics: analyticsClient,
            ),
      if (flags.headhunters)
        '/talent-ai/headhunters/mandates/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return talent.MandateDetailScreen(
            mandateId: id,
            analytics: analyticsClient,
          );
        },
      if (flags.launchpad)
        '/talent-ai/launchpad': (_) => talent.LaunchpadProgrammeListScreen(
              analytics: analyticsClient,
            ),
      if (flags.launchpad)
        '/talent-ai/launchpad/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return talent.LaunchpadProgrammeDetailScreen(
            programmeId: id,
            analytics: analyticsClient,
          );
        },
      if (flags.launchpad)
        '/talent-ai/launchpad/applications/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return talent.LaunchpadApplicationScreen(
            applicationId: id,
            analytics: analyticsClient,
          );
        },
      if (flags.aiWorkspace)
        '/talent-ai/ai-workspace': (_) => talent.AiWorkspaceScreen(
              analytics: analyticsClient,
            ),
      if (flags.volunteering)
        '/talent-ai/volunteering': (_) => talent.VolunteeringListScreen(
              analytics: analyticsClient,
            ),
      if (flags.volunteering)
        '/talent-ai/volunteering/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return talent.VolunteeringDetailScreen(
            opportunityId: id,
            analytics: analyticsClient,
          );
        },
    };

    final adsRoutes =
        advertisementFlags.enabled ? ads_pkg.buildAdsRoutes() : <String, WidgetBuilder>{};

    final jobsRoutes = _jobsRoutes(jobs);
    final freelanceRoutes = _freelanceRoutes(freelance);
    final liveRoutes = _liveEventsRoutes(live);

    return {
      ...adsRoutes,
      ...talentRoutes,
      ...jobsRoutes,
      ...freelanceRoutes,
      ...liveRoutes,
    };
  }

  /// Helper to expose the icon/label pairs for the host navigation drawer or bottom nav.
  static List<AddonMenuEntry> menuItems({
    required AdvertisementFeatureFlags advertisementFlags,
    required talent.TalentAiFeatureFlags flags,
    JobsIntegrationOptions? jobs,
    FreelanceIntegrationOptions? freelance,
    LiveEventsIntegrationOptions? live,
  }) {
    final adsMenu = advertisementFlags.enabled
        ? ads_pkg.adsMenuItems
            .map(
              (item) => AddonMenuEntry(
                title: item.title,
                icon: item.icon,
                route: item.route,
              ),
            )
            .toList()
        : <AddonMenuEntry>[];

    final talentMenu = talent.talentAiMenuItems(flags)
        .map(
          (item) => AddonMenuEntry(
            title: item.label,
            icon: _talentIcon(item.iconKey),
            route: item.routeName,
          ),
        )
        .toList();

    final jobsMenu = _jobsMenuEntries(jobs);
    final freelanceMenus = _freelanceMenuEntries(freelance);
    final liveMenus = _liveMenuEntries(live);

    return [...adsMenu, ...talentMenu, ...jobsMenu, ...freelanceMenus, ...liveMenus];
  }
}

class FreelanceIntegrationOptions {
  static String _defaultTokenProvider() => '';

  final bool enabled;
  final bool showFreelancerMenu;
  final bool showClientMenu;
  final bool includeSetupLink;
  final String baseUrl;
  final String apiPrefix;
  final SyncTokenProvider tokenProvider;
  final Duration? requestTimeout;

  const FreelanceIntegrationOptions({
    this.enabled = false,
    this.baseUrl = '',
    this.tokenProvider = _defaultTokenProvider,
    this.showFreelancerMenu = true,
    this.showClientMenu = true,
    this.includeSetupLink = true,
    this.apiPrefix = 'api/freelance',
    this.requestTimeout,
  });
}

class LiveEventsIntegrationOptions {
  final bool enabled;
  final String baseUrl;
  final FutureOr<String?> Function()? tokenProvider;
  final String apiPrefix;
  final Duration? requestTimeout;
  final bool includeInterviewsInMainNav;
  final bool includeCandidateInterviewMenu;
  final bool includeEmployerInterviewMenu;

  const LiveEventsIntegrationOptions({
    this.enabled = false,
    this.baseUrl = '',
    this.tokenProvider,
    this.apiPrefix = 'api/live',
    this.requestTimeout,
    this.includeInterviewsInMainNav = false,
    this.includeCandidateInterviewMenu = false,
    this.includeEmployerInterviewMenu = false,
  });
}

class JobsIntegrationOptions {
  final bool enabled;
  final String baseUrl;
  final SyncTokenProvider tokenProvider;
  final bool showSeekerMenu;
  final bool showEmployerMenu;

  const JobsIntegrationOptions({
    this.enabled = false,
    this.baseUrl = '',
    this.tokenProvider = FreelanceIntegrationOptions._defaultTokenProvider,
    this.showSeekerMenu = true,
    this.showEmployerMenu = true,
  });
}

/// Convenience factory for building the advertisement repository with a shared token provider.
ads_pkg.AdvertisementRepository buildAdvertisementRepository({
  required String baseUrl,
  required AuthTokenProvider tokenProvider,
}) {
  return ads_pkg.AdvertisementRepository(
    api: ads_pkg.AdvertisementApiClient(
      baseUrl: baseUrl,
      tokenProvider: tokenProvider,
    ),
  );
}

IconData _talentIcon(String? key) {
  switch (key) {
    case 'work':
      return Icons.work_outline;
    case 'school':
      return Icons.school_outlined;
    case 'bolt':
      return Icons.smart_toy_outlined;
    case 'favorite':
      return Icons.volunteer_activism_outlined;
    case 'people':
      return Icons.people_alt_outlined;
    case 'badge':
      return Icons.badge_outlined;
    case 'business':
      return Icons.apartment;
    case 'shield':
      return Icons.shield_outlined;
    case 'story':
      return Icons.auto_stories_outlined;
    case 'analytics':
      return Icons.analytics_outlined;
    case 'mail':
      return Icons.mail_outline;
    case 'lock':
      return Icons.lock_outline;
    default:
      return Icons.apps;
  }
}

Map<String, WidgetBuilder> _freelanceRoutes(FreelanceIntegrationOptions? options) {
  if (options == null || !options.enabled || options.baseUrl.isEmpty) {
    return const {};
  }

  final overrides = freelance.FreelanceAddonIntegration.providerOverrides(
    baseUrl: options.baseUrl,
    apiPrefix: options.apiPrefix,
    tokenProvider: options.tokenProvider,
    requestTimeout: options.requestTimeout,
  );

  WidgetBuilder withOverrides(WidgetBuilder builder) {
    return (context) => riverpod.ProviderScope(
          overrides: overrides,
          child: builder(context),
        );
  }

  final baseRoutes = freelance.FreelanceAddonIntegration.routes();
  return baseRoutes.map((path, builder) => MapEntry(path, withOverrides(builder)));
}

List<AddonMenuEntry> _freelanceMenuEntries(FreelanceIntegrationOptions? options) {
  if (options == null || !options.enabled) {
    return const [];
  }

  final menuItems = freelance.FreelanceAddonIntegration.navigationItems(
    isFreelancer: options.showFreelancerMenu,
    isClient: options.showClientMenu,
    includeSetup: options.includeSetupLink,
  );

  return menuItems
      .map(
        (item) => AddonMenuEntry(
          title: item.title,
          icon: item.icon,
          route: item.route,
        ),
      )
      .toList();
}

Map<String, WidgetBuilder> _liveEventsRoutes(LiveEventsIntegrationOptions? options) {
  if (options == null || !options.enabled || options.baseUrl.isEmpty) {
    return const {};
  }

  final client = live_addon.LiveAddonIntegration.createClient(
    baseUrl: options.baseUrl,
    apiPrefix: options.apiPrefix,
    tokenProvider: options.tokenProvider,
    requestTimeout: options.requestTimeout,
  );

  return live_addon.LiveAddonIntegration.routes(client);
}

List<AddonMenuEntry> _liveMenuEntries(LiveEventsIntegrationOptions? options) {
  if (options == null || !options.enabled) {
    return const [];
  }

  final menuMap = <String, live_addon.MenuItem>{};

  void addItems(List<live_addon.MenuItem> items) {
    for (final item in items) {
      menuMap[item.route] = item;
    }
  }

  addItems(live_addon.LiveAddonIntegration.liveNavigation());

  if (options.includeInterviewsInMainNav || options.includeCandidateInterviewMenu) {
    addItems(live_addon.LiveAddonIntegration.candidateInterviewNavigation());
  }

  if (options.includeEmployerInterviewMenu) {
    addItems(live_addon.LiveAddonIntegration.employerInterviewNavigation());
  }

  return menuMap.values
      .map(
        (item) => AddonMenuEntry(
          title: item.title,
          icon: item.icon,
          route: item.route,
        ),
      )
      .toList();
}

Map<String, WidgetBuilder> _jobsRoutes(JobsIntegrationOptions? options) {
  if (options == null || !options.enabled || options.baseUrl.isEmpty) {
    return const {};
  }

  jobs.JobsAddon.configure(
    baseUrl: options.baseUrl,
    token: options.tokenProvider(),
  );

  return jobs.buildRoutes();
}

List<AddonMenuEntry> _jobsMenuEntries(JobsIntegrationOptions? options) {
  if (options == null || !options.enabled) {
    return const [];
  }

  final entries = <AddonMenuEntry>[];
  if (options.showSeekerMenu) {
    entries.addAll(
      jobs.seekerMenu
          .map((item) => AddonMenuEntry(title: item.title, icon: item.icon, route: item.route))
          .toList(),
    );
  }
  if (options.showEmployerMenu) {
    entries.addAll(
      jobs.employerMenu
          .map((item) => AddonMenuEntry(title: item.title, icon: item.icon, route: item.route))
          .toList(),
    );
  }

  return entries;
}
