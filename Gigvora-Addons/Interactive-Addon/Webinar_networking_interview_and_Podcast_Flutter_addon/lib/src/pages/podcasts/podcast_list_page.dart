import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/podcast_series.dart';
import '../../state/paginated_controller.dart';
import 'podcast_series_page.dart';

class PodcastListPage extends StatefulWidget {
  final WnipApiClient apiClient;

  const PodcastListPage({super.key, required this.apiClient});

  @override
  State<PodcastListPage> createState() => _PodcastListPageState();
}

class _PodcastListPageState extends State<PodcastListPage> {
  late PaginatedController<PodcastSeries> controller;

  @override
  void initState() {
    super.initState();
    controller = PaginatedController<PodcastSeries>(
      (page) => widget.apiClient.fetchPodcastSeries(page: page),
    );
    controller.loadInitial();
  }

  @override
  void dispose() {
    controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Podcasts')),
      body: AnimatedBuilder(
        animation: controller,
        builder: (context, _) {
          if (controller.isLoading && controller.items.isEmpty) {
            return const Center(child: CircularProgressIndicator());
          }
          if (controller.error != null && controller.items.isEmpty) {
            return Center(child: Text(controller.error!));
          }
          return RefreshIndicator(
            onRefresh: controller.loadInitial,
            child: ListView.builder(
              itemCount: controller.items.length + (controller.hasMore ? 1 : 0),
              itemBuilder: (context, index) {
                if (index >= controller.items.length) {
                  controller.loadMore();
                  return const Padding(
                    padding: EdgeInsets.symmetric(vertical: 16),
                    child: Center(child: CircularProgressIndicator()),
                  );
                }
                final series = controller.items[index];
                return ListTile(
                  title: Text(series.title),
                  subtitle: Text(series.description ?? 'No description'),
                  trailing: Icon(series.isPublic ? Icons.public : Icons.lock_outline),
                  onTap: () => Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => PodcastSeriesPage(apiClient: widget.apiClient, seriesId: series.id),
                    ),
                  ),
                );
              },
            ),
          );
        },
      ),
    );
  }
}
