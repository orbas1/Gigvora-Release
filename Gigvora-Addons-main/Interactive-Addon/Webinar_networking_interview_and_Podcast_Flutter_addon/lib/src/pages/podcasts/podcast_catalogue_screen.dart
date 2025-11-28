import 'package:flutter/material.dart';

import '../../services/podcast_service.dart';
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
    return Scaffold(
      appBar: AppBar(title: const Text('Podcasts')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: TextField(decoration: const InputDecoration(hintText: 'Search podcasts')),
          ),
          Expanded(
            child: ListView.builder(
              itemCount: series.length,
              itemBuilder: (context, index) {
                final item = series[index];
                return LiveEventCard(
                  title: item.title,
                  subtitle: item.tagline ?? '',
                  meta: 'Host: ${item.host ?? 'Unknown'}',
                  onTap: () => Navigator.pushNamed(context, '/live/podcasts/series/${item.id}', arguments: item.id),
                );
              },
            ),
          )
        ],
      ),
    );
  }
}
