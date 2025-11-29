import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../models/project.dart';
import '../../../state/core_providers.dart';
import '../../../state/project_provider.dart';

class ProjectDetailPage extends ConsumerStatefulWidget {
  const ProjectDetailPage({super.key, required this.slug});

  final String slug;

  @override
  ConsumerState<ProjectDetailPage> createState() => _ProjectDetailPageState();
}

class _ProjectDetailPageState extends ConsumerState<ProjectDetailPage> {
  final bidController = TextEditingController();
  final coverLetterController = TextEditingController();

  @override
  void dispose() {
    bidController.dispose();
    coverLetterController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final projectState = ref.watch(projectDetailsProvider(widget.slug));

    return Scaffold(
      appBar: AppBar(title: const Text('Project details')),
      body: projectState.when(
        data: (project) => _ProjectDetailView(
          project: project,
          onBid: (amount, letter) => _placeBid(context, project, amount, letter),
        ),
        error: (error, _) => Center(child: Text('Unable to load project: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }

  Future<void> _placeBid(BuildContext context, Project project, double amount, String? letter) async {
    try {
      await ref.read(freelanceRepositoryProvider).placeBid(
            projectSlug: project.slug,
            amount: amount,
            currency: 'USD',
            coverLetter: letter,
          );
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Bid submitted successfully')),
      );
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to place bid: $error')),
      );
    }
  }
}

class _ProjectDetailView extends StatefulWidget {
  const _ProjectDetailView({required this.project, required this.onBid});

  final Project project;
  final Future<void> Function(double amount, String? letter) onBid;

  @override
  State<_ProjectDetailView> createState() => _ProjectDetailViewState();
}

class _ProjectDetailViewState extends State<_ProjectDetailView> {
  final _amountController = TextEditingController();
  final _letterController = TextEditingController();

  @override
  void dispose() {
    _amountController.dispose();
    _letterController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final project = widget.project;
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(project.title, style: Theme.of(context).textTheme.headlineSmall),
          const SizedBox(height: 8),
          Row(
            children: [
              const Icon(Icons.location_on_outlined),
              const SizedBox(width: 6),
              Text(project.location ?? 'Remote'),
            ],
          ),
          const SizedBox(height: 8),
          Text(project.description, style: Theme.of(context).textTheme.bodyMedium),
          const SizedBox(height: 16),
          Wrap(
            spacing: 10,
            runSpacing: 6,
            children: [
              _pill(context, 'Budget',
                  '\\$${project.budgetMin.toStringAsFixed(0)} - \\$${project.budgetMax.toStringAsFixed(0)}'),
              _pill(context, 'Offers', '${project.offers ?? 0} proposals'),
              _pill(context, 'Status', project.isFavourite ? 'Saved' : 'Open'),
            ],
          ),
          const SizedBox(height: 24),
          Text('Place a bid', style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 12),
          TextField(
            controller: _amountController,
            keyboardType: TextInputType.number,
            decoration: const InputDecoration(labelText: 'Amount (USD)'),
          ),
          TextField(
            controller: _letterController,
            maxLines: 3,
            decoration: const InputDecoration(labelText: 'Cover letter (optional)'),
          ),
          const SizedBox(height: 12),
          ElevatedButton.icon(
            onPressed: () async {
              final amount = double.tryParse(_amountController.text);
              if (amount == null) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Enter a valid amount')),
                );
                return;
              }
              await widget.onBid(amount, _letterController.text.isEmpty ? null : _letterController.text);
            },
            icon: const Icon(Icons.send_outlined),
            label: const Text('Submit bid'),
          ),
        ],
      ),
    );
  }

  Widget _pill(BuildContext context, String label, String value) {
    return Chip(
      label: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: Theme.of(context).textTheme.bodySmall),
          Text(value, style: Theme.of(context).textTheme.bodyMedium),
        ],
      ),
    );
  }
}
