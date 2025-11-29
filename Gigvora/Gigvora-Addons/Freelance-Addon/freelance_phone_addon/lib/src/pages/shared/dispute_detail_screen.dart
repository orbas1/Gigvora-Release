import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/dispute.dart';
import '../../state/dispute_stage_provider.dart';

class DisputeDetailScreen extends ConsumerStatefulWidget {
  const DisputeDetailScreen({super.key});

  @override
  ConsumerState<DisputeDetailScreen> createState() => _DisputeDetailScreenState();
}

class _DisputeDetailScreenState extends ConsumerState<DisputeDetailScreen> {
  final _controller = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final dispute = ModalRoute.of(context)?.settings.arguments as Dispute?;
    if (dispute == null) {
      return const Scaffold(body: Center(child: Text('No dispute selected')));
    }

    final stages = ref.watch(disputeStagesProvider(dispute.id));

    return Scaffold(
      appBar: AppBar(title: Text(dispute.title)),
      body: Column(
        children: [
          ListTile(title: Text(dispute.contract), subtitle: Text('Reason: ${dispute.reason}')),
          stages.when(
            data: (data) => Wrap(
              spacing: 6,
              children: data.map((s) => Chip(label: Text(s.stage))).toList(),
            ),
            loading: () => const Padding(
              padding: EdgeInsets.all(8),
              child: CircularProgressIndicator(),
            ),
            error: (error, _) => Padding(
              padding: const EdgeInsets.all(8),
              child: Text('Unable to load stages: $error'),
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: dispute.messages.length,
              itemBuilder: (context, index) {
                final m = dispute.messages[index];
                return ListTile(title: Text(m.author), subtitle: Text(m.body), trailing: Text(m.time));
              },
            ),
          ),
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _controller,
                      decoration: const InputDecoration(hintText: 'Add message (reply not wired to API yet)'),
                    ),
                  ),
                  IconButton(onPressed: () {}, icon: const Icon(Icons.lock_outline)),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}
