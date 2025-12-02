import '../api/wnip_api_client.dart';
import '../models/pagination.dart';
import '../models/podcast_episode.dart';
import '../models/podcast_series.dart';

class PodcastService {
  final WnipApiClient apiClient;

  PodcastService(this.apiClient);

  Future<PaginatedResponse<PodcastSeries>> fetchSeries({int page = 1}) {
    return apiClient.fetchPodcastSeries(page: page);
  }

  Future<PodcastSeries> fetchSeriesDetail(int id) {
    return apiClient.fetchPodcastSeriesDetails(id);
  }

  Future<PodcastEpisode> createEpisode(int seriesId, PodcastEpisodePayload payload) {
    return apiClient.createPodcastEpisode(seriesId, payload);
  }

  Future<PodcastEpisode> fetchEpisodeDetail(int seriesId, int episodeId) {
    return apiClient.fetchPodcastEpisodeDetails(seriesId, episodeId);
  }

  Future<void> toggleFollow(int seriesId, {bool follow = true}) {
    return apiClient.togglePodcastSeriesFollow(seriesId, follow: follow);
  }

  Future<void> recordPlayback(int seriesId, int episodeId, {int? progressSeconds, bool completed = false}) {
    return apiClient.recordPodcastPlayback(
      seriesId,
      episodeId,
      progressSeconds: progressSeconds,
      completed: completed,
    );
  }
}
