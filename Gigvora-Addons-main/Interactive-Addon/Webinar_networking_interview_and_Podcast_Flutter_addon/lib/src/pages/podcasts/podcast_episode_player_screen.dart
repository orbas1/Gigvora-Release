import 'dart:async';

import 'package:flutter/material.dart';

import '../../models/podcast_episode.dart';

class PodcastEpisodePlayerScreen extends StatefulWidget {
  const PodcastEpisodePlayerScreen({super.key, required this.episode});

  final PodcastEpisode episode;

  @override
  State<PodcastEpisodePlayerScreen> createState() => _PodcastEpisodePlayerScreenState();
}

class _PodcastEpisodePlayerScreenState extends State<PodcastEpisodePlayerScreen> {
  late Timer _timer;
  double progress = 0;
  bool playing = false;

  @override
  void initState() {
    super.initState();
    _timer = Timer.periodic(const Duration(seconds: 1), (_) {
      if (!playing) return;
      setState(() {
        progress = (progress + 0.01).clamp(0, 1);
      });
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final position = Duration(seconds: (progress * 1800).toInt());
    final label = '${position.inMinutes.remainder(60).toString().padLeft(2, '0')}:${(position.inSeconds % 60).toString().padLeft(2, '0')}';
    return Scaffold(
      appBar: AppBar(title: Text(widget.episode.title ?? 'Episode')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(widget.episode.title ?? '', style: Theme.of(context).textTheme.headlineSmall),
            const SizedBox(height: 12),
            Row(
              children: [
                ElevatedButton(
                  onPressed: () => setState(() => playing = !playing),
                  child: Text(playing ? 'Pause' : 'Play'),
                ),
                Expanded(
                  child: Slider(value: progress, onChanged: (value) => setState(() => progress = value)),
                ),
                Text(label),
              ],
            ),
            DropdownButton<double>(
              value: 1,
              onChanged: (_) {},
              items: const [DropdownMenuItem(value: 1, child: Text('1x')), DropdownMenuItem(value: 1.5, child: Text('1.5x'))],
            ),
            const SizedBox(height: 12),
            Text(widget.episode.description ?? ''),
            const SizedBox(height: 12),
            const Text('Show notes'),
            Text(widget.episode.showNotes ?? 'No notes provided'),
          ],
        ),
      ),
    );
  }
}
