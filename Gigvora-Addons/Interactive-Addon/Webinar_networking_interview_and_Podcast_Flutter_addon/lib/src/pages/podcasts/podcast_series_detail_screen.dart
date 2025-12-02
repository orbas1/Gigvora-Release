import 'package:flutter/material.dart';

import '../../models/podcast_episode.dart';
import '../../services/podcast_service.dart';
import '../../state/live_states.dart';
import '../../state/podcast_state.dart';
import '../../theme/live_mobile_theme.dart';

class PodcastSeriesDetailScreen extends StatefulWidget {
  const PodcastSeriesDetailScreen({super.key, required this.service, required this.seriesId});

  final PodcastService service;
  final int seriesId;

  @override
  State<PodcastSeriesDetailScreen> createState() => _PodcastSeriesDetailScreenState();
}

class _PodcastSeriesDetailScreenState extends State<PodcastSeriesDetailScreen> {
  late final PodcastState _state;
  bool _updatingFollow = false;

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
    final status = _state.selected.status;

    Widget body;
    switch (status) {
      case LoadStatus.loading:
        body = const Center(child: CircularProgressIndicator());
        break;
      case LoadStatus.error:
        body = Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Could not load this series'),
              const SizedBox(height: 8),
              ElevatedButton(onPressed: () => _state.selectSeries(widget.seriesId), child: const Text('Retry')),
            ],
          ),
        );
        break;
      default:
        if (series == null) {
          body = const SizedBox.shrink();
          break;
        }

        final followerLabel = '${series.followersCount} ${series.followersCount == 1 ? 'follower' : 'followers'}';
        body = SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Row(
              children: [
                Container(
                  width: 100,
                  height: 100,
                  decoration: BoxDecoration(
                    color: LiveMobileTheme.surfaceVariant(context),
                    borderRadius: BorderRadius.circular(LiveMobileTheme.cardRadius),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                    Text(series.title, style: Theme.of(context).textTheme.headlineSmall),
                    const SizedBox(height: 4),
                    Text(series.description ?? ''),
                    const SizedBox(height: 8),
                    Wrap(
                      spacing: 8,
                      crossAxisAlignment: WrapCrossAlignment.center,
                      children: [
                        Chip(label: Text(followerLabel)),
                        OutlinedButton(
                          onPressed: _updatingFollow
                              ? null
                              : () async {
                                  setState(() => _updatingFollow = true);
                                  await widget.service.toggleFollow(series.id, follow: !(series.isFollowed ?? false));
                                  await _state.selectSeries(widget.seriesId);
                                  setState(() => _updatingFollow = false);
                                },
                          child: Text((series.isFollowed ?? false) ? 'Following' : 'Follow'),
                        ),
                      ],
                    ),
                  ]),
                )
              ],
            ),
            const SizedBox(height: 16),
            Text('Episodes', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            ...series.episodes.map((episode) => _episodeTile(context, series.id, episode)).toList(),
          ]),
        );
    }

    return Scaffold(appBar: AppBar(title: Text(series?.title ?? 'Series')), body: body);
  }

  Widget _episodeTile(BuildContext context, int seriesId, PodcastEpisode episode) {
    return Card(
      child: ListTile(
        title: Text(episode.title ?? 'Episode'),
        subtitle: Text('${episode.publishedAt ?? ''} • ${episode.duration ?? ''}'),
        trailing: const Icon(Icons.play_arrow),
        onTap: () => Navigator.pushNamed(
          context,
          '/live/podcasts/episode/${episode.id}',
          arguments: {'episodeId': episode.id, 'seriesId': seriesId, 'episode': episode},
        ),
      ),
    );
  }
}
