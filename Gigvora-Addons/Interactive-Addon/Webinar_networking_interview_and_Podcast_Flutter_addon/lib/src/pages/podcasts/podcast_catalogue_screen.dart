import 'package:flutter/material.dart';

import '../../services/podcast_service.dart';
import '../../state/live_states.dart';
import '../../state/podcast_state.dart';
import '../../widgets/live_cards.dart';

class PodcastCatalogueScreen extends StatefulWidget {
  const PodcastCatalogueScreen({super.key, required this.service});

  final PodcastService service;

  @override
  State<PodcastCatalogueScreen> createState() => _PodcastCatalogueScreenState();
}

class _PodcastCatalogueScreenState extends State<PodcastCatalogueScreen> {
  late final PodcastState _state;

  @override
  void initState() {
    super.initState();
    _state = PodcastState(widget.service)..addListener(_onState);
    _state.loadSeries();
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final series = _state.series.data ?? [];
    final status = _state.series.status;

    Widget content;
    switch (status) {
      case LoadStatus.loading:
        content = ListView(children: const [SizedBox(height: 240, child: Center(child: CircularProgressIndicator()))]);
        break;
      case LoadStatus.error:
        content = ListView(
          children: [
            SizedBox(
              height: 260,
              child: Center(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text('Unable to load podcasts'),
                    const SizedBox(height: 8),
                    ElevatedButton(onPressed: _state.loadSeries, child: const Text('Retry')),
                  ],
                ),
              ),
            ),
          ],
        );
        break;
      case LoadStatus.empty:
        content = ListView(children: const [SizedBox(height: 240, child: Center(child: Text('No podcasts available yet.')))]);
        break;
      default:
        content = ListView.builder(
          itemCount: series.length,
          itemBuilder: (context, index) {
            final item = series[index];
            final followerLabel = '${item.followersCount} ${item.followersCount == 1 ? 'follower' : 'followers'}';
            return LiveEventCard(
              title: item.title,
              subtitle: item.description ?? '',
              meta: followerLabel,
              onTap: () => Navigator.pushNamed(context, '/live/podcasts/series/${item.id}', arguments: item.id),
            );
          },
        );
    }

    return Scaffold(
      appBar: AppBar(title: const Text('Podcasts')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: TextField(
              decoration: const InputDecoration(hintText: 'Search podcasts'),
              onSubmitted: (_) => _state.loadSeries(),
            ),
          ),
          Expanded(child: RefreshIndicator(onRefresh: _state.loadSeries, child: content)),
        ],
      ),
    );
  }
}
