import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../models/models.dart';
import '../state/state.dart';
import 'common.dart';

class MyNetworkScreen extends StatefulWidget {
  final MyNetworkState? myNetworkState;
  final RecommendationsState? recommendationsState;
  final AnalyticsClient analytics;

  const MyNetworkScreen({
    super.key,
    required this.analytics,
    this.myNetworkState,
    this.recommendationsState,
  });

  @override
  State<MyNetworkScreen> createState() => _MyNetworkScreenState();
}

class _MyNetworkScreenState extends State<MyNetworkScreen> {
  MyNetworkState get networkState => widget.myNetworkState ?? context.read<MyNetworkState>();
  RecommendationsState get recommendationsState =>
      widget.recommendationsState ?? context.read<RecommendationsState>();
  int _degreeFilter = 0;

  String _degreeLabel(int degree) {
    if (degree == 1) return '1st degree';
    if (degree == 2) return '2nd degree';
    return '3rd degree';
  }

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('MyNetwork', {});
    _load();
  }

  Future<void> _load() async {
    await Future.wait([
      networkState.loadConnections(filters: _degreeFilter == 0 ? null : {'degree': _degreeFilter}),
      recommendationsState.load(),
    ]);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Network')),
      body: RefreshIndicator(
        onRefresh: _load,
        child: Consumer2<MyNetworkState, RecommendationsState>(
          builder: (context, network, recs, _) {
            if (network.loading && network.summary == null) {
              return const LoadingView(message: 'Loading your network...');
            }
            if (network.error != null) {
              return ErrorView(message: network.error!, onRetry: _load);
            }
            return ListView(
              padding: const EdgeInsets.all(16),
              children: [
                _SummaryCards(summary: network.summary),
                const SizedBox(height: 16),
                SectionHeader(
                  title: 'Connections',
                  actions: [
                    TextButton(
                      onPressed: () => Navigator.of(context).pushNamed('/connections'),
                      child: const Text('View all'),
                    ),
                  ],
                ),
                if (network.connections.isEmpty)
                  const EmptyView(message: 'No connections yet')
                else
                  ...network.connections.map(
                    (c) => ListTile(
                      leading: const CircleAvatar(child: Icon(Icons.person)),
                      title: Text(c.name ?? 'User ${c.userId}'),
                      subtitle: Text('${c.title ?? ''} • ${_degreeLabel(c.degree)}'),
                    ),
                  ),
                const SizedBox(height: 16),
                SectionHeader(title: 'Recommendations'),
                if (recs.loading && recs.people.isEmpty)
                  const LoadingView(message: 'Fetching recommendations...')
                else if (recs.error != null)
                  ErrorView(message: recs.error!, onRetry: recs.load)
                else ...[
                  _RecommendationList(
                    title: 'People',
                    items: recs.people,
                    state: recommendationsState,
                    analytics: widget.analytics,
                  ),
                  _RecommendationList(
                    title: 'Companies',
                    items: recs.companies,
                    state: recommendationsState,
                    analytics: widget.analytics,
                  ),
                  _RecommendationList(
                    title: 'Groups',
                    items: recs.groups,
                    state: recommendationsState,
                    analytics: widget.analytics,
                  ),
                  _RecommendationList(
                    title: 'Content',
                    items: recs.content,
                    state: recommendationsState,
                    analytics: widget.analytics,
                  ),
                ],
              ],
            );
          },
        ),
      ),
    );
  }
}

class ConnectionsListScreen extends StatefulWidget {
  final MyNetworkState? state;
  final AnalyticsClient analytics;

  const ConnectionsListScreen({super.key, required this.analytics, this.state});

  @override
  State<ConnectionsListScreen> createState() => _ConnectionsListScreenState();
}

