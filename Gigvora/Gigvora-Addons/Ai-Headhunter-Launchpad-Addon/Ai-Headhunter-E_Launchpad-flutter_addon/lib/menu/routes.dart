import 'package:flutter/material.dart';
import '../analytics/analytics_client.dart';
import '../state/state.dart';
import '../ui/account_security.dart';
import '../ui/analytics.dart';
import '../ui/chat.dart';
import '../ui/company.dart';
import '../ui/marketplace.dart';
import '../ui/moderation.dart';
import '../ui/network.dart';
import '../ui/newsletters.dart';
import '../ui/posts.dart';
import '../ui/profile.dart';
import '../ui/stories.dart';

class ProNetworkRoutes {
  static Map<String, WidgetBuilder> builders(AnalyticsClient analytics) {
    return {
      '/my-network': (context) => MyNetworkScreen(analytics: analytics),
      '/connections': (context) => ConnectionsListScreen(analytics: analytics),
      '/connections/mutual': (context) {
        final userId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return MutualConnectionsScreen(userId: userId, analytics: analytics);
      },
      '/professional-profile': (context) => ProfessionalProfileScreen(analytics: analytics),
      '/professional-profile/edit': (context) => EditProfessionalProfileScreen(analytics: analytics),
      '/company': (context) {
        final companyId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return CompanyProfileScreen(companyId: companyId, analytics: analytics);
      },
      '/escrow': (context) {
        final orderId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return OrderEscrowScreen(orderId: orderId, analytics: analytics);
      },
      '/dispute': (context) {
        final disputeId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return DisputeDetailScreen(disputeId: disputeId, analytics: analytics);
      },
      '/stories-viewer': (context) {
        final storyId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return StoriesViewerScreen(storyId: storyId, analytics: analytics);
      },
      '/stories-creator': (context) => StoryCreatorScreen(analytics: analytics),
      '/posts/polls/create': (context) => CreatePollScreen(analytics: analytics),
      '/posts/threads/create': (context) => ThreadedPostScreen(analytics: analytics),
      '/posts/celebrate/create': (context) => CelebrateOccasionComposer(analytics: analytics),
      '/chat': (context) => ChatListScreen(analytics: analytics),
      '/chat/requests': (context) => MessageRequestsScreen(analytics: analytics),
      '/chat/detail': (context) {
        final conversationId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return ChatDetailScreen(conversationId: conversationId, analytics: analytics);
      },
      '/analytics': (context) => AnalyticsOverviewScreen(analytics: analytics),
      '/analytics/entity': (context) => EntityAnalyticsScreen(analytics: analytics),
      '/moderation': (context) => ModerationQueueScreen(analytics: analytics),
      '/moderation/detail': (context) {
        final item =
            ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>? ?? {};
        return ModerationDetailScreen(analytics: analytics, item: item);
      },
      '/newsletters': (context) => NewsletterSettingsScreen(analytics: analytics),
      '/account-security': (context) => AccountSecurityScreen(analytics: analytics),
    };
  }
}

/// Host apps can merge [proNetworkMenuItems] with their existing navigation
/// and register [ProNetworkRoutes.builders] inside their route map or router
/// delegate. All screens rely on Provider instances for the relevant state
/// classes (e.g. `MyNetworkState`, `ProfessionalProfileState`) that should be
/// provided higher in the widget tree, alongside a shared [AnalyticsClient].
