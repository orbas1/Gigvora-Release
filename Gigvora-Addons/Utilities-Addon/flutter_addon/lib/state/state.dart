import 'package:flutter/foundation.dart';

import '../models/models.dart';
import '../services/analytics_api.dart';
import '../services/chat_api.dart';
import '../services/marketplace_api.dart';
import '../services/network_api.dart';
import '../services/posts_api.dart';
import '../services/security_api.dart';
import '../services/services.dart';
import '../services/stories_api.dart';

class MyNetworkState extends ChangeNotifier {
  final NetworkApi api;
  bool loading = false;
  String? error;
  NetworkSummary? summary;
  List<NetworkConnection> connections = [];
  PaginationMeta? meta;

  MyNetworkState(this.api);

  Future<void> loadConnections({int page = 1, Map<String, dynamic>? filters}) async {
    await _guard(() async {
      final result = await api.listConnections(page: page, filters: filters);
      connections = result.data;
      summary = result.summary;
      meta = result.meta;
    });
  }

  Future<void> loadMutual(int userId) async {
    await _guard(() async {
      summary = await api.mutualConnections(userId);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class RecommendationsState extends ChangeNotifier {
  final RecommendationsApi api;
  bool loading = false;
  String? error;
  List<RecommendationItem> people = [];
  List<RecommendationItem> companies = [];
  List<RecommendationItem> groups = [];
  List<RecommendationItem> content = [];

  RecommendationsState(this.api);

  Future<void> load() async {
    await _guard(() async {
      people = (await api.recommendedPeople()).items;
      companies = (await api.recommendedCompanies()).items;
      groups = (await api.recommendedGroups()).items;
      content = (await api.recommendedContent()).items;
    });
  }

  Future<void> respond(String type, int id, String action) async {
    await _guard(() async {
      await api.respondToRecommendation(type: type, id: id, action: action);
      if (type == 'people') {
        people = people.where((p) => p.id != id).toList();
      } else if (type == 'companies') {
        companies = companies.where((p) => p.id != id).toList();
      } else if (type == 'groups') {
        groups = groups.where((p) => p.id != id).toList();
      } else {
        content = content.where((p) => p.id != id).toList();
      }
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class ProfessionalProfileState extends ChangeNotifier {
  final ProfileApi api;
  bool loading = false;
  String? error;
  ProfessionalProfile? profile;

  ProfessionalProfileState(this.api);

  Future<void> load({int? userId}) async {
    await _guard(() async {
      profile = await api.fetchProfile(userId: userId);
    });
  }

  Future<void> update(Map<String, dynamic> payload) async {
    await _guard(() async {
      profile = await api.updateProfile(payload);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class CompanyProfileState extends ChangeNotifier {
  final CompanyApi api;
  bool loading = false;
  String? error;
  CompanyProfile? company;

  CompanyProfileState(this.api);

  Future<void> loadCompany(int companyId) async {
    await _guard(() async {
      company = await api.fetchCompany(companyId);
    });
  }

  Future<void> updateCompany(int companyId, Map<String, dynamic> payload) async {
    await _guard(() async {
      company = await api.updateCompany(companyId, payload);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class EscrowState extends ChangeNotifier {
  final MarketplaceEscrowApi api;
  bool loading = false;
  String? error;
  Escrow? escrow;

  EscrowState(this.api);

  Future<void> loadByOrder(int orderId) async {
    await _guard(() async {
      escrow = await api.showByOrder(orderId);
    });
  }

  Future<void> open(int orderId, Map<String, dynamic> payload) async {
    await _guard(() async {
      escrow = await api.openEscrow(orderId, payload);
    });
  }

  Future<void> release(int escrowId, Map<String, dynamic> payload) async {
    await _guard(() async {
      escrow = await api.releaseEscrow(escrowId, payload);
    });
  }

  Future<void> refund(int escrowId, Map<String, dynamic> payload) async {
    await _guard(() async {
      escrow = await api.refundEscrow(escrowId, payload);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class DisputesState extends ChangeNotifier {
  final DisputesApi api;
  bool loading = false;
  String? error;
  Dispute? active;

  DisputesState(this.api);

  Future<void> open(int orderId, Map<String, dynamic> payload) async {
    await _guard(() async {
      active = await api.openDispute(orderId, payload);
    });
  }

  Future<void> load(int disputeId) async {
    await _guard(() async {
      active = await api.showDispute(disputeId);
    });
  }

  Future<void> reply(int disputeId, Map<String, dynamic> payload) async {
    await _guard(() async {
      active = await api.replyToDispute(disputeId, payload);
    });
  }

  Future<void> resolve(int disputeId, Map<String, dynamic> payload) async {
    await _guard(() async {
      active = await api.resolveDispute(disputeId, payload);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class StoriesState extends ChangeNotifier {
  final StoriesApi api;
  bool loading = false;
  String? error;
  List<StoryViewer> currentViewers = [];
  List<Story> stories = [];

  StoriesState(this.api);

  Future<void> loadViewers(int storyId) async {
    await _guard(() async {
      currentViewers = await api.fetchViewers(storyId);
    });
  }

  Future<void> loadStories() async {
    await _guard(() async {
      stories = await api.fetchStories();
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class StoryCreationState extends ChangeNotifier {
  final StoriesApi api;
  bool loading = false;
  String? error;
  Story? lastCreated;

  StoryCreationState(this.api);

  Future<void> createOrUpdate(Map<String, dynamic> payload) async {
    await _guard(() async {
      lastCreated = await api.createOrUpdateStory(payload);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class LiveState extends ChangeNotifier {
  final LiveApi api;
  bool loading = false;
  String? error;
  List<LiveStream> streams = [];

  LiveState(this.api);

  Future<void> load() async {
    await _guard(() async {
      streams = await api.fetchLiveStreams();
    });
  }

  Future<void> sendMessage(int streamId, Map<String, dynamic> payload) async {
    await _guard(() async {
      final updated = await api.sendLiveMessage(streamId, payload);
      streams = streams.map((s) => s.id == updated.id ? updated : s).toList();
    });
  }

  Future<void> donate(int streamId, Map<String, dynamic> payload) async {
    await _guard(() async {
      final updated = await api.sendDonation(streamId, payload);
      streams = streams.map((s) => s.id == updated.id ? updated : s).toList();
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class PostsEnhancementState extends ChangeNotifier {
  final PostsApi postsApi;
  final ReactionsApi reactionsApi;
  bool loading = false;
  String? error;
  PostPoll? poll;
  Thread? thread;
  CelebratePost? celebratePost;
  ReactionScore? score;

  PostsEnhancementState(this.postsApi, this.reactionsApi);

  Future<void> createPoll(Map<String, dynamic> payload) async {
    await _guard(() async {
      poll = await postsApi.createPoll(payload);
    });
  }

  Future<void> votePoll(int pollId, Map<String, dynamic> payload) async {
    await _guard(() async {
      poll = await postsApi.votePoll(pollId, payload);
    });
  }

  Future<void> createThread(Map<String, dynamic> payload) async {
    await _guard(() async {
      thread = await postsApi.createThread(payload);
    });
  }

  Future<void> reshare(Map<String, dynamic> payload) async {
    await _guard(() async {
      await postsApi.reshare(payload);
    });
  }

  Future<void> celebrate(Map<String, dynamic> payload) async {
    await _guard(() async {
      celebratePost = await postsApi.createCelebrate(payload);
    });
  }

  Future<void> react(Map<String, dynamic> payload) async {
    await _guard(() async {
      await reactionsApi.react(payload);
    });
  }

  Future<void> unreact(Map<String, dynamic> payload) async {
    await _guard(() async {
      await reactionsApi.unreact(payload);
    });
  }

  Future<void> dislike(Map<String, dynamic> payload) async {
    await _guard(() async {
      await reactionsApi.dislike(payload);
    });
  }

  Future<void> undislike(Map<String, dynamic> payload) async {
    await _guard(() async {
      await reactionsApi.undislike(payload);
    });
  }

  Future<void> loadReactionScore(int userId) async {
    await _guard(() async {
      score = await reactionsApi.profileScore(userId);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class ChatState extends ChangeNotifier {
  final ChatApi api;
  bool loading = false;
  String? error;
  List<ChatConversation> conversations = [];
  List<ChatConversation> requests = [];
  ChatConversation? active;
  ChatSettings? settings;

  ChatState(this.api);

  Future<void> loadConversations() async {
    await _guard(() async {
      conversations = await api.listConversations();
    });
  }

  Future<void> openConversation(int conversationId) async {
    await _guard(() async {
      active = await api.conversation(conversationId);
    });
  }

  Future<void> deleteConversation(int conversationId) async {
    await _guard(() async {
      await api.deleteConversation(conversationId);
      conversations = conversations.where((c) => c.id != conversationId).toList();
    });
  }

  Future<void> clearConversation(int conversationId) async {
    await _guard(() async {
      active = await api.clearConversation(conversationId);
    });
  }

  Future<void> updateSettings(Map<String, dynamic> payload) async {
    await _guard(() async {
      settings = await api.updateSettings(payload);
    });
  }

  Future<void> loadRequests() async {
    await _guard(() async {
      requests = await api.messageRequests();
    });
  }

  Future<void> acceptRequest(int requestId) async {
    await _guard(() async {
      final convo = await api.acceptRequest(requestId);
      conversations = [...conversations, convo];
      requests = requests.where((r) => r.id != requestId).toList();
    });
  }

  Future<void> declineRequest(int requestId) async {
    await _guard(() async {
      await api.declineRequest(requestId);
      requests = requests.where((r) => r.id != requestId).toList();
    });
  }

  Future<void> sendMessage(int conversationId, Map<String, dynamic> payload) async {
    await _guard(() async {
      active = await api.sendMessage(conversationId, payload);
      conversations = conversations
          .map((c) => c.id == conversationId ? active! : c)
          .toList();
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class AnalyticsState extends ChangeNotifier {
  final AnalyticsApi api;
  bool loading = false;
  String? error;
  List<AnalyticsMetric> metrics = [];
  AnalyticsSeries? seriesData;

  AnalyticsState(this.api);

  Future<void> loadMetrics(Map<String, dynamic> filters) async {
    await _guard(() async {
      metrics = await api.metrics(filters);
    });
  }

  Future<void> loadSeries(Map<String, dynamic> filters) async {
    await _guard(() async {
      seriesData = await api.series(filters);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class ModerationState extends ChangeNotifier {
  final SecurityApi api;
  bool loading = false;
  String? error;
  List<Map<String, dynamic>> queue = [];

  ModerationState(this.api);

  Future<void> loadQueue(Map<String, dynamic> filters) async {
    await _guard(() async {
      queue = await api.moderationQueue(filters);
    });
  }

  Future<void> moderate(Map<String, dynamic> payload) async {
    await _guard(() async {
      await api.moderate(payload);
      await loadQueue({});
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class NewsletterState extends ChangeNotifier {
  final NewsletterApi api;
  bool loading = false;
  String? error;
  NewsletterSubscription? subscription;

  NewsletterState(this.api);

  Future<void> subscribe(String email) async {
    await _guard(() async {
      subscription = await api.subscribe(email);
    });
  }

  Future<void> unsubscribe(String email) async {
    await _guard(() async {
      subscription = await api.unsubscribe(email);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class SecurityAndVerificationState extends ChangeNotifier {
  final SecurityApi securityApi;
  final AgeVerificationApi ageApi;
  bool loading = false;
  String? error;
  List<SecurityEvent> events = [];
  AgeVerificationStatus? ageStatus;

  SecurityAndVerificationState(this.securityApi, this.ageApi);

  Future<void> loadEvents(Map<String, dynamic> filters) async {
    await _guard(() async {
      events = await securityApi.events(filters);
    });
  }

  Future<void> loadAgeStatus() async {
    await _guard(() async {
      ageStatus = await ageApi.status();
    });
  }

  Future<void> startVerification(Map<String, dynamic> payload) async {
    await _guard(() async {
      ageStatus = await ageApi.start(payload);
    });
  }

  Future<void> _guard(Future<void> Function() cb) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await cb();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
