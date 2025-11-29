import '../models/models.dart';
import 'api_client.dart';

class PostsApi extends BaseApiService {
  PostsApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<PostPoll> createPoll(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/posts/polls', data: payload);
    return PostPoll.fromJson(data as Map<String, dynamic>);
  }

  Future<PostPoll> votePoll(int pollId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/posts/polls/$pollId/vote', data: payload);
    return PostPoll.fromJson(data as Map<String, dynamic>);
  }

  Future<Thread> createThread(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/posts/threads', data: payload);
    return Thread.fromJson(data as Map<String, dynamic>);
  }

  Future<Reshare> reshare(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/posts/reshare', data: payload);
    return Reshare.fromJson(data as Map<String, dynamic>);
  }

  Future<CelebratePost> createCelebrate(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/posts/celebrate', data: payload);
    return CelebratePost.fromJson(data as Map<String, dynamic>);
  }
}

class ReactionsApi extends BaseApiService {
  ReactionsApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<Reaction> react(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/reactions', data: payload) as Map<String, dynamic>;
    return Reaction.fromJson((data['reaction'] as Map<String, dynamic>?) ?? data);
  }

  Future<void> unreact(Map<String, dynamic> payload) async {
    await delete('/api/pro-network/reactions', data: payload);
  }

  Future<Reaction> dislike(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/reactions/dislike', data: payload) as Map<String, dynamic>;
    return Reaction.fromJson((data['reaction'] as Map<String, dynamic>?) ?? data);
  }

  Future<void> undislike(Map<String, dynamic> payload) async {
    await delete('/api/pro-network/reactions/dislike', data: payload);
  }

  Future<ReactionScore> profileScore(int userId) async {
    final data = await get('/api/pro-network/profiles/$userId/reaction-score');
    return ReactionScore.fromJson(data as Map<String, dynamic>);
  }
}

class HashtagsApi extends BaseApiService {
  HashtagsApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<List<String>> trending() async {
    final data = await get('/api/pro-network/hashtags');
    return List<String>.from(data as List? ?? []);
  }

  Future<List<String>> search(String keyword) async {
    final data = await post('/api/pro-network/hashtags/search', data: {'query': keyword});
    return List<String>.from(data as List? ?? []);
  }
}

class MusicLibraryApi extends BaseApiService {
  MusicLibraryApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<List<MusicTrack>> tracks() async {
    final data = await get('/api/pro-network/music-library');
    return (data as List)
        .map((e) => MusicTrack.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<List<MusicTrack>> searchTracks(String query) async {
    final data = await post('/api/pro-network/music-library/search', data: {'query': query});
    return (data as List)
        .map((e) => MusicTrack.fromJson(e as Map<String, dynamic>))
        .toList();
  }
}
