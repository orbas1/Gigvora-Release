import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../state/dispute_provider.dart';
import '../../../state/dispute_stage_provider.dart';
import '../../../models/dispute_stage.dart';
import '../../theme/mobile_theme.dart';

class DisputeListPage extends ConsumerWidget {
  const DisputeListPage({super.key});

  static const routeName = '/freelance/disputes';

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final disputesState = ref.watch(disputesProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Disputes'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(disputesProvider.notifier).refresh(),
          ),
        ],
      ),
      body: disputesState.when(
        data: (disputes) => disputes.isEmpty
            ? const _EmptyDisputes()
            : ListView.separated(
                padding: const EdgeInsets.all(12),
                itemBuilder: (context, index) {
                  final dispute = disputes[index];
                  return ListTile(
                    tileColor: FreelanceMobileTheme.surfaceVariant(context),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(FreelanceMobileTheme.cardRadius),
                    ),
                    title: Text(dispute.subject),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Status: ${dispute.status}'),
                        if (dispute.referenceType != null)
                          Text('Reference: ${dispute.referenceType} #${dispute.referenceId ?? ''}'),
                        if (dispute.messages.isNotEmpty) Text('Messages: ${dispute.messages.length}'),
                        const SizedBox(height: 8),
                        _DisputeStageSummary(disputeId: dispute.id),
                      ],
                    ),
                  );
                },
                separatorBuilder: (_, __) => const SizedBox(height: 8),
                itemCount: disputes.length,
              ),
        error: (error, _) => Center(child: Text('Failed to load disputes: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _openCreateDialog(context, ref),
        child: const Icon(Icons.add),
      ),
    );
  }

  Future<void> _openCreateDialog(BuildContext context, WidgetRef ref) async {
    final result = await showDialog<_CreateDisputeResult>(
      context: context,
      builder: (_) => const _CreateDisputeDialog(),
    );
    if (result != null) {
      await ref.read(disputesProvider.notifier).openDispute(
            subject: result.subject,
            referenceType: result.referenceType,
            referenceId: result.referenceId,
            message: result.message,
          );
    }
  }
}

class _EmptyDisputes extends StatelessWidget {
  const _EmptyDisputes();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.report_gmailerrorred_outlined,
                size: 56, color: FreelanceMobileTheme.emphasisIcon(context)),
            const SizedBox(height: 12),
            const Text('No disputes filed yet.'),
          ],
        ),
      ),
    );
  }
}

class _DisputeStageSummary extends ConsumerWidget {
  const _DisputeStageSummary({required this.disputeId});

  final int disputeId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final stages = ref.watch(disputeStagesProvider(disputeId));
    return stages.when(
      data: (data) => Wrap(
        spacing: 6,
        runSpacing: 4,
        children: data
            .map((s) => Chip(
                  avatar: const Icon(Icons.timelapse_outlined, size: 18),
                  label: Text(s.stage),
                ))
            .toList(),
      ),
      loading: () => const SizedBox(height: 16, width: 16, child: CircularProgressIndicator(strokeWidth: 2)),
      error: (err, _) => Text('Stages unavailable: $err'),
    );
  }
}

class _CreateDisputeDialog extends StatefulWidget {
  const _CreateDisputeDialog();

  @override
  State<_CreateDisputeDialog> createState() => _CreateDisputeDialogState();
}

class _CreateDisputeDialogState extends State<_CreateDisputeDialog> {
  final subjectController = TextEditingController();
  final messageController = TextEditingController();
  final referenceController = TextEditingController();
  String referenceType = 'project';

  @override
  void dispose() {
    subjectController.dispose();
    messageController.dispose();
    referenceController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text('Open a dispute'),
      content: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          TextField(
            controller: subjectController,
            decoration: const InputDecoration(labelText: 'Subject'),
          ),
          DropdownButtonFormField<String>(
            value: referenceType,
            items: const [
              DropdownMenuItem(value: 'project', child: Text('Project')),
              DropdownMenuItem(value: 'gig', child: Text('Gig')),
              DropdownMenuItem(value: 'escrow', child: Text('Escrow')),
            ],
            onChanged: (value) => setState(() => referenceType = value ?? 'project'),
            decoration: const InputDecoration(labelText: 'Reference type'),
          ),
          TextField(
            controller: referenceController,
            keyboardType: TextInputType.number,
            decoration: const InputDecoration(labelText: 'Reference ID'),
          ),
          TextField(
            controller: messageController,
            maxLines: 3,
            decoration: const InputDecoration(labelText: 'Message (optional)'),
          ),
        ],
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.of(context).pop(),
          child: const Text('Cancel'),
        ),
        ElevatedButton(
          onPressed: () {
            final referenceId = int.tryParse(referenceController.text);
            if (referenceId == null || subjectController.text.isEmpty) {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Subject and reference id are required')),
              );
              return;
            }
            Navigator.of(context).pop(
              _CreateDisputeResult(
                subject: subjectController.text,
                referenceType: referenceType,
                referenceId: referenceId,
                message: messageController.text.isEmpty ? null : messageController.text,
              ),
            );
          },
          child: const Text('Submit'),
        ),
      ],
    );
  }
}

class _CreateDisputeResult {
  _CreateDisputeResult({
    required this.subject,
    required this.referenceType,
    required this.referenceId,
    this.message,
  });

  final String subject;
  final String referenceType;
  final int referenceId;
  final String? message;
}
