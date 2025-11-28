import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../models/project.dart';
import '../../../repositories/freelance_repository.dart';

class ProposalEditScreen extends ConsumerStatefulWidget {
  const ProposalEditScreen({super.key, this.project});

  final Project? project;

  @override
  ConsumerState<ProposalEditScreen> createState() => _ProposalEditScreenState();
}

class _ProposalEditScreenState extends ConsumerState<ProposalEditScreen> {
  final _formKey = GlobalKey<FormState>();
  final _coverLetter = TextEditingController();
  double amount = 0;
  int durationDays = 0;
  bool submitting = false;

  @override
  Widget build(BuildContext context) {
    final Project? project = widget.project ?? (ModalRoute.of(context)?.settings.arguments as Project?);
    final commission = amount * 0.1;
    final net = amount - commission;
    return Scaffold(
      appBar: AppBar(title: const Text('Proposal')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            if (project != null) Text(project.title, style: Theme.of(context).textTheme.titleLarge),
            TextFormField(
              decoration: const InputDecoration(labelText: 'Amount'),
              keyboardType: TextInputType.number,
              validator: (v) => (double.tryParse(v ?? '') ?? 0) <= 0 ? 'Amount required' : null,
              onChanged: (v) => setState(() => amount = double.tryParse(v) ?? 0),
            ),
            TextFormField(
              decoration: const InputDecoration(labelText: 'Duration (days)'),
              keyboardType: TextInputType.number,
              onChanged: (v) => setState(() => durationDays = int.tryParse(v) ?? 0),
            ),
            TextFormField(
              controller: _coverLetter,
              maxLines: 4,
              decoration: const InputDecoration(labelText: 'Cover letter'),
            ),
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: Colors.grey.shade100, borderRadius: BorderRadius.circular(8)),
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                Text('Commission: \$${commission.toStringAsFixed(2)}'),
                Text('Net earning: \$${net.toStringAsFixed(2)}'),
              ]),
            ),
            const SizedBox(height: 12),
            ElevatedButton(
              onPressed: submitting ? null : () => _submit(ref, project),
              child: submitting ? const CircularProgressIndicator() : const Text('Submit Proposal'),
            ),
            TextButton(
              onPressed: submitting
                  ? null
                  : () {
                      _formKey.currentState?.reset();
                      setState(() {
                        amount = 0;
                        durationDays = 0;
                        _coverLetter.clear();
                      });
                    },
              child: const Text('Reset'),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _submit(WidgetRef ref, Project? project) async {
    if (!_formKey.currentState!.validate() || project == null) {
      return;
    }
    setState(() => submitting = true);
    try {
      final repository = ref.read(freelanceRepositoryProvider);
      final slug = project.slug ?? project.id.toString();
      await repository.placeBid(
        projectSlug: slug,
        amount: amount,
        currency: 'USD',
        coverLetter: _coverLetter.text.isEmpty ? null : _coverLetter.text,
      );
      if (mounted) {
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Proposal submitted successfully')),
        );
      }
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to submit proposal: $error')),
      );
    } finally {
      if (mounted) setState(() => submitting = false);
    }
  }
}
