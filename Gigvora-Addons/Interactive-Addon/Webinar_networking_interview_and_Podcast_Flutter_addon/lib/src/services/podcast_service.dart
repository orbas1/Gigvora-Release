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
}
