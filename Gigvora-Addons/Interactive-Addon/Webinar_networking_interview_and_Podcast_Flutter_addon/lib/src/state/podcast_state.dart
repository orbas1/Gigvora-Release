import 'package:flutter/foundation.dart';

import '../models/podcast_episode.dart';
import '../models/podcast_series.dart';
import '../services/podcast_service.dart';
import 'live_states.dart';

class PodcastState extends ChangeNotifier {
  PodcastState(this.service);

  final PodcastService service;

  final ViewState<List<PodcastSeries>> series = ViewState(data: []);
  final ViewState<PodcastSeries> selected = ViewState();
  PodcastEpisode? nowPlaying;

  Future<void> loadSeries() async {
    try {
      series.setLoading();
      final response = await service.fetchSeries();
      if (response.data.isEmpty) {
        series.setEmpty();
      } else {
        series.setData(response.data);
      }
    } catch (error) {
      series.setError(error.toString());
    }
  }

  Future<void> selectSeries(int id) async {
    try {
      selected.setLoading();
      final detail = await service.fetchSeriesDetail(id);
      selected.setData(detail);
    } catch (error) {
      selected.setError(error.toString());
    }
  }

  void setNowPlaying(PodcastEpisode episode) {
    nowPlaying = episode;
    notifyListeners();
  }
}
