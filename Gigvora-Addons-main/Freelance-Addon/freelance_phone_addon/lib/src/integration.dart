import 'package:flutter/widgets.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;

import 'menu.dart';
import 'pages/global/freelance_onboarding_screen.dart';
import 'state/core_providers.dart';

/// Helpers to attach the Freelance add-on to the Gigvora host mobile shell.
class FreelanceAddonIntegration {
  const FreelanceAddonIntegration._();

  /// Provider overrides to plug in the host API base URL, token source, and client.
  static List<Override> providerOverrides({
    required String baseUrl,
    required String Function() tokenProvider,
    http.Client? httpClient,
    String apiPrefix = 'api/freelance',
    Duration? requestTimeout,
  }) {
    return [
      baseUrlProvider.overrideWithValue(baseUrl),
      apiPrefixProvider.overrideWithValue(apiPrefix),
      tokenProviderOverride.overrideWithValue(tokenProvider),
      if (httpClient != null) httpClientProvider.overrideWithValue(httpClient),
      if (requestTimeout != null)
        requestTimeoutProvider.overrideWithValue(requestTimeout),
    ];
  }

  /// Drawer/tab entries that mirror the Gigvora web navigation.
  static List<MenuItem> navigationItems({
    required bool isFreelancer,
    required bool isClient,
    bool includeSetup = true,
  }) {
    return buildFreelanceMenu(
      isFreelancer: isFreelancer,
      isClient: isClient,
      includeGlobal: includeSetup,
    );
  }

  /// Routes ready to inject into the host app Router.
  static Map<String, WidgetBuilder> routes() => buildRoutes();

  /// Quick access to the onboarding entry for conditional flows.
  static WidgetBuilder onboardingRoute() => (context) => const FreelanceOnboardingScreen();
}