class _ConnectionsListScreenState extends State<ConnectionsListScreen> {
  MyNetworkState get state => widget.state ?? context.read<MyNetworkState>();
  int _degree = 0;

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ConnectionsList', {});
    _load();
  }

  Future<void> _load() async {
    await state.loadConnections(filters: _degree == 0 ? null : {'degree': _degree});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Connections')),
      body: Consumer<MyNetworkState>(
        builder: (context, s, _) {
          if (s.loading && s.connections.isEmpty) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: state.loadConnections);
          }
          if (s.connections.isEmpty) {
            return const EmptyView(message: 'No connections found');
          }
          return Column(
            children: [
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                child: Wrap(
                  spacing: 8,
                  children: [
                    FilterChip(
                      label: const Text('All'),
                      selected: _degree == 0,
                      onSelected: (_) => setState(() {
                        _degree = 0;
                        _load();
                      }),
                    ),
                    ...[1, 2, 3].map(
                      (d) => FilterChip(
                        label: Text(_degreeLabel(d)),
                        selected: _degree == d,
                        onSelected: (_) => setState(() {
                          _degree = d;
                          _load();
                        }),
                      ),
                    ),
                  ],
                ),
              ),
              Expanded(
                child: ListView.builder(
                  itemCount: s.connections.length,
                  itemBuilder: (context, index) {
                    final connection = s.connections[index];
                    return ListTile(
                      leading: const CircleAvatar(child: Icon(Icons.person_outline)),
                      title: Text(connection.name ?? 'User ${connection.userId}'),
                      subtitle: Text(connection.title ?? ''),
                      trailing: Text('${connection.degree}°'),
                    );
                  },
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}

class MutualConnectionsScreen extends StatefulWidget {
  final int userId;
  final MyNetworkState? state;
  final AnalyticsClient analytics;

  const MutualConnectionsScreen({
    super.key,
    required this.userId,
    required this.analytics,
    this.state,
  });

  @override
  State<MutualConnectionsScreen> createState() => _MutualConnectionsScreenState();
}

class _MutualConnectionsScreenState extends State<MutualConnectionsScreen> {
  MyNetworkState get state => widget.state ?? context.read<MyNetworkState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('MutualConnections', {'userId': widget.userId});
    state.loadMutual(widget.userId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mutual Connections')),
      body: Consumer<MyNetworkState>(
        builder: (context, s, _) {
          if (s.loading && s.connections.isEmpty) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(
              message: s.error!,
              onRetry: () => state.loadMutual(widget.userId),
            );
          }
          if (s.connections.isEmpty) {
            return const EmptyView(message: 'No mutual connections');
          }
          return ListView(
            children: s.connections
                .map(
                  (c) => ListTile(
                    leading: const CircleAvatar(child: Icon(Icons.person)),
                    title: Text(c.name),
                    subtitle: Text(c.title ?? ''),
                  ),
                )
                .toList(),
          );
        },
      ),
    );
  }
}

class _SummaryCards extends StatelessWidget {
  final NetworkSummary? summary;

  const _SummaryCards({this.summary});

  @override
  Widget build(BuildContext context) {
    if (summary == null) {
      return const LoadingView(message: 'Loading network stats...');
    }
    return Wrap(
      spacing: 12,
      runSpacing: 12,
      children: [
        _StatCard(label: '1st Degree', value: summary?.firstDegree ?? 0),
        _StatCard(label: '2nd Degree', value: summary?.secondDegree ?? 0),
        _StatCard(label: '3rd Degree', value: summary?.thirdDegree ?? 0),
      ],
    );
  }
}

class _StatCard extends StatelessWidget {
  final String label;
  final int value;

  const _StatCard({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(value.toString(), style: Theme.of(context).textTheme.headlineMedium),
          ],
        ),
      ),
    );
  }
}

class _RecommendationList extends StatelessWidget {
  final String title;
  final List<RecommendationItem> items;
  final RecommendationsState state;
  final AnalyticsClient analytics;

  const _RecommendationList({
    required this.title,
    required this.items,
    required this.state,
    required this.analytics,
  });

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) {
      return Padding(
        padding: const EdgeInsets.symmetric(vertical: 8),
        child: Text('No $title available'),
      );
    }
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(vertical: 8),
          child: Text(title, style: Theme.of(context).textTheme.titleSmall),
        ),
        ...items.map(
          (item) => ListTile(
            leading: const CircleAvatar(child: Icon(Icons.insights)),
            title: Text(item.title),
            subtitle: Text(item.subtitle ?? item.type),
            trailing: Wrap(
              spacing: 6,
              children: [
                IconButton(
                  icon: const Icon(Icons.check_circle_outline),
                  onPressed: () {
                    if (item.id != null) {
                      state.respond(item.type, item.id!, 'accept');
                      analytics.trackAction('recommendation_accept', {
                        'type': item.type,
                        'id': item.id,
                      });
                    }
                  },
                ),
                IconButton(
                  icon: const Icon(Icons.close),
                  onPressed: () {
                    if (item.id != null) {
                      state.respond(item.type, item.id!, 'dismiss');
                      analytics.trackAction('recommendation_dismiss', {
                        'type': item.type,
                        'id': item.id,
                      });
                    }
                  },
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
