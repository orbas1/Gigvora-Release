import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/networking_session.dart';
import '../../state/paginated_controller.dart';
import 'networking_session_page.dart';

class NetworkingListPage extends StatefulWidget {
  final WnipApiClient apiClient;

  const NetworkingListPage({super.key, required this.apiClient});

  @override
  State<NetworkingListPage> createState() => _NetworkingListPageState();
}

class _NetworkingListPageState extends State<NetworkingListPage> {
  late PaginatedController<NetworkingSession> controller;

  @override
  void initState() {
    super.initState();
    controller = PaginatedController<NetworkingSession>(
      (page) => widget.apiClient.fetchNetworkingSessions(page: page),
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
      appBar: AppBar(title: const Text('Networking Sessions')),
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
                final session = controller.items[index];
                return ListTile(
                  title: Text(session.title),
                  subtitle: Text('Starts ' + session.startsAt.toLocal().toIso8601String()),
                  trailing: Text(session.status),
                  onTap: () => Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => NetworkingSessionPage(
                        apiClient: widget.apiClient,
                        sessionId: session.id,
                      ),
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
