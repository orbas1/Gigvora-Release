import 'package:flutter/material.dart';

import 'api/wnip_api_client.dart';
import 'pages/interviews/interview_detail_screen.dart';
import 'pages/interviews/interview_live_screen.dart';
import 'pages/interviews/interview_schedule_screen.dart';
import 'pages/interviews/interview_waiting_room_screen.dart';
import 'pages/interviews/interviewer_panel_screen.dart';
import 'pages/networking/networking_home_screen.dart';
import 'pages/networking/networking_live_screen.dart';
import 'pages/networking/networking_session_detail_screen.dart';
import 'pages/networking/networking_waiting_room_screen.dart';
import 'pages/podcasts/podcast_catalogue_screen.dart';
import 'pages/podcasts/podcast_episode_player_screen.dart';
import 'pages/podcasts/podcast_live_recording_screen.dart';
import 'pages/podcasts/podcast_series_detail_screen.dart';
import 'pages/webinars/webinar_detail_screen.dart';
import 'pages/webinars/webinar_live_screen.dart';
import 'pages/webinars/webinar_recording_player_screen.dart';
import 'pages/webinars/webinar_waiting_room_screen.dart';
import 'pages/webinars/webinars_home_screen.dart';
import 'models/podcast_episode.dart';
import 'services/interview_service.dart';
import 'services/networking_service.dart';
import 'services/podcast_service.dart';
import 'services/webinar_service.dart';

class MenuItem {
  final String title;
  final String route;
  final IconData icon;

  const MenuItem({required this.title, required this.route, required this.icon});
}

const _webinarMenu = MenuItem(title: 'Webinars', route: '/live/webinars', icon: Icons.videocam_outlined);
const _networkingMenu = MenuItem(title: 'Networking', route: '/live/networking', icon: Icons.people_outline);
const _podcastMenu = MenuItem(title: 'Podcasts', route: '/live/podcasts', icon: Icons.podcasts);
const _interviewMenu = MenuItem(title: 'Interviews', route: '/live/interviews', icon: Icons.event_note_outlined);

List<MenuItem> buildMenuItems({bool includeInterviewsInMainNav = false}) {
  final items = <MenuItem>[_webinarMenu, _networkingMenu, _podcastMenu];
  if (includeInterviewsInMainNav) {
    items.add(_interviewMenu);
  }
  return items;
}

List<MenuItem> buildLiveEventsMenu() => const [_webinarMenu, _networkingMenu, _podcastMenu];

List<MenuItem> buildCandidateInterviewMenu() => const [_interviewMenu];

List<MenuItem> buildEmployerInterviewMenu() => const [
      MenuItem(title: 'Interview Panel', route: '/live/interviews', icon: Icons.event_available_outlined),
    ];

Map<String, WidgetBuilder> buildAddonRoutes(
    {WnipApiClient? apiClient,
    WebinarService? webinarService,
    NetworkingService? networkingService,
    PodcastService? podcastService,
    InterviewService? interviewService}) {
  final webinarSvc = webinarService ?? WebinarService(apiClient!);
  final networkingSvc = networkingService ?? NetworkingService(apiClient!);
  final podcastSvc = podcastService ?? PodcastService(apiClient!);
  final interviewSvc = interviewService ?? InterviewService(apiClient!);

  return {
    '/live/webinars': (_) => WebinarsHomeScreen(service: webinarSvc),
    '/live/webinars/:id': (context) {
      final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
      return WebinarDetailScreen(service: webinarSvc, webinarId: id);
    },
    '/live/webinars/waiting/:id': (context) {
      final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
      return WebinarWaitingRoomScreen(
        webinarTitle: args?['title'] as String? ?? 'Webinar',
        startsAt: args?['startsAt'] as DateTime? ?? DateTime.now(),
        waitingRoomMessage: args?['message'] as String?,
        isLive: args?['isLive'] as bool? ?? false,
      );
    },
    '/live/webinars/live': (_) => const WebinarLiveScreen(title: 'Webinar Live'),
    '/live/webinars/recording/:id': (context) {
      final recording = ModalRoute.of(context)?.settings.arguments as dynamic;
      return WebinarRecordingPlayerScreen(recording: recording);
    },
    '/live/networking': (_) => NetworkingHomeScreen(service: networkingSvc),
    '/live/networking/:id': (context) {
      final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
      return NetworkingSessionDetailScreen(service: networkingSvc, sessionId: id);
    },
    '/live/networking/waiting/:id': (context) {
      final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
      return NetworkingWaitingRoomScreen(
        sessionTitle: args?['title'] as String? ?? 'Networking Session',
        startsAt: args?['startsAt'] as DateTime? ?? DateTime.now(),
        isLive: args?['isLive'] as bool? ?? false,
      );
    },
    '/live/networking/live': (_) => const NetworkingLiveScreen(),
    '/live/podcasts': (_) => PodcastCatalogueScreen(service: podcastSvc),
    '/live/podcasts/series/:id': (context) {
      final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
      return PodcastSeriesDetailScreen(service: podcastSvc, seriesId: id);
    },
    '/live/podcasts/episode/:id': (context) {
      final args = ModalRoute.of(context)?.settings.arguments;
      if (args is Map<String, dynamic>) {
        return PodcastEpisodePlayerScreen(
          service: podcastSvc,
          episode: args['episode'] as PodcastEpisode?,
          episodeId: args['episodeId'] as int?,
          seriesId: args['seriesId'] as int?,
        );
      }

      if (args is PodcastEpisode) {
        return PodcastEpisodePlayerScreen(service: podcastSvc, episode: args, seriesId: args.podcastSeriesId, episodeId: args.id);
      }

      final episodeId = args is int ? args : 0;
      return PodcastEpisodePlayerScreen(service: podcastSvc, episodeId: episodeId, seriesId: null);
    },
    '/live/podcasts/live': (_) => const PodcastLiveRecordingScreen(),
    '/live/interviews': (_) => InterviewScheduleScreen(service: interviewSvc),
    '/live/interviews/:id': (context) {
      final id = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
      return InterviewDetailScreen(service: interviewSvc, interviewId: id);
    },
    '/live/interviews/waiting/:id': (context) {
      final title = ModalRoute.of(context)?.settings.arguments as String? ?? 'Interview';
      return InterviewWaitingRoomScreen(title: title);
    },
    '/live/interviews/live': (_) => const InterviewLiveScreen(),
    '/live/interviews/interviewer/:id': (context) {
      final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
      final interviewId = args?['interviewId'] as int? ?? 0;
      final slotId = args?['slotId'] as int? ?? 0;
      return InterviewerPanelScreen(service: interviewSvc, interviewId: interviewId, slotId: slotId);
    },
  };
}
