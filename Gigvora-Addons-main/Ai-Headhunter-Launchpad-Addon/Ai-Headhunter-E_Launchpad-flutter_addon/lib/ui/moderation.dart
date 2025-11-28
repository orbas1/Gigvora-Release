import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../state/state.dart';
import 'common.dart';

class ModerationQueueScreen extends StatefulWidget {
  final ModerationState? state;
  final AnalyticsClient analytics;

  const ModerationQueueScreen({super.key, required this.analytics, this.state});

  @override
  State<ModerationQueueScreen> createState() => _ModerationQueueScreenState();
}

class _ModerationQueueScreenState extends State<ModerationQueueScreen> {
  ModerationState get state => widget.state ?? context.read<ModerationState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ModerationQueue', {});
    state.loadQueue({});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Moderation Queue')),
      body: Consumer<ModerationState>(
        builder: (context, s, _) {
          if (s.loading && s.queue.isEmpty) return const LoadingView();
          if (s.error != null) return ErrorView(message: s.error!, onRetry: () => state.loadQueue({}));
          if (s.queue.isEmpty) return const EmptyView(message: 'No items in queue');
          return ListView(
            children: s.queue
                .map(
                  (item) => ListTile(
                    title: Text(item['title']?.toString() ?? 'Content ${item['id']}'),
                    subtitle: Text(item['reason']?.toString() ?? 'Awaiting review'),
                    onTap: () => Navigator.of(context)
                        .pushNamed('/moderation/detail', arguments: item),
                  ),
                )
                .toList(),
          );
        },
      ),
    );
  }
}

class ModerationDetailScreen extends StatelessWidget {
  final ModerationState? state;
  final AnalyticsClient analytics;
  final Map<String, dynamic> item;

  const ModerationDetailScreen({
    super.key,
    required this.analytics,
    this.state,
    this.item = const {},
  });

  @override
  Widget build(BuildContext context) {
    final moderationState = state ?? context.read<ModerationState>();
    analytics.trackScreen('ModerationDetail', {'id': item['id']});
    return Scaffold(
      appBar: AppBar(title: const Text('Moderation Detail')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(item['title']?.toString() ?? 'Content', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(item['body']?.toString() ?? 'No body provided'),
            const SizedBox(height: 12),
            Row(
              children: [
                ElevatedButton(
                  onPressed: () {
                    moderationState.moderate({'id': item['id'], 'action': 'approve'});
                    analytics.trackAction('moderation_approve', {'id': item['id']});
                    Navigator.of(context).pop();
                  },
                  child: const Text('Approve'),
                ),
                const SizedBox(width: 8),
                OutlinedButton(
                  onPressed: () {
                    moderationState.moderate({'id': item['id'], 'action': 'hide'});
                    analytics.trackAction('moderation_hide', {'id': item['id']});
                    Navigator.of(context).pop();
                  },
                  child: const Text('Hide'),
                ),
                const SizedBox(width: 8),
                TextButton(
                  onPressed: () {
                    moderationState.moderate({'id': item['id'], 'action': 'block'});
                    analytics.trackAction('moderation_block', {'id': item['id']});
                    Navigator.of(context).pop();
                  },
                  child: const Text('Block'),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
