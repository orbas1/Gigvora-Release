import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/podcast_episode.dart';
import '../../models/podcast_series.dart';

class PodcastSeriesPage extends StatefulWidget {
  final WnipApiClient apiClient;
  final int seriesId;

  const PodcastSeriesPage({super.key, required this.apiClient, required this.seriesId});

  @override
  State<PodcastSeriesPage> createState() => _PodcastSeriesPageState();
}

class _PodcastSeriesPageState extends State<PodcastSeriesPage> {
  late Future<PodcastSeries> _future;
  String? error;
  bool working = false;

  @override
  void initState() {
    super.initState();
    _future = widget.apiClient.fetchPodcastSeriesDetails(widget.seriesId);
  }

  Future<void> _publishEpisode(PodcastEpisode episode) async {
    setState(() {
      working = true;
      error = null;
    });
    try {
      await widget.apiClient.publishPodcastEpisode(widget.seriesId, episode.id);
      _future = widget.apiClient.fetchPodcastSeriesDetails(widget.seriesId);
    } catch (e) {
      error = e.toString();
    } finally {
      setState(() {
        working = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Podcast Series')),
      body: FutureBuilder<PodcastSeries>(
        future: _future,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }
          final series = snapshot.data;
          if (series == null) return const SizedBox.shrink();
          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(series.title, style: Theme.of(context).textTheme.headlineSmall),
                const SizedBox(height: 8),
                Text(series.description ?? 'No description'),
                const SizedBox(height: 8),
                Text(series.isPublic ? 'Public' : 'Private'),
                if (series.coverArtPath != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 8),
                    child: Text('Cover art: ' + series.coverArtPath!),
                  ),
                const SizedBox(height: 12),
                const Text('Episodes', style: TextStyle(fontWeight: FontWeight.bold)),
                ...series.episodes.map((episode) => Card(
                      child: ListTile(
                        title: Text(episode.title),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            if (episode.description != null)
                              Text(episode.description!),
                            Text('Status: ' + (episode.publishedAt != null ? 'Published' : 'Draft')),
                            if (episode.scheduledFor != null)
                              Text('Scheduled for: ' + episode.scheduledFor!.toLocal().toString()),
                          ],
                        ),
                        trailing: episode.publishedAt == null
                            ? IconButton(
                                icon: const Icon(Icons.publish),
                                onPressed: working ? null : () => _publishEpisode(episode),
                              )
                            : const Icon(Icons.check, color: Colors.green),
                      ),
                    )),
                if (error != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Text(error!, style: const TextStyle(color: Colors.red)),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}
