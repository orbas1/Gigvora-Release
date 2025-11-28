import 'package:flutter/material.dart';

import '../../models/podcast_episode.dart';
import '../../services/podcast_service.dart';
import '../../state/podcast_state.dart';

class PodcastSeriesDetailScreen extends StatefulWidget {
  const PodcastSeriesDetailScreen({super.key, required this.service, required this.seriesId});

  final PodcastService service;
  final int seriesId;

  @override
  State<PodcastSeriesDetailScreen> createState() => _PodcastSeriesDetailScreenState();
}

class _PodcastSeriesDetailScreenState extends State<PodcastSeriesDetailScreen> {
  late final PodcastState _state;

  @override
  void initState() {
    super.initState();
    _state = PodcastState(widget.service)..addListener(_onState);
    _state.selectSeries(widget.seriesId);
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final series = _state.selected.data;
    return Scaffold(
      appBar: AppBar(title: Text(series?.title ?? 'Series')),
      body: series == null
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                Row(
                  children: [
                    Container(width: 100, height: 100, color: Colors.grey.shade300),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                        Text(series.title, style: Theme.of(context).textTheme.headlineSmall),
                        const SizedBox(height: 4),
                        Text(series.description ?? ''),
                        const SizedBox(height: 8),
                        OutlinedButton(onPressed: () {}, child: const Text('Follow')),
                      ]),
                    )
                  ],
                ),
                const SizedBox(height: 16),
                Text('Episodes', style: Theme.of(context).textTheme.titleMedium),
                const SizedBox(height: 8),
                ...series.episodes.map((episode) => _episodeTile(context, episode)).toList(),
              ]),
            ),
    );
  }

  Widget _episodeTile(BuildContext context, PodcastEpisode episode) {
    return Card(
      child: ListTile(
        title: Text(episode.title ?? 'Episode'),
        subtitle: Text('${episode.publishedAt ?? ''} • ${episode.duration ?? ''}'),
        trailing: const Icon(Icons.play_arrow),
        onTap: () => Navigator.pushNamed(context, '/live/podcasts/episode/${episode.id}', arguments: episode),
      ),
    );
  }
}
