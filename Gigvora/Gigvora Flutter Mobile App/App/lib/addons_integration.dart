import 'package:advertisement_flutter_addon/advertisement_flutter_addon.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:provider/provider.dart';
import 'package:talent_ai_flutter_addon/pro_network_utilities_security_analytics.dart';

/// Shared token provider signature reused by both addons.
typedef AuthTokenProvider = Future<String?> Function();

class TalentAiApis {
  final AnalyticsApi analyticsApi;
  final HeadhunterApi headhunterApi;
  final LaunchpadApi launchpadApi;
  final AiWorkspaceApi aiWorkspaceApi;
  final VolunteeringApi volunteeringApi;

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
      analyticsApi: AnalyticsApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      headhunterApi: HeadhunterApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      launchpadApi: LaunchpadApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      aiWorkspaceApi: AiWorkspaceApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
      volunteeringApi: VolunteeringApi(baseUrl: baseUrl, tokenProvider: tokenProvider),
    );
  }
}

class GigvoraAddonProviders {
  /// Bloc providers for the advertisement addon.
  static List<BlocProvider> ads({required AdvertisementRepository repository}) {
    return [
      BlocProvider(create: (_) => CampaignBloc(repository)..load()),
      BlocProvider(create: (_) => CreativeBloc(repository)),
      BlocProvider(create: (_) => AnalyticsBloc(repository)),
      BlocProvider(create: (_) => ForecastBloc(repository)),
      BlocProvider(create: (_) => KeywordPlannerBloc(repository)),
      BlocProvider(create: (_) => AffiliateBloc(repository)..refresh()),
    ];
  }

  /// ChangeNotifier providers for Talent & AI pillars.
  static List<ChangeNotifierProvider> talentAi({
    required TalentAiApis apis,
    required TalentAiFeatureFlags flags,
  }) {
    final analyticsClient = TalentAiAnalyticsClient(AnalyticsClient(apis.analyticsApi));
    return [
      if (flags.headhunters)
        ChangeNotifierProvider(
          create: (_) => HeadhunterState(apis.headhunterApi),
        ),
      if (flags.launchpad)
        ChangeNotifierProvider(
          create: (_) => LaunchpadState(apis.launchpadApi),
        ),
      if (flags.aiWorkspace)
        ChangeNotifierProvider(
          create: (_) => AiWorkspaceState(apis.aiWorkspaceApi),
        ),
      if (flags.volunteering)
        ChangeNotifierProvider(
          create: (_) => VolunteeringState(apis.volunteeringApi),
        ),
      Provider.value(value: analyticsClient),
    ];
  }
}

class GigvoraAddonNavigation {
  /// Combined advertisement + Talent & AI routes using MaterialApp route maps.
  static Map<String, WidgetBuilder> routes({
    required TalentAiFeatureFlags flags,
    required TalentAiApis apis,
  }) {
    final analyticsClient = TalentAiAnalyticsClient(AnalyticsClient(apis.analyticsApi));
    final talentRoutes = <String, WidgetBuilder>{
      if (flags.headhunters)
        '/talent-ai/headhunters': (_) => HeadhunterDashboardScreen(
              analytics: analyticsClient,
            ),
      if (flags.headhunters)
        '/talent-ai/headhunters/mandates': (_) => MandateListScreen(
              analytics: analyticsClient,
            ),
      if (flags.headhunters)
        '/talent-ai/headhunters/mandates/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return MandateDetailScreen(
            mandateId: id,
            analytics: analyticsClient,
          );
        },
      if (flags.launchpad)
        '/talent-ai/launchpad': (_) => LaunchpadProgrammesScreen(
              analytics: analyticsClient,
            ),
      if (flags.launchpad)
        '/talent-ai/launchpad/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return LaunchpadProgrammeDetailScreen(
            programmeId: id,
            analytics: analyticsClient,
          );
        },
      if (flags.launchpad)
        '/talent-ai/launchpad/applications/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return LaunchpadApplicationScreen(
            applicationId: id,
            analytics: analyticsClient,
          );
        },
      if (flags.aiWorkspace)
        '/talent-ai/ai-workspace': (_) => AiWorkspaceScreen(
              analytics: analyticsClient,
            ),
      if (flags.volunteering)
        '/talent-ai/volunteering': (_) => VolunteeringListScreen(
              analytics: analyticsClient,
            ),
      if (flags.volunteering)
        '/talent-ai/volunteering/:id': (context) {
          final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
          return VolunteeringDetailScreen(
            opportunityId: id,
            analytics: analyticsClient,
          );
        },
    };

    return {
      ...buildAdsRoutes(),
      ...talentRoutes,
    };
  }

  /// Helper to expose the icon/label pairs for the host navigation drawer or bottom nav.
  static List<MenuItem> menuItems({
    required TalentAiFeatureFlags flags,
  }) {
    final ads = adsMenuItems;
    final talent = talentAiMenuItems(flags);
    return [...ads, ...talent];
  }
}

/// Convenience factory for building the advertisement repository with a shared token provider.
AdvertisementRepository buildAdvertisementRepository({
  required String baseUrl,
  required AuthTokenProvider tokenProvider,
}) {
  return AdvertisementRepository(
    api: AdvertisementApiClient(
      baseUrl: baseUrl,
      tokenProvider: tokenProvider,
    ),
  );
}
