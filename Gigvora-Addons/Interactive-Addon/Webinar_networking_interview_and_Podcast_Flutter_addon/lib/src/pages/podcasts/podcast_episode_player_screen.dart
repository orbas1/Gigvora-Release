import 'dart:async';

import 'package:flutter/material.dart';

import '../../models/podcast_episode.dart';
import '../../services/podcast_service.dart';
import '../../state/live_states.dart';
import '../../state/podcast_state.dart';

class PodcastEpisodePlayerScreen extends StatefulWidget {
  const PodcastEpisodePlayerScreen({
    super.key,
    required this.service,
    this.episode,
    this.episodeId,
    this.seriesId,
  });

  final PodcastService service;
  final PodcastEpisode? episode;
  final int? episodeId;
  final int? seriesId;

  @override
  State<PodcastEpisodePlayerScreen> createState() => _PodcastEpisodePlayerScreenState();
}

class _PodcastEpisodePlayerScreenState extends State<PodcastEpisodePlayerScreen> {
  late final PodcastState _state;
  Timer? _timer;
  double progress = 0;
  bool playing = false;
  Duration _duration = const Duration(minutes: 30);

  PodcastEpisode? get _episode => widget.episode ?? _state.episode.data ?? _state.nowPlaying;

  @override
  void initState() {
    super.initState();
    _state = PodcastState(widget.service)..addListener(_onState);

    if (widget.episode != null) {
      _state.setNowPlaying(widget.episode!);
      _duration = Duration(seconds: widget.episode!.duration ?? 1800);
    } else if (widget.seriesId != null && widget.episodeId != null) {
      _state.loadEpisode(widget.seriesId!, widget.episodeId!);
    }

    _timer = Timer.periodic(const Duration(seconds: 1), (_) {
      if (!playing || _duration.inSeconds == 0) return;
      setState(() {
        progress = (progress + (1 / _duration.inSeconds)).clamp(0, 1);
      });
      if (progress >= 1) {
        playing = false;
        _recordPlayback(completed: true);
      }
    });
  }

  void _onState() {
    final ep = _episode;
    if (ep != null && ep.duration != null) {
      _duration = Duration(seconds: ep.duration!);
    }
    setState(() {});
  }

  @override
  void dispose() {
    _timer?.cancel();
    _state.removeListener(_onState);
    _recordPlayback();
    super.dispose();
  }

  Future<void> _recordPlayback({bool completed = false}) async {
    final ep = _episode;
    if (ep == null) return;
    final seconds = (progress * _duration.inSeconds).round();
    await widget.service
        .recordPlayback(ep.podcastSeriesId, ep.id, progressSeconds: seconds, completed: completed)
        .catchError((_) => null);
  }

  @override
  Widget build(BuildContext context) {
    final ep = _episode;
    final status = _state.episode.status;

    Widget body;
    if (ep == null && status == LoadStatus.loading) {
      body = const Center(child: CircularProgressIndicator());
    } else if (status == LoadStatus.error) {
      body = Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text('Unable to load episode'),
            const SizedBox(height: 8),
            ElevatedButton(
              onPressed: () {
                if (widget.seriesId != null && widget.episodeId != null) {
                  _state.loadEpisode(widget.seriesId!, widget.episodeId!);
                }
              },
              child: const Text('Retry'),
            ),
          ],
        ),
      );
    } else if (ep == null) {
      body = const Center(child: Text('Episode unavailable'));
    } else {
      final position = Duration(seconds: (progress * _duration.inSeconds).round());
      final label = '${position.inMinutes.remainder(60).toString().padLeft(2, '0')}:${(position.inSeconds % 60).toString().padLeft(2, '0')}';

      body = Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(ep.title, style: Theme.of(context).textTheme.headlineSmall),
            const SizedBox(height: 12),
            Row(
              children: [
                ElevatedButton(
                  onPressed: () {
                    setState(() => playing = !playing);
                    _recordPlayback();
                  },
                  child: Text(playing ? 'Pause' : 'Play'),
                ),
                Expanded(
                  child: Slider(
                    value: progress,
                    onChanged: (value) => setState(() => progress = value),
                  ),
                ),
                Text(label),
              ],
            ),
            DropdownButton<double>(
              value: 1,
              onChanged: (_) {},
              items: const [
                DropdownMenuItem(value: 1, child: Text('1x')),
                DropdownMenuItem(value: 1.25, child: Text('1.25x')),
                DropdownMenuItem(value: 1.5, child: Text('1.5x')),
              ],
            ),
            const SizedBox(height: 12),
            Text(ep.description ?? ''),
            const SizedBox(height: 12),
            const Text('Show notes'),
            Text(ep.metadata?['show_notes']?.toString() ?? 'No notes provided'),
          ],
        ),
      );
    }

    return Scaffold(appBar: AppBar(title: Text(ep?.title ?? 'Episode')), body: body);
  }
}
