import '../models/analytics_models.dart';
import '../services/analytics_api.dart';

class AnalyticsClient {
  final AnalyticsApi api;

  AnalyticsClient(this.api);

  Future<void> trackScreen(String name, Map<String, dynamic> props) async {
    await api.metrics({'event': 'screen_view', 'name': name, 'props': props});
  }

  Future<void> trackAction(String event, Map<String, dynamic> props) async {
    await api.metrics({'event': event, 'props': props});
  }

  Future<AnalyticsSeries> fetchSeries(Map<String, dynamic> filters) {
    return api.series(filters);
  }
}
