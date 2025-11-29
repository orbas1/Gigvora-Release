import 'dart:async';

import 'package:flutter/widgets.dart';
import 'package:http/http.dart' as http;

import 'api/wnip_api_client.dart';
import 'menu.dart';
import 'services/interview_service.dart';
import 'services/networking_service.dart';
import 'services/podcast_service.dart';
import 'services/webinar_service.dart';

/// Helper utilities for wiring the Live & Events add-on into the Gigvora host mobile app.
class LiveAddonIntegration {
  const LiveAddonIntegration._();

  static WnipApiClient createClient({
    required String baseUrl,
    FutureOr<String?> Function()? tokenProvider,
    http.Client? httpClient,
    String apiPrefix = 'api/live',
    Duration? requestTimeout,
  }) {
    return WnipApiClient(
      baseUrl: baseUrl,
      httpClient: httpClient,
      tokenProvider: tokenProvider,
      apiPrefix: apiPrefix,
      requestTimeout: requestTimeout ?? const Duration(seconds: 20),
    );
  }

  static Map<String, WidgetBuilder> routes(WnipApiClient apiClient) {
    final webinarService = WebinarService(apiClient);
    final networkingService = NetworkingService(apiClient);
    final podcastService = PodcastService(apiClient);
    final interviewService = InterviewService(apiClient);

    return buildAddonRoutesWithServices(
      webinarService: webinarService,
      networkingService: networkingService,
      podcastService: podcastService,
      interviewService: interviewService,
    );
  }

  static Map<String, WidgetBuilder> buildAddonRoutesWithServices({
    required WebinarService webinarService,
    required NetworkingService networkingService,
    required PodcastService podcastService,
    required InterviewService interviewService,
  }) {
    return buildAddonRoutes(
      webinarService: webinarService,
      networkingService: networkingService,
      podcastService: podcastService,
      interviewService: interviewService,
    );
  }

  /// Top-level navigation entries for the shared Live & Events section.
  static List<MenuItem> liveNavigation() => buildLiveEventsMenu();

  /// Candidate-facing interview entries to place inside jobs/application areas.
  static List<MenuItem> candidateInterviewNavigation() => buildCandidateInterviewMenu();

  /// Employer/HR interview entries for admin sections.
  static List<MenuItem> employerInterviewNavigation() => buildEmployerInterviewMenu();
}
