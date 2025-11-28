import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../state/state.dart';
import 'common.dart';

class AnalyticsOverviewScreen extends StatefulWidget {
  final AnalyticsState? state;
  final AnalyticsClient analytics;

  const AnalyticsOverviewScreen({super.key, required this.analytics, this.state});

  @override
  State<AnalyticsOverviewScreen> createState() => _AnalyticsOverviewScreenState();
}

class _AnalyticsOverviewScreenState extends State<AnalyticsOverviewScreen> {
  AnalyticsState get state => widget.state ?? context.read<AnalyticsState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('AnalyticsOverview', {});
    _load();
  }

  Future<void> _load() async {
    await Future.wait([
      state.loadMetrics({}),
      state.loadSeries({}),
    ]);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Analytics')),
      body: RefreshIndicator(
        onRefresh: _load,
        child: Consumer<AnalyticsState>(
          builder: (context, s, _) {
            if (s.loading && s.metrics.isEmpty) return const LoadingView();
            if (s.error != null) return ErrorView(message: s.error!, onRetry: _load);
            return ListView(
              padding: const EdgeInsets.all(16),
              children: [
                SectionHeader(title: 'Summary Metrics'),
                if (s.metrics.isEmpty)
                  const EmptyView(message: 'No analytics available')
                else
                  Wrap(
                    spacing: 12,
                    runSpacing: 12,
                    children: s.metrics
                        .map((m) => Card(
                              child: Padding(
                                padding: const EdgeInsets.all(12),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(m.name, style: Theme.of(context).textTheme.titleMedium),
                                    const SizedBox(height: 6),
                                    Text(m.value.toString(), style: Theme.of(context).textTheme.headlineSmall),
                                  ],
                                ),
                              ),
                            ))
                        .toList(),
                  ),
                const SizedBox(height: 16),
                SectionHeader(title: 'Trends'),
                if (s.seriesData == null)
                  const EmptyView(message: 'No trends to display')
                else
                  SizedBox(
                    height: 240,
                    child: LineChart(
                      LineChartData(
                        titlesData: FlTitlesData(show: true),
                        lineBarsData: [
                          LineChartBarData(
                            spots: s.seriesData!.points
                                .map((p) => FlSpot(p.x.toDouble(), p.y.toDouble()))
                                .toList(),
                            isCurved: true,
                            color: Theme.of(context).colorScheme.primary,
                          ),
                        ],
                      ),
                    ),
                  ),
              ],
            );
          },
        ),
      ),
    );
  }
}

class EntityAnalyticsScreen extends StatelessWidget {
  final AnalyticsState? state;
  final AnalyticsClient analytics;
  final Map<String, dynamic> filters;

  const EntityAnalyticsScreen({
    super.key,
    required this.analytics,
    this.state,
    this.filters = const {},
  });

  @override
  Widget build(BuildContext context) {
    final analyticsState = state ?? context.read<AnalyticsState>();
    analytics.trackScreen('EntityAnalytics', filters);
    return Scaffold(
      appBar: AppBar(title: const Text('Entity Analytics')),
      body: FutureBuilder(
        future: analyticsState.loadMetrics(filters),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const LoadingView();
          }
          if (analyticsState.error != null) {
            return ErrorView(message: analyticsState.error!, onRetry: () => analyticsState.loadMetrics(filters));
          }
          return ListView(
            padding: const EdgeInsets.all(16),
            children: analyticsState.metrics
                .map((m) => ListTile(
                      title: Text(m.name),
                      trailing: Text(m.value.toString()),
                    ))
                .toList(),
          );
        },
      ),
    );
  }
}
