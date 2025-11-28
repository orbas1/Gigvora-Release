import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/interview.dart';
import '../../state/paginated_controller.dart';
import 'interview_page.dart';

class InterviewListPage extends StatefulWidget {
  final WnipApiClient apiClient;

  const InterviewListPage({super.key, required this.apiClient});

  @override
  State<InterviewListPage> createState() => _InterviewListPageState();
}

class _InterviewListPageState extends State<InterviewListPage> {
  late PaginatedController<Interview> controller;

  @override
  void initState() {
    super.initState();
    controller = PaginatedController<Interview>(
      (page) => widget.apiClient.fetchInterviews(page: page),
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
      appBar: AppBar(title: const Text('Interviews')),
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
                final interview = controller.items[index];
                return ListTile(
                  title: Text(interview.title),
                  subtitle: Text('Scheduled ' + interview.scheduledAt.toLocal().toIso8601String()),
                  trailing: Icon(interview.isPanel ? Icons.groups : Icons.person),
                  onTap: () => Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => InterviewPage(apiClient: widget.apiClient, interviewId: interview.id),
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
