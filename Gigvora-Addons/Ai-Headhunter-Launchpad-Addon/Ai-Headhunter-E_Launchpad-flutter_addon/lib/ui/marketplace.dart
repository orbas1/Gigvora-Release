import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../models/models.dart';
import '../state/state.dart';
import 'common.dart';

class OrderEscrowScreen extends StatefulWidget {
  final int orderId;
  final EscrowState? state;
  final AnalyticsClient analytics;

  const OrderEscrowScreen({
    super.key,
    required this.orderId,
    required this.analytics,
    this.state,
  });

  @override
  State<OrderEscrowScreen> createState() => _OrderEscrowScreenState();
}

class _OrderEscrowScreenState extends State<OrderEscrowScreen> {
  EscrowState get state => widget.state ?? context.read<EscrowState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('OrderEscrow', {'orderId': widget.orderId});
    state.loadByOrder(widget.orderId);
  }

  Future<void> _release() async {
    await state.release(state.escrow!.id, {'confirm': true});
    widget.analytics.trackAction('escrow_release', {'escrowId': state.escrow?.id});
  }

  Future<void> _refund() async {
    await state.refund(state.escrow!.id, {'confirm': true});
    widget.analytics.trackAction('escrow_refund', {'escrowId': state.escrow?.id});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Escrow')),
      body: Consumer<EscrowState>(
        builder: (context, s, _) {
          if (s.loading && s.escrow == null) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.loadByOrder(widget.orderId));
          }
          final escrow = s.escrow;
          if (escrow == null) return const EmptyView(message: 'No escrow for this order');
          return RefreshIndicator(
            onRefresh: () => state.loadByOrder(widget.orderId),
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                ListTile(
                  title: Text('Escrow #${escrow.id}'),
                  subtitle: Text('Status: ${escrow.status}'),
                  trailing: Text('£${escrow.amount.toStringAsFixed(2)}'),
                ),
                ...escrow.milestones.map(
                  (m) => Card(
                    child: ListTile(
                      title: Text(m.title),
                      subtitle: Text('Due: ${m.dueDate?.toLocal().toString().split(' ').first ?? 'N/A'}'),
                      trailing: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text('£${m.amount.toStringAsFixed(2)}'),
                          Text(m.status, style: Theme.of(context).textTheme.bodySmall),
                        ],
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: ElevatedButton(
                        onPressed: s.loading ? null : _release,
                        child: const Text('Release'),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: OutlinedButton(
                        onPressed: s.loading ? null : _refund,
                        child: const Text('Refund'),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class DisputeDetailScreen extends StatefulWidget {
  final int disputeId;
  final DisputesState? state;
  final AnalyticsClient analytics;

  const DisputeDetailScreen({
    super.key,
    required this.disputeId,
    required this.analytics,
    this.state,
  });

  @override
  State<DisputeDetailScreen> createState() => _DisputeDetailScreenState();
}

class _DisputeDetailScreenState extends State<DisputeDetailScreen> {
  final TextEditingController _message = TextEditingController();
  DisputesState get state => widget.state ?? context.read<DisputesState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('DisputeDetail', {'disputeId': widget.disputeId});
    state.load(widget.disputeId);
  }

  @override
  void dispose() {
    _message.dispose();
    super.dispose();
  }

  Future<void> _reply() async {
    if (_message.text.trim().isEmpty) return;
    await state.reply(widget.disputeId, {'message': _message.text.trim()});
    widget.analytics.trackAction('dispute_reply', {'disputeId': widget.disputeId});
    _message.clear();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Dispute Detail')),
      body: Consumer<DisputesState>(
        builder: (context, s, _) {
          if (s.loading && s.active == null) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.load(widget.disputeId));
          }
          final dispute = s.active;
          if (dispute == null) return const EmptyView(message: 'Dispute not found');
          return Column(
            children: [
              ListTile(
                title: Text('Dispute #${dispute.id}'),
                subtitle: Text('Status: ${dispute.status}'),
              ),
              Expanded(
                child: ListView(
                  padding: const EdgeInsets.all(12),
                  children: dispute.messages
                      .map(
                        (m) => ListTile(
                          leading: const Icon(Icons.message_outlined),
                          title: Text(m.message),
                          subtitle: Text('User ${m.userId} • ${m.createdAt ?? ''}'),
                        ),
                      )
                      .toList(),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _message,
                        decoration: const InputDecoration(hintText: 'Reply...'),
                      ),
                    ),
                    IconButton(
                      onPressed: s.loading ? null : _reply,
                      icon: const Icon(Icons.send),
                    ),
                  ],
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}
