import 'analytics_client.dart';

class TalentAiAnalyticsClient {
  final AnalyticsClient api;

  TalentAiAnalyticsClient(this.api);

  Future<void> trackScreen(String name, Map<String, dynamic> props) =>
      api.trackScreen(name, props);

  Future<void> trackAction(String event, Map<String, dynamic> props) =>
      api.trackAction(event, props);
}
