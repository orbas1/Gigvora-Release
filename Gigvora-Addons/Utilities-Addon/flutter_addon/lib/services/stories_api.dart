import '../models/models.dart';
import 'api_client.dart';

class StoriesApi extends BaseApiService {
  StoriesApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<Story> createOrUpdateStory(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/stories', data: payload);
    return Story.fromJson(data as Map<String, dynamic>);
  }

  Future<List<StoryViewer>> fetchViewers(int storyId) async {
    final data = await get('/api/pro-network/stories/$storyId/viewers');
    return (data as List)
        .map((e) => StoryViewer.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<List<Story>> fetchStories() async {
    try {
      final data = await get('/api/pro-network/stories');
      return (data as List)
          .map((e) => Story.fromJson(e as Map<String, dynamic>))
          .toList();
    } on ApiException {
      return [];
    }
  }
}

class LiveApi extends BaseApiService {
  LiveApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<List<LiveStream>> fetchLiveStreams() async {
    final data = await get('/api/pro-network/live');
    return (data as List)
        .map((e) => LiveStream.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<LiveStream> sendLiveMessage(int streamId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/live/$streamId/chat', data: payload);
    return LiveStream.fromJson(data as Map<String, dynamic>);
  }

  Future<LiveStream> sendDonation(int streamId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/live/$streamId/donations', data: payload);
    return LiveStream.fromJson(data as Map<String, dynamic>);
  }
}
