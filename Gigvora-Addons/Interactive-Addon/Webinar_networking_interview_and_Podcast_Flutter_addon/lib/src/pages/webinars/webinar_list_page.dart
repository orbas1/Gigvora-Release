import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/webinar.dart';
import '../../state/paginated_controller.dart';
import 'webinar_detail_page.dart';

class WebinarListPage extends StatefulWidget {
  final WnipApiClient apiClient;
  final bool upcomingOnly;

  const WebinarListPage({super.key, required this.apiClient, this.upcomingOnly = false});

  @override
  State<WebinarListPage> createState() => _WebinarListPageState();
}

class _WebinarListPageState extends State<WebinarListPage> {
  late PaginatedController<Webinar> controller;

  @override
  void initState() {
    super.initState();
    controller = PaginatedController<Webinar>(
      (page) => widget.apiClient.fetchWebinars(upcoming: widget.upcomingOnly, page: page),
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
      appBar: AppBar(
        title: Text(widget.upcomingOnly ? 'Upcoming Webinars' : 'Webinars'),
      ),
      body: AnimatedBuilder(
        animation: controller,
        builder: (context, _) {
          if (controller.isLoading && controller.items.isEmpty) {
            return const Center(child: CircularProgressIndicator());
          }

          if (controller.error != null && controller.items.isEmpty) {
            return _ErrorState(message: controller.error!, onRetry: controller.loadInitial);
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
                final webinar = controller.items[index];
                return ListTile(
                  title: Text(webinar.title),
                  subtitle: Text(
                    webinar.description ?? 'Starts ' + webinar.startsAt.toLocal().toIso8601String(),
                  ),
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(webinar.status),
                      if (webinar.isPaid) Text('Paid: ' + (webinar.price?.toString() ?? 'N/A')),
                    ],
                  ),
                  onTap: () => Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => WebinarDetailPage(apiClient: widget.apiClient, webinarId: webinar.id),
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

class _ErrorState extends StatelessWidget {
  final String message;
  final VoidCallback onRetry;

  const _ErrorState({required this.message, required this.onRetry});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(message),
          const SizedBox(height: 8),
          ElevatedButton(onPressed: onRetry, child: const Text('Retry')),
        ],
      ),
    );
  }
}
